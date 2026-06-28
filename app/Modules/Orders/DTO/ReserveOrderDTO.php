<?php

declare(strict_types=1);

namespace App\Modules\Orders\DTO;

class ReserveOrderDTO
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
