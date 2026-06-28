<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Domain\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchants';

    protected $fillable = [
        'name',
        'owner_id',
    ];

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'merchant_users'
        )->withPivot('role')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'merchant_users')
        ->withPivot('role')
        ->get();
    }
}
