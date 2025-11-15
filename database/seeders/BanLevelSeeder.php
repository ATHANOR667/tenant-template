<?php

namespace Database\Seeders;

use App\Models\BanLevel;
use Illuminate\Database\Seeder;

class BanLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Avertissement',
                'duration_days' => 1,
                'color' => '#FFFF00',
            ],
            [
                'name' => 'Suspension',
                'duration_days' => 7,
                'color' => '#FFA500',
            ],
            [
                'name' => 'Permanent',
                'duration_days' => null,
                'color' => '#FF0000',
            ],
        ];

        for ($i = 0; $i < 3; $i++) {
            BanLevel::factory()->create($levels[$i]);
        }
    }
}
