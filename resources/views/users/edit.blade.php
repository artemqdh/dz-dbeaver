<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .card {
            border-radius: 10px;
            border: none;
        }
        .form-label {
            font-weight: 500;
        }
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dee2e6;
            margin: 0 auto 20px;
            display: block;
        }
        .avatar-upload {
            position: relative;
            cursor: pointer;
        }
        .avatar-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-4">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Users
                    </a>
                </div>

                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit User: {{ $user->name }}</h4>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="text-center mb-4">
                                <div class="avatar-upload">
                                    <img src="{{ $user->avatar_url }}" alt="Avatar" class="avatar-preview" id="avatarPreview">
                                    <input type="file" name="avatar" id="avatarInput" accept="image/*" class="form-control">
                                </div>
                                <label for="avatarInput" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-camera"></i> Change Avatar
                                </label>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">User Role *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="user" {{ old('status', $user->status) == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="moderator" {{ old('status', $user->status) == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                    <option value="admin" {{ old('status', $user->status) == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio/Description</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" 
                                          name="bio" 
                                          rows="3">{{ old('bio', $user->bio ?? '') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i> View Profile
                                </a>
                                
                                <div class="btn-group">
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Update User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(auth()->user()->status === 'admin' && $user->id !== auth()->id())
                    <div class="card shadow-sm border-danger mt-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Danger Zone</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Once you delete a user, there is no going back. Please be certain.</p>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                                    <i class="bi bi-trash"></i> Delete User Permanently
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            form.addEventListener('submit', function(e) {
                if (password.value && password.value.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters long.');
                    password.focus();
                    return false;
                }
                
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    confirmPassword.focus();
                    return false;
                }
            });
        });
    </script>
</body>
</html>