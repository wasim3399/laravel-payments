<?php

namespace App\Http\Controllers;

use App\Library\Intergiro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected $intergiro;

    /**
     * Constructor to inject the Intergiro service.
     */
    public function __construct()
    {
        $this->intergiro = new Intergiro();
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
        $payload = [
            'amount'   => 2,
            'number'   => Str::random(20),
            'currency' => 'EUR',
            'card'     => [
                'pan'     => $request['card-number'], // 4111111111111111
                'expires' => [2, 25],
                'csc'     => $request['cvv'],
            ],
            'target' => url('/callback/getIgCallback'),
        ];

        return $this->intergiro->makeIgRequest($payload);
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
                    // Capture the payment
                    return $this->intergiro->capturePayment($id, $amount);
                }
            }

            return view('payments.fail', ['error' => 'Invalid authorization token or data.']);
        } catch (\Exception $e) {
            return view('payments.fail', ['error' => $e->getMessage()]);
        }
    }
}
