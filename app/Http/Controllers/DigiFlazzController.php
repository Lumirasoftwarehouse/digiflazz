<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DigiFlazzService;
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
}
