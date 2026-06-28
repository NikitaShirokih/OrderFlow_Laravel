<?php

declare(strict_types=1);

namespace App\Modules\Orders\Events;

readonly class OrderCanceled
{
    public function __construct(
        public int $orderId,
        public int $merchantId
    ) {
    }
}
