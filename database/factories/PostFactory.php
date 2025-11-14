<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(3),
            'user_id' => User::factory(),
        ];
    }
}