<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;


Route::get('/', [EmailController::class,'index']);
Route::get('/password/{id}', [EmailController::class, 'password'])->name('password');
Route::post('/aktivasi', [EmailController::class, 'aktivasi'])->name('aktivasi');
