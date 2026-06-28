<?php

declare(strict_types=1);

namespace App\Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'merchant_id',
        'type',
        'email',
        'status',
        'payload',
    ];

    protected $casts = [
        'merchant_id' => 'integer',
        'payload' => 'array',
    ];

    public function scopeForMerchant(Builder $query, int $merchantId): Builder
    {
        return $query->where('merchant_id', $merchantId);
    }
}
