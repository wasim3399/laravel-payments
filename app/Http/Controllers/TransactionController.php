<?php

namespace App\Http\Controllers;

use App\Library\Intergiro;
use App\Library\Rendix;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected Intergiro $intergiro;
    protected Rendix $rendix;

    /**
     * Constructor to initialize services.
     */
    public function __construct(Intergiro $intergiro, Rendix $rendix)
    {
        $this->intergiro = $intergiro;
        $this->rendix = $rendix;
    }

    /**
     * Render the payment test page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function test(): \Illuminate\Contracts\View\View
    {
        return view('payments.test');
    }

    /**
     * Render the main payment page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function loadPaymentPage(): \Illuminate\Contracts\View\View
    {
        return view('payments.main');
    }

    /**
     * Create a transaction and return the payment URL.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createTransaction(Request $request): JsonResponse
    {
        try {
            $token = $request->input('request_reference', Str::uuid());
            $paymentUrl = route('payment.show', ['token' => $token]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $paymentUrl,
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating transaction',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle the payment process.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|string
     */
    public function makePayment(Request $request)
    {
        if ($request->input('name') === 'rendix') {
            return $this->handleRendixPayment();
        }

        return $this->intergiro->makeIgRequest($request);
    }

    /**
     * Handle Intergiro callback for payment verification and capture.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View|string
     */
    public function getIgCallback(Request $request)
    {
        try {
            if (!$request->has('authorization')) {
                throw new \InvalidArgumentException('Invalid authorization token.');
            }

            $response = $this->intergiro->verifyToken($request->input('authorization'));
            $responseData = json_decode($response, true);

            if (isset($responseData['id'], $responseData['amount'])) {
                return $this->intergiro->capturePayment($responseData['id'], $responseData['amount']);
            }

            throw new \UnexpectedValueException('Missing required data for payment capture.');
        } catch (\Exception $exception) {
            return view('payments.fail', ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Handle Rendix payment flow.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function handleRendixPayment()
    {
        $tokenResponse = $this->rendix->getToken();
        $tokenData = json_decode($tokenResponse, true);

        if (!isset($tokenData['success'], $tokenData['data']['token']) || !$tokenData['success']) {
            return view('payments.failure', ['data' => $tokenData]);
        }

        $transactionResponse = $this->rendix->createTranx($tokenData['data']['token']);
        $transactionData = json_decode($transactionResponse, true);

        if (isset($transactionData['success'], $transactionData['data']['saleId']) && $transactionData['success']) {
            return view('payments.qr', ['qrCode' => $transactionData['data']['qrCodeBase64']]);
        }

        return view('payments.failure', ['data' => $transactionData]);
    }
}
