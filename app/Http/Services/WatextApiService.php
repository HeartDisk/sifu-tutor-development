<?php
namespace App\Services;

use GuzzleHttp\Client;

class WatextApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.watext.com/',
            'headers' => [
                'Authorization' => 'Bearer e6f2cb62a2b54cfbb6a1b25fbfee6131',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function makeRequest($method, $endpoint, $data = [])
    {
        $response = $this->client->request($method, $endpoint, [
            'json' => $data,
        ]);

        return json_decode($response->getBody(), true);
    }
}
