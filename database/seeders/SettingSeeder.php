<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'brand_name',
                'value' => 'TOGER',
                'type' => 'text',
                'description' => 'Ana marka adı',
            ],
            [
                'key' => 'brand_website',
                'value' => 'toger.co',
                'type' => 'text',
                'description' => 'Marka web sitesi',
            ],
            [
                'key' => 'sub_brand_name',
                'value' => 'TPS',
                'type' => 'text',
                'description' => 'Alt marka adı',
            ],
            [
                'key' => 'sub_brand_description',
                'value' => 'Transit Park Sistemi',
                'type' => 'text',
                'description' => 'Alt marka açıklaması',
            ],
            [
                'key' => 'contact_phone',
                'value' => '(+90) 212 909 56 76',
                'type' => 'text',
                'description' => 'İletişim telefon numarası',
            ],
            [
                'key' => 'qr_url',
                'value' => 'https://toger.co',
                'type' => 'text',
                'description' => 'QR kod URL adresi',
            ],
            [
                'key' => 'logo_url',
                'value' => '',
                'type' => 'text',
                'description' => 'Ana logo URL (örnek: images/metropark-icon.webp)',
            ],
            [
                'key' => 'payment_icon_url',
                'value' => '',
                'type' => 'text',
                'description' => 'Ödeme ikonu URL',
            ],
            [
                'key' => 'hgs_icon_url',
                'value' => '',
                'type' => 'text',
                'description' => 'HGS ikonu URL',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
