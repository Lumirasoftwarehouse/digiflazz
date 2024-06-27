<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DigiFlazzController;
use App\Http\Controllers\ProgramSosialController;

Route::get('/check-balance', [DigiFlazzController::class, 'checkBalance']);
Route::get('/price-list-pulsa', [DigiFlazzController::class, 'getPriceListPulsa']);
Route::post('/deposit', [DigiFlazzController::class, 'deposit']);
Route::post('/topup', [DigiFlazzController::class, 'topup']);
Route::post('/cek-tagihan', [DigiFlazzController::class, 'cekTagihan']);
Route::post('/bayar-tagihan', [DigiFlazzController::class, 'bayarTagihan']);




Route::group([
    'prefix' => 'tagihan'
], function () {
    // Route::group([
        //     'middleware' => 'auth:api'
        // ], function () {
        Route::post('pln', [DigiFlazzController::class, 'cekTagihan']);
    // });
});


Route::group([
    'prefix' => 'harga'
], function () {
    // Route::group([
        //     'middleware' => 'auth:api'
        // ], function () {
        Route::get('semua', [DigiFlazzController::class, 'getPriceList']);
        Route::get('pulsa-data', [DigiFlazzController::class, 'getPriceListPulsaData']);
    // });
});




Route::group([
    'prefix' => 'sosial'
], function () {
    // Route::group([
        //     'middleware' => 'auth:api'
        // ], function () {
        Route::get('list-sosial', [ProgramSosialController::class, 'listProgramSosial']);
        Route::get('detail-sosial/{id}', [ProgramSosialController::class, 'detailProgramSosial']);
        Route::post('create-sosial', [ProgramSosialController::class, 'createProgramSosial']);
        Route::post('update-sosial/{id}', [ProgramSosialController::class, 'updateProgramSosial']);
        Route::delete('delete-sosial/{id}', [ProgramSosialController::class, 'deleteProgramSosial']);
    // });
});