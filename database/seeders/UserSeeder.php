<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProgramSosial;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'level' => '1',
            'password' => Hash::make('12345'),
        ]);
        ProgramSosial::create([
            'image' => 'image pertama',
            'judul' => 'sosial pertama',
            'deskripsi' => 'deskripsi pertama',
            'status' => '1',
            'id_owner' => '1',
        ]);
        ProgramSosial::create([
            'image' => 'image kedua',
            'judul' => 'sosial kedua',
            'deskripsi' => 'deskripsi kedua',
            'id_owner' => '2',
        ]);
    }
}
