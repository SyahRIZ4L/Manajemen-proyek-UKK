<?php

namespace App\Http\Controllers;

use App\Models\CardTodo;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class TodoController extends Controller
{
    /**
     * Get all todos for a specific card
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'card_id' => 'required|exists:cards,card_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid card ID',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cardId = $request->card_id;
            $user = Auth::user();

            // Check if user has access to this card
            $card = Card::with(['assignments', 'board.project'])->find($cardId);
            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found'
                ], 404);
            }

            // Check access rights
            $hasAccess = $this->checkCardAccess($card, $user);
            if (!$hasAccess) {
                $debugInfo = [
                    'user' => [
                        'id' => $user->user_id,
                        'role' => $user->role,
                        'name' => $user->full_name
                    ],
                    'card_id' => $cardId,
                    'has_board' => $card->board ? true : false,
                    'board_id' => $card->board_id,
                ];

                if ($card->board) {
                    $debugInfo['board'] = [
                        'id' => $card->board->board_id,
                        'name' => $card->board->board_name,
                        'project_id' => $card->board->project_id,
                        'has_project' => $card->board->project ? true : false
                    ];

                    if ($card->board->project) {
                        $debugInfo['project'] = [
                            'id' => $card->board->project->project_id,
                            'name' => $card->board->project->project_name
                        ];

                        // Check project membership for Team Lead
                        if ($user->role === 'Team Lead' || $user->role === 'Team_Lead') {
                            $membership = \App\Models\ProjectMember::where('project_id', $card->board->project->project_id)
                                ->where('user_id', $user->user_id)
                                ->first();

                            $debugInfo['team_lead_membership'] = $membership ? [
                                'exists' => true,
                                'role' => $membership->role
                            ] : ['exists' => false];
                        }
                    }
                }

                $debugInfo['is_assigned'] = $card->assignments()->where('user_id', $user->user_id)->exists();

                return response()->json([
                    'success' => false,
                    'message' => 'Access denied',
                    'debug' => $debugInfo
                ], 403);
            }

            $todos = CardTodo::with('user:user_id,full_name,username')
                ->where('card_id', $cardId)
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'todos' => $todos
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch todos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new todo for a card
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'card_id' => 'required|exists:cards,card_id',
                'text' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $cardId = $request->card_id;

            // Check if user has access to this card
            $card = Card::with(['assignments', 'board.project'])->find($cardId);
            if (!$card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Card not found'
                ], 404);
            }

            $hasAccess = $this->checkCardAccess($card, $user);
            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $todo = CardTodo::create([
                'card_id' => $cardId,
                'user_id' => $user->user_id,
                'text' => $request->text,
                'completed' => false
            ]);

            $todo->load('user:user_id,full_name,username');

            return response()->json([
                'success' => true,
                'message' => 'Todo created successfully',
                'todo' => $todo
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a todo
     */
    public function update(Request $request, $todoId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'text' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $todo = CardTodo::with(['card.assignments', 'card.board.project'])->find($todoId);

            if (!$todo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todo not found'
                ], 404);
            }

            // Check if user has access
            $hasAccess = $this->checkCardAccess($todo->card, $user);
            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $todo->text = $request->text;
            $todo->save();

            $todo->load('user:user_id,full_name,username');

            return response()->json([
                'success' => true,
                'message' => 'Todo updated successfully',
                'todo' => $todo
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle todo completion status
     */
    public function toggle(Request $request, $todoId)
    {
        try {
            $user = Auth::user();
            $todo = CardTodo::with(['card.assignments', 'card.board.project'])->find($todoId);

            if (!$todo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todo not found'
                ], 404);
            }

            // Check if user has access
            $hasAccess = $this->checkCardAccess($todo->card, $user);
            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $todo->completed = !$todo->completed;
            $todo->save();

            $todo->load('user:user_id,full_name,username');

            return response()->json([
                'success' => true,
                'message' => 'Todo status updated successfully',
                'todo' => $todo
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a todo
     */
    public function destroy($todoId)
    {
        try {
            $user = Auth::user();
            $todo = CardTodo::with(['card.assignments', 'card.board.project'])->find($todoId);

            if (!$todo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todo not found'
                ], 404);
            }

            // Check if user has access
            $hasAccess = $this->checkCardAccess($todo->card, $user);
            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $todo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Todo deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has access to a card
     */
    private function checkCardAccess($card, $user)
    {
        // Admin has access to all cards
        if ($user->role === 'Admin') {
            return true;
        }

        // Team Lead can access cards in their project
        if ($user->role === 'Team Lead' || $user->role === 'Team_Lead') {
            // Load board with project if not already loaded
            if (!$card->relationLoaded('board')) {
                $card->load('board.project');
            }

            if ($card->board) {
                // Load project if not already loaded
                if (!$card->board->relationLoaded('project')) {
                    $card->board->load('project');
                }

                if ($card->board->project) {
                    $projectId = $card->board->project->project_id;

                    $isTeamLead = \App\Models\ProjectMember::where('project_id', $projectId)
                        ->where('user_id', $user->user_id)
                        ->whereIn('role', ['Team Lead', 'Team_Lead'])
                        ->exists();

                    if ($isTeamLead) {
                        return true;
                    }
                }
            }
        }

        // Members can access cards they are assigned to
        $isAssigned = $card->assignments()->where('user_id', $user->user_id)->exists();
        if ($isAssigned) {
            return true;
        }

        return false;
    }
}
