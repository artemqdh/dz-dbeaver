<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $userId = $this->route('user');
        
        return auth()->check() && (
            auth()->user()->status === 'admin' || 
            (auth()->user()->status === 'moderator' && in_array($this->input('status'), ['user', 'moderator'])) ||
            $userId == auth()->id()
        );
    }

    public function rules(): array
    {
        $userId = $this->route('user');
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|string|in:user,moderator,admin',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'status.required' => 'Please select a user role.',
            'status.in' => 'Invalid user role selected.',
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a JPEG, PNG, JPG, or GIF file.',
            'avatar.max' => 'The avatar size must not exceed 2MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
            'status' => 'user role',
            'bio' => 'biography',
            'avatar' => 'profile picture',
        ];
    }
}