<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class LoginUserAction
{
    /**
     * @param  array{email: string, password: string}  $data
     * @return array{user: User, token: string}|null
     */
    public function execute(array $data): ?array
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if ($user === null || ! Hash::check($data['password'], $user->password)) {
            return null;
        }

        $user->tokens()
            ->where('name', 'auth-token')
            ->delete();

        return [
            'user' => $user,
            'token' => $user->createToken(name: 'auth-token')->plainTextToken,
        ];
    }
}
