<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|min:2|max:255',
        ]);

        PostComment::create([
            'post_id' => $validated['post_id'],
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('posts.index')->with('success', 'Comment added successfully!');
    }

    public function destroy(PostComment $comment): RedirectResponse
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('posts.index')->with('success', 'Comment deleted successfully!');
    }
}