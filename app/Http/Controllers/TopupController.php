<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topup;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class TopupController extends Controller
{
    private $secret = 'LeOkBLS7';

    public function mySaldo(Request $request)
    {
        $validateData = $request->validate([
            'id' => 'required'
        ]);

        $dataUser = User::find($validateData['id']);

        return response()->json(['message' => 'success', 'saldo' => $dataUser->saldo]);
    }

    // topup dari user
    public function topUp(Request $request)
    {
        $validateData = $request->validate([
            'jumlah_transaksi' => 'required',
            'bank_account_name' => 'required',
            'bank_type' => 'required',
            'keterangan' => 'required',
            'userId' => 'required',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Simpan file bukti transfer ke local storage
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $path = $file->store('bukti_transfer', 'public');
            $validateData['bukti_transfer'] = $path;
        }

        Topup::create($validateData);

        return response()->json(['message' => 'success']);
    }

    // verifikasi dengan response dari cek mutasi
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

                // cek apakah data topup sama dengan response dari cek mutasi?
                $typeBank = Topup::where('bank_type', $transaction['bank']['bank_type'] ?? null)->first();
                $namaPemilik = Topup::where('bank_account_name', $transaction['bank']['atas_nama'] ?? null)->first();
                $jumlahTransfer = Topup::where('jumlah_transaksi', $transaction['amount'] ?? null)->first();
                
                if ($typeBank && $namaPemilik && $jumlahTransfer && $jumlahTransfer->status == '0') {
                    // lakukan update data dan statusnya dibuat '1'
                    $jumlahTransfer->update([
                        'keterangan' => $transaction['description'] ?? null,
                        'account_number' => $transaction['account_number'] ?? null,
                        'date' => $transaction['date'] ?? null,
                        'type' => $transaction['type'] ?? null,
                        'note' => $transaction['note'] ?? null,
                        'balance' => $transaction['balance'] ?? null,
                        'mutation_id' => $transaction['mutation_id'] ?? null,
                        'bank_id' => $transaction['bank_id'] ?? null,
                        'status' => '1' // sudah divalidasi
                    ]);

                    // Update saldo user
                    $dataUser = User::find($jumlahTransfer->userId);
                    $dataUser->saldo += $transaction['amount'];
                    $dataUser->save();
                }
            }
        }

        Log::info('Received valid webhook:', $data);

        return response()->json(['message' => 'Webhook received successfully'], 200);
    }
}
