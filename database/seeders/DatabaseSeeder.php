<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserImage;
use App\Models\PostComment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        User::factory()
            ->count(5)
            ->has(UserImage::factory()->count(1), 'profileImage')
            ->has(Post::factory()->count(3)->state(function (array $attributes) {
                return ['category_id' => \App\Models\Category::inRandomOrder()->first()->id];
            }), 'posts')
            ->create();
    }
}