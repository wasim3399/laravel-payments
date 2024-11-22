<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

class CL
{
    protected GuzzleHelper $guzzleHelper;

    /**
     * Initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.CL_BASE_URL'),
        ]);
    }

    /**
     * Create a transaction in the blixtpay system.
     *
     * @param string
     * @return string
     */
    public function createTranx(): string
    {
        return $this->guzzleHelper->sendRequest('POST', '/backend/api/create-payin-link', [
            'headers' => [
                'Accept'=> 'application/json',
                'Content-Type' => 'application/json',
                'token' => config('constants.RCL_API_KEY'),
            ],
            'json' => [
                "request_id" => 123,
                "brand_name" => "pepsi",
                "amount" => 20,
                "device" => "desktop",
//                "device" => "mobile",
                "redirect_url" => false, // what if we send
                "transaction_type" => "fiat",
                'call_back_url' => 'https://webhook.site/cardeye-hpp-callback',
            ],
        ]);
    }
}
