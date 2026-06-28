<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'merchant_id',
        'name',
        'sku',
        'status',
    ];

    public function merchant()
    {
        return $this->belongsTo(\App\Modules\Merchant\Models\Merchant::class);
    }

    public function scopeForMerchant($query, int $merchantId)
    {
        return $query->where('merchant_id', $merchantId);
    }
}
