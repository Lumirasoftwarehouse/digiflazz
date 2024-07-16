<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'category',
        'brand',
        'type',
        'seller_name',
        'price',
        'buyer_sku_code',
        'seller_product_status',
        'unlimited_stock',
        'stock',
        'multi',
        'start_cut_off',
        'end_cut_off',
        'desc',
        'margin'
    ];
}
