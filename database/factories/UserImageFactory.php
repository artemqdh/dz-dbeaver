<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'path' => 'profile_images/' . md5(md5(Str::random())) . '.jpeg'
        ];
    }
}