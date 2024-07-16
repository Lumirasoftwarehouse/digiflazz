<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topup extends Model
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
        "bank_id",
        "bank_account_name",
        "bank_type",
        "bukti_transfer",
        "status",
        "userId"
    ];
}
