<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

class Paysafe
{
    protected GuzzleHelper $guzzleHelper;

    /**
     * Initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.PAYSAFE_BASE_URL'),
        ]);
    }

    /**
     * Create a transaction in the blixtpay system.
     *
     * @param string
     * @return string
     */
    public function createPaymentHandle($payload): string
    {
        return $this->guzzleHelper->sendRequest('POST', 'v1/paymenthandles', [
            'headers' => [
                'Content-Type' => 'application/json',
                "Authorization" => " Basic " . base64_encode("pmle-1084670:B-qa2-0-6564d84d-0-302c02143f7e3b354a792f385b128552db7ced761772b4d602142c85253a3b2a5ad553eccb944f88a32811a3513f"),
            ],
            'json' => $payload,
        ]);
    }

    public function capturePayment($data): string
    {
        return $this->guzzleHelper->sendRequest('POST', 'v1/payments', [
            'headers' => [
                'Content-Type' => 'application/json',
                "Authorization" => " Basic " . base64_encode("pmle-1084670:B-qa2-0-6564d84d-0-302c02143f7e3b354a792f385b128552db7ced761772b4d602142c85253a3b2a5ad553eccb944f88a32811a3513f"),
            ],
            'json' => [
                "merchantRefNum" => $data['merchantRefNum'],
                "amount" => 500,
                "currencyCode" => "USD",
                "dupCheck" => true,
                "paymentHandleToken" => $data['paymentHandleToken']
            ],
        ]);
    }


}
