<?php

declare(strict_types=1);

namespace App\Services\Merchant;

use App\Models\Merchant;

class MerchantService
{
    public function create(int $userId, array $data): Merchant
    {
        return Merchant::create([
            'name' => $data['name'],
            'owner_id' => $userId,
        ]);
    }
}