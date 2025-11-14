<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\UserImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\File;

class AuthController extends Controller
{
    public function registerView(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Only one image allowed
        ], [
            'name.required' => 'Your name is required.',
            'email.required' => 'Your email address is required.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'A password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'profile_image.image' => 'The profile image must be an image file.',
            'profile_image.mimes' => 'Only JPEG, PNG, JPG files are allowed for profile image.',
            'profile_image.max' => 'Profile image size must be less than 2MB.',
        ]);

        try
        {
            $user = User::create(
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => 'user',
            ]);

            if ($request->hasFile('profile_image'))
            {
                $image = $request->file('profile_image');

                $fileName = md5(md5(Str::random()))  . '.jpeg';

                $path = $image->storeAs('profile_images', $fileName, 'public');

                if ($path)
                {
                    $newUserImage = new UserImage();
                    $newUserImage->user_id = $user->id;
                    $newUserImage->path = $path;
                    $newUserImage->save();
                }
                else
                {
                    Log::error("Failed to store profile image for user ID: {$user->id}");
                }
            }

            Auth::login($user);
            $user->load('profileImage');

            return redirect()->route('welcome', ['userId' => $user->id])->with('success', 'Registration successful!');

        }
        catch (\Exception $exception)
        {
            Log::critical($exception->getMessage());
            return redirect()->back()->withErrors([$exception->getMessage()]);
        }
    }

    public function loginView(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('welcome')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome')->with('success', 'Logged out successfully!');
    }
}
