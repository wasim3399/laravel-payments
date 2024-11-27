<?php

namespace App\Http\Controllers;

use App\Library\CL;
use App\Library\Intergiro;
use App\Library\Rendix;
use App\Library\Trustflow;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected Intergiro $intergiro;
    protected Rendix $rendix;
    protected CL $cl;
    protected Trustflow $trustflow;

    /**
     * Constructor to initialize services.
     */
    public function __construct(Intergiro $intergiro, Rendix $rendix, CL $cl, Trustflow $trustflow)
    {
        $this->intergiro = $intergiro;
        $this->rendix = $rendix;
        $this->cl = $cl;
        $this->trustflow = $trustflow;
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
        // rendix flow
        if ($request->input('name') === 'rendix') {
            return $this->handleRendixPayment();
        }

        // webchque flow
        if ($request->input('name') === 'webcheque') {
            return $this->handleWebchequePayment();
        }

        // CL2.0
        if ($request->input('name') === 'cl') {
            return $this->handleCLPayment();
        }

        // CL2.0
        if ($request->input('name') === 'trustflow') {
            return $this->handleTrustFlowPayment();
        }

        //default IG flow
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

    /**
     * Handle Rendix payment flow.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function handleTrustFlowPayment()
    {
        $order_id = Str::random(20);
        $hash = $this->trustflow->getHash(true, $order_id);
        $payload = [
            'AMOUNT' => '1000',
            'APP_ID' => '1213240327171820',
            'CARD_EXP_DT' => '122030',
            'CARD_NUMBER' => '4111110000000021',
            'CURRENCY_CODE' => '840',
            'CUST_CITY' => 'Winterfell',
            'CUST_COUNTRY' => 'US',
            'CUST_EMAIL' => 'john_snow@test.com',
            'CUST_NAME' => 'John',
            'CUST_PHONE' => '9454243567',
            'CUST_SHIP_FIRST_NAME' => 'John',
            'CUST_SHIP_LAST_NAME' => 'Snow',
            'CUST_STATE' => 'The North',
            'CUST_STREET_ADDRESS1' => 'Great Wall',
            'CUST_ZIP' => '32546',
            'CVV' => '123',
            'INITIATE_SEAMLESS_TRANSACTION' => 'Y',
            'MERCHANTNAME' => 'Test Merchant',
            'ORDER_ID' => $order_id,
            'PAYMENT_TYPE' => 'CC',
            'PRODUCT_DESC' => 'Valerian Streel Blades',
            'RETURN_URL' => 'http://127.0.0.1:8000/trust-flow-pay-redirect',
            'TXNTYPE' => 'SALE',
            'HASH' => $hash
        ];

        $createTranx = $this->trustflow->createTranx($payload);
        $data = json_decode($createTranx, true);

        if($data['STATUS'] == 'Enrolled' && $data['RESPONSE_CODE'] == "000")
        {
            $app_id = $data['APP_ID'];
            $trx_id = $data['TXN_ID'];
            $hash = $data['HASH'];
            return view('payments.trustflow_threeds', compact('app_id', 'trx_id', 'hash'));
        }
    }

    public function trustflowTranxStatus()
    {
        $hash = $this->trustflow->getHash(false, $order_id = '5cSJSo9WW7KmbVtGY8wg');
        $payload = [
            'AMOUNT' => '1000',
            'APP_ID' => '1213240327171820',
            'CURRENCY_CODE' => '840',
            'ORDER_ID' => 'ElisrMAI7p059fxK3VOc',
            'TXN_ID' => '1220241127174227',
            'HASH' => $hash
        ];

        $createTranx = $this->trustflow->checkStatus($payload);
        $data = json_decode($createTranx, true);
        dd($data);
    }

    /**
     * Handle Rendix payment flow.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function handleWebchequePayment()
    {
        dd('webcheque waiting credentials');
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

    /**
     * Handle Rendix payment flow.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function handleCLPayment()
    {
        return $this->cl->createTranx();
    }
}
