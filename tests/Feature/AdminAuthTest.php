<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_require_authentication(): void
    {
        $this->getJson('/api/v1/admin/release')->assertUnauthorized();
    }

    public function test_username_login_returns_safe_user_and_protected_access(): void
    {
        User::create([
            'name' => 'Nadiku Admin',
            'username' => 'admin',
            'email' => 'admin@13nadi.local',
            'password' => Hash::make('admin'),
        ]);

        $login = $this->postJson('/api/v1/auth/login', [
            'username' => 'admin',
            'password' => 'admin',
        ])->assertOk()
          ->assertJsonMissingPath('data.user.password')
          ->assertJsonPath('data.user.username', 'admin');

        $token = $login->json('data.token');
        $this->withToken($token)->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonMissingPath('data.password');

        $this->withToken($token)->postJson('/api/v1/auth/logout')->assertOk();
    }
}
