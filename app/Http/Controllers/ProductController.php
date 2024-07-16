<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListPrice;

class ProductController extends Controller
{
    public function listMyProduct()
    {
        $dataMyProduct = ListPrice::all();

        if ($dataMyProduct) {
            // Filter the price list to include only items where category is "Pulsa"
            $pulsaList = array_filter($dataMyProduct, function ($item) {
                return isset($item['category']) && $item['category'] === 'Pulsa' || $item['category'] === 'Data';
            });
    
            Log::info('Controller Price List Response: ', $pulsaList);
        } else {
            Log::error('Controller Price List Response is null');
            $pulsaList = [];
        }

        return response()->json([
            'message' => 'success',
            'data' => $dataMyProduct
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
