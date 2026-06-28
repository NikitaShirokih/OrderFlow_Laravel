<?php

declare(strict_types=1);

namespace App\Modules\Payments\Events;

readonly class PaymentCreated
{
    public function __construct(
        public int $paymentId,
        public int $orderId,
        public int $merchantId
    ) {
    }
}
