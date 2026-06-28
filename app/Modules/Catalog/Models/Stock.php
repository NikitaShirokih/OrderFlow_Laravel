<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Modules\Merchant\Models\Merchant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $fillable = [
        'product_variant_id',
        'merchant_id',
        'quantity',
        'reserved',
    ];

    protected $casts = [
        'product_variant_id' => 'integer',
        'merchant_id' => 'integer',
        'quantity' => 'integer',
        'reserved' => 'integer',
    ];

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function getAvailableAttribute(): int
    {
        return $this->quantity - $this->reserved;
    }

    public function scopeForMerchant(Builder $query, int $merchantId): Builder
    {
        return $query->where('merchant_id', $merchantId);
    }
}
