<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

class Paymatico
{
    protected GuzzleHelper $guzzleHelper;

    /**
     * Initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.PAYMATICO_BASE_URL'),
        ]);
    }

    /**
     * Create a transaction in the blixtpay system.
     *
     * @param string
     * @return string
     */
    public function createTranx($payload): string
    {
        return $this->guzzleHelper->sendRequest('POST', '/v1/transaction/', [
            'headers' => [
                'Authorization' => 'AppKeys 95de4f05bf0045c8811f588145952c37:1161b2caa99943788effd681818cf1d8',
                'Accept'=> 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);
    }

    public function capturePayment($token)
    {
        $payload = [
            'name' => 'test',
            'number' => '5455330200000016',
            'month' => 12,
            'year' => 2024,
            'verification_value' => '123',
            'language' => 'en'
        ];

        return $this->guzzleHelper->sendRequest('POST', '/v1/pay/'.$token.'/card/AUTO/', [
            'headers' => [
                'Authorization' => 'AppKeys 95de4f05bf0045c8811f588145952c37:1161b2caa99943788effd681818cf1d8',
                'Accept'=> 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            'json' => $payload,
        ]);
    }


}
