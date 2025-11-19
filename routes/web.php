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
use Illuminate\Http\Request;

// Root redirect - SIMPLIFIED
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Debug route for card details issue
Route::get('/debug-card/{cardId}', function($cardId) {
    try {
        $user = Auth::user();

        $response = [
            'card_id' => $cardId,
            'user_authenticated' => $user ? true : false,
            'user_id' => $user ? $user->user_id : null,
            'user_role' => $user ? $user->role : null,
        ];

        // Test database connection
        try {
            $cardCount = DB::table('cards')->count();
            $response['total_cards'] = $cardCount;
        } catch (Exception $e) {
            $response['db_error'] = $e->getMessage();
        }

        // Test specific card
        try {
            $card = DB::table('cards')->where('card_id', $cardId)->first();
            $response['card_exists'] = $card ? true : false;
            if ($card) {
                $response['card_title'] = $card->card_title;
                $response['card_status'] = $card->status;
            }
        } catch (Exception $e) {
            $response['card_query_error'] = $e->getMessage();
        }

        return response()->json($response);

    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->middleware('auth');









// Test route to create a card in review status for testing
Route::post('/api/test/create-review-card', function () {
    try {
        $user = Auth::user();

        // Create a test card in review status
        $cardId = DB::table('cards')->insertGetId([
            'title' => 'Test Card for Review - ' . now()->format('Y-m-d H:i:s'),
            'description' => 'This is a test card created for testing the approve/reject functionality.',
            'status' => 'review',
            'priority' => 'medium',
            'due_date' => now()->addDays(7),
            'created_by' => $user->user_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create assignment for the card
        DB::table('card_assignments')->insert([
            'card_id' => $cardId,
            'user_id' => $user->user_id,
            'assigned_by' => $user->user_id,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test card created successfully',
            'card_id' => $cardId,
            'card' => DB::table('cards')->where('card_id', $cardId)->first()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
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

    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Profile API routes (legacy)
    Route::post('/api/profile/update', [UserController::class, 'updateProfile'])->name('profile.api.update');
    Route::post('/api/profile/upload-photo', [UserController::class, 'uploadProfilePhoto'])->name('profile.upload-photo');
    Route::delete('/api/profile/delete-photo', [UserController::class, 'deleteProfilePhoto'])->name('profile.delete-photo');

    // Homepage/Dashboard - Role-specific dashboards
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard'); // Alias for backward compatibility

    // Admin Panel Route - Only accessible by admins
    Route::get('/admin/panel', [AdminController::class, 'panel'])->name('admin.panel');
    Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Admin API Routes for Team Lead Management
    Route::middleware('auth')->group(function () {
        Route::get('/api/admin/team-leads/availability', [AdminController::class, 'checkTeamLeadAvailability'])->name('admin.team-leads.availability');
        Route::post('/api/admin/team-leads/assign', [AdminController::class, 'assignTeamLeadToProject'])->name('admin.team-leads.assign');
        Route::delete('/api/admin/team-leads/remove', [AdminController::class, 'removeTeamLeadFromProject'])->name('admin.team-leads.remove');
        Route::get('/api/admin/team-leads/available', [AdminController::class, 'getAvailableTeamLeads'])->name('admin.team-leads.available');
        Route::get('/api/teamlead/status/{teamLeadId?}', [TeamLeadController::class, 'getTeamLeadStatus'])->name('teamlead.status');
        Route::post('/api/teamlead/status-update/project-complete', [TeamLeadController::class, 'updateStatusOnProjectCompleteAPI'])->name('teamlead.status-update.project-complete');

        // Admin Board Management Routes
        Route::get('/api/projects/{projectId}/boards', [AdminController::class, 'getProjectBoards'])->name('admin.project.boards');
        Route::post('/api/boards', [AdminController::class, 'createBoard'])->name('admin.create-board');
        Route::get('/api/boards/{boardId}', [AdminController::class, 'getBoardDetail'])->name('admin.board-detail');
        Route::put('/api/boards/{boardId}', [AdminController::class, 'updateBoard'])->name('admin.update-board');
        Route::delete('/api/boards/{boardId}', [AdminController::class, 'deleteBoard'])->name('admin.delete-board');
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
    Route::post('/api/teamlead/cards', [\App\Http\Controllers\TeamLeadBoardController::class, 'createCard'])->name('teamlead.create-card');
    Route::put('/api/teamlead/cards/{cardId}', [\App\Http\Controllers\TeamLeadBoardController::class, 'updateCard'])->name('teamlead.update-card');
    Route::delete('/api/teamlead/cards/{cardId}', [\App\Http\Controllers\TeamLeadBoardController::class, 'deleteCard'])->name('teamlead.delete-card');
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
    Route::get('/api/teamlead/cards/{cardId}/detail', [TeamLeadController::class, 'getCardDetails'])->name('teamlead.card-details');
    Route::post('/api/teamlead/cards/{cardId}/comments', [TeamLeadController::class, 'addCardComment'])->name('teamlead.add-card-comment');
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

    // Debug route for testing card details (no auth required)
    Route::get('/debug/cards/{cardId}/detail', function($cardId) {
        try {
            // Basic card query
            $card = DB::table('cards')->where('card_id', $cardId)->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found',
                    'card_id' => $cardId
                ], 404);
            }

            // Get comments
            $comments = DB::table('card_comments as cc')
                ->leftJoin('users as u', 'cc.user_id', '=', 'u.user_id')
                ->where('cc.card_id', $cardId)
                ->select([
                    'cc.comment_id',
                    DB::raw('COALESCE(cc.comment_text, cc.comment, "No comment") as comment_text'),
                    'cc.created_at',
                    DB::raw('COALESCE(u.full_name, u.username, "Unknown") as user_name'),
                    DB::raw('COALESCE(u.role, "Member") as user_role')
                ])
                ->get();

            return response()->json([
                'success' => true,
                'card' => $card,
                'comments' => $comments,
                'debug_info' => [
                    'requested_card_id' => $cardId,
                    'card_found' => $card ? true : false,
                    'comments_count' => $comments->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug error: ' . $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ], 500);
        }
    })->name('debug.card-details');

    // Debug route to list all cards
    Route::get('/debug/cards/list', function() {
        try {
            $cards = DB::table('cards')->select('card_id', 'card_title', 'status')->limit(10)->get();
            $cardCount = DB::table('cards')->count();

            return response()->json([
                'success' => true,
                'total_cards' => $cardCount,
                'sample_cards' => $cards,
                'message' => "Found {$cardCount} cards in database"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    })->name('debug.cards-list');

    // API Routes that should return JSON responses
    Route::prefix('api')->group(function () {
        // Universal card comment routes with custom auth handling
        Route::get('/cards/{cardId}/detail', function($cardId) {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required. Please login first.',
                    'redirect' => '/login'
                ], 401);
            }
            return app(TeamLeadController::class)->getCardDetails($cardId);
        })->name('universal.card-details');

        Route::get('/cards/{cardId}/comments', function($cardId) {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required. Please login first.',
                    'redirect' => '/login'
                ], 401);
            }
            return app(TeamLeadController::class)->getCardComments($cardId);
        })->name('universal.card-comments');

        Route::post('/cards/{cardId}/comments', function(Request $request, $cardId) {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required. Please login first.',
                    'redirect' => '/login'
                ], 401);
            }
            return app(TeamLeadController::class)->addCardComment($request, $cardId);
        })->name('universal.add-card-comment');
    });

        })->name('debug.cards-list');

    // Simple test route
    Route::get('/test/api', function() {
        return response()->json([
            'success' => true,
            'message' => 'API is working!',
            'timestamp' => now(),
            'auth_status' => Auth::check() ? 'authenticated' : 'not authenticated',
            'user' => Auth::user() ? Auth::user()->name : null
        ]);
    });

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
        Route::get('/api/developer/cards/{cardId}/details', [DeveloperController::class, 'getCardDetails'])->name('developer.card-details');

        // Card Actions
        Route::post('/api/developer/cards/{cardId}/accept', [DeveloperController::class, 'acceptCard'])->name('developer.accept-card');
        Route::post('/api/developer/cards/{cardId}/start', [DeveloperController::class, 'startCard'])->name('developer.start-card');
        Route::put('/api/developer/cards/{cardId}/status', [DeveloperController::class, 'updateCardStatus'])->name('developer.update-card-status');
        Route::post('/api/developer/cards/{cardId}/submit', [DeveloperController::class, 'submitCardToTeamLead'])->name('developer.submit-card');
        Route::post('/api/developer/cards/{cardId}/submit-alt', [DeveloperController::class, 'submitCard'])->name('developer.submit-card-alt');

        // Timer and Comments
        Route::post('/api/developer/cards/{cardId}/toggle-timer', [DeveloperController::class, 'toggleTimer'])->name('developer.toggle-timer');
        Route::post('/api/developer/cards/{cardId}/comments', [DeveloperController::class, 'addCardComment'])->name('developer.add-comment');

        // Legacy routes kept for API compatibility
        Route::get('/developer/dashboard', [DeveloperController::class, 'dashboard'])->name('developer.dashboard.page');
        Route::get('/developer/work', [DeveloperController::class, 'myTasks'])->name('developer.work.legacy');
        Route::get('/developer/notifications', [DeveloperController::class, 'notifications'])->name('developer.notifications.legacy');
        Route::get('/developer/comments', [DeveloperController::class, 'comments'])->name('developer.comments.legacy');
        Route::get('/developer/profile', [DeveloperController::class, 'profile'])->name('developer.profile.legacy');
        Route::get('/developer/bugs', [DeveloperController::class, 'bugReports'])->name('developer.bugs');
        Route::post('/developer/bugs', [DeveloperController::class, 'createBugReport'])->name('developer.create-bug');
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
        Route::get('/api/designer/cards/{cardId}/details', [DesignerController::class, 'getCardDetails'])->name('designer.card-details');

        // Card Actions for Designer
        Route::post('/api/designer/cards/{cardId}/accept', [DesignerController::class, 'acceptCard'])->name('designer.accept-card');
        Route::post('/api/designer/cards/{cardId}/start', [DesignerController::class, 'startCard'])->name('designer.start-card');
        Route::put('/api/designer/cards/{cardId}/status', [DesignerController::class, 'updateCardStatus'])->name('designer.update-card-status');
        Route::post('/api/designer/cards/{cardId}/submit', [DesignerController::class, 'submitCardToTeamLead'])->name('designer.submit-card');
        Route::post('/api/designer/cards/{cardId}/submit-alt', [DesignerController::class, 'submitCard'])->name('designer.submit-card-alt');

        // Designer Specific Features
        Route::post('/api/designer/cards/{cardId}/upload-files', [DesignerController::class, 'uploadDesignFiles'])->name('designer.upload-files');
        Route::post('/api/designer/cards/{cardId}/toggle-timer', [DesignerController::class, 'toggleTimer'])->name('designer.toggle-timer');
        Route::post('/api/designer/cards/{cardId}/comments', [DesignerController::class, 'addCardComment'])->name('designer.add-comment');

        // Legacy routes kept for API compatibility
        Route::get('/designer/dashboard', [DesignerController::class, 'dashboard'])->name('designer.dashboard.page');
        Route::get('/designer/work', [DesignerController::class, 'myTasks'])->name('designer.work.legacy');
        Route::get('/designer/notifications', [DesignerController::class, 'notifications'])->name('designer.notifications.legacy');
        Route::get('/designer/comments', [DesignerController::class, 'comments'])->name('designer.comments.legacy');
        Route::get('/designer/profile', [DesignerController::class, 'profile'])->name('designer.profile.legacy');
        Route::get('/designer/portfolio', [DesignerController::class, 'portfolio'])->name('designer.portfolio.legacy');
        Route::get('/designer/brand-guidelines', [DesignerController::class, 'brandGuidelines'])->name('designer.brand-guidelines.legacy');
        Route::get('/designer/user-research', [DesignerController::class, 'userResearch'])->name('designer.user-research.legacy');
        Route::post('/designer/upload-design', [DesignerController::class, 'uploadDesignFile'])->name('designer.upload-design');
        Route::post('/designer/create-mockup', [DesignerController::class, 'createMockup'])->name('designer.create-mockup');
        Route::post('/designer/submit-review', [DesignerController::class, 'submitForReview'])->name('designer.submit-review');
    });

    // Unified Routes for both Developer and Designer
    Route::middleware(['auth'])->group(function () {
        // Main dashboard routes - automatically route based on user role
        Route::get('/dashboard', function () {
            $user = Auth::user();
            if ($user->role === 'Developer') {
                return app(App\Http\Controllers\DeveloperController::class)->dashboard();
            } elseif ($user->role === 'Designer') {
                return app(App\Http\Controllers\DesignerController::class)->dashboard();
            }
            return redirect('/');
        })->name('dashboard');

        Route::get('/work', function () {
            $user = Auth::user();
            if ($user->role === 'Developer') {
                return app(App\Http\Controllers\DeveloperController::class)->myTasks();
            } elseif ($user->role === 'Designer') {
                return app(App\Http\Controllers\DesignerController::class)->myTasks();
            }
            return redirect('/');
        })->name('work');

        Route::get('/notifications', function () {
            $user = Auth::user();
            if ($user->role === 'Developer') {
                return app(App\Http\Controllers\DeveloperController::class)->notifications();
            } elseif ($user->role === 'Designer') {
                return app(App\Http\Controllers\DesignerController::class)->notifications();
            }
            return redirect('/');
        })->name('notifications');

        Route::get('/comments', function () {
            $user = Auth::user();
            if ($user->role === 'Developer') {
                return app(App\Http\Controllers\DeveloperController::class)->comments();
            } elseif ($user->role === 'Designer') {
                return app(App\Http\Controllers\DesignerController::class)->comments();
            }
            return redirect('/');
        })->name('comments');

        // Profile route moved to main section

        // Designer-specific routes
        Route::get('/portfolio', [DesignerController::class, 'portfolio'])->name('portfolio');
        Route::get('/brand-guidelines', [DesignerController::class, 'brandGuidelines'])->name('brand-guidelines');
        Route::get('/user-research', [DesignerController::class, 'userResearch'])->name('user-research');
        Route::post('/upload-design', [DesignerController::class, 'uploadDesignFile'])->name('upload-design');

        // Developer-specific routes
        Route::get('/bugs', [DeveloperController::class, 'bugReports'])->name('bugs');
        Route::post('/bugs', [DeveloperController::class, 'createBugReport'])->name('create-bug');
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

    // Card Todo List API Routes - Todos for specific cards
    Route::get('/api/card-todos', [App\Http\Controllers\TodoController::class, 'index'])->name('card-todos.index');
    Route::post('/api/card-todos', [App\Http\Controllers\TodoController::class, 'store'])->name('card-todos.store');
    Route::put('/api/card-todos/{todo}', [App\Http\Controllers\TodoController::class, 'update'])->name('card-todos.update');
    Route::delete('/api/card-todos/{todo}', [App\Http\Controllers\TodoController::class, 'destroy'])->name('card-todos.destroy');
    Route::put('/api/card-todos/{todo}/toggle', [App\Http\Controllers\TodoController::class, 'toggle'])->name('card-todos.toggle');

    // Time Log API Routes - Time tracking for cards and tasks
    Route::get('/api/time-logs', [App\Http\Controllers\TimeLogController::class, 'index'])->name('time-logs.index');
    Route::post('/api/time-logs/start', [App\Http\Controllers\TimeLogController::class, 'start'])->name('time-logs.start');
    Route::post('/api/time-logs/stop', [App\Http\Controllers\TimeLogController::class, 'stop'])->name('time-logs.stop');
    Route::get('/api/time-logs/active', [App\Http\Controllers\TimeLogController::class, 'getActiveTimer'])->name('time-logs.active');
    Route::put('/api/time-logs/{id}', [App\Http\Controllers\TimeLogController::class, 'update'])->name('time-logs.update');
    Route::delete('/api/time-logs/{id}', [App\Http\Controllers\TimeLogController::class, 'destroy'])->name('time-logs.destroy');
    Route::get('/api/time-logs/statistics', [App\Http\Controllers\TimeLogController::class, 'getStatistics'])->name('time-logs.statistics');
    Route::get('/api/cards/{cardId}/time-logs', [App\Http\Controllers\TimeLogController::class, 'getCardTimeLogs'])->name('cards.time-logs');

    // Member Panel Routes - For developers, designers, and regular members
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/panel', function() {
            return view('member.panel');
        })->name('panel');
        Route::get('/dashboard', [App\Http\Controllers\MemberController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-cards', [App\Http\Controllers\MemberController::class, 'myCards'])->name('my-cards');
        Route::get('/card/{cardId}', [App\Http\Controllers\MemberController::class, 'cardDetail'])->name('card-detail');
        Route::get('/time-logs', [App\Http\Controllers\MemberController::class, 'timeLogs'])->name('time-logs');

        // Debug route untuk troubleshoot member projects
        Route::get('/debug-projects', function() {
            $user = Auth::user();

            // Check raw memberships
            $rawMemberships = \App\Models\ProjectMember::where('user_id', $user->user_id)->get();

            // Check memberships with projects
            $membershipsWithProjects = \App\Models\ProjectMember::with('project')
                ->where('user_id', $user->user_id)->get();

            // Check all projects
            $allProjects = \App\Models\Project::all();

            // Check all members
            $allMembers = \App\Models\ProjectMember::with(['project', 'user'])->get();

            // Test Board-Project relations
            $boardProjectTest = \App\Models\Board::with('project')->first();

            return response()->json([
                'current_user' => [
                    'id' => $user->user_id,
                    'name' => $user->full_name,
                    'role' => $user->role
                ],
                'raw_memberships_count' => $rawMemberships->count(),
                'raw_memberships' => $rawMemberships,
                'memberships_with_projects_count' => $membershipsWithProjects->count(),
                'memberships_with_projects' => $membershipsWithProjects,
                'all_projects_count' => $allProjects->count(),
                'all_projects' => $allProjects,
                'all_members_count' => $allMembers->count(),
                'all_members' => $allMembers,
                'board_project_relation_test' => $boardProjectTest
            ], 200, [], JSON_PRETTY_PRINT);
        })->name('debug-projects');

        // Card actions
        Route::post('/card/{cardId}/status', [App\Http\Controllers\MemberController::class, 'updateCardStatus'])->name('card.update-status');

        // Timer actions
        Route::post('/card/{cardId}/timer/start', [App\Http\Controllers\MemberController::class, 'startTimer'])->name('card.timer.start');
        Route::post('/card/{cardId}/timer/stop', [App\Http\Controllers\MemberController::class, 'stopTimer'])->name('card.timer.stop');
        Route::get('/timer/active', [App\Http\Controllers\MemberController::class, 'getActiveTimer'])->name('timer.active');

        // Comment actions
        Route::get('/card/{cardId}/comments', [App\Http\Controllers\MemberController::class, 'getCardComments'])->name('card.comments');
        Route::post('/card/{cardId}/comments', [App\Http\Controllers\MemberController::class, 'addCardComment'])->name('card.add-comment');
    });

    // Member Panel API Routes
    Route::prefix('api/member')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\MemberController::class, 'getDashboard'])->name('api.member.dashboard');
        Route::get('/my-cards', [App\Http\Controllers\MemberController::class, 'getMyCards'])->name('api.member.my-cards');
        Route::get('/projects', [App\Http\Controllers\MemberController::class, 'getProjects'])->name('api.member.projects');
    });

    // Card API Routes for Member Panel
    Route::prefix('api/cards')->middleware(['auth'])->group(function () {
        Route::get('/{cardId}', [App\Http\Controllers\MemberController::class, 'getCardDetail'])->name('api.card.detail');
        Route::post('/{cardId}/start', [App\Http\Controllers\MemberController::class, 'startCard'])->name('api.card.start');
        Route::post('/{cardId}/submit', [App\Http\Controllers\MemberController::class, 'submitCard'])->name('api.card.submit');
    });

    // Quick test route for card observer fix
    Route::get('/test-card-observer-fix', function() {
        try {
            $card = \App\Models\Card::find(1);
            if (!$card) {
                return response()->json(['error' => 'Card not found'], 404);
            }

            $oldStatus = $card->status;
            $newStatus = $oldStatus === 'todo' ? 'in_progress' : 'todo';

            $card->status = $newStatus;
            $card->save();

            return response()->json([
                'success' => true,
                'message' => 'Card status updated successfully - Observer fix working!',
                'data' => [
                    'card_id' => $card->card_id,
                    'old_status' => $oldStatus,
                    'new_status' => $card->status,
                    'fixed_issue' => 'handlingStatusChange property is now static and guarded'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    });

    // Debug route for testing todo access
    Route::get('/debug/todo-access/{cardId}', function($cardId) {
        try {
            $user = Auth::user();
            $card = \App\Models\Card::with(['board.project', 'assignments'])->find($cardId);

            if (!$card) {
                return response()->json(['error' => 'Card not found'], 404);
            }

            $debugInfo = [
                'user' => [
                    'id' => $user->user_id,
                    'name' => $user->full_name,
                    'role' => $user->role
                ],
                'card' => [
                    'id' => $card->card_id,
                    'title' => $card->card_title,
                    'board_id' => $card->board_id,
                    'has_board' => $card->board ? true : false,
                    'board_name' => $card->board ? $card->board->board_name : null,
                    'has_project' => ($card->board && $card->board->project) ? true : false,
                    'project_id' => ($card->board && $card->board->project) ? $card->board->project->project_id : null,
                    'project_name' => ($card->board && $card->board->project) ? $card->board->project->project_name : null,
                ],
                'assignments' => $card->assignments->map(function($a) {
                    return [
                        'user_id' => $a->user_id,
                        'user_name' => $a->user ? $a->user->full_name : null
                    ];
                }),
                'is_assigned' => $card->assignments()->where('user_id', $user->user_id)->exists()
            ];

            // Check Team Lead membership
            if ($user->role === 'Team Lead' && $card->board && $card->board->project) {
                $isTeamLead = \App\Models\ProjectMember::where('project_id', $card->board->project->project_id)
                    ->where('user_id', $user->user_id)
                    ->where('role', 'Team Lead')
                    ->exists();

                $debugInfo['team_lead_check'] = [
                    'is_team_lead' => $isTeamLead,
                    'project_id' => $card->board->project->project_id
                ];
            }

            return response()->json($debugInfo);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    });

