<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register Routes
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (requires authentication)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (example protected route)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Home redirect
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    });
});

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Additional routes for different roles (examples)
Route::middleware(['auth'])->group(function () {

    // Project Admin Routes
    Route::middleware('role:Project_Admin')->prefix('admin')->group(function () {
        Route::get('/users', function () {
            return view('admin.users');
        })->name('admin.users');

        Route::get('/projects', function () {
            return view('admin.projects');
        })->name('admin.projects');
    });

    // Team Lead Routes
    Route::middleware('role:Team_Lead,Project_Admin')->prefix('team')->group(function () {
        Route::get('/members', function () {
            return view('team.members');
        })->name('team.members');

        Route::get('/tasks', function () {
            return view('team.tasks');
        })->name('team.tasks');
    });

    // Developer Routes
    Route::middleware('role:Developer,Team_Lead,Project_Admin')->prefix('dev')->group(function () {
        Route::get('/tasks', function () {
            return view('dev.tasks');
        })->name('dev.tasks');
    });

    // Designer Routes
    Route::middleware('role:Designer,Team_Lead,Project_Admin')->prefix('design')->group(function () {
        Route::get('/tasks', function () {
            return view('design.tasks');
        })->name('design.tasks');
    });
});
