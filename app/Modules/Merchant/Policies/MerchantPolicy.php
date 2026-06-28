<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Policies;

use App\Modules\Merchant\Models\Merchant;
use App\Models\User;

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
