<?php

namespace App\Providers;

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
        // Boot methods
    }
}