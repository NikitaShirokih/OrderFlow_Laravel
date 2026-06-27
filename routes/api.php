<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MerchantController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/merchants', [MerchantController::class, 'store']);
});