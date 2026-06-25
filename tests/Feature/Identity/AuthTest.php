<?php

declare(strict_types=1);

namespace Tests\Feature\Identity;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Никита',
            'email' => 'nikita@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.name', 'Никита')
            ->assertJsonPath('data.user.email', 'nikita@example.com')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'nikita@example.com',
        ]);
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create([
            'email' => 'nikita@example.com',
        ]);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Никита',
            'email' => 'nikita@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('error.code', 'validation_error')
            ->assertJsonValidationErrors('email', 'error.details.fields');
    }

    public function test_password_must_be_confirmed(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Никита',
            'email' => 'nikita@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('error.code', 'validation_error')
            ->assertJsonValidationErrors('password', 'error.details.fields');
    }

    public function test_user_can_login_successfully(): void
    {
        User::factory()->create([
            'name' => 'Никита',
            'email' => 'nikita@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nikita@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'nikita@example.com')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ],
            ]);
    }

    public function test_invalid_password_returns_unauthorized(): void
    {
        User::factory()->create([
            'email' => 'nikita@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nikita@example.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertUnauthorized()
            ->assertExactJson([
                'success' => false,
                'error' => [
                    'code' => 'invalid_credentials',
                    'message' => 'Неверный email или пароль.',
                ],
            ]);
    }

    public function test_guest_cannot_get_current_user(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response
            ->assertUnauthorized()
            ->assertJsonPath('success', false)
            ->assertJsonPath('error.code', 'unauthenticated');
    }

    public function test_authenticated_user_can_get_current_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Никита',
            'email' => 'nikita@example.com',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/auth/me');

        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => 'Никита',
                    'email' => 'nikita@example.com',
                ],
            ]);
    }

    public function test_authenticated_user_can_logout_current_token(): void
    {
        $user = User::factory()->create();
        $currentToken = $user->createToken(name: 'auth-token');
        $otherToken = $user->createToken(name: 'mobile-token');

        $response = $this
            ->withToken($currentToken->plainTextToken)
            ->postJson('/api/v1/auth/logout');

        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'data' => [
                    'message' => 'Вы успешно вышли из системы.',
                ],
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $currentToken->accessToken->id,
        ]);
        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $otherToken->accessToken->id,
        ]);
    }
}
