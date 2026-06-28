<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Modules\Merchant\Models\Merchant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'merchant_id',
        'name',
        'sku',
        'price',
        'attributes',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'merchant_id' => 'integer',
        'price' => 'decimal:2',
        'attributes' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function scopeForMerchant(Builder $query, int $merchantId): Builder
    {
        return $query->where('merchant_id', $merchantId);
    }
}
