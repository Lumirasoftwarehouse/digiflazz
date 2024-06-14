<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DigiFlazzService
{
    protected $client;
    protected $baseUrl;
    protected $username;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = env('DIGIFLAZZ_URL');
        $this->username = env('DIGIFLAZZ_USER');
        $this->apiKey = env('DIGIFLAZZ_KEY');
    }

    private function signature($command)
    {
        return md5($this->username . $this->apiKey . $command);
    }

    public function checkBalance()
    {
        $command = 'depo';
        $signature = $this->signature($command);

        $payload = [
            'cmd' => 'deposit',
            'username' => $this->username,
            'sign' => $signature,
        ];

        Log::info('Check Balance Payload: ', $payload);

        $response = $this->sendRequest('POST', '/cek-saldo', $payload);

        if ($response) {
            Log::info('Check Balance Response: ', $response);
        } else {
            Log::error('Check Balance Response is null');
        }

        return $response;
    }

    public function getPriceList()
    {
        $command = 'pricelist';
        $signature = $this->signature($command);

        $payload = [
            'cmd' => $command,
            'username' => $this->username,
            'sign' => $signature,
        ];

        Log::info('Get Price List Payload: ', $payload);

        $response = $this->sendRequest('POST', '/price-list', $payload);

        if ($response) {
            Log::info('Get Price List Response: ', $response);
        } else {
            Log::error('Get Price List Response is null');
        }

        return $response;
    }

    public function deposit($refId, $amount, $bank, $ownerName)
    {
        $command = 'deposit';
        $signature = $this->signature($command);

        $payload = [
            // 'cmd' => $command,
            'username' => $this->username,
            'amount' => (int)$amount,
            'Bank' => $bank,
            'sign' => $signature,
            'owner_name' => $ownerName,
            // 'ref_id' => $refId,
        ];

        Log::info('Deposit Payload: ', $payload);

        $response = $this->sendRequest('POST', '/deposit', $payload);

        if ($response) {
            Log::info('Deposit Response: ', $response);
        } else {
            Log::error('Deposit Response is null');
        }

        return $response;
    }

    public function topup($buyer_sku_code, $customer_no, $ref_id)
    {
        $signature = $this->signature($ref_id);

        $payload = [
            'username' => $this->username,
            'buyer_sku_code' => $buyer_sku_code,
            'customer_no' => $customer_no,
            'ref_id' => $ref_id,
            'sign' => $signature,
        ];

        Log::info('Transaction Payload: ', $payload);

        $response = $this->sendRequest('POST', '/transaction', $payload);

        if ($response) {
            Log::info('Transaction Response: ', $response);
        } else {
            Log::error('Transaction Response is null');
        }

        return $response;   
    }

    public function cekTagihan($buyer_sku_code, $customer_no, $ref_id)
    {
        $signature = $this->signature($ref_id);

        $payload = [
            'commands' => "inq-pasca",
            'username' => $this->username,
            'buyer_sku_code' => $buyer_sku_code,
            'customer_no' => $customer_no,
            'ref_id' => $ref_id,
            'sign' => $signature,
        ];

        Log::info('Transaction Payload: ', $payload);

        $response = $this->sendRequest('POST', '/transaction', $payload);

        if ($response) {
            Log::info('Transaction Response: ', $response);
        } else {
            Log::error('Transaction Response is null');
        }

        return $response;   
    }

    public function sendRequest($method, $endpoint, $data)
    {
        try {
            $response = $this->client->request($method, $this->baseUrl . $endpoint, [
                'json' => $data
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody) {
                Log::info('API Response: ', $responseBody);
            } else {
                Log::error('API Response is null');
            }

            return $responseBody;
        } catch (\Exception $e) {
            Log::error("Error connecting to DigiFlazz: " . $e->getMessage());
            return null;
        }
    }
}
