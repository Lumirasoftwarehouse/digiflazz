<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MutasiBankService;
use GuzzleHttp\Client;
use App\Models\Mutasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class MutasiBankController extends Controller
{
    private $secret = 'HJ4VUQ6Q'; // Gantilah dengan secret yang sesuai

    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Signature');
        $computedSignature = hash_hmac('sha256', $payload, $this->secret);

        if (!hash_equals($computedSignature, $signature)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);

        if (is_array($data)) {
            foreach ($data as $transaction) {
                Mutasi::create([
                    'jumlah_transaksi' => $transaction['amount'] ?? null,
                    'keterangan' => $transaction['description'] ?? null,
                    'account_number' => $transaction['account_number'] ?? null,
                    'date' => $transaction['date'] ?? null,
                    'type' => $transaction['type'] ?? null,
                    'note' => $transaction['note'] ?? null,
                    'balance' => $transaction['balance'] ?? null,
                    'mutation_id' => $transaction['mutation_id'] ?? null,
                    'bank_id' => $transaction['bank_id'] ?? null
                ]);
            }
        }

        Log::info('Received valid webhook:', $data);

        return response()->json(['message' => 'Webhook received successfully'], 200);
    }

    public function webHook(Request $request)
    {
        $requestData = $request->validate([
            'jumlah_transaksi' => 'required',
            'keterangan' => 'required'
        ]);
        Mutasi::create([
            'jumlah_transaksi' => $requestData['jumlah_transaksi'],
            'keterangan' => $requestData['keterangan']
        ]);

        return response()->json(['message' => 'success']);
    }
    // protected $client;
    // protected $apiUrl;
    // protected $apiKey;

    // public function __construct()
    // {
        // $this->client = new Client();
        // $this->apiUrl = env('MUTASIBANK_API_URL');
        // $this->apiKey = env('MUTASIBANK_API_KEY');
    // }

    // public function getCurrentUser()
    // {
    //     $response = $this->client->request('GET', $this->apiUrl . 'user', [
    //         'headers' => [
    //             'Authorization' => $this->apiKey,
    //         ]
    //     ]);

    //     $dataResponse = json_decode($response->getBody()->getContents(), true);

    //     return response()->json(['data'=> $dataResponse]);
    // }

    // public function getAccpunts()
    // {
    //     $response = $this->client->request('GET', $this->apiUrl . 'accounts', [
    //         'headers' => [
    //             'Authorization' => $this->apiKey,
    //         ]
    //     ]);

    //     $dataResponse = json_decode($response->getBody()->getContents(), true);

    //     return response()->json(['data'=> $dataResponse]);
    // }
    
    // public function showMutations()
    // {
    //     $response = $this->client->request('GET', $this->apiUrl . 'account/:6684ec0a29026', [
    //         'headers' => [
    //             'Authorization' => $this->apiKey,
    //         ]
    //     ]);

    //     $dataResponse = json_decode($response->getBody()->getContents(), true);

    //     return response()->json(['data'=> $dataResponse]);
    // }

    // public function showMutations(Request $request, $accountId)
    // {
    //     $mutations = $this->mutasiBankService->getMutations($accountId);

    //     return response()->json(['data'=> $mutations]);
    // }
}
