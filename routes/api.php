<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Catalog\Controllers\ProductVariantController;
use App\Modules\Catalog\Controllers\StockController;
use App\Modules\Merchant\Controllers\MerchantController;
use App\Modules\Orders\Controllers\OrderController;

Route::middleware('auth:sanctum', 'active.merchant')->group(function () {
    Route::get('/v1/merchants', [MerchantController::class, 'index']);
    Route::post('/v1/merchants', [MerchantController::class, 'store']);
    Route::post('/v1/product-variants', [ProductVariantController::class, 'store']);
    Route::post('/v1/stock/set', [StockController::class, 'set']);
    Route::post('/v1/stock/reserve', [StockController::class, 'reserve']);
    Route::post('/v1/stock/release', [StockController::class, 'release']);
    Route::post('/v1/orders', [OrderController::class, 'store']);
});
