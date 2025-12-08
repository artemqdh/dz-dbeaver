<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->singleton(\App\Repositories\CategoryRepository::class, function ($app) {
            return new \App\Repositories\CategoryRepository();
        });
        
        $this->app->singleton(\App\Repositories\PostRepository::class, function ($app) {
            return new \App\Repositories\PostRepository();
        });
        
        $this->app->singleton(\App\Repositories\UserRepository::class, function ($app) {
            return new \App\Repositories\UserRepository();
        });

        // Bind services with dependencies
        $this->app->bind(
            \App\Services\PostService::class,
            function ($app) {
                return new \App\Services\PostService(
                    $app->make(\App\Repositories\PostRepository::class),
                    $app->make(\App\Repositories\CategoryRepository::class)
                );
            }
        );
    }
    
    public function boot(): void
    {
        Gate::define('manage-post', function (User $user, Post $post) {
            return $user->isAdmin() || $user->id === $post->user_id;
        });

        Gate::define('manage-user', function (User $user, User $targetUser) {
            if ($user->isAdmin()) {
                return true;
            }
            
            return $user->id === $targetUser->id;
        });
    }
}