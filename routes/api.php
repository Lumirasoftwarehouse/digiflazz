<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DigiFlazzController;

Route::get('/check-balance', [DigiFlazzController::class, 'checkBalance']);
Route::get('/price-list', [DigiFlazzController::class, 'getPriceList']);
Route::post('/deposit', [DigiFlazzController::class, 'deposit']);
Route::post('/topup', [DigiFlazzController::class, 'topup']);