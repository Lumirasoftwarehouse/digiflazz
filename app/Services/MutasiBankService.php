<?php
namespace App\Services;

use GuzzleHttp\Client;

class MutasiBankService
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = env('MUTASIBANK_API_URL');
        $this->apiKey = env('MUTASIBANK_API_KEY');
    }

    public function getMutations($accountId)
    {
        $response = $this->client->request('GET', $this->apiUrl . 'account/:6007', [
            'headers' => [
                'Authorization' => $this->apiKey,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    // Anda bisa menambahkan metode lain sesuai kebutuhan
}
