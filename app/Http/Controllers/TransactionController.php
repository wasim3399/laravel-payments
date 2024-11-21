<?php

namespace App\Http\Controllers;

use App\Library\Intergiro;
use App\Library\Rendix;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected $intergiro;
    protected $rendix;

    /**
     * Constructor to inject the Intergiro service.
     */
    public function __construct()
    {
        $this->intergiro = new Intergiro();
        $this->rendix = new Rendix();
    }

    /**
     * Test page for payments.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function test()
    {
        return view('payments.test');
    }

    /**
     * Load the main payment page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function loadPaymentPage()
    {
        return view('payments.main');
    }

    /**
     * Create a transaction and generate a payment URL.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createTransaction(Request $request): JsonResponse
    {
        try {
            $token = $request->request_reference ?? Str::uuid();
            $url = url('/') . '/payment/' . $token;

            $response = [
                'success' => true,
                'message' => 'Transaction created successfully',
                'data'    => $url,
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating transaction',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Make a payment using the Intergiro API.
     *
     * @param Request $request
     * @return string
     */
    public function makePayment(Request $request)
    {
        if($request->name == 'rendix')
        {
            $rendixToken = $this->rendix->getToken();
            $responseData = json_decode($rendixToken, true);
            if (isset($responseData['success']) && $responseData['data']['token'])
            {
                $tranx = $this->rendix->createTranx($responseData['data']['token']);
                $response = json_decode($tranx, true);

                if (isset($response['success']) && $response['data']['saleId'])
                {
                    return view('payments.qr', ['qrCode' => $response['data']['qrCodeBase64']]);
                }

            } else {
                // Redirect to a failure or pending view
                return view('payments.failure', ['data' => $responseData]);
            }

        }

        // default route to IG
        return $this->intergiro->makeIgRequest($request);
    }

    /**
     * Handle Intergiro callback for payment verification and capture.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function getIgCallback(Request $request)
    {
        try {
            // Verify the authorization token
            if ($request->has('authorization')) {
                $response = $this->intergiro->verifyToken($request->authorization);
                $responseData = json_decode($response, true);

                $id = $responseData['id'] ?? null;
                $amount = $responseData['amount'] ?? null;

                if ($id && $amount) {
                    // Capture the payment of IG
                    return $this->intergiro->capturePayment($id, $amount);
                }
            }

            return view('payments.fail', ['error' => 'Invalid authorization token or data.']);
        } catch (\Exception $e) {
            return view('payments.fail', ['error' => $e->getMessage()]);
        }
    }
}
