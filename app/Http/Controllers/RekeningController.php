<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;

class RekeningController extends Controller
{
    public function listRekening()
    {
        $dataRekening = Rekening::get();

        return response()->json(['message' => 'success', 'data' => $dataRekening]);
    }

    public function createRekening(Request $request)
    {
        $validateData = $request->validate([
            "jenis_bank" => "required",
            "atas_nama" => "required",
            "nomor_rekening" => "required",
        ]);
        $rekening = new Rekening();
        $rekening->jenis_bank =$validateData['jenis_bank'];
        $rekening->atas_nama =$validateData['atas_nama'];
        $rekening->nomor_rekening =$validateData['nomor_rekening'];
        $rekening->save();

        return response()->json(['message' => 'success', 'data' => $rekening], 201);
    }

    public function updateRekening(Request $request, $id)
    {
        $validateData = $request->validate([
            "jenis_bank" => "required",
            "atas_nama" => "required",
            "nomor_rekening" => "required",
        ]);

        $dataRekening = Rekening::find($id);
        if ($dataRekening) {
            $dataRekening->jenis_bank = $validateData['jenis_bank'];
            $dataRekening->atas_nama = $validateData['atas_nama'];
            $dataRekening->nomor_rekening = $validateData['nomor_rekening'];
            $dataRekening->save();
            return response()->json(['message' => 'success'], 200);
        }
        return response()->json(['message' => 'no data found'], 401);
    }

    public function deleteRekening($id)
    {
        $dataRekening = Rekening::find($id);
        if ($dataRekening) {
           $dataRekening->delete();
            return response()->json(['message' => 'success'], 200);
        }
        return response()->json(['message' => 'no data found'], 401);
    }
}
