<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Merchant\Controllers\MerchantController;

Route::middleware('auth:sanctum', 'active.merchant')->group(function () {
    Route::get('/v1/merchants', [MerchantController::class, 'index']);
    Route::post('/v1/merchants', [MerchantController::class, 'store']);
});
