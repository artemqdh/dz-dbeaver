<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\PostRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostService
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CategoryRepository $categoryRepository
    ) {}
    
    public function getPostsWithFilter(?int $categoryId = null)
    {
        return $this->postRepository->getWithCategoryFilter($categoryId);
    }
    
    public function createPost(array $data)
    {
        $data['user_id'] = Auth::id();
        return $this->postRepository->store($data);
    }
    
    public function updatePost($postId, array $data)
    {
        $post = $this->postRepository->getCachedPost($postId);
        $this->authorizePost($post);
        
        return $this->postRepository->update($post, $data);
    }
    
    public function deletePost($postId)
    {
        $post = $this->postRepository->getCachedPost($postId);
        $this->authorizePost($post);
        
        return $this->postRepository->destroy($post);
    }
    
    public function authorizePost(Post $post): void
    {
        if (Gate::denies('manage-post', $post)) {
            abort(403, 'Unauthorized action.');
        }
    }
}