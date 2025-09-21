<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('home') : redirect()->route('login');
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

    // Homepage/Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard'); // Alias for backward compatibility

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Task Management Routes
    Route::resource('tasks', TaskController::class);
    Route::get('/tasks-history', [TaskController::class, 'history'])->name('tasks.history');
    Route::put('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update');
    Route::post('/tasks/{task}/time', [TaskController::class, 'logTime'])->name('tasks.time.log');

    // Time Tracking API
    Route::get('/api/time-tracking', [HomeController::class, 'getTimeTrackingData'])->name('api.time-tracking');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
});

// Admin Routes
Route::middleware(['auth', 'project.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Projects Management
    Route::resource('projects', AdminController::class, [
        'only' => ['index', 'create', 'store', 'edit', 'update', 'destroy'],
        'names' => [
            'index' => 'projects.index',
            'create' => 'projects.create',
            'store' => 'projects.store',
            'edit' => 'projects.edit',
            'update' => 'projects.update',
            'destroy' => 'projects.destroy'
        ]
    ]);

    // Project Members
    Route::get('/projects/{project}/members', [AdminController::class, 'projectMembers'])->name('projects.members');
    Route::post('/projects/{project}/members', [AdminController::class, 'addProjectMember'])->name('projects.members.add');
    Route::delete('/members/{member}', [AdminController::class, 'removeProjectMember'])->name('members.remove');
    Route::put('/members/{member}/role', [AdminController::class, 'updateMemberRole'])->name('members.role');

    // Other Admin Routes
    Route::get('/team', [AdminController::class, 'team'])->name('team');
    Route::get('/tasks', [AdminController::class, 'tasks'])->name('tasks.index');
    Route::get('/tasks/{task}/edit', [AdminController::class, 'editTask'])->name('tasks.edit');
    Route::put('/tasks/{task}', [AdminController::class, 'updateTask'])->name('tasks.update');
    Route::get('/data', [AdminController::class, 'allData'])->name('data');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::post('/reports/generate', [AdminController::class, 'generateReport'])->name('reports.generate');
});
