<?php

declare(strict_types=1);

namespace App\Modules\Catalog\DTO;

class CreateProductVariantDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly string $name,
        public readonly string $sku,
        public readonly float $price,
        public readonly ?array $attributes = null
    ) {
    }

    public static function fromValidated(array $data): self
    {
        return new self(
            (int) $data['product_id'],
            $data['name'],
            $data['sku'],
            (float) $data['price'],
            $data['attributes'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'attributes' => $this->attributes,
        ];
    }
}
