<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DigiFlazzService;
use App\Models\ProgramSosial;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DigiFlazzController extends Controller
{
    protected $digiflazzService;

    public function __construct(DigiFlazzService $digiflazzService)
    {
        $this->digiflazzService = $digiflazzService;
    }

    public function checkBalance()
    {
        $balance = $this->digiflazzService->checkBalance();

        if ($balance) {
            Log::info('Controller Balance Response: ', $balance);
        } else {
            Log::error('Controller Balance Response is null');
        }

        return response()->json($balance);
    }

    public function getPriceList()
    {
        $priceList = $this->digiflazzService->getPriceList();

        if ($priceList) {
            Log::info('Controller Price List Response: ', $priceList);
        } else {
            Log::error('Controller Price List Response is null');
        }

        return response()->json($priceList);
    }

    public function getPriceListPulsaData()
    {
        $priceList = $this->digiflazzService->getPriceList();
    
        if ($priceList) {
            // Filter the price list to include only items where category is "Pulsa"
            $pulsaList = array_filter($priceList['data'], function ($item) {
                return isset($item['category']) && $item['category'] === 'Pulsa' || $item['category'] === 'Data';
            });
    
            Log::info('Controller Price List Response: ', $pulsaList);
        } else {
            Log::error('Controller Price List Response is null');
            $pulsaList = [];
        }
    
        return response()->json(['data' => array_values($pulsaList)]);
    }

    public function getPriceListGame()
    {
        $priceList = $this->digiflazzService->getPriceList();
    
        if ($priceList) {
            // Filter the price list to include only items where category is "Pulsa"
            $pulsaList = array_filter($priceList['data'], function ($item) {
                return isset($item['category']) && $item['category'] === 'Games';
            });
    
            Log::info('Controller Price List Response: ', $pulsaList);
        } else {
            Log::error('Controller Price List Response is null');
            $pulsaList = [];
        }
    
        return response()->json(['data' => array_values($pulsaList)]);
    }

    public function getPriceListVoucherGame(Request $request)
    {
        $priceList = $this->digiflazzService->getPriceList();
    
        if ($priceList) {
            // Filter the price list to include only items where category is "Games" and brand matches the request brand
            $pulsaList = array_filter($priceList['data'], function ($item) use ($request) {
                return isset($item['category']) && $item['category'] === 'Games' && strcasecmp($item['brand'], $request->brand) === 0;
            });
    
            Log::info('Controller Price List Response: ', $pulsaList);
        } else {
            Log::error('Controller Price List Response is null');
            $pulsaList = [];
        }
    
        return response()->json(['data' => array_values($pulsaList)]);
    }
    

    public function deposit(Request $request)
    {
        $refId = $request->input('ref_id');
        $amount = $request->input('amount');
        $bank = $request->input('bank');
        $ownerName = $request->input('owner_name');

        $deposit = $this->digiflazzService->deposit($refId, $amount, $bank, $ownerName);

        if ($deposit) {
            Log::info('Controller Deposit Response: ', $deposit);
        } else {
            Log::error('Controller Deposit Response is null');
        }

        return response()->json($deposit);
    }

    public function topup(Request $request)
    {
        $buyer_sku_code = $request->input('buyer_sku_code');
        $customer_no = $request->input('customer_no');
        $ref_id = $request->input('ref_id');

        $topup = $this->digiflazzService->topup($buyer_sku_code, $customer_no, $ref_id);

        if ($topup) {
            Log::info('Controller Topup Response: ', $topup);
        } else {
            Log::error('Controller Topup Response is null');
        }

        return response()->json($topup);
    }

    public function cekTagihan(Request $request)
    {
        $buyer_sku_code = $request->input('buyer_sku_code');
        $customer_no = $request->input('customer_no');
        $ref_id = $request->input('ref_id');

        $cekTagihan = $this->digiflazzService->cekTagihan($buyer_sku_code, $customer_no, $ref_id);
        Log::info('Controller Cek Tagihan Response: ');

        if ($cekTagihan) {
            Log::info('Controller Cek Tagihan Response: ', $cekTagihan);
        } else {
            Log::error('Controller Cek Tagihan Response is null');
        }

        return response()->json($cekTagihan);
    }
   
    public function bayarTagihan(Request $request)
    {
        $buyer_sku_code = $request->input('buyer_sku_code');
        $customer_no = $request->input('customer_no');
        $ref_id = $request->input('ref_id');
        $harga = $request->input('harga');
        $margin = $request->input('margin');
        $program_id = $request->input('program_id');


        $dataUser = User::find(auth()->user()->id);
        if ($dataUser->saldo > $harga) {
    
            $dataProgram = ProgramSosial::find($program_id);
            $dataProgram->saldo = $margin/2;
            $dataProgram->save();

            $dataUser->saldo = $dataUser->saldo - $harga;
            $dataUser->save();
            
            $cekTagihan = $this->digiflazzService->bayarTagihan($buyer_sku_code, $customer_no, $ref_id);
    
            if ($cekTagihan) {
                Log::info('Controller Bayar Tagihan Response: ', $cekTagihan);
            } else {
                Log::error('Controller Bayar Tagihan Response is null');
            }
    
            return response()->json(['message' => 'success', 'data' =>$cekTagihan], 200);
        }
        return response()->json(['message' => 'failed'], 400);

    }

    public function inquiryPln(Request $request)
    {
        $customer_no = $request->input('customer_no');

        $hasilInquiry = $this->digiflazzService->inquiryPln($customer_no);

        if ($hasilInquiry) {
            Log::info('Controller Cek Tagihan Response: ', $hasilInquiry);
        } else {
            Log::error('Controller Cek Tagihan Response is null');
        }

        return response()->json($hasilInquiry);
    }
}
