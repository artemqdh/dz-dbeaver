<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .auth-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="auth-buttons">
        @auth
            <div class="d-flex gap-2">
                <span class="align-self-center">Welcome, {{ Auth::user()->name }}!</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        Logout
                    </button>
                </form>
            </div>
        @else
            <div class="d-flex gap-2">
                <a href="{{ route('login.view') }}" class="btn btn-outline-primary btn-sm">
                    Login
                </a>
                <a href="{{ route('register.view') }}" class="btn btn-primary btn-sm">
                    Register
                </a>
            </div>
        @endauth
    </div>

    <div class="container mt-5">
        <h1 class="text-center">Welcome to Our Site!</h1>

        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        @auth
            <div class="text-center mt-4">
                <h2>Hello, {{ Auth::user()->name }}!</h2>
            </div>
        @else
            <p class="text-center mt-4">Please log in or register to see personalized content.</p>
        @endif

        @if(auth()->user()->status === 'admin' || auth()->user()->status === 'moderator')
            <a href="{{ route('users.index') }}" class="btn btn-info btn-lg ms-3">
                Manage Users
            </a>
        @endif
        
        <!-- Optional: Add link to user's own profile -->
        <a href="{{ route('users.show', auth()->id()) }}" class="btn btn-outline-primary btn-lg ms-3">
            My Profile
        </a>

        <div class="text-center mt-4">
            <a href="{{ route('posts.index') }}" class="btn btn-success btn-lg">
                View All Posts
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>