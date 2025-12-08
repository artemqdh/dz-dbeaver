<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        .badge-admin {
            background-color: #dc3545;
        }
        .badge-user {
            background-color: #0d6efd;
        }
        .badge-moderator {
            background-color: #6f42c1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Users Management</h1>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New User
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" 
                               value="{{ request('search') }}" placeholder="Search by name or email...">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Posts</th>
                                <th>Joined</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                         class="user-avatar" width="40" height="40">
                                </td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>
                                    <small>{{ $user->email }}</small>
                                </td>
                                <td>
                                    @if($user->status === 'admin')
                                        <span class="badge badge-admin">Admin</span>
                                    @elseif($user->status === 'moderator')
                                        <span class="badge badge-moderator">Moderator</span>
                                    @else
                                        <span class="badge badge-user">User</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $user->posts_count ?? $user->posts->count() }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('users.show', $user) }}" 
                                           class="btn btn-outline-info" title="View">
                                            View
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" 
                                           class="btn btn-outline-warning" title="Edit">
                                            Edit
                                        </a>
                                        @can('manage-user', $user)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                                    title="Delete">
                                                Delete
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No users found</p>
                                        @if(request('search'))
                                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                                Clear search
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($users->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>

        <div class="mt-4 text-center text-muted">
            <small>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirm before deleting
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[action*="destroy"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>