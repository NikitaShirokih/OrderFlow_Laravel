<?php

declare(strict_types=1);

use App\Shared\Domain\Exceptions\BusinessRuleException;
use App\Shared\Domain\Exceptions\ResourceNotFoundException;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::get('/api/test/success', function () {
    return ApiResponse::success([
        'message' => 'OrderFlow работает.',
    ]);
});

Route::get('/api/test/not-found', function () {
    throw new ResourceNotFoundException(
        resource: 'order',
        identifier: 100,
    );
});

Route::get('/api/test/business-error', function () {
    throw new BusinessRuleException(
        message: 'Недостаточно товара на складе.',
        codeName: 'insufficient_inventory',
        details: [
            'product_id' => 15,
            'available' => 0,
        ],
    );
});
