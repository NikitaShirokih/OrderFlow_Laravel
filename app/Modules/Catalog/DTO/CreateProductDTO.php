<?php

declare(strict_types=1);

namespace App\Modules\Catalog\DTO;

class CreateProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $sku
    ) {
    }

    public static function fromValidated(array $data): self
    {
        return new self($data['name'], $data['sku']);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
        ];
    }
}
