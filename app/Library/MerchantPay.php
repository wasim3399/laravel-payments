<?php

namespace App\Library;

use App\Helpers\GuzzleHelper;
use Illuminate\Support\Facades\Config;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MerchantPay
{
    protected GuzzleHelper $guzzleHelper;

    /**
     * Initialize GuzzleHelper with base configurations.
     */
    public function __construct()
    {
        $this->guzzleHelper = new GuzzleHelper([
            'base_uri' => Config::get('constants.EMERCHANTPAY_BASE_URL'),
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
        $headers = [
            'Content-Type'  => 'application/xml',
            'Authorization' => 'Basic OGQwZGIxNzQ4ZGM4ZTMwNjJmOTg0OWM0ZWFhNzgxNjk5OGFjNTMzMTo0MWI5OGVlZGVlZmM2NDQxMTZmYzM3ZTE3N2ViZjFhZTFjMjU0MjRj'
        ];

        return $this->guzzleHelper->sendRequest('POST', '/process/9b029956ebc24bdac87434665e60b6e3caaee486', [
            'headers' => $headers,
            'body'    => $payload,
        ]);
    }

    public function parseXml($responseXml)
    {
        try {
            // Parse the XML response
            $xml = new \SimpleXMLElement($responseXml); // Add a backslash to use the global class

            // Convert to an array
            $arrayData = json_decode(json_encode($xml), true);

            // Convert to JSON
            $jsonData = json_encode($arrayData, JSON_PRETTY_PRINT);

            // Output JSON response
            header('Content-Type: application/json');
            return $jsonData;

        } catch (\Exception $e) { // Use the global Exception class
            // Handle errors and return as JSON
            $errorResponse = [
                'error' => true,
                'message' => $e->getMessage()
            ];
            header('Content-Type: application/json');
            return json_encode($errorResponse, JSON_PRETTY_PRINT);
        }
    }

}
