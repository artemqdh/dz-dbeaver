<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Route::prefix('auth')->group(function ()
{
    // Register
    Route::get('register', [AuthController::class, 'registerView'])->name('register.view');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

// Search Routes
Route::get('search', [SearchController::class, 'search'])->name('search');
Route::get('search-ajax', [SearchController::class, 'searchAjax'])->name('search.ajax');