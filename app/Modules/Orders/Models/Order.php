<?php

declare(strict_types=1);

namespace App\Modules\Orders\Models;

use App\Modules\Merchant\Models\Merchant;
use App\Modules\Orders\Enums\OrderStatus;
use App\Modules\Payments\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'merchant_id',
        'user_id',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'merchant_id' => 'integer',
        'user_id' => 'integer',
        'status' => OrderStatus::class,
        'total_amount' => 'decimal:2',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeForMerchant(Builder $query, int $merchantId): Builder
    {
        return $query->where('merchant_id', $merchantId);
    }
}
