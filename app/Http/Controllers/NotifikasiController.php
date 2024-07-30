<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function myNotifikasi()
    {
        $dataNotifikasi = Notifikasi::get();

        return response()->json(['message' => 'success', 'data' => $dataNotifikasi], 200);
    }
}
