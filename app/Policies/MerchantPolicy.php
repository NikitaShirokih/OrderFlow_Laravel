<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Merchant;

class MerchantPolicy
{
    public function view(User $user, Merchant $merchant): bool
    {
        return $user->merchants()
            ->where('merchant_id', $merchant->id)
            ->exists();
    }

    public function update(User $user, Merchant $merchant): bool
    {
        return $user->hasMerchantRole($merchant->id, 'owner')
            || $user->hasMerchantRole($merchant->id, 'manager');
    }

    public function delete(User $user, Merchant $merchant): bool
    {
        return $user->hasMerchantRole($merchant->id, 'owner');
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

public function create(User $user): bool
    {
        return true;
    }
}