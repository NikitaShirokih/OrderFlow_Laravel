<?php

declare(strict_types=1);

namespace App\Modules\Orders\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'user_id' => $this->user_id,
            'status' => $this->status?->value ?? $this->status,
            'total_amount' => $this->total_amount,
            'items' => $this->whenLoaded('items'),
            'payment' => $this->whenLoaded('payment'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
