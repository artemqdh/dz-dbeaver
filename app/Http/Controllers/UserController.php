<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserService $userService
    ) {}
    
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        $users = $search
            ? $this->userService->searchUsers($search)
            : $this->userService->getAllUsers();
            
        return view('users.index', compact('users', 'search'));
    }
    
    public function create(): View
    {
        return view('users.create');
    }
    
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $this->userService->createUser($request->validated());
        
        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }
    
    public function show($id): View
    {
        $user = $this->userService->getUser($id);
        return view('users.show', compact('user'));
    }
    
    public function edit($id): View
    {
        $user = $this->userService->getUser($id);
        $this->userService->authorizeUser($user);
        
        return view('users.edit', compact('user'));
    }
    
    public function update(UserStoreRequest $request, $id): RedirectResponse
    {
        $this->userService->updateUser($id, $request->validated());
        
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }
    
    public function destroy($id): RedirectResponse
    {
        $this->userService->deleteUser($id);
        
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}