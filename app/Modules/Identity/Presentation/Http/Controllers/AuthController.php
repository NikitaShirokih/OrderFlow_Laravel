<?php

declare(strict_types=1);

namespace App\Modules\Identity\Presentation\Http\Controllers;

use App\Models\User;
use App\Modules\Identity\Application\Actions\LoginUserAction;
use App\Modules\Identity\Application\Actions\LogoutUserAction;
use App\Modules\Identity\Application\Actions\RegisterUserAction;
use App\Modules\Identity\Presentation\Http\Requests\LoginRequest;
use App\Modules\Identity\Presentation\Http\Requests\RegisterRequest;
use App\Modules\Identity\Presentation\Http\Resources\UserResource;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final readonly class AuthController
{
    public function __construct(
        private RegisterUserAction $registerUser,
        private LoginUserAction $loginUser,
        private LogoutUserAction $logoutUser,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        /** @var array{name: string, email: string, password: string} $data */
        $data = $request->validated();
        $result = $this->registerUser->execute($data);

        return ApiResponse::success(
            data: [
                'user' => UserResource::make($result['user'])->resolve($request),
                'token' => $result['token'],
            ],
            status: 201,
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        /** @var array{email: string, password: string} $data */
        $data = $request->validated();
        $result = $this->loginUser->execute($data);

        if ($result === null) {
            return ApiResponse::error(
                code: 'invalid_credentials',
                message: 'Неверный email или пароль.',
                status: 401,
            );
        }

        return ApiResponse::success([
            'user' => UserResource::make($result['user'])->resolve($request),
            'token' => $result['token'],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return ApiResponse::success(
            data: UserResource::make($user)->resolve($request),
        );
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->logoutUser->execute($user);

        return ApiResponse::success([
            'message' => 'Вы успешно вышли из системы.',
        ]);
    }
}
