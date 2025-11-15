<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeamLeadController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Root redirect - SIMPLIFIED
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Test API route
Route::get('/test-api', function () {
    return view('test-api');
})->middleware('auth');

// Auto login test route
Route::get('/auto-login', function () {
    return view('auto-login');
});

// Test login route
Route::get('/test-login', function () {
    return view('test-login');
});

// Test my cards data without auth
Route::get('/test-my-cards-data', function () {
    try {
        $teamLeadId = 2; // existing team lead

        $myCards = DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
            ->leftJoin('users as assigned_user', 'card_assignments.assigned_user_id', '=', 'assigned_user.user_id')
            ->where('cards.created_by', $teamLeadId)
            ->select(
                'cards.card_id as id',
                'cards.card_title as title',
                'cards.description',
                'cards.status',
                'cards.priority',
                'cards.due_date',
                'cards.created_at',
                'cards.estimated_hours',
                'cards.actual_hours',
                'boards.board_name',
                'projects.project_id',
                'projects.project_name',
                'assigned_user.username as assigned_to',
                'assigned_user.full_name as assigned_to_name',
                'card_assignments.assigned_at'
            )
            ->orderBy('cards.created_at', 'desc')
            ->get();

        // Group cards by status
        $cardsByStatus = [
            'todo' => [],
            'in_progress' => [],
            'review' => [],
            'done' => []
        ];

        foreach ($myCards as $card) {
            $status = strtolower($card->status);
            if (!isset($cardsByStatus[$status])) {
                $cardsByStatus[$status] = [];
            }
            $cardsByStatus[$status][] = $card;
        }

        return response()->json([
            'success' => true,
            'data' => $cardsByStatus,
            'total_cards' => count($myCards),
            'debug_info' => [
                'team_lead_id' => $teamLeadId,
                'raw_cards_count' => count($myCards),
                'status_counts' => [
                    'todo' => count($cardsByStatus['todo']),
                    'in_progress' => count($cardsByStatus['in_progress']),
                    'review' => count($cardsByStatus['review']),
                    'done' => count($cardsByStatus['done'])
                ]
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});

// Test calling TeamLeadController getMyCards directly
Route::get('/test-teamlead-my-cards', function () {
    try {
        // Simulate authentication by getting user with ID 2
        $user = \App\Models\User::find(2);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Manually authenticate
        Auth::login($user);

        // Call the controller method
        $controller = new \App\Http\Controllers\TeamLeadController();
        $request = new \Illuminate\Http\Request();

        return $controller->getMyCards($request);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});

// Frontend test route
Route::get('/frontend-test', function () {
    return view('frontend-test');
})->middleware('auth');

// Debug route for checking authentication
Route::get('/debug-auth', function () {
    $user = Auth::user();
    $isAuth = Auth::check();

    return response()->json([
        'authenticated' => $isAuth,
        'user' => $user ? [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'username' => $user->username
        ] : null,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
})->middleware('auth');

// Test API with session simulation
Route::get('/test-api-with-session', function () {
    // Simulate login
    $teamLead = \App\Models\User::where('username', 'teamlead')->first();
    if ($teamLead) {
        Auth::login($teamLead);

        // Now test the API endpoint
        $request = new \Illuminate\Http\Request(['board_id' => 12]);
        $controller = new \App\Http\Controllers\TeamLeadController();

        try {
            $response = $controller->getAssignableMembers($request);
            return $response;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    return response()->json(['error' => 'Team lead not found']);
});

// Quick login as team lead for testing
Route::get('/quick-login', function () {
    $teamLead = \App\Models\User::where('username', 'teamlead')->first();
    if ($teamLead) {
        Auth::login($teamLead);
        return redirect('/teamlead/panel')->with('message', 'Logged in as Team Lead for testing');
    }
    return redirect('/login')->with('error', 'Team Lead user not found');
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

    // Profile update routes
    Route::post('/api/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/api/profile/upload-photo', [UserController::class, 'uploadProfilePhoto'])->name('profile.upload-photo');
    Route::delete('/api/profile/delete-photo', [UserController::class, 'deleteProfilePhoto'])->name('profile.delete-photo');

    // Homepage/Dashboard - Role-specific dashboards
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard'); // Alias for backward compatibility

    // Admin Panel Route - Only accessible by admins
    Route::get('/admin/panel', [AdminController::class, 'panel'])->name('admin.panel');

    // Admin API Routes for Team Lead Management
    Route::middleware('auth')->group(function () {
        Route::get('/api/admin/team-leads/availability', [AdminController::class, 'checkTeamLeadAvailability'])->name('admin.team-leads.availability');
        Route::post('/api/admin/team-leads/assign', [AdminController::class, 'assignTeamLeadToProject'])->name('admin.team-leads.assign');
        Route::delete('/api/admin/team-leads/remove', [AdminController::class, 'removeTeamLeadFromProject'])->name('admin.team-leads.remove');
        Route::get('/api/admin/team-leads/available', [AdminController::class, 'getAvailableTeamLeads'])->name('admin.team-leads.available');
        Route::get('/api/teamlead/status/{teamLeadId?}', [TeamLeadController::class, 'getTeamLeadStatus'])->name('teamlead.status');
        Route::post('/api/teamlead/status-update/project-complete', [TeamLeadController::class, 'updateStatusOnProjectCompleteAPI'])->name('teamlead.status-update.project-complete');
    });

    // Team Lead Panel Route - Only accessible by team leads
    Route::get('/teamlead/panel', [TeamLeadController::class, 'panel'])->name('teamlead.panel');

    // Team Lead API routes (already within auth middleware group)
    Route::get('/api/teamlead/statistics', [TeamLeadController::class, 'getStatistics'])->name('teamlead.statistics');
    Route::get('/api/teamlead/projects', [TeamLeadController::class, 'getProjects'])->name('teamlead.projects');
    Route::get('/api/teamlead/current-project', [TeamLeadController::class, 'getCurrentProject'])->name('teamlead.current-project');
    Route::get('/api/teamlead/project-timeline', [TeamLeadController::class, 'getProjectTimeline'])->name('teamlead.project-timeline');
    Route::get('/api/teamlead/recent-activities', [TeamLeadController::class, 'getRecentActivities'])->name('teamlead.recent-activities');
    Route::get('/api/teamlead/boards', [\App\Http\Controllers\TeamLeadBoardController::class, 'getBoards'])->name('teamlead.boards-list');
    Route::get('/api/teamlead/cards', [TeamLeadController::class, 'getCards'])->name('teamlead.cards');
    Route::get('/api/teamlead/my-cards', [TeamLeadController::class, 'getMyCards'])->name('teamlead.my-cards');
    Route::get('/api/teamlead/assigned-cards', [TeamLeadController::class, 'getAssignedCards'])->name('teamlead.assigned-cards');
    Route::get('/api/teamlead/assigned-history', [TeamLeadController::class, 'getAssignedHistory'])->name('teamlead.assigned-history');
    Route::get('/api/teamlead/card-time-logs', [TeamLeadController::class, 'getCardTimeLogs'])->name('teamlead.card-time-logs');

    // Team Member Management API routes
    Route::get('/api/teamlead/available-users', [TeamLeadController::class, 'getAvailableUsers'])->name('teamlead.available-users');
    Route::post('/api/teamlead/add-user-to-project', [TeamLeadController::class, 'addUserToProject'])->name('teamlead.add-user-to-project');
    Route::delete('/api/teamlead/remove-user-from-project', [TeamLeadController::class, 'removeUserFromProject'])->name('teamlead.remove-user-from-project');
    Route::get('/api/teamlead/project-members', [TeamLeadController::class, 'getProjectMembers'])->name('teamlead.project-members');
    Route::post('/api/teamlead/assignments/{assignmentId}/approve', [TeamLeadController::class, 'approveAssignment'])->name('teamlead.approve-assignment');
    Route::post('/api/teamlead/assignments/{assignmentId}/reject', [TeamLeadController::class, 'rejectAssignment'])->name('teamlead.reject-assignment');
    Route::post('/api/teamlead/cards', [TeamLeadController::class, 'createCard'])->name('teamlead.create-card');
    Route::post('/api/teamlead/cards/assign', [TeamLeadController::class, 'assignCardToMember'])->name('teamlead.assign-card');
    Route::get('/api/teamlead/assignable-members', [TeamLeadController::class, 'getAssignableMembers'])->name('teamlead.assignable-members');
    Route::get('/api/teamlead/cards/{cardId}/assignments', [TeamLeadController::class, 'getCardAssignments'])->name('teamlead.card-assignments');
    Route::delete('/api/teamlead/cards/assignments', [TeamLeadController::class, 'removeCardAssignment'])->name('teamlead.remove-card-assignment');
    Route::get('/api/teamlead/cards/unassigned', [TeamLeadController::class, 'getUnassignedCards'])->name('teamlead.unassigned-cards');
    Route::get('/api/teamlead/team-members', [TeamLeadController::class, 'getTeamMembersForAssignment'])->name('teamlead.team-members');
    Route::get('/api/teamlead/boards-for-card', [TeamLeadController::class, 'getBoardsForCard'])->name('teamlead.boards-for-card');
    Route::get('/api/teamlead/project-detail', [TeamLeadController::class, 'getProjectDetail'])->name('teamlead.project-detail');

    // Card Workflow Routes for TeamLead
    Route::get('/api/teamlead/pending-reviews', [TeamLeadController::class, 'getPendingCardReviews'])->name('teamlead.pending-reviews');
    Route::post('/api/teamlead/cards/{cardId}/approve', [TeamLeadController::class, 'approveCard'])->name('teamlead.approve-card');
    Route::post('/api/teamlead/cards/{cardId}/reject', [TeamLeadController::class, 'rejectCard'])->name('teamlead.reject-card');

    // Board Management Routes
    Route::post('/api/teamlead/boards', [\App\Http\Controllers\TeamLeadBoardController::class, 'createBoard'])->name('teamlead.create-board');
    Route::get('/api/teamlead/boards/{boardId}/detail', [\App\Http\Controllers\TeamLeadBoardController::class, 'getBoardDetail'])->name('teamlead.board-detail');
    Route::delete('/api/teamlead/boards/{boardId}', [\App\Http\Controllers\TeamLeadBoardController::class, 'deleteBoard'])->name('teamlead.delete-board');

    // Developer Panel Route - Only accessible by developers
    Route::get('/developer/panel', [DeveloperController::class, 'panel'])->name('developer.panel');

    // Designer Panel Route - Only accessible by designers
    Route::get('/designer/panel', [DesignerController::class, 'panel'])->name('designer.panel');

    // Developer API Routes - Only accessible by developers
    Route::middleware(['auth'])->group(function () {
        Route::get('/api/developer/statistics', [DeveloperController::class, 'getStatistics'])->name('developer.statistics');
        Route::get('/api/developer/tasks', [DeveloperController::class, 'getTasks'])->name('developer.tasks');
        Route::get('/api/developer/projects', [DeveloperController::class, 'getProjects'])->name('developer.projects');
        Route::post('/api/developer/time-log', [DeveloperController::class, 'logTime'])->name('developer.log-time');
        Route::get('/api/developer/activities', [DeveloperController::class, 'getRecentActivities'])->name('developer.activities');
        Route::get('/api/developer/time-logs', [DeveloperController::class, 'getTimeLogs'])->name('developer.time-logs');
        Route::put('/api/developer/tasks/{taskId}/status', [DeveloperController::class, 'updateTaskStatus'])->name('developer.update-task-status');

        // Card Workflow Routes for Developer
        Route::get('/api/developer/cards', [DeveloperController::class, 'getCards'])->name('developer.cards');
        Route::get('/api/developer/my-cards', [DeveloperController::class, 'getMyCards'])->name('developer.my-cards');
        Route::get('/api/developer/dashboard', [DeveloperController::class, 'getDashboardStats'])->name('developer.dashboard');
        Route::put('/api/developer/cards/{cardId}/status', [DeveloperController::class, 'updateCardStatus'])->name('developer.update-card-status');
        Route::post('/api/developer/cards/{cardId}/submit', [DeveloperController::class, 'submitCardToTeamLead'])->name('developer.submit-card');
    });

    // Designer API Routes - Only accessible by designers
    Route::middleware('auth')->group(function () {
        Route::get('/api/designer/statistics', [DesignerController::class, 'getDesignerStatistics'])->name('designer.statistics');
        Route::get('/api/designer/assets', [DesignerController::class, 'getPanelDesignAssets'])->name('designer.assets');
        Route::get('/api/designer/projects', [DesignerController::class, 'getDesignProjects'])->name('designer.projects');
        Route::get('/api/designer/gallery', [DesignerController::class, 'getGalleryItems'])->name('designer.gallery');
        Route::get('/api/designer/feedback', [DesignerController::class, 'getClientFeedback'])->name('designer.feedback');
        Route::get('/api/designer/activities', [DesignerController::class, 'getDesignerActivities'])->name('designer.activities');

        // Card Workflow Routes for Designer (same as Developer)
        Route::get('/api/designer/cards', [DesignerController::class, 'getCards'])->name('designer.cards');
        Route::get('/api/designer/my-cards', [DesignerController::class, 'getMyCards'])->name('designer.my-cards');
        Route::get('/api/designer/dashboard', [DesignerController::class, 'getDashboardStats'])->name('designer.dashboard');
        Route::put('/api/designer/cards/{cardId}/status', [DesignerController::class, 'updateCardStatus'])->name('designer.update-card-status');
        Route::post('/api/designer/cards/{cardId}/submit', [DesignerController::class, 'submitCardToTeamLead'])->name('designer.submit-card');
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

    // Notification Routes - Accessible by all authenticated users
    Route::middleware(['auth'])->group(function () {
        Route::get('/api/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/api/notifications/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.count');
        Route::get('/api/notifications/recent', [App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
        Route::put('/api/notifications/{notificationId}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::put('/api/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/api/notifications/{notificationId}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // Subtasks API Routes - Accessible by all authenticated users
    Route::get('/api/subtasks', [App\Http\Controllers\SubtaskController::class, 'index'])->name('subtasks.index');
    Route::post('/api/subtasks', [App\Http\Controllers\SubtaskController::class, 'store'])->name('subtasks.store');
    Route::get('/api/subtasks/{subtask}', [App\Http\Controllers\SubtaskController::class, 'show'])->name('subtasks.show');
    Route::put('/api/subtasks/{subtask}', [App\Http\Controllers\SubtaskController::class, 'update'])->name('subtasks.update');
    Route::delete('/api/subtasks/{subtask}', [App\Http\Controllers\SubtaskController::class, 'destroy'])->name('subtasks.destroy');
    Route::put('/api/subtasks/{subtask}/complete', [App\Http\Controllers\SubtaskController::class, 'complete'])->name('subtasks.complete');

    // Subtask Comments API Routes
    Route::post('/api/subtasks/{subtask}/comments', [App\Http\Controllers\SubtaskController::class, 'addComment'])->name('subtasks.comments.store');
    Route::delete('/api/subtasks/comments/{comment}', [App\Http\Controllers\SubtaskController::class, 'deleteComment'])->name('subtasks.comments.destroy');

    // Todo List API Routes - Personal todos for each user
    Route::get('/api/todos', [App\Http\Controllers\TodoController::class, 'index'])->name('todos.index');
    Route::post('/api/todos', [App\Http\Controllers\TodoController::class, 'store'])->name('todos.store');
    Route::put('/api/todos/{todo}', [App\Http\Controllers\TodoController::class, 'update'])->name('todos.update');
    Route::delete('/api/todos/{todo}', [App\Http\Controllers\TodoController::class, 'destroy'])->name('todos.destroy');
    Route::put('/api/todos/{todo}/toggle', [App\Http\Controllers\TodoController::class, 'toggle'])->name('todos.toggle');

    // Time Log API Routes - Time tracking for cards and tasks
    Route::get('/api/time-logs', [App\Http\Controllers\TimeLogController::class, 'index'])->name('time-logs.index');
    Route::post('/api/time-logs/start', [App\Http\Controllers\TimeLogController::class, 'start'])->name('time-logs.start');
    Route::post('/api/time-logs/stop', [App\Http\Controllers\TimeLogController::class, 'stop'])->name('time-logs.stop');
    Route::get('/api/time-logs/active', [App\Http\Controllers\TimeLogController::class, 'getActiveTimer'])->name('time-logs.active');
    Route::put('/api/time-logs/{id}', [App\Http\Controllers\TimeLogController::class, 'update'])->name('time-logs.update');
    Route::delete('/api/time-logs/{id}', [App\Http\Controllers\TimeLogController::class, 'destroy'])->name('time-logs.destroy');
    Route::get('/api/time-logs/statistics', [App\Http\Controllers\TimeLogController::class, 'getStatistics'])->name('time-logs.statistics');
    Route::get('/api/cards/{cardId}/time-logs', [App\Http\Controllers\TimeLogController::class, 'getCardTimeLogs'])->name('cards.time-logs');
});
