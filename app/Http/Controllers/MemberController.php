<?php
namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardAssignment;
use App\Models\CardHistory;
use App\Models\TimeLog;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $assignedCards = CardAssignment::select("card_assignments.*")
            ->with(["card:card_id,card_title,status,priority,due_date,created_at", "card.board:board_id,board_name"])
            ->where("user_id", $user->user_id)
            ->whereHas("card", function($query) {
                $query->whereNotIn("status", ["done", "cancelled"]);
            })
            ->get();

        $stats = [
            "total_cards" => $assignedCards->count(),
            "in_progress" => $assignedCards->where("card.status", "in_progress")->count(),
            "todo" => $assignedCards->where("card.status", "todo")->count(),
            "review" => $assignedCards->where("card.status", "review")->count(),
            "overdue" => $assignedCards->filter(function($assignment) {
                return $assignment->card->due_date && $assignment->card->due_date < Carbon::today() && $assignment->card->status !== "done";
            })->count()
        ];

        $recentCards = $assignedCards->sortByDesc("card.created_at")->take(5);

        $todayTimeLogs = TimeLog::select("duration_minutes", "start_time", "end_time", "card_id")
            ->with("card:card_id,card_title")
            ->where("user_id", $user->user_id)
            ->whereDate("start_time", Carbon::today())
            ->orderBy("start_time", "desc")
            ->get();

        $totalTimeToday = $todayTimeLogs->sum("duration_minutes");

        return view("member.dashboard", compact(
            "stats",
            "recentCards",
            "todayTimeLogs",
            "totalTimeToday"
        ));
    }

    public function myCards(Request $request)
    {
        $user = Auth::user();
        $status = $request->get("status", "all");

        $query = CardAssignment::select("card_assignments.*")
            ->with([
                "card" => function($q) {
                    $q->select('card_id', 'card_title', 'description', 'status', 'priority', 'due_date', 'created_at', 'created_by', 'board_id');
                },
                "card.board" => function($q) {
                    $q->select('board_id', 'board_name', 'project_id');
                },
                "card.board.project" => function($q) {
                    $q->select('project_id', 'project_name');
                },
                "card.creator" => function($q) {
                    $q->select('user_id', 'full_name');
                }
            ])
            ->where("user_id", $user->user_id);

        if ($status !== "all") {
            $query->whereHas("card", function($q) use ($status) {
                $q->where("status", $status);
            });
        }

        $assignments = $query->orderBy("card_id", "desc")->get();

        // Get projects that this member is part of
        $memberProjects = ProjectMember::with([
            'project' => function($query) {
                $query->select('project_id', 'project_name', 'description', 'status', 'created_at')
                    ->withCount([
                        'cards as total_cards',
                        'cards as completed_cards' => function($q) {
                            $q->where('status', 'done');
                        }
                    ])
                    ->with(['members' => function($q) {
                        $q->select('project_id', 'user_id', 'role');
                    }]);
            }
        ])
        ->where('user_id', $user->user_id)
        ->get();

        // Debug: Check if user has project memberships
        $debugMembershipCount = ProjectMember::where('user_id', $user->user_id)->count();
        Log::info('User ID: ' . $user->user_id . ' has ' . $debugMembershipCount . ' project memberships');
        Log::info('Raw member projects count: ' . $memberProjects->count());

        $memberProjects = $memberProjects->map(function($member) {
            $project = $member->project;
            if ($project) {
                // Calculate progress percentage
                $totalCards = $project->total_cards;
                $completedCards = $project->completed_cards;
                $project->progress_percentage = $totalCards > 0 ? round(($completedCards / $totalCards) * 100) : 0;

                // Add member role and team size
                $project->member_role = $member->role;
                $project->team_size = $project->members->count();
                $project->joined_at = $member->joined_at;

                Log::info('Project found: ' . $project->project_name);
                return $project;
            }
            return null;
        })
        ->filter()
        ->values();

        $statusCounts = [
            "all" => CardAssignment::where("user_id", $user->user_id)->count(),
            "todo" => CardAssignment::where("user_id", $user->user_id)
                ->whereHas("card", function($q) { $q->where("status", "todo"); })->count(),
            "in_progress" => CardAssignment::where("user_id", $user->user_id)
                ->whereHas("card", function($q) { $q->where("status", "in_progress"); })->count(),
            "review" => CardAssignment::where("user_id", $user->user_id)
                ->whereHas("card", function($q) { $q->where("status", "review"); })->count(),
            "done" => CardAssignment::where("user_id", $user->user_id)
                ->whereHas("card", function($q) { $q->where("status", "done"); })->count(),
        ];

        return view("member.my-cards", compact("assignments", "statusCounts", "status", "memberProjects"));
    }

    public function cardDetail($cardId)
    {
        $user = Auth::user();

        $assignment = CardAssignment::where('card_id', $cardId)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$assignment) {
            abort(403, 'You are not assigned to this card.');
        }

        $card = Card::with(['board', 'creator', 'subtasks', 'timeLogs'])
            ->findOrFail($cardId);

        $timeLogs = TimeLog::where('card_id', $cardId)
            ->where('user_id', $user->user_id)
            ->orderBy('start_time', 'desc')
            ->get();

        $totalTime = $timeLogs->sum('duration_minutes');

        $comments = DB::table('card_comments')
            ->join('users', 'card_comments.user_id', '=', 'users.user_id')
            ->where('card_comments.card_id', $cardId)
            ->select(
                'card_comments.*',
                'users.full_name',
                'users.role',
                'users.profile_photo'
            )
            ->orderBy('card_comments.created_at', 'asc')
            ->get();

        return view('member.card-detail', compact('card', 'assignment', 'timeLogs', 'totalTime', 'comments'));
    }

    public function updateCardStatus(Request $request, $cardId)
    {
        try {
            $user = Auth::user();

            // Log the request for debugging
            \Log::info("Card status update request", [
                'card_id' => $cardId,
                'user_id' => $user->user_id,
                'requested_status' => $request->status,
                'timestamp' => now()
            ]);

            $assignment = CardAssignment::where('card_id', $cardId)
                ->where('user_id', $user->user_id)
                ->first();

            if (!$assignment) {
                \Log::warning("User not assigned to card", ['card_id' => $cardId, 'user_id' => $user->user_id]);
                return response()->json(['error' => 'You are not assigned to this card.'], 403);
            }

            $request->validate([
                'status' => 'required|in:todo,in_progress,review,done'
            ]);

            $card = Card::findOrFail($cardId);
            $oldStatus = $card->status;
            $newStatus = $request->status;

            // Validate status transition
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                \Log::warning("Invalid status transition", [
                    'card_id' => $cardId,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
                return response()->json([
                    'error' => "Invalid status transition from {$oldStatus} to {$newStatus}"
                ], 400);
            }

            // Use database transaction to ensure data consistency
            \DB::beginTransaction();

            try {
                $card->status = $newStatus;
                $timerStarted = false;

                // Update assignment status based on card status
                // Note: assignment_status ENUM values: ['assigned', 'in_progress', 'completed']
                if ($newStatus === 'in_progress' && $oldStatus !== 'in_progress') {
                    $assignment->started_at = now();
                    $assignment->assignment_status = 'in_progress';
                    $card->started_at = now();

                    $this->autoStartTimer($cardId, $user->user_id);
                    $timerStarted = true;
                } elseif ($newStatus === 'done') {
                    $assignment->completed_at = now();
                    $assignment->assignment_status = 'completed';
                    $card->completed_at = now();

                    $this->autoStopTimer($cardId, $user->user_id);
                } elseif ($newStatus === 'review') {
                    // For review status, keep assignment as in_progress since 'review' is not in ENUM
                    // The card status will be 'review' but assignment stays 'in_progress'
                    $assignment->assignment_status = 'in_progress';
                } elseif ($newStatus === 'todo') {
                    // For todo status, set assignment back to assigned
                    $assignment->assignment_status = 'assigned';
                    $assignment->started_at = null;
                }

                $card->save();
                $assignment->save();

                \DB::commit();

                \Log::info("Card status updated successfully", [
                    'card_id' => $cardId,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'timer_started' => $timerStarted
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Card status updated successfully.',
                    'status' => $newStatus,
                    'timer_started' => $timerStarted
                ]);

            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error("Database error during card status update", [
                    'card_id' => $cardId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning("Validation error in updateCardStatus", [
                'card_id' => $cardId,
                'errors' => $e->errors()
            ]);
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error("Unexpected error in updateCardStatus", [
                'card_id' => $cardId,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while updating the card status. Please try again.',
                'debug_info' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function timeLogs(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        $timeLogs = TimeLog::with(['card:card_id,card_title', 'card.board:board_id,board_name'])
            ->where('user_id', $user->user_id)
            ->whereDate('start_time', $date)
            ->orderBy('start_time', 'desc')
            ->get();

        $totalDuration = $timeLogs->sum('duration_minutes');

        $weekStart = Carbon::parse($date)->startOfWeek();
        $weekEnd = Carbon::parse($date)->endOfWeek();

        $weeklyLogs = TimeLog::where('user_id', $user->user_id)
            ->whereBetween('start_time', [$weekStart, $weekEnd])
            ->selectRaw('DATE(start_time) as date, SUM(duration_minutes) as total_minutes')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $weeklyTotal = $weeklyLogs->sum('total_minutes');
        $dailySummary = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $dayKey = $day->format('Y-m-d');
            $dayTotal = $weeklyLogs->get($dayKey)->total_minutes ?? 0;

            $dailySummary[] = [
                'date' => $day,
                'total_minutes' => $dayTotal,
                'total_hours' => round($dayTotal / 60, 2)
            ];
        }

        return view('member.time-logs', compact(
            'timeLogs',
            'totalDuration',
            'date',
            'dailySummary',
            'weeklyTotal'
        ));
    }

    public function startTimer(Request $request, $cardId)
    {
        $user = Auth::user();

        $assignment = CardAssignment::where('card_id', $cardId)
            ->where('user_id', $user->user_id)
            ->first();

        if (!$assignment) {
            return response()->json(['error' => 'You are not assigned to this card.'], 403);
        }

        TimeLog::where('user_id', $user->user_id)
            ->whereNull('end_time')
            ->update([
                'end_time' => now(),
                'duration_minutes' => DB::raw('TIMESTAMPDIFF(MINUTE, start_time, NOW())')
            ]);

        $timeLog = TimeLog::create([
            'user_id' => $user->user_id,
            'card_id' => $cardId,
            'start_time' => now(),
            'description' => $request->get('description', '')
        ]);

        $card = Card::findOrFail($cardId);
        $card->is_timer_active = true;
        $card->timer_started_at = now();
        $card->save();

        return response()->json([
            'success' => true,
            'message' => 'Timer started successfully.',
            'time_log_id' => $timeLog->log_id
        ]);
    }

    public function stopTimer(Request $request, $cardId)
    {
        $user = Auth::user();

        $timeLog = TimeLog::where('user_id', $user->user_id)
            ->where('card_id', $cardId)
            ->whereNull('end_time')
            ->first();

        if (!$timeLog) {
            return response()->json(['error' => 'No active timer found for this card.'], 404);
        }

        $timeLog->end_time = now();
        $timeLog->duration_minutes = Carbon::parse($timeLog->start_time)
            ->diffInMinutes($timeLog->end_time);

        if ($request->has('description')) {
            $timeLog->description = $request->description;
        }

        $timeLog->save();

        $card = Card::findOrFail($cardId);
        $card->is_timer_active = false;
        $card->timer_started_at = null;

        $totalMinutes = TimeLog::where('card_id', $cardId)->sum('duration_minutes');
        $card->actual_hours = round($totalMinutes / 60, 2);
        $card->save();

        return response()->json([
            'success' => true,
            'message' => 'Timer stopped successfully.',
            'duration' => $timeLog->duration_minutes
        ]);
    }

    public function getActiveTimer()
    {
        $user = Auth::user();

        $activeTimer = TimeLog::with('card:card_id,card_title')
            ->where('user_id', $user->user_id)
            ->whereNull('end_time')
            ->first();

        if (!$activeTimer) {
            return response()->json(['active' => false]);
        }

        $duration = Carbon::parse($activeTimer->start_time)->diffInMinutes(now());

        return response()->json([
            'active' => true,
            'card_id' => $activeTimer->card_id,
            'card_title' => $activeTimer->card->card_title,
            'start_time' => $activeTimer->start_time,
            'duration' => $duration
        ]);
    }

    /**
     * Validate if status transition is allowed
     */
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            'todo' => ['in_progress'],
            'in_progress' => ['review', 'todo'],
            'review' => ['done', 'in_progress'],
            'done' => [] // No transitions from done
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }

    private function autoStartTimer($cardId, $userId)
    {
        try {
            // Stop any existing timers for this user
            $updatedRows = TimeLog::where('user_id', $userId)
                ->whereNull('end_time')
                ->update([
                    'end_time' => now(),
                    'duration_minutes' => DB::raw('TIMESTAMPDIFF(MINUTE, start_time, NOW())')
                ]);

            if ($updatedRows > 0) {
                \Log::info("Stopped {$updatedRows} existing timer(s) for user {$userId}");
            }

            // Create new timer
            $timeLog = TimeLog::create([
                'user_id' => $userId,
                'card_id' => $cardId,
                'start_time' => now(),
                'description' => 'Auto-started when beginning work'
            ]);

            // Update card timer status
            $card = Card::find($cardId);
            if ($card) {
                $card->is_timer_active = true;
                $card->timer_started_at = now();
                $card->save();

                \Log::info("Timer started for card {$cardId}, user {$userId}", [
                    'time_log_id' => $timeLog->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Error in autoStartTimer", [
                'card_id' => $cardId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            // Don't throw the error, just log it so main operation can continue
        }
    }

    private function autoStopTimer($cardId, $userId)
    {
        try {
            $timeLog = TimeLog::where('user_id', $userId)
                ->where('card_id', $cardId)
                ->whereNull('end_time')
                ->first();

            if ($timeLog) {
                $timeLog->end_time = now();
                $timeLog->duration_minutes = Carbon::parse($timeLog->start_time)
                    ->diffInMinutes($timeLog->end_time);
                $timeLog->save();

                // Update card timer status and calculate total hours
                $card = Card::find($cardId);
                if ($card) {
                    $card->is_timer_active = false;
                    $card->timer_started_at = null;

                    // Calculate total actual hours from all time logs
                    $totalMinutes = TimeLog::where('card_id', $cardId)
                        ->whereNotNull('duration_minutes')
                        ->sum('duration_minutes');
                    $card->actual_hours = round($totalMinutes / 60, 2);
                    $card->save();

                    \Log::info("Timer stopped for card {$cardId}, user {$userId}", [
                        'duration_minutes' => $timeLog->duration_minutes,
                        'total_hours' => $card->actual_hours
                    ]);
                }
            } else {
                \Log::warning("No active timer found to stop for card {$cardId}, user {$userId}");
            }
        } catch (\Exception $e) {
            \Log::error("Error in autoStopTimer", [
                'card_id' => $cardId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            // Don't throw the error, just log it so main operation can continue
        }
    }

    public function getCardComments($cardId)
    {
        $user = Auth::user();

        $hasAccess = CardAssignment::where('card_id', $cardId)
            ->where('user_id', $user->user_id)
            ->exists();

        if (!$hasAccess && $user->role === 'Team_Lead') {
            $hasAccess = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('project_members', 'projects.project_id', '=', 'project_members.project_id')
                ->where('cards.card_id', $cardId)
                ->where('project_members.user_id', $user->user_id)
                ->where('project_members.role', 'Team_Lead')
                ->exists();
        }

        if (!$hasAccess) {
            return response()->json(['error' => 'You do not have access to this card.'], 403);
        }

        $comments = DB::table('card_comments')
            ->join('users', 'card_comments.user_id', '=', 'users.user_id')
            ->where('card_comments.card_id', $cardId)
            ->select(
                'card_comments.*',
                'users.full_name',
                'users.role',
                'users.profile_photo'
            )
            ->orderBy('card_comments.created_at', 'asc')
            ->get();

        $organizedComments = [];
        $replies = [];

        foreach ($comments as $comment) {
            if ($comment->parent_id) {
                if (!isset($replies[$comment->parent_id])) {
                    $replies[$comment->parent_id] = [];
                }
                $replies[$comment->parent_id][] = $comment;
            } else {
                $organizedComments[] = $comment;
            }
        }

        foreach ($organizedComments as &$comment) {
            $comment->replies = $replies[$comment->comment_id] ?? [];
        }

        return response()->json([
            'success' => true,
            'comments' => $organizedComments
        ]);
    }

    public function addCardComment(Request $request, $cardId)
    {
        $user = Auth::user();

        $hasAccess = CardAssignment::where('card_id', $cardId)
            ->where('user_id', $user->user_id)
            ->exists();

        if (!$hasAccess && $user->role === 'Team_Lead') {
            $hasAccess = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('project_members', 'projects.project_id', '=', 'project_members.project_id')
                ->where('cards.card_id', $cardId)
                ->where('project_members.user_id', $user->user_id)
                ->where('project_members.role', 'Team_Lead')
                ->exists();
        }

        if (!$hasAccess) {
            return response()->json(['error' => 'You do not have access to this card.'], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:card_comments,comment_id'
        ]);

        $commentId = DB::table('card_comments')->insertGetId([
            'card_id' => $cardId,
            'user_id' => $user->user_id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $comment = DB::table('card_comments')
            ->join('users', 'card_comments.user_id', '=', 'users.user_id')
            ->where('card_comments.comment_id', $commentId)
            ->select(
                'card_comments.*',
                'users.full_name',
                'users.role',
                'users.profile_photo'
            )
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'comment' => $comment
        ]);
    }

    /**
     * Get dashboard data for API
     */
    public function getDashboard()
    {
        try {
            $user = Auth::user();

            $assignedCards = CardAssignment::with(['card'])
                ->where('user_id', $user->user_id)
                ->whereHas('card', function($query) {
                    $query->whereNotIn('status', ['cancelled']);
                })
                ->get();

            $stats = [
                'total' => $assignedCards->count(),
                'in_progress' => $assignedCards->where('card.status', 'in_progress')->count(),
                'completed' => $assignedCards->where('card.status', 'done')->count(),
                'overdue' => $assignedCards->filter(function($assignment) {
                    return $assignment->card->due_date &&
                           $assignment->card->due_date < Carbon::today() &&
                           $assignment->card->status !== 'done';
                })->count()
            ];

            $recentActivities = [];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'activities' => $recentActivities
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading member dashboard: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading dashboard'
            ], 500);
        }
    }

    /**
     * Get cards for member API with caching
     */
    public function getMyCards()
    {
        try {
            $user = Auth::user();

            // Simple optimization: use direct query with minimal joins
            $cards = DB::table('card_assignments as ca')
                ->join('cards as c', 'ca.card_id', '=', 'c.card_id')
                ->join('boards as b', 'c.board_id', '=', 'b.board_id')
                ->leftJoin('projects as p', 'b.project_id', '=', 'p.project_id')
                ->where('ca.user_id', $user->user_id)
                ->select([
                    'c.card_id',
                    'c.card_title',
                    'c.description',
                    'c.status',
                    'c.priority',
                    'c.estimated_hours',
                    'c.due_date',
                    'b.board_name',
                    'p.project_name'
                ])
                ->orderBy('c.card_id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'cards' => $cards
            ])->header('Cache-Control', 'no-cache, must-revalidate');

        } catch (\Exception $e) {
            Log::error('Error loading member cards: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading cards'
            ], 500);
        }
    }

    /**
     * Get projects for member API
     */
    public function getProjects()
    {
        try {
            $user = Auth::user();

            $projects = DB::table('project_members as pm')
                ->join('projects as p', 'pm.project_id', '=', 'p.project_id')
                ->where('pm.user_id', $user->user_id)
                ->select([
                    'p.project_id',
                    'p.project_name',
                    'p.description',
                    'pm.role',
                    'p.created_at'
                ])
                ->get();

            return response()->json([
                'success' => true,
                'projects' => $projects
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading member projects: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading projects'
            ], 500);
        }
    }

    /**
     * Get card detail for API
     */
    public function getCardDetail($cardId)
    {
        try {
            $user = Auth::user();

            // Check if user has access to this card
            $hasAccess = DB::table('card_assignments')
                ->where('card_id', $cardId)
                ->where('user_id', $user->user_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $card = DB::table('cards as c')
                ->join('boards as b', 'c.board_id', '=', 'b.board_id')
                ->leftJoin('projects as p', 'b.project_id', '=', 'p.project_id')
                ->leftJoin('users as creator', 'c.created_by', '=', 'creator.user_id')
                ->where('c.card_id', $cardId)
                ->select([
                    'c.*',
                    'b.board_name',
                    'p.project_name',
                    'creator.full_name as created_by_name'
                ])
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'card' => $card
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading card detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading card detail'
            ], 500);
        }
    }

    /**
     * Start working on a card
     */
    public function startCard($cardId)
    {
        try {
            $user = Auth::user();

            // Single query to check access and get card
            $card = DB::table('cards as c')
                ->join('card_assignments as ca', 'c.card_id', '=', 'ca.card_id')
                ->where('c.card_id', $cardId)
                ->where('ca.user_id', $user->user_id)
                ->where('c.status', 'todo')
                ->select('c.card_id', 'c.status')
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found or cannot be started'
                ], 404);
            }

            // Quick update card status
            $updated = DB::table('cards')
                ->where('card_id', $cardId)
                ->update([
                    'status' => 'in_progress',
                    'started_at' => now()
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update card status'
                ], 500);
            }

            // Async history logging (non-blocking)
            dispatch(function() use ($cardId, $user) {
                try {
                    CardHistory::create([
                        'card_id' => $cardId,
                        'user_id' => $user->user_id,
                        'action' => 'started',
                        'old_status' => 'todo',
                        'new_status' => 'in_progress',
                        'comment' => 'Card started by member',
                        'action_date' => now()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error logging card history: ' . $e->getMessage());
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Card started successfully',
                'data' => [
                    'card_id' => $cardId,
                    'status' => 'in_progress',
                    'started_at' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error starting card: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error starting card'
            ], 500);
        }
    }

    /**
     * Submit card for review
     */
    public function submitCard(Request $request, $cardId)
    {
        try {
            $user = Auth::user();

            // Single query to check access and get card
            $card = DB::table('cards as c')
                ->join('card_assignments as ca', 'c.card_id', '=', 'ca.card_id')
                ->where('c.card_id', $cardId)
                ->where('ca.user_id', $user->user_id)
                ->where('c.status', 'in_progress')
                ->select('c.card_id', 'c.status')
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found or cannot be submitted'
                ], 404);
            }

            // Quick update card status
            $updated = DB::table('cards')
                ->where('card_id', $cardId)
                ->update(['status' => 'review']);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update card status'
                ], 500);
            }

            // Async history logging (non-blocking)
            $comment = $request->input('comment', '');
            dispatch(function() use ($cardId, $user, $comment) {
                try {
                    CardHistory::create([
                        'card_id' => $cardId,
                        'user_id' => $user->user_id,
                        'action' => 'submitted',
                        'old_status' => 'in_progress',
                        'new_status' => 'review',
                        'comment' => 'Card submitted for review by member',
                        'feedback' => $comment,
                        'action_date' => now()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error logging card history: ' . $e->getMessage());
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Card submitted for review successfully',
                'data' => [
                    'card_id' => $cardId,
                    'status' => 'review',
                    'submitted_at' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error submitting card: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error submitting card'
            ], 500);
        }
    }
}
