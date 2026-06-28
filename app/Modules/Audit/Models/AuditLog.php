<?php

declare(strict_types=1);

namespace App\Modules\Audit\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'merchant_id',
        'event',
        'entity_type',
        'entity_id',
        'payload',
    ];

    protected $casts = [
        'merchant_id' => 'integer',
        'entity_id' => 'integer',
        'payload' => 'array',
    ];
}
