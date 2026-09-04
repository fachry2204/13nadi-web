<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('content_items')->where('type', 'banner')->exists()) {
            DB::table('content_items')->insert([
                'type' => 'banner',
                'title' => 'Banner Utama',
                'slug' => 'banner-utama',
                'image_url' => '/slide2.jpg',
                'external_url' => null,
                'metadata' => json_encode(['link_enabled' => false]),
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('content_items')->where('type', 'banner')->where('slug', 'banner-utama')->delete();
    }
};
