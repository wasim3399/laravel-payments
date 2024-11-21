<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

class Rendix
{
    protected GuzzleHelper $guzzleHelper;

    /**
     * Initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.RENDIX_BASE_URL'),
        ]);
    }

    /**
     * Retrieve authentication token from the API.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->guzzleHelper->sendRequest('POST', '/efx/v1/external/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => Config::get('constants.EMAIL'),
                'password' => Config::get('constants.PASS'),
                'merchantId' => Config::get('constants.MERCHANT_ID'),
            ],
        ]);
    }

    /**
     * Create a transaction in the Rendix system.
     *
     * @param string $token
     * @return string
     */
    public function createTranx(string $token): string
    {
        return $this->guzzleHelper->sendRequest('POST', '/efx/v1/external/sell', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => [
                'purchase' => 1,
                'cpf' => '12979230901',
                'controlNumber' => 'abc123',
                'phone' => '5512991234567',
                'email' => 'teste@teste.com',
                'urlWebhook' => 'https://webhook.site/cardeye-hpp-callback',
            ],
        ]);
    }
}
