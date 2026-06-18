<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['user', 'token'])
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_access_me_endpoint(): void
    {
        $user = User::factory()->create();
        $this->actingAsApiUser($user);

        $response = $this->getJson('/api/me');

        $response->assertOk()
            ->assertJsonPath('email', $user->email);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $this->actingAsApiUser();

        $response = $this->postJson('/api/logout');

        $response->assertOk()
            ->assertJsonPath('message', 'Logout realizado com sucesso.');
    }

    public function test_protected_routes_require_authentication(): void
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertUnauthorized()
            ->assertJsonPath('message', 'Não autenticado.');
    }
}
