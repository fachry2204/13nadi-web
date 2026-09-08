<?php

namespace Tests\Feature;

use App\Models\MugenFormField;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MugenTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_read_and_submit_mugen_form(): void
    {
        $this->getJson('/api/v1/public/mugen-form')
            ->assertOk()
            ->assertJsonCount(10, 'data');

        $answers = MugenFormField::all()->mapWithKeys(fn ($field) => [
            $field->key => $field->type === 'email' ? 'artist@example.com'
                : ($field->type === 'url' ? 'https://instagram.com/artist'
                    : ($field->type === 'radio' ? $field->options[0] : 'Test value')),
        ])->all();

        $this->postJson('/api/v1/public/mugen', ['answers' => $answers])
            ->assertCreated()
            ->assertJsonPath('message', 'Pendaftaran MUGGEN berhasil dikirim.');

        $this->assertDatabaseCount('mugen_submissions', 1);
    }

    public function test_admin_can_change_mugen_field_types(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $field = MugenFormField::first();

        $this->putJson('/api/v1/admin/mugen/fields', ['fields' => [[
            'id' => $field->id,
            'key' => $field->key,
            'label' => 'Artist Name',
            'type' => 'textarea',
            'options' => [],
            'is_required' => true,
            'is_active' => true,
        ]]])->assertOk()->assertJsonPath('data.fields.0.type', 'textarea');
    }
}
