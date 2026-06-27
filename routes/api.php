<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MerchantController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/v1/merchants', [MerchantController::class, 'index']);
    Route::post('/v1/merchants', [MerchantController::class, 'store']);
});