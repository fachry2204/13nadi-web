<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_create_an_admin_without_exposing_password(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/admin/users', [
            'name' => 'Editor Musik',
            'username' => 'editor-musik',
            'email' => 'editor@example.com',
            'password' => 'SecureAdminPass123',
            'password_confirmation' => 'SecureAdminPass123',
        ])->assertCreated()
            ->assertJsonPath('data.username', 'editor-musik')
            ->assertJsonMissingPath('data.password');

        $user = User::where('username', 'editor-musik')->firstOrFail();
        $this->assertTrue(Hash::check('SecureAdminPass123', $user->password));
        $this->assertDatabaseHas('users', ['id' => $response->json('data.id')]);
    }

    public function test_admin_cannot_delete_the_account_used_for_the_current_session(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->deleteJson("/api/v1/admin/users/{$user->id}")
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Akun yang sedang digunakan tidak dapat dihapus.');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_user_management_requires_authentication(): void
    {
        $this->getJson('/api/v1/admin/users')->assertUnauthorized();
    }
}
