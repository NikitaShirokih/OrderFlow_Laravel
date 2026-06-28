<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Events;

readonly class StockReleased
{
    public function __construct(
        public int $stockId,
        public int $variantId,
        public int $merchantId,
        public int $quantity
    ) {
    }
}
