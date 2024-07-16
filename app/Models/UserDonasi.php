<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDonasi extends Model
{
    use HasFactory;

    protected $fillable = ['id_program', 'id_user', 'jumlah_donasi'];
}
