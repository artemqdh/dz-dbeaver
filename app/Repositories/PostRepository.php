<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostRepository
{
    public function getAllPaginated(int $perPage = 10)
    {
        $cacheKey = 'posts:all';
        
        return Cache::remember($cacheKey, 60, function () use ($perPage) {
            return Post::with(['user', 'category'])
                ->latest()
                ->paginate($perPage);
        });
    }
    
    public function getWithCategoryFilter(?int $categoryId, int $perPage = 10)
    {
        if (!$categoryId) {
            return $this->getAllPaginated($perPage);
        }
        
        $cacheKey = 'posts:category_' . $categoryId;
        
        return Cache::remember($cacheKey, 60, function () use ($categoryId, $perPage) {
            return Post::with(['user', 'category'])
                ->where('category_id', $categoryId)
                ->latest()
                ->paginate($perPage);
        });
    }
    
    public function getCachedPost($postId)
    {
        $cacheKey = "post:{$postId}";
        
        return Cache::remember($cacheKey, 60, function () use ($postId) {
            return Post::with(['user', 'category', 'comments.user'])
                ->findOrFail($postId);
        });
    }
    
    public function store(array $data)
    {
        return Post::create($data);
    }
    
    public function update(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }
    
    public function destroy(Post $post)
    {
        return $post->delete();
    }
    
    public function show(Post $post)
    {
        return [
            'post' => $post->load(['user', 'category', 'comments.user'])
        ];
    }
}