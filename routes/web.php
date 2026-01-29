<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\CalendarController;
use App\Http\Controllers\Pages\InfoController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/info', [InfoController::class, 'index'])->name('info');
    Route::post('/info', [InfoController::class, 'store'])->name('info.store');

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Pages\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [\App\Http\Controllers\Pages\ProfileController::class, 'update'])->name('profile.update');
});
