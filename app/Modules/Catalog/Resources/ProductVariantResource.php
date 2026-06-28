<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'merchant_id' => $this->merchant_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'attributes' => $this->attributes,
            'stock' => $this->whenLoaded(
                'stock',
                fn () => new StockResource($this->stock)
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
