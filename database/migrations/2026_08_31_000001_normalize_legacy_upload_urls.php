<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('content_items')
            ->whereNotNull('image_url')
            ->orderBy('id')
            ->each(function (object $item): void {
                $path = parse_url((string) $item->image_url, PHP_URL_PATH);

                if (is_string($path) && Str::startsWith($path, '/uploads/')) {
                    DB::table('content_items')->where('id', $item->id)->update([
                        'image_url' => $path,
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Absolute legacy hosts are intentionally not restored.
    }
};
