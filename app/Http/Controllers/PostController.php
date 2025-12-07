<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Repositories\PostRepository;
use App\Repositories\CategoryRepository;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly PostService $postService
    ) {}
    
    public function index(Request $request): View
    {
        $categories = $this->categoryRepository->getAllCached();
        $selectedCategory = $request->get('category');
        
        $posts = $selectedCategory
            ? $this->postRepository->getWithCategoryFilter($selectedCategory)
            : $this->postRepository->getAllPaginated();
            
        return view('posts.index', compact('posts', 'categories', 'selectedCategory'));
    }
    
    public function create(): View
    {
        $categories = $this->categoryRepository->getAllCached();
        return view('posts.create', compact('categories'));
    }
    
    public function store(PostStoreRequest $request): RedirectResponse
    {
        $this->postService->createPost($request->validated());
        
        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }
    
    public function show($id): View
    {
        $post = $this->postRepository->getCachedPost($id);
        return view('posts.show', compact('post'));
    }
    
    public function edit($id): View
    {
        $post = $this->postRepository->getCachedPost($id);
        $this->postService->authorizePost($post);
        
        $categories = $this->categoryRepository->getAllCached();
        return view('posts.edit', compact('post', 'categories'));
    }
    
    public function update(PostStoreRequest $request, $id): RedirectResponse
    {
        $this->postService->updatePost($id, $request->validated());
        
        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }
    
    public function destroy($id): RedirectResponse
    {
        $this->postService->deletePost($id);
        
        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}