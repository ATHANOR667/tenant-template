<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\AdminBase\Models\UserNotificationPreference;

class UserNotificationPreferenceFactory extends Factory
{
    protected $model = UserNotificationPreference::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'notifiable_id' => null, // À définir dans le seeder
            'notifiable_type' => null, // À définir dans le seeder
            'preferences' => [
                'mail' => $this->faker->boolean,
                'sms' => $this->faker->boolean,
                'whatsapp' => $this->faker->boolean,
            ],
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

