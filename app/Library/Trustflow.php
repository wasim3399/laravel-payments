<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

class Trustflow
{
    protected GuzzleHelper $guzzleHelper;

    /**
     * Initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.TRUST_FLOW_PAY_BASE_URL'),
        ]);
    }

    public function getHash($order_id)
    {
        $payloadString = 'AMOUNT=1000~APP_ID=1213240327171820~CARD_EXP_DT=122030~CARD_NUMBER=4111110000000021~CURRENCY_CODE=840~CUST_CITY=Winterfell~CUST_COUNTRY=US~CUST_EMAIL=john_snow@test.com~CUST_NAME=John~CUST_PHONE=9454243567~CUST_SHIP_FIRST_NAME=John~CUST_SHIP_LAST_NAME=Snow~CUST_STATE=The North~CUST_STREET_ADDRESS1=Great Wall~CUST_ZIP=32546~CVV=123~INITIATE_SEAMLESS_TRANSACTION=Y~MERCHANTNAME=Test Merchant~ORDER_ID='.$order_id.'~PAYMENT_TYPE=CC~PRODUCT_DESC=Valerian Streel Blades~RETURN_URL=http://127.0.0.1:8000/test~TXNTYPE=SALEf037897e6fb8427e';
        return strtoupper(hash("sha512", $payloadString));
    }

    /**
     * Create a transaction in the blixtpay system.
     *
     * @param string
     * @return string
     */
    public function createTranx($payload): string
    {
        return $this->guzzleHelper->sendRequest('POST', '/pgui/services/paymentServices/initiate/payment', [
            'headers' => [
                'Accept'=> 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);
    }

    public function capturePayment($payload): string
    {
        return $this->guzzleHelper->sendRequest('POST', '/pgui/services/paymentServices/initiate/payment', [
            'headers' => [
                'Accept'=> 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);
    }
}
