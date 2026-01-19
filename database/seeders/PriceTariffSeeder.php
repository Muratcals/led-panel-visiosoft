<?php

namespace Database\Seeders;

use App\Models\PriceTariff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceTariffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tariffs = [
            [
                'time_range' => '0-1 Saat',
                'price' => 0,
                'order' => 1,
                'is_free' => true,
                'is_highlighted' => true,
                'is_active' => true,
            ],
            [
                'time_range' => '1-2 Saat',
                'price' => 150,
                'order' => 2,
                'is_free' => false,
                'is_highlighted' => false,
                'is_active' => true,
            ],
            [
                'time_range' => '2-3 Saat',
                'price' => 200,
                'order' => 3,
                'is_free' => false,
                'is_highlighted' => false,
                'is_active' => true,
            ],
            [
                'time_range' => '3-4 Saat',
                'price' => 250,
                'order' => 4,
                'is_free' => false,
                'is_highlighted' => false,
                'is_active' => true,
            ],
            [
                'time_range' => '4-8 Saat',
                'price' => 300,
                'order' => 5,
                'is_free' => false,
                'is_highlighted' => false,
                'is_active' => true,
            ],
            [
                'time_range' => '8-12 Saat',
                'price' => 350,
                'order' => 6,
                'is_free' => false,
                'is_highlighted' => false,
                'is_active' => true,
            ],
            [
                'time_range' => 'TAM GÜN',
                'price' => 400,
                'order' => 7,
                'is_free' => false,
                'is_highlighted' => false,
                'is_active' => true,
            ],
        ];

        foreach ($tariffs as $tariff) {
            PriceTariff::create($tariff);
        }
    }
}
