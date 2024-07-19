<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $fillable = [
        "jenis_bank",
        "atas_nama",
        "nomor_rekening",
        "rekeningId"
    ];
}
