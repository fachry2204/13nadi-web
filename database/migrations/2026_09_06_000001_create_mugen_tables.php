<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mugen_form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('type')->default('text');
            $table->json('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('mugen_submissions', function (Blueprint $table) {
            $table->id();
            $table->json('answers');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        $now = now();
        $fields = [
            ['artist_band', 'Artist / Band', 'text', []],
            ['location', 'Location', 'text', []],
            ['bogor_plans', 'Does the artist currently have plans to be in Bogor in the near future?', 'radio', ['Yes', 'No', 'Maybe']],
            ['genre', 'Genre', 'text', []],
            ['social_media', 'Social Media', 'url', []],
            ['your_name', 'Your Name', 'text', []],
            ['your_email', 'Your Email', 'email', []],
            ['your_whatsapp', 'Your Whatsapp', 'tel', []],
            ['has_manager', 'Does Artist Have Manager?', 'radio', ['Yes', 'No']],
            ['signed_label', 'Is The Artist Signed to a Label?', 'radio', ['Yes', 'No']],
        ];
        DB::table('mugen_form_fields')->insert(array_map(fn ($field, $order) => [
            'key' => $field[0], 'label' => $field[1], 'type' => $field[2],
            'options' => json_encode($field[3]), 'is_required' => true,
            'is_active' => true, 'sort_order' => $order,
            'created_at' => $now, 'updated_at' => $now,
        ], $fields, array_keys($fields)));
    }

    public function down(): void
    {
        Schema::dropIfExists('mugen_submissions');
        Schema::dropIfExists('mugen_form_fields');
    }
};
