<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name',   'value' => 'Bubble JJ'],
            ['key' => 'description', 'value' => 'Masukkan deskripsi disini..'],
            ['key' => 'keywords',   'value' => 'laravel,app,demo'],
            ['key' => 'author',     'value' => 'Admin'],
            ['key' => 'copyright', 'value' => 'PT Digjaya Mahakarya Teknologi'],
            ['key' => 'favicon',    'value' => 'assets/images/brand-logos/fav.ico'],
            ['key' => 'logo',       'value' => 'assets/images/brand-logos/desktop-logo.png'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
