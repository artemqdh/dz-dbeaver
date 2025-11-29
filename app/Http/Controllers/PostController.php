<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();
        $selectedCategory = $request->get('category');
        
        $posts = Post::with(['user', 'category'])
                    ->when($selectedCategory, function ($query) use ($selectedCategory) {
                        return $query->where('category_id', $selectedCategory);
                    })
                    ->latest()
                    ->paginate(10);

        return view('posts.index', compact('posts', 'categories', 'selectedCategory'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'category_id' => 'required|exists:categories,id'
        ]);

        Post::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post->update([
            'content' => $request->content
        ]);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $isAdmin = Auth::user()->status === 'admin';
        if ($post->user_id !== Auth::id() && !$isAdmin) {
            abort(403);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
}