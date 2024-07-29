<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSosial extends Model
{
    use HasFactory;

    protected $fillable = [
        'image', 
        'judul', 
        'deskripsi',
        'status',
        'saldo',
        'id_owner'
    ];
}
