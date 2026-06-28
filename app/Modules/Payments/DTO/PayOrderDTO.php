<?php

declare(strict_types=1);

namespace App\Modules\Payments\DTO;

class PayOrderDTO
{
    public function __construct(
        public readonly int $orderId
    ) {
    }

    public static function fromValidated(array $data): self
    {
        return new self((int) $data['order_id']);
    }
}
