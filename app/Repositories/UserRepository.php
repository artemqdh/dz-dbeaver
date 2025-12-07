<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['status'] = 'user';
        
        return User::create($data);
    }
    
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    
    public function getLatestAdmin()
    {
        return User::where('status', 'admin')
            ->latest()
            ->first();
    }
}