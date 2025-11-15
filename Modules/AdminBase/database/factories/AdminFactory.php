<?php

namespace Modules\AdminBase\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\AdminBase\Models\Admin;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'matricule' => 'ADM' . $this->faker->unique()->numberBetween(1000, 9999),
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password123'),
            'telephone' => $this->faker->phoneNumber,
            'passcode' => $this->faker->numberBetween(100000, 999999),
            'dateNaissance' => $this->faker->dateTimeBetween('-40 years', '-20 years'),
            'lieuNaissance' => $this->faker->city,
            'pieceIdentiteRecto' => $this->faker->imageUrl(),
            'pieceIdentiteVerso' => $this->faker->imageUrl(),
            'photoProfil' => $this->faker->imageUrl(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

