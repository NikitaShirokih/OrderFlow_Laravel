<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class RegisterUserAction
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     * @return array{user: User, token: string}
     */
    public function execute(array $data): array
    {
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken(name: 'auth-token')->plainTextToken,
        ];
    }
}
