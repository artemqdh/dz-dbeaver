<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\VerifyController;

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Route::prefix('auth')->group(function ()
{
    // Register
    Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    
    // login
    Route::get('login', [AuthController::class, 'loginView'])->name('login.view');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Search Routes
Route::get('search', [SearchController::class, 'search'])->name('search');
Route::get('search-ajax', [SearchController::class, 'searchAjax'])->name('search.ajax');

// Posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// Comment Routes
Route::post('/comments', [PostCommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [PostCommentController::class, 'destroy'])->name('comments.destroy');
Route::put('/comments/{comment}', [PostCommentController::class, 'update'])->name('comments.update');

// Verify Mail
Route::get('/verify-email/{id}/{hash}', [VerifyController::class, 'verify'])->middleware('signed')->name('verification.verify');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');
