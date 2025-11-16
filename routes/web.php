<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PostController;

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