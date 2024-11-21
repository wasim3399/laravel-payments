<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

class Rendix
{
    protected $guzzleHelper;

    /**
     * Constructor to initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.RENDIX_BASE_URL'),
        ]);
    }

    /**
     * Make a request to the Intergiro API to initiate payment authorization.
     *
     * @param array $payload
     * @return string
     */
    public function getToken(): string
    {
        $payload = [
            'email'   => Config::get('constants.EMAIL'),
            'password'   => Config::get('constants.PASS'),
            'merchantId' => Config::get('constants.MERCHANT_ID')
        ];

        $headers = [
            'Content-Type'  => 'application/json',
        ];

        return $this->guzzleHelper->sendRequest('POST', '/efx/v1/external/login', [
            'headers' => $headers,
            'json'    => $payload,
        ]);
    }

    /**
     * Verify the authorization token via the Intergiro API.
     *
     * @param string $token
     * @return string
     */
    public function createTranx(string $token): string
    {
        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $body = [
            "purchase" => 1,
            "cpf" => "12979230901",
            "controlNumber" => "abc123",
            "phone" => "5512991234567",
            "email" => "teste@teste.com",
            "urlWebhook" => "https://webhook.site/cardeye-hpp-callback"
        ];

        return $this->guzzleHelper->sendRequest('POST', '/efx/v1/external/sell', [
            'headers' => $headers,
            'json'    => $body,
        ]);
    }

    /**
     * Capture an authorized payment via the Intergiro API.
     *
     * @param string $id
     * @param float|int $amount
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function capturePayment(string $id, $amount)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . Config::get('constants.IG_PVK'),
        ];

        $body = [
            'amount' => $amount,
        ];

        $response = $this->guzzleHelper->sendRequest('POST', "/v1/authorization/{$id}/capture", [
            'headers' => $headers,
            'json'    => $body,
        ]);

        $responseData = json_decode($response, true);

        // Determine view based on payment status
        if (isset($responseData['status']) && $responseData['status'] === 'approved') {
            return view('payments.success', ['data' => $responseData]);
        } else {
            return view('payments.fail', ['data' => $responseData]);
        }
    }

}
