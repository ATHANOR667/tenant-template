<?php

namespace Database\Factories;

use App\Models\BanLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BanLevelFactory extends Factory
{
    protected $model = BanLevel::class;

    public function definition(): array
    {
        $names = ['Avertissement', 'Suspension', 'Permanent'];
        $durations = [1, 7, null];
        $colors = ['#FFFF00', '#FFA500', '#FF0000'];

        $index = $this->faker->numberBetween(0, 2);

        return [
            'id' => (string) Str::uuid(),
            'name' => $names[$index],
            'duration_days' => $durations[$index],
            'color' => $colors[$index],
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
