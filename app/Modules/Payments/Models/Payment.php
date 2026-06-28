<?php

declare(strict_types=1);

namespace App\Modules\Payments\Models;

use App\Modules\Merchant\Models\Merchant;
use App\Modules\Orders\Models\Order;
use App\Modules\Payments\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'merchant_id',
        'status',
        'amount',
        'provider',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'merchant_id' => 'integer',
        'status' => PaymentStatus::class,
        'amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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
