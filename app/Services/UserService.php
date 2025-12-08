<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}
    
    public function getAllUsers()
    {
        return $this->userRepository->getAllPaginated();
    }
    
    public function getUser($id): User
    {
        return $this->userRepository->getCachedUser($id);
    }
    
    public function createUser(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->userRepository->createUser($data);
    }
    
    public function updateUser($id, array $data): User
    {
        $user = $this->getUser($id);
        $this->authorizeUser($user);
        
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        return $this->userRepository->updateUser($id, $data);
    }
    
    public function deleteUser($id): void
    {
        $user = $this->getUser($id);
        $this->authorizeUser($user);
        
        $this->userRepository->deleteUser($id);
    }
    
    public function authorizeUser(User $user): void
    {
        if (Gate::denies('manage-user', $user)) {
            abort(403, 'Unauthorized action.');
        }
    }
    
    public function searchUsers($query)
    {
        return $this->userRepository->searchUsers($query);
    }
    
    public function findByEmail($email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
    
    public function getLatestAdmin(): ?User
    {
        return $this->userRepository->getLatestAdmin();
    }
}