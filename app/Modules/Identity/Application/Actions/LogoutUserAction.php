<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Actions;

use App\Models\User;

final readonly class LogoutUserAction
{
    public function execute(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
