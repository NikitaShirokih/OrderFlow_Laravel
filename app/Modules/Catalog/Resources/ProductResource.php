<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'status' => $this->status,
            'variants' => $this->whenLoaded(
                'variants',
                fn () => ProductVariantResource::collection($this->variants)
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
