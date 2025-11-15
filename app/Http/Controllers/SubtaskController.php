<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\SubtaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the user's subtasks.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Subtask::where('user_id', $user->user_id);

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $subtasks = $query->with(['comments.user'])
                         ->orderBy('created_at', 'desc')
                         ->get();

        return response()->json([
            'success' => true,
            'data' => $subtasks
        ]);
    }

    /**
     * Store a newly created subtask.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $subtask = Subtask::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subtask created successfully',
            'data' => $subtask
        ], 201);
    }

    /**
     * Display the specified subtask.
     */
    public function show(Subtask $subtask)
    {
        // Check if user owns this subtask
        if ($subtask->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $subtask->load(['comments.user']);

        return response()->json([
            'success' => true,
            'data' => $subtask
        ]);
    }

    /**
     * Update the specified subtask.
     */
    public function update(Request $request, Subtask $subtask)
    {
        // Check if user owns this subtask
        if ($subtask->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'sometimes|required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|in:active,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $subtask->update($request->only([
            'title', 'description', 'priority', 'due_date', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Subtask updated successfully',
            'data' => $subtask
        ]);
    }

    /**
     * Remove the specified subtask.
     */
    public function destroy(Subtask $subtask)
    {
        // Check if user owns this subtask
        if ($subtask->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $subtask->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subtask deleted successfully'
        ]);
    }

    /**
     * Mark subtask as completed.
     */
    public function complete(Subtask $subtask)
    {
        // Check if user owns this subtask
        if ($subtask->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $subtask->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subtask marked as completed',
            'data' => $subtask
        ]);
    }

    /**
     * Add a comment to a subtask.
     */
    public function addComment(Request $request, Subtask $subtask)
    {
        // Check if user owns this subtask
        if ($subtask->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = SubtaskComment::create([
            'subtask_id' => $subtask->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 201);
    }

    /**
     * Delete a comment.
     */
    public function deleteComment(SubtaskComment $comment)
    {
        // Check if user owns this comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);
    }
}
