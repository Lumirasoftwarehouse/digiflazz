<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgramSosial;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProgramSosialController extends Controller
{
    public function listProgramSosial()
    {
        try {
            $dataProgramSosial = ProgramSosial::all();

            return response()->json(['message' => 'success', 'data' => $dataProgramSosial], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function detailProgramSosial($id)
    {
        try {
            $dataDetailProgramSosial = ProgramSosial::findOrFail($id);

            return response()->json(['message' => 'success', 'data' => $dataDetailProgramSosial], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function createProgramSosial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'error', 'errors' => $validator->errors()], 400);
        }

        try {
            $imagePath = $request->file('image')->store('images', 'public');

            ProgramSosial::create([
                'image' => Storage::url($imagePath),
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
            ]);

            return response()->json(['message' => 'success'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateProgramSosial(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'error', 'errors' => $validator->errors()], 400);
        }

        try {
            $dataProgramSosial = ProgramSosial::findOrFail($id);

            if ($request->hasFile('image')) {
                // Delete old image
                if ($dataProgramSosial->image) {
                    $oldImagePath = str_replace('/storage/', '', $dataProgramSosial->image);
                    Storage::disk('public')->delete($oldImagePath);
                }

                // Store new image
                $imagePath = $request->file('image')->store('images', 'public');
                $dataProgramSosial->image = Storage::url($imagePath);
            }

            $dataProgramSosial->judul = $request->judul;
            $dataProgramSosial->deskripsi = $request->deskripsi;
            $dataProgramSosial->save();

            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteProgramSosial($id)
    {
        try {
            $dataProgramSosial = ProgramSosial::findOrFail($id);

            // Delete image
            if ($dataProgramSosial->image) {
                $imagePath = str_replace('/storage/', '', $dataProgramSosial->image);
                Storage::disk('public')->delete($imagePath);
            }

            $dataProgramSosial->delete();

            return response()->json(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'error', 'error' => $e->getMessage()], 500);
        }
    }
}
