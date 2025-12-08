<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class UserRepository
{
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['status'] = $data['status'] ?? 'user';
        
        return User::create($data);
    }
    
    public function findByEmail($email): ?User
    {
        return User::where('email', $email)->first();
    }
    
    public function getLatestAdmin(): ?User
    {
        return User::where('status', 'admin')
            ->latest()
            ->first();
    }
    
    public function getAllPaginated($perPage = 15)
    {
        return User::paginate($perPage);
    }
    
    public function getCachedUser($id): User
    {
        return Cache::remember("users:{$id}", 3600, function () use ($id) {
            return User::with('posts', 'comments')->findOrFail($id);
        });
    }
    
    public function find($id): User
    {
        return User::findOrFail($id);
    }
    
    public function updateUser($id, array $data): User
    {
        $user = $this->find($id);
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
        
        // Clear cache
        Cache::forget("users:{$id}");
        
        return $user->fresh();
    }
    
    public function deleteUser($id): void
    {
        $user = $this->find($id);
        $user->delete();
        
        // Clear cache
        Cache::forget("users:{$id}");
    }
    
    public function searchUsers($query)
    {
        return User::where('name', 'like', "%{$query}%")
                   ->orWhere('email', 'like', "%{$query}%")
                   ->paginate(15);
    }
    
    public function getAll()
    {
        return User::all();
    }
    
    public function getAdmins()
    {
        return User::where('status', 'admin')->get();
    }
    
    public function getUsersCount()
    {
        return User::count();
    }
    
    public function getUserWithPosts($id)
    {
        return User::with(['posts' => function($query) {
            $query->latest()->limit(10);
        }])->findOrFail($id);
    }
}