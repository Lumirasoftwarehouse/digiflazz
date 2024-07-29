<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListPrice;
use App\Models\Notifikasi;

class ProductController extends Controller
{
    public function listMyProduct()
    {
        // Mengambil semua data dari model ListPrice
        $dataMyProduct = ListPrice::all();

        return response()->json([
            'message' => 'success',
            'data' => $dataMyProduct
        ]);
    }
    
    public function listPulsaData()
    {
        $dataMyProduct = ListPrice::all();

        if ($dataMyProduct->isNotEmpty()) {
            // Filter data untuk hanya menyertakan item dengan kategori 'Pulsa' atau 'Data'
            $pulsaList = $dataMyProduct->filter(function ($item) {
                return $item->category === 'Pulsa' || $item->category === 'Data';
            });
        } else {
            $pulsaList = collect(); // Menggunakan koleksi kosong
        }

        return response()->json([
            'message' => 'success',
            'data' => $pulsaList->values() // Mengembalikan data yang telah difilter
        ]);
    }

    public function addProduct(Request $request)
    {
        $validateData = $request->validate([
            'product_name' => 'required',
            'category' => 'required',
            'brand' => 'required',
            'type' => 'required',
            'seller_name' => 'required',
            'price' => 'required',
            'buyer_sku_code' => 'required',
            'seller_product_status' => 'required',
            'unlimited_stock' => 'required',
            'stock' => 'required',
            'multi' => 'required',
            'start_cut_off' => 'required',
            'end_cut_off' => 'required',
            'desc' => 'required'
        ]);

        if (ListPrice::where('buyer_sku_code', $validateData['buyer_sku_code'])->exists()) {
            return response()->json(['error' => 'true', 'message' => 'Product sudah ada']);
        }

        ListPrice::create($validateData);

        Notifikasi::create([
            'judul' => $validateData['product_name'], 
            'kategori' => 'produk', 
            'deskripsi' => $validateData['desc']
        ]);

        return response()->json(['error' => 'false', 'message' => 'Product berhasil ditambahkan']);
    }

    public function settingMargin(Request $request)
    {
        $validateData = $request->validate([
            'buyer_sku_code' => 'required',
            'margin' => 'required'
        ]);

        $dataProduct = ListPrice::where('buyer_sku_code', $validateData['buyer_sku_code'])->first();
        if ($dataProduct) {
            $dataProduct->margin = $validateData['margin'];
            $dataProduct->save();

            return response()->json(['error' => 'false', 'message' => 'Margin berhasil diatur']);
        }

        return response()->json(['error' => 'true', 'message' => 'Product not found']);
    }

    public function deleteProduct($id)
    {
        $dataProduct = ListPrice::find($id);
        if ($dataProduct) {
            $dataProduct->delete();
            return response()->json(['error' => 'false', 'message' => 'Product berhasil dihapus']);
        }
        return response()->json(['error' => 'true', 'message' => 'Product tidak ditemukan']);
    }
}
