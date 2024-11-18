<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GuzzleHelper
{
    protected $client;

    /**
     * Constructor to initialize Guzzle Client.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
    }

    /**
     * Send a request using Guzzle.
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $url Endpoint URL
     * @param array $options Guzzle options (headers, json, etc.)
     * @return mixed|string Response content or error message
     */
    public function sendRequest(string $method, string $url, array $options = [])
    {
        try {
            $response = $this->client->request($method, $url, $options);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            Log::error("Guzzle Request Error: {$e->getMessage()}");
            return 'Error: ' . $e->getMessage();
        }
    }
}
