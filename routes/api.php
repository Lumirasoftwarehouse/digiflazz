<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DigiFlazzController;
use App\Http\Controllers\ProgramSosialController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RekeningController;


Route::group([
    'prefix' => 'auth'
  ], function () {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('import', [AuthController::class,'import']);
    Route::group([
        'middleware' => 'auth:api'
    ], function(){
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class,'me']);
        
        // voting process
        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            Route::post('make-pin', [AuthController::class,'makePin']);
            Route::post('verify', [AuthController::class,'verifyPin']);
        });
    });
});




Route::get('/check-balance', [DigiFlazzController::class, 'checkBalance']);
Route::get('/price-list-pulsa', [DigiFlazzController::class, 'getPriceListPulsa']);
Route::post('/deposit', [DigiFlazzController::class, 'deposit']);
Route::post('/topup', [DigiFlazzController::class, 'topup']);
Route::post('/cek-tagihan', [DigiFlazzController::class, 'cekTagihan']);
Route::post('/bayar-tagihan', [DigiFlazzController::class, 'bayarTagihan']);
Route::post('/inquiry-pln', [DigiFlazzController::class, 'inquiryPln']);


Route::get('/my-saldo', [TopupController::class, 'mySaldo']);
Route::post('/topup-saldo', [TopupController::class, 'topUp']);
Route::post('/handle', [TopupController::class, 'handle']);


Route::group([
    'prefix' => 'harga'
], function () {
    // Route::group([
        //     'middleware' => 'auth:api'
        // ], function () {
        Route::get('semua', [DigiFlazzController::class, 'getPriceList']);
        Route::get('pulsa-data', [DigiFlazzController::class, 'getPriceListPulsaData']);
        Route::get('games', [DigiFlazzController::class, 'getPriceListGame']);
        Route::post('voucher-game', [DigiFlazzController::class, 'getPriceListVoucherGame']);
    // });
});

Route::group([
    'prefix' => 'product'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list-product', [ProductController::class, 'listMyProduct']);
    Route::get('pulsa-data', [ProductController::class, 'listPulsaData']);
    Route::post('add-product', [ProductController::class, 'AddProduct']);
    Route::post('margin-product', [ProductController::class, 'settingMargin']);
    Route::delete('delete-product/{id}', [ProductController::class, 'deleteProduct']);
    // });
});

Route::group([
    'prefix' => 'rekening'
], function () {
    // Route::group([
    //     'middleware' => 'auth:api'
    // ], function () {
    Route::get('list', [RekeningController::class, 'listRekening']);
    Route::post('create', [RekeningController::class, 'createRekening']);
    Route::post('update/{id}', [RekeningController::class, 'updateRekening']);
    Route::delete('delete/{id}', [RekeningController::class, 'deleteRekening']);
    // });
});




Route::group([
    'prefix' => 'sosial'
], function () {
    Route::group([
            'middleware' => 'auth:api'
        ], function () {
        Route::get('list-sosial', [ProgramSosialController::class, 'listProgramSosial']);
        Route::get('my-program', [ProgramSosialController::class, 'myProgramSosial']);
        Route::get('detail-sosial/{id}', [ProgramSosialController::class, 'detailProgramSosial']);
        Route::post('create-sosial', [ProgramSosialController::class, 'createProgramSosial']);
        Route::post('update-sosial/{id}', [ProgramSosialController::class, 'updateProgramSosial']);
        Route::delete('delete-sosial/{id}', [ProgramSosialController::class, 'deleteProgramSosial']);
        Route::post('change-status', [ProgramSosialController::class, 'changeStatus']);
    });
});