<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topup;
use App\Models\User;
use App\Models\HistoriTransaksi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class TopupController extends Controller
{
    protected $secret;
    public function __construct()
    {
        $this->secret = env('MOTA_KEY');
    }
    public function mySaldo()
    {
        $dataUser = User::find(auth()->user()->id);

        return response()->json(['message' => 'success', 'saldo' => $dataUser->saldo]);
    }

    // topup dari user
    public function topUp(Request $request)
    {
        try {
            $validateData = $request->validate([
                'bank_type' => 'required',
                'bank_account_name' => 'required',
                'account_number' => 'required',
                'jumlah_transaksi' => 'required',
                'keterangan' => 'required',
                'rekeningId' => 'required',
                'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
    
            // Simpan file bukti transfer ke local storage
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $path = $file->store('bukti_transfer', 'public');
                $validateData['bukti_transfer'] = $path;
            }
    
            $topup = new Topup();
            $topup->bank_type = $validateData['bank_type'];
            $topup->bank_account_name = $validateData['bank_account_name'];
            $topup->account_number = $validateData['account_number'];
            $topup->jumlah_transaksi = $validateData['jumlah_transaksi'];
            $topup->keterangan = $validateData['keterangan'];
            $topup->userId = auth()->user()->id;
            $topup->rekeningId = $validateData['rekeningId'];
            $topup->bukti_transfer = $validateData['bukti_transfer'];
    
            $topup->save();
    
            return response()->json(['message' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Tangani error lainnya
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
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
                $statusTransfer = Topup::where('status', '0')->first();
                
                if ($typeBank && $namaPemilik && $jumlahTransfer && $statusTransfer) {
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

                    HistoriTransaksi::create([
                        'judul' => 'Topup', 
                        'nominal' => $transaction['amount'], 
                        'jenis' => '1', 
                        'userId' => $jumlahTransfer->userId
                    ]);
                    return response()->json(['message' => 'Transaksi valid'], 200);
                }
            }
        }
        
        Log::info('Received valid webhook:', $data);
        
        return response()->json(['message' => 'Webhook received successfully'], 200);
    }


    public function historyTransaksi()
    {
        $dataTransaksi = HistoriTransaksi::where('userId', auth()->user()->id)->get();
        return response()->json(['message' => 'success', 'data' => $dataTransaksi], 200);
    }
}
