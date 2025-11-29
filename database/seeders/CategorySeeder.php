<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Football', 'slug' => 'football', 'color' => '#28a745'],
            ['name' => 'Food', 'slug' => 'food', 'color' => '#dc3545'],
            ['name' => 'Technology', 'slug' => 'technology', 'color' => '#007bff'],
            ['name' => 'Travel', 'slug' => 'travel', 'color' => '#fd7e14'],
            ['name' => 'Music', 'slug' => 'music', 'color' => '#6f42c1'],
            ['name' => 'Movies', 'slug' => 'movies', 'color' => '#e83e8c'],
            ['name' => 'Gaming', 'slug' => 'gaming', 'color' => '#20c997'],
            ['name' => 'Sports', 'slug' => 'sports', 'color' => '#ffc107'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}