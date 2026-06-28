<?php

declare(strict_types=1);

namespace App\Modules\Catalog\DTO;

class ReserveStockDTO
{
    public function __construct(
        public readonly int $variantId,
        public readonly int $quantity
    ) {
    }

    public static function fromValidated(array $data): self
    {
        return new self((int) $data['variant_id'], (int) $data['quantity']);
    }
}
