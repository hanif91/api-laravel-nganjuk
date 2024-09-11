<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(["prefix" => "v1", "as"=>"v1."],function() {
    Route::get('/', [ApiController::class, 'index'])->name('index');

    Route::middleware(['auth:sanctum','log.request'])->group(function(){

        Route::get('tagihan-pelanggan',[ApiController::class,'getTagihan'])
            ->name('tagihan-pelanggan');

        Route::post('bayar-tagihan',[ApiController::class,'bayarTagihan'])
            ->name('bayar-tagihan');

        Route::get('lpp',[ApiController::class,'getLpp'])
            ->name('lpp');

        Route::get('cek-lpp',[ApiController::class,'getLppByNomor'])
            ->name('cek-lpp');
    });
});

