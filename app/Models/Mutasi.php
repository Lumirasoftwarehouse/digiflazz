<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;

    protected $fillable = [
        "jumlah_transaksi", 
        "keterangan",
        "account_number",
        "date",
        "type",
        "note",
        "balance",
        "mutation_id",
        "bank_id"
    ];
}
