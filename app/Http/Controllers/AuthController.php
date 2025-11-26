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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;

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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

            Mail::to($user->email)->send(new VerifyEmail($user));
            $user->load('profileImage');

            return redirect()->route('welcome')->with('success', 'Registration successful! Please check your email to verify your account.');

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

    public function login(Request $request) : RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        if (Auth::attempt($credentials))
        {
            $user = Auth::user();

            if ($user->email_verified_at === null)
            {
                Auth::logout();
                return back()->with('error', 'Please verify your email before logging in.');
            }

            return redirect()->route('welcome')->with('success', 'Login successful!');
        }
        return back()->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome')->with('success', 'Logged out successfully!');
    }

    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null): View
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login.view')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}