<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DigiFlazzController;

Route::get('/check-balance', [DigiFlazzController::class, 'checkBalance']);
Route::get('/price-list', [DigiFlazzController::class, 'getPriceList']);
Route::post('/deposit', [DigiFlazzController::class, 'deposit']);
Route::post('/topup', [DigiFlazzController::class, 'topup']);
Route::post('/cek-tagihan', [DigiFlazzController::class, 'cekTagihan']);
Route::post('/bayar-tagihan', [DigiFlazzController::class, 'bayarTagihan']);