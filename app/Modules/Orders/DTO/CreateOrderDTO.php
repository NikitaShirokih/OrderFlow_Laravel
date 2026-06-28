<?php

declare(strict_types=1);

namespace App\Modules\Orders\DTO;

class CreateOrderDTO
{
    public function __construct(
        public readonly array $items
    ) {
    }

    public static function fromValidated(array $data): self
    {
        return new self($data['items']);
    }
}
