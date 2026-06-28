<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Controllers\AuditAdminController;
use App\Modules\Admin\Controllers\NotificationsAdminController;
use App\Modules\Admin\Controllers\OrdersAdminController;
use App\Modules\Admin\Controllers\PaymentsAdminController;
use App\Modules\Admin\Controllers\ProductsAdminController;
use App\Modules\Catalog\Controllers\ProductVariantController;
use App\Modules\Catalog\Controllers\StockController;
use App\Modules\Merchant\Controllers\MerchantController;
use App\Modules\Orders\Controllers\OrderController;
use App\Modules\Payments\Controllers\PaymentController;

Route::middleware('auth:sanctum', 'active.merchant')->group(function () {
    Route::get('/v1/merchants', [MerchantController::class, 'index']);
    Route::post('/v1/merchants', [MerchantController::class, 'store']);
    Route::post('/v1/product-variants', [ProductVariantController::class, 'store']);
    Route::post('/v1/stock/set', [StockController::class, 'set']);
    Route::post('/v1/stock/reserve', [StockController::class, 'reserve']);
    Route::post('/v1/stock/release', [StockController::class, 'release']);
    Route::post('/v1/orders', [OrderController::class, 'store']);
    Route::post('/v1/orders/create', [OrderController::class, 'store']);
    Route::post('/v1/orders/reserve', [OrderController::class, 'reserve']);
    Route::post('/v1/orders/pay', [OrderController::class, 'pay']);
    Route::post('/v1/orders/cancel', [OrderController::class, 'cancel']);
    Route::post('/v1/payments/pay', [PaymentController::class, 'pay']);

    Route::prefix('/v1/admin')->group(function () {
        Route::get('/products', [ProductsAdminController::class, 'index']);
        Route::get('/products/{product}', [ProductsAdminController::class, 'show']);
        Route::get('/orders', [OrdersAdminController::class, 'index']);
        Route::get('/orders/{order}', [OrdersAdminController::class, 'show']);
        Route::post('/orders/{order}/cancel', [OrdersAdminController::class, 'cancel']);
        Route::get('/payments', [PaymentsAdminController::class, 'index']);
        Route::get('/payments/{payment}', [PaymentsAdminController::class, 'show']);
        Route::get('/payments/{payment}/status', [PaymentsAdminController::class, 'status']);
        Route::get('/audit-logs', [AuditAdminController::class, 'index']);
        Route::get('/notifications', [NotificationsAdminController::class, 'index']);
    });
});
