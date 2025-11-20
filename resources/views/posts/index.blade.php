<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .post-card {
            transition: transform 0.2s;
        }
        .post-card:hover {
            transform: translateY(-2px);
        }
        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #007bff, #6610f2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .post-content {
            white-space: pre-line;
            line-height: 1.6;
        }
    </style>
</head>
<body>
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Posts Feed</h1>
                @auth
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login.view') }}" class="btn btn-outline-primary">Login to Post</a>
                @endauth
            </div>

            <div id="posts-container">
                @foreach($posts as $post)
                    <div class="card post-card mb-4 shadow-sm">
                        <div class="card-body">

                            <div class="d-flex align-items-center mb-3">
                                @if($post->user->profileImage)
                                    <img src="{{ Storage::url($post->user->profileImage->path) }}"
                                         alt="{{ $post->user->name }}"
                                         class="rounded-circle me-3"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="user-avatar me-3">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                @endif

                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">{{ $post->user->name }}</h5>
                                    <small class="text-muted">
                                        {{ $post->created_at->format('M j, Y \a\t g:i A') }}
                                    </small>
                                </div>
                            </div>

                            <p class="card-text post-content">{{ $post->content }}</p>

                            <div class="mt-4">
                                @auth
                                    <form method="POST" action="{{ route('comments.store') }}" class="mb-3">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="comment" class="form-control" placeholder="Write a comment..." required>
                                            <button type="submit" class="btn btn-primary">Comment</button>
                                        </div>
                                    </form>
                                @endauth

                                @if($post->comments->count() > 0)
                                    <div class="comments-section">
                                        @foreach($post->comments as $comment)
                                            <div class="d-flex align-items-start mb-2">

                                                @if($comment->user->profileImage)
                                                    <img src="{{ Storage::url($comment->user->profileImage->path) }}"
                                                         alt="{{ $comment->user->name }}"
                                                         class="rounded-circle me-2"
                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                @else
                                                    <div class="user-avatar me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                                        {{ substr($comment->user->name, 0, 1) }}
                                                    </div>
                                                @endif

                                                <div class="flex-grow-1">
                                                    <div class="bg-light rounded p-2">

                                                        <strong>{{ $comment->user->name }}</strong>
                                                        <p class="mb-1">{{ $comment->comment }}</p>
                                                        <small class="text-muted">{{ $comment->created_at->format('M j, g:i A') }}</small>

                                                        <div id="edit-form-{{ $comment->id }}" class="mt-2" style="display:none;">
                                                            <form method="POST" action="{{ route('comments.update', $comment) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" name="comment" class="form-control"
                                                                           value="{{ $comment->comment }}" required>
                                                                    <button type="submit" class="btn btn-success">Save</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                            onclick="toggleEditForm({{ $comment->id }})">
                                                                        Cancel
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        @auth
                                                            @if(Auth::id() === $comment->user_id)
                                                                <button class="btn btn-sm btn-outline-primary ms-2"
                                                                        onclick="toggleEditForm({{ $comment->id }})">
                                                                    Edit
                                                                </button>
                                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-2"
                                                                            onclick="return confirm('Delete this comment?')">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endauth

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <button class="btn btn-sm btn-outline-secondary me-2">Like</button>
                                </div>

                                <div>
                                    @auth
                                        @if(Auth::id() === $post->user_id)
                                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary me-2">Edit</a>

                                            <form method="POST" action="/posts/{{ $post->id }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                    <small class="text-muted">#{{ $post->id }}</small>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

                @if($posts->isEmpty())
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <h5 class="card-title">No posts yet</h5>
                            <p class="card-text text-muted">Be the first to share something!</p>
                            @auth
                                <a href="{{ route('posts.create') }}" class="btn btn-primary">Create First Post</a>
                            @else
                                <a href="{{ route('register.view') }}" class="btn btn-primary">Join Now</a>
                            @endauth
                        </div>
                    </div>
                @endif

                @if($posts->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleEditForm(id) {
        const form = document.getElementById('edit-form-' + id);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>

</body>
</html>
