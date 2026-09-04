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

    public function test_admin_can_manage_a_linkable_home_banner(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/admin/banner', [
            'title' => 'Banner Promo',
            'slug' => 'banner-promo',
            'image_url' => '/uploads/banner-promo.webp',
            'external_url' => 'https://example.com/promo',
            'metadata' => ['link_enabled' => true],
            'sort_order' => 0,
            'is_active' => true,
        ])->assertCreated();

        $this->assertDatabaseHas('content_items', [
            'id' => $response->json('data.id'),
            'type' => 'banner',
            'image_url' => '/uploads/banner-promo.webp',
            'external_url' => 'https://example.com/promo',
            'is_active' => true,
        ]);

        $this->getJson('/api/v1/public/banners')
            ->assertOk()
            ->assertJsonFragment([
                'slug' => 'banner-promo',
                'link_enabled' => true,
            ]);
    }
}
