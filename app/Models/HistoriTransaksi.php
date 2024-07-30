<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriTransaksi extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'nominal', 'jenis', 'userId'];
}
