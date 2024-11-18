<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

class Intergiro
{
    protected $guzzleHelper;

    /**
     * Constructor to initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.INTERGIRO_BASE_URL'),
        ]);
    }

    /**
     * Make a request to the Intergiro API to initiate payment authorization.
     *
     * @param array $payload
     * @return string
     */
    public function makeIgRequest(array $payload): string
    {
        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . Config::get('constants.IG_PBK'),
        ];

        return $this->guzzleHelper->sendRequest('POST', '/v1/authorization/redirect', [
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
    public function verifyToken(string $token): string
    {
        $headers = [
            'Content-Type'  => 'application/jwt',
            'Authorization' => 'Bearer ' . Config::get('constants.IG_PBK'),
        ];

        return $this->guzzleHelper->sendRequest('POST', '/v1/authorization/verify', [
            'headers' => $headers,
            'body'    => $token,
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
