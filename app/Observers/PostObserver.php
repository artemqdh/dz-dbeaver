<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        Cache::tags(['posts'])->flush();
        Cache::forget('posts:all');
        Cache::forget("post:{$post->id}");
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        Cache::tags(['posts'])->flush();
        Cache::forget('posts:all');
        Cache::forget("post:{$post->id}");
    }
    
    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        Cache::tags(['posts'])->flush();
        Cache::forget('posts:all');
        Cache::forget("post:{$post->id}");
    }
}