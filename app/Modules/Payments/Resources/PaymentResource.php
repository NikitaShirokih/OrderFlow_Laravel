<?php

declare(strict_types=1);

namespace App\Modules\Payments\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'merchant_id' => $this->merchant_id,
            'status' => $this->status?->value ?? $this->status,
            'amount' => $this->amount,
            'provider' => $this->provider,
            'order' => $this->whenLoaded('order'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
