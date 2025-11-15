<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the user's todos.
     */
    public function index()
    {
        $todos = Todo::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $todos
        ]);
    }

    /**
     * Store a newly created todo.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo = Todo::create([
            'user_id' => Auth::id(),
            'text' => $request->text,
            'completed' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully',
            'data' => $todo
        ], 201);
    }

    /**
     * Update the specified todo.
     */
    public function update(Request $request, Todo $todo)
    {
        // Check if user owns this todo
        if ($todo->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'sometimes|required|string|max:255',
            'completed' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo->update($request->only(['text', 'completed']));

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully',
            'data' => $todo
        ]);
    }

    /**
     * Remove the specified todo.
     */
    public function destroy(Todo $todo)
    {
        // Check if user owns this todo
        if ($todo->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully'
        ]);
    }

    /**
     * Toggle todo completion status.
     */
    public function toggle(Todo $todo)
    {
        // Check if user owns this todo
        if ($todo->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $todo->update([
            'completed' => !$todo->completed
        ]);

        return response()->json([
            'success' => true,
            'message' => $todo->completed ? 'Todo marked as completed' : 'Todo marked as active',
            'data' => $todo
        ]);
    }
}
