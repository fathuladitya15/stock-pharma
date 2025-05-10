<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'Stock Pharma'],
            ['key' => 'app_logo', 'value' => 'logo.png'],
            ['key' => 'app_description', 'value' => 'Ini adalah deskripsi aplikasi.'],
            ['key' => 'app_email', 'value' => 'admin@example.com'],
            ['key' => 'app_phone', 'value' => '081234567890'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
