<?php

namespace App\Http\Controllers;

use App\Library\CL;
use App\Library\Intergiro;
use App\Library\Rendix;
use App\Library\Trustflow;
use App\Library\MerchantPay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    protected Intergiro $intergiro;
    protected Rendix $rendix;
    protected CL $cl;
    protected Trustflow $trustflow;
    protected MerchantPay $merchantPay;

    /**
     * Constructor to initialize services.
     */
    public function __construct(Intergiro $intergiro, Rendix $rendix, CL $cl, Trustflow $trustflow, MerchantPay $merchantPay)
    {
        $this->intergiro = $intergiro;
        $this->rendix = $rendix;
        $this->cl = $cl;
        $this->trustflow = $trustflow;
        $this->merchantPay = $merchantPay;
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

        // e-merchant
        if ($request->input('name') === 'e-merchant') {
            return $this->handleMerchantPayPayment();
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
        $hash = $this->trustflow->getHash($order_id);
        $payload = [
            'AMOUNT' => '1000',
            'APP_ID' => '1213240327171820',
            'CARD_EXP_DT' => '122030',
            'CARD_NUMBER' => '4111110000000211',
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

    public function trustFlowPayRedirect()
    {
        return response()->make('<script>window.close();</script>');
    }

    public function trustflowTranxStatus(Request $request)
    {
        $hash = self::createHash($request->order_id, $request->trx_id);
        $payload = [
            'AMOUNT' => '1000',
            'APP_ID' => '1213240327171820',
            'CURRENCY_CODE' => '840',
            'ORDER_ID' => $request->order_id,
            'TXN_ID' => $request->trx_id,
            'HASH' => $hash
        ];

        $createTranx = $this->trustflow->checkStatus($payload);
        $data = json_decode($createTranx, true);
        dd($data);
    }

    private function createHash($order_id, $trx_id)
    {
        $payloadString = 'AMOUNT=1000~APP_ID=1213240327171820~CURRENCY_CODE=840~ORDER_ID='.$order_id.'~TXN_ID='.$trx_id.'f037897e6fb8427e';
        return strtoupper(hash("sha512", $payloadString));
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

    /**
     * Handle Rendix payment flow.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function handleMerchantPayPayment()
    {
        $id = Str::random(20);
        $payload = '<?xml version="1.0" encoding="UTF-8"?>
  <payment_transaction>
    <transaction_type>sale3d</transaction_type>
    <transaction_id>'.$id.'</transaction_id>
    <usage>Create payment</usage>
    <remote_ip>13.48.73.137</remote_ip>
    <amount>500</amount>
    <currency>EUR</currency>
    <card_holder>First Last</card_holder>
    <card_number>4012000000060085</card_number>
    <expiration_month>12</expiration_month>
    <expiration_year>2025</expiration_year>
    <cvv>834</cvv>
    <customer_email>demo@cardeye.com</customer_email>
    <customer_phone>+46709404990</customer_phone>
    <billing_address>
        <first_name>First</first_name>
        <last_name>Last</last_name>
        <address1>Street</address1>
        <zip_code>postalcode</zip_code>
        <city>city</city>
        <neighborhood>Street</neighborhood>
        <country>GB</country>
    </billing_address>
    <notification_url>'.Config::get('constants.EMERCHANTPAY_NOTIFICATION_CALLBACK').'</notification_url>
    <return_success_url>'.Config::get('constants.EMERCHANTPAY_NOTIFICATION_CALLBACK').'</return_success_url>
    <return_failure_url>'.Config::get('constants.EMERCHANTPAY_NOTIFICATION_CALLBACK').'</return_failure_url>
    <threeds_v2_params>
        <threeds_method>
            <callback_url>https://webhook.site/cardeye-hpp-callback</callback_url>
        </threeds_method>
        <control>
            <device_type>browser</device_type>
            <challenge_window_size>full_screen</challenge_window_size>
        </control>
        <browser>
            <accept_header>*/*</accept_header>
            <java_enabled>false</java_enabled>
            <language>en-US</language>
            <color_depth>24</color_depth>
            <screen_height>720</screen_height>
            <screen_width>1280</screen_width>
            <time_zone_offset>0</time_zone_offset>
            <user_agent>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.6668.29 Safari/537.36</user_agent>
        </browser>
    </threeds_v2_params>
  </payment_transaction>';

        $responseXml = $this->merchantPay->createTranx($payload);
        $responseJson = $this->merchantPay->parseXml($responseXml);

        // handle json response
        try {
            // Decode the JSON response into an associative array
            $responseData = json_decode($responseJson, true);

            // Check if decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
            }

            // Handle the response based on the status
            if ($responseData['status'] === 'approved') {
                // Extract necessary data
                $transactionId = $responseData['transaction_id'];
                $amount = $responseData['amount'];
                $currency = $responseData['currency'];
                $message = $responseData['message'];
                $timestamp = $responseData['timestamp'];

                // Log or process the approved transaction
                echo "Transaction Approved:\n";
                echo "Transaction ID: $transactionId\n";
                echo "Amount: $amount $currency\n";
                echo "Message: $message\n";
                echo "Timestamp: $timestamp\n";
            } else {
                // Handle non-approved status
                $errorMessage = $responseData['message'] ?? 'Unknown error occurred.';
                echo "Transaction Failed:\n";
                echo "Error Message: $errorMessage\n";
            }
        } catch (\Exception $e) {
            // Handle exceptions and errors
            echo "Error handling response: " . $e->getMessage();
        }
    }
}
