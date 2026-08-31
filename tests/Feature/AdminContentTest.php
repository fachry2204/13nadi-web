<?php

namespace Tests\Feature;

use App\Models\ContentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_content_and_persist_it(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $item = ContentItem::create([
            'type' => 'about',
            'title' => 'Judul Lama',
            'slug' => 'tentang-kami',
            'is_active' => true,
        ]);

        $this->putJson("/api/v1/admin/about/{$item->id}", [
            'title' => 'Judul Baru',
            'slug' => 'tentang-kami',
            'description' => 'Konten berhasil disimpan.',
            'image_url' => '/uploads/tentang.jpg',
            'is_active' => true,
        ])->assertOk()->assertJsonPath('data.title', 'Judul Baru');

        $this->assertDatabaseHas('content_items', [
            'id' => $item->id,
            'title' => 'Judul Baru',
            'image_url' => '/uploads/tentang.jpg',
        ]);
    }

    public function test_admin_upload_creates_a_public_https_safe_image_path(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->post('/api/v1/admin/upload/image', [
            'image' => UploadedFile::fake()->image('tentang.jpg', 600, 400),
        ])->assertCreated();

        $url = $response->json('data.url');
        $this->assertStringStartsWith('/uploads/', $url);
        $this->assertFileExists(public_path(ltrim($url, '/')));

        File::delete(public_path(ltrim($url, '/')));
    }
}
