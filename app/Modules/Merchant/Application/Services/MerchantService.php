<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Application\Services;

use App\Modules\Merchant\Domain\Models\Merchant;

class MerchantService
{
    public function create(int $userId, array $data): Merchant
    {
        return Merchant::create([
            'name' => $data['name'],
            'owner_id' => $userId,
        ]);
    }

    public function listForUser(int $userId)
    {
        return Merchant::query()
        ->where('owner_id', $userId)
        ->get();
    }
}
