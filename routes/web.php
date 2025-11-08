<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeamLeadController;
use App\Http\Controllers\DeveloperController;
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

    // Team Lead Panel Route - Only accessible by team leads
    Route::get('/teamlead/panel', [TeamLeadController::class, 'panel'])->name('teamlead.panel');

    // Developer Panel Route - Only accessible by developers
    Route::get('/developer/panel', [DeveloperController::class, 'panel'])->name('developer.panel');

    // Developer API Routes - Only accessible by developers
    Route::middleware('role:Developer')->group(function () {
        Route::get('/api/developer/statistics', [DeveloperController::class, 'getStatistics'])->name('developer.statistics');
        Route::get('/api/developer/tasks', [DeveloperController::class, 'getTasks'])->name('developer.tasks');
        Route::get('/api/developer/projects', [DeveloperController::class, 'getProjects'])->name('developer.projects');
        Route::post('/api/developer/time-log', [DeveloperController::class, 'logTime'])->name('developer.log-time');
        Route::get('/api/developer/activities', [DeveloperController::class, 'getRecentActivities'])->name('developer.activities');
        Route::get('/api/developer/time-logs', [DeveloperController::class, 'getTimeLogs'])->name('developer.time-logs');
        Route::put('/api/developer/tasks/{taskId}/status', [DeveloperController::class, 'updateTaskStatus'])->name('developer.update-task-status');
    });

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

        // Project Status Management Routes
        Route::put('/api/projects/{id}/complete', [ProjectController::class, 'completeProject'])->name('projects.complete');
        Route::put('/api/projects/{id}/cancel', [ProjectController::class, 'cancelProject'])->name('projects.cancel');
        Route::put('/api/projects/{id}/reactivate', [ProjectController::class, 'reactivateProject'])->name('projects.reactivate');

        // Member Management Routes
        Route::get('/api/projects/{id}/available-users', [ProjectController::class, 'getAvailableUsers'])->name('projects.available-users');
        Route::post('/api/projects/{id}/members', [ProjectController::class, 'addMember'])->name('projects.add-member');
        Route::delete('/api/projects/{id}/members/{memberId}', [ProjectController::class, 'removeMember'])->name('projects.remove-member');
        Route::put('/api/projects/{id}/members/{memberId}/role', [ProjectController::class, 'updateMemberRole'])->name('projects.update-member-role');
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

    // Report Routes - Only accessible by admins and team leads
    Route::middleware('can:manage-projects')->group(function () {
        Route::get('/api/reports/statistics', [App\Http\Controllers\ReportController::class, 'getStatistics'])->name('reports.statistics');
        Route::get('/api/reports/projects', [App\Http\Controllers\ReportController::class, 'getProjectReport'])->name('reports.projects');
        Route::get('/api/reports/users', [App\Http\Controllers\ReportController::class, 'getUserReport'])->name('reports.users');
        Route::get('/api/reports/timeline', [App\Http\Controllers\ReportController::class, 'getTimelineReport'])->name('reports.timeline');
        Route::get('/admin/reports/export', [App\Http\Controllers\ReportController::class, 'exportReport'])->name('reports.export');
    });

    // Notification Routes - Accessible by authenticated admin and team leads
    Route::middleware('can:manage-projects')->group(function () {
        Route::get('/api/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/api/notifications/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');
        Route::get('/api/notifications/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
        Route::put('/api/notifications/{notificationId}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::put('/api/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/api/notifications/{notificationId}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::post('/api/notifications/test', [App\Http\Controllers\NotificationController::class, 'testNotification'])->name('notifications.test');
    });
});
