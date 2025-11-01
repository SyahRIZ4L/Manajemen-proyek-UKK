<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



// Root redirect - SIMPLIFIED
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Debug route untuk cek user data
Route::get('/debug-user', function () {
    if (!Auth::check()) {
        return 'User not logged in';
    }

    $user = Auth::user();
    return [
        'user_id' => $user->user_id,
        'username' => $user->username,
        'email' => $user->email,
        'role' => $user->role,
        'full_name' => $user->full_name,
        'current_task_status' => $user->current_task_status,
    ];
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (requires authentication)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Homepage/Dashboard - Role-specific dashboards
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard'); // Alias for backward compatibility

    // Admin Panel Route - Only accessible by admins
    Route::get('/admin/panel', [AdminController::class, 'panel'])->name('admin.panel');

    // Project Management Routes - Only accessible by admins and team leads
    Route::middleware('can:manage-projects')->group(function () {
        Route::get('/api/projects/stats', [ProjectController::class, 'getStatistics'])->name('projects.stats');
        Route::get('/api/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::post('/api/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/api/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/api/projects/{id}/members', [ProjectController::class, 'getMembers'])->name('projects.members');
        Route::get('/api/projects/{id}/board-stats', [ProjectController::class, 'getBoardStats'])->name('projects.board-stats');
        Route::put('/api/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/api/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    // User Management Routes - Only accessible by admins
    Route::middleware('can:manage-users')->group(function () {
        Route::get('/api/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/api/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/api/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::put('/api/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/api/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/api/users/stats', [UserController::class, 'getStatistics'])->name('users.stats');
    });
});
