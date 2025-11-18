<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CheckPermission;

class TeamLeadBoardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'Team_Lead') {
                return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan Team Lead.');
            }
            return $next($request);
        });
    }

    /**
     * API: Get boards for Team Lead's projects
     */
    public function getBoards()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get boards from Team Lead's projects with enhanced info
        $boards = DB::table('boards')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->join('members', 'projects.project_id', '=', 'members.project_id')
            ->leftJoin('cards', 'boards.board_id', '=', 'cards.board_id')
            ->where('members.user_id', $user->user_id)
            ->where('members.role', 'Team_Lead')
            ->select(
                'boards.board_id as id',
                'boards.board_name as name',
                'boards.description',
                'boards.created_at',
                'projects.project_name',
                DB::raw('COUNT(cards.card_id) as total_cards'),
                DB::raw('COUNT(CASE WHEN cards.status = "todo" THEN 1 END) as todo_cards'),
                DB::raw('COUNT(CASE WHEN cards.status = "in_progress" THEN 1 END) as in_progress_cards'),
                DB::raw('COUNT(CASE WHEN cards.status = "review" THEN 1 END) as review_cards'),
                DB::raw('COUNT(CASE WHEN cards.status = "done" THEN 1 END) as done_cards')
            )
            ->groupBy('boards.board_id', 'boards.board_name', 'boards.description', 'boards.created_at', 'projects.project_name')
            ->orderBy('boards.created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'boards' => $boards
        ]);
    }

    /**
     * API: Create new board
     */
    public function createBoard(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            $request->validate([
                'board_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500'
            ]);

            // Get the project where user is Team Lead
            $project = DB::table('members')
                ->join('projects', 'members.project_id', '=', 'projects.project_id')
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('projects.*')
                ->first();

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project assigned to you as Team Lead'
                ]);
            }

            // Check if board name already exists in this project
            $existingBoard = DB::table('boards')
                ->where('project_id', $project->project_id)
                ->where('board_name', $request->board_name)
                ->first();

            if ($existingBoard) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board with this name already exists in the project'
                ]);
            }

            // Create the board
            $boardId = DB::table('boards')->insertGetId([
                'project_id' => $project->project_id,
                'board_name' => $request->board_name,
                'description' => $request->description,
                'position' => 0, // Default position
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Board created successfully',
                'board_id' => $boardId,
                'board' => [
                    'board_id' => $boardId,
                    'board_name' => $request->board_name,
                    'description' => $request->description,
                    'project_name' => $project->project_name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating board: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get board detail with cards
     */
    public function getBoardDetail($boardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Verify the board belongs to Team Lead's project and get board info
            $board = DB::table('boards')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('boards.board_id', $boardId)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select(
                    'boards.*',
                    'projects.project_name',
                    'projects.project_id'
                )
                ->first();

            if (!$board) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board not found or access denied'
                ]);
            }

            // Get cards in this board with assignment info
            $cards = DB::table('cards')
                ->leftJoin('card_assignments', 'cards.card_id', '=', 'card_assignments.card_id')
                ->leftJoin('users as assigned_users', 'card_assignments.user_id', '=', 'assigned_users.user_id')
                ->where('cards.board_id', $boardId)
                ->select(
                    'cards.*',
                    'card_assignments.assignment_id',
                    'card_assignments.assigned_at',
                    'card_assignments.assignment_status',
                    'assigned_users.full_name as assigned_user_name',
                    'assigned_users.email as assigned_user_email'
                )
                ->orderBy('cards.created_at', 'desc')
                ->get();

            // Group cards by status for kanban view
            $cardsByStatus = [
                'todo' => $cards->where('status', 'todo')->values(),
                'in_progress' => $cards->where('status', 'in_progress')->values(),
                'review' => $cards->where('status', 'review')->values(),
                'done' => $cards->where('status', 'done')->values()
            ];

            // Calculate board statistics
            $statistics = [
                'total_cards' => $cards->count(),
                'todo_cards' => $cards->where('status', 'todo')->count(),
                'in_progress_cards' => $cards->where('status', 'in_progress')->count(),
                'review_cards' => $cards->where('status', 'review')->count(),
                'done_cards' => $cards->where('status', 'done')->count(),
                'assigned_cards' => $cards->whereNotNull('assignment_id')->count(),
                'unassigned_cards' => $cards->whereNull('assignment_id')->count()
            ];

            return response()->json([
                'success' => true,
                'board' => $board,
                'cards' => $cards,
                'cards_by_status' => $cardsByStatus,
                'statistics' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading board detail: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Create new card
     */
    public function createCard(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'board_id' => 'required|integer',
                'card_title' => 'required|string|max:100',
                'description' => 'nullable|string',
                'priority' => 'nullable|in:low,medium,high',
                'due_date' => 'nullable|date|after:today',
                'position' => 'nullable|integer'
            ]);

            // Verify the board belongs to Team Lead's project
            $board = DB::table('boards')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('boards.board_id', $request->board_id)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('boards.*', 'projects.project_name')
                ->first();

            if (!$board) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board not found or access denied'
                ], 403);
            }

            // Create the card
            $cardId = DB::table('cards')->insertGetId([
                'board_id' => $request->board_id,
                'card_title' => $request->card_title,
                'description' => $request->description,
                'status' => 'todo', // Default status
                'priority' => $request->priority ?? 'medium',
                'due_date' => $request->due_date,
                'position' => $request->position ?? 0,
                'created_by' => $user->user_id,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Card created successfully',
                'card_id' => $cardId,
                'card' => [
                    'card_id' => $cardId,
                    'card_title' => $request->card_title,
                    'description' => $request->description,
                    'status' => 'todo',
                    'priority' => $request->priority ?? 'medium',
                    'due_date' => $request->due_date,
                    'position' => $request->position ?? 0,
                    'board_name' => $board->board_name
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating card: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update card
     */
    public function updateCard(Request $request, $cardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'card_title' => 'sometimes|required|string|max:100',
                'description' => 'nullable|string',
                'status' => 'sometimes|in:todo,in_progress,review,done',
                'priority' => 'sometimes|in:low,medium,high',
                'due_date' => 'nullable|date',
                'position' => 'sometimes|integer'
            ]);

            // Verify the card belongs to Team Lead's project
            $card = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('cards.card_id', $cardId)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('cards.*', 'boards.board_name', 'projects.project_name')
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found or access denied'
                ], 403);
            }

            // Prepare update data (no updated_at column in cards table)
            $updateData = [];

            if ($request->has('card_title')) {
                $updateData['card_title'] = $request->card_title;
            }
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }
            if ($request->has('status')) {
                $updateData['status'] = $request->status;
            }
            if ($request->has('priority')) {
                $updateData['priority'] = $request->priority;
            }
            if ($request->has('due_date')) {
                $updateData['due_date'] = $request->due_date;
            }
            if ($request->has('position')) {
                $updateData['position'] = $request->position;
            }

            // Only update if there's data to update
            if (empty($updateData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data provided for update'
                ], 400);
            }

            // Update the card
            DB::table('cards')
                ->where('card_id', $cardId)
                ->update($updateData);

            // Get updated card data
            $updatedCard = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->where('cards.card_id', $cardId)
                ->select('cards.*', 'boards.board_name')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Card updated successfully',
                'card' => $updatedCard
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating card: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Delete card
     */
    public function deleteCard($cardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Verify the card belongs to Team Lead's project
            $card = DB::table('cards')
                ->join('boards', 'cards.board_id', '=', 'boards.board_id')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('cards.card_id', $cardId)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('cards.*')
                ->first();

            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found or access denied'
                ], 403);
            }

            // Delete related records first (if any)
            DB::table('card_assignments')->where('card_id', $cardId)->delete();
            DB::table('subtasks')->where('card_id', $cardId)->delete();
            DB::table('card_comments')->where('card_id', $cardId)->delete();

            // Delete the card
            DB::table('cards')->where('card_id', $cardId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Card deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting card: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting card: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Delete board
     */
    public function deleteBoard($boardId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Verify the board belongs to Team Lead's project
            $board = DB::table('boards')
                ->join('projects', 'boards.project_id', '=', 'projects.project_id')
                ->join('members', 'projects.project_id', '=', 'members.project_id')
                ->where('boards.board_id', $boardId)
                ->where('members.user_id', $user->user_id)
                ->where('members.role', 'Team_Lead')
                ->select('boards.*')
                ->first();

            if (!$board) {
                return response()->json([
                    'success' => false,
                    'message' => 'Board not found or access denied'
                ]);
            }

            // Check if board has cards
            $cardCount = DB::table('cards')->where('board_id', $boardId)->count();

            if ($cardCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete board. It contains {$cardCount} cards. Please move or delete all cards first."
                ]);
            }

            // Delete the board
            DB::table('boards')->where('board_id', $boardId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Board deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting board: ' . $e->getMessage()
            ]);
        }
    }
}
