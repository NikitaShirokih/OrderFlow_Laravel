<?php

declare(strict_types=1);

namespace App\Modules\Orders\Events;

readonly class OrderReserved
{
    public function __construct(
        public int $orderId,
        public int $merchantId
    ) {
    }
}
