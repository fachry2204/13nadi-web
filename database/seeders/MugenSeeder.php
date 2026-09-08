<?php

namespace Database\Seeders;

use App\Models\MugenFormField;
use Illuminate\Database\Seeder;

class MugenSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            ['artist_band', 'Artist / Band', 'text', true, []],
            ['location', 'Location', 'text', true, []],
            ['bogor_plans', 'Does the artist currently have plans to be in Bogor in the near future?', 'radio', true, ['Yes', 'No', 'Maybe']],
            ['genre', 'Genre', 'text', true, []],
            ['social_media', 'Social Media', 'url', true, []],
            ['your_name', 'Your Name', 'text', true, []],
            ['your_email', 'Your Email', 'email', true, []],
            ['your_whatsapp', 'Your Whatsapp', 'tel', true, []],
            ['has_manager', 'Does Artist Have Manager?', 'radio', true, ['Yes', 'No']],
            ['signed_label', 'Is The Artist Signed to a Label?', 'radio', true, ['Yes', 'No']],
        ];
        foreach ($fields as $order => [$key, $label, $type, $required, $options]) {
            MugenFormField::updateOrCreate(['key' => $key], compact('label', 'type') + ['is_required' => $required, 'is_active' => true, 'sort_order' => $order, 'options' => $options ?? []]);
        }
    }
}
