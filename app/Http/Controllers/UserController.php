<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();

            // Filter by role if specified
            if ($request->has('role') && $request->role != '') {
                $query->where('role', $request->role);
            }

            // Filter by status if specified
            if ($request->has('status') && $request->status != '') {
                $query->where('current_task_status', $request->status);
            }

            // Search by name or email
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            }

            // Order by creation date (newest first)
            $query->orderBy('created_at', 'desc');

            $users = $query->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:Project_Admin,Team_Lead,Developer,Designer,member',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'current_task_status' => 'idle',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($id, 'user_id')],
            'full_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($id, 'user_id')],
            'role' => 'required|in:Project_Admin,Team_Lead,Developer,Designer,member',
            'current_task_status' => 'required|in:idle,working',
            'password' => 'nullable|string|min:6',
        ]);

        try {
            $user = User::findOrFail($id);

            $updateData = [
                'username' => $request->username,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'role' => $request->role,
                'current_task_status' => $request->current_task_status,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Don't allow deleting the currently authenticated user
            if (Auth::user()->user_id == $user->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $totalUsers = User::count();
            $activeUsers = User::where('current_task_status', 'working')->count();
            $recentUsers = User::whereDate('created_at', '>=', now()->subDays(7))->count();

            // Count by roles
            $roleStats = User::select('role', DB::raw('count(*) as total'))
                            ->groupBy('role')
                            ->pluck('total', 'role')
                            ->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'recent_users' => $recentUsers,
                    'role_stats' => $roleStats
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the request
            $validated = $request->validate([
                'full_name' => 'nullable|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->user_id, 'user_id')
                ],
                'bio' => 'nullable|string|max:1000',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'birth_date' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'website' => 'nullable|url|max:255',
                'skills' => 'nullable|array',
                'skills.*' => 'string|max:100',
                'status' => 'nullable|in:active,inactive,busy,available'
            ]);

            // Prepare update data
            $updateData = [];
            $allowedFields = ['full_name', 'email', 'bio', 'phone', 'address', 'birth_date', 'gender', 'website', 'status'];

            foreach ($allowedFields as $field) {
                if (isset($validated[$field])) {
                    $updateData[$field] = $validated[$field];
                }
            }

            // Handle skills array
            if (isset($validated['skills'])) {
                $updateData['skills'] = json_encode($validated['skills']);
            }

            // Update the user
            DB::table('users')
                ->where('user_id', $user->user_id)
                ->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'full_name' => $validated['full_name'] ?? $user->full_name,
                    'email' => $validated['email'],
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate the uploaded file
            $validated = $request->validate([
                'profile_photo' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048'
            ]);

            // Create upload directory if it doesn't exist
            $uploadPath = public_path('uploads/profiles');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Delete old profile photo if exists
            if ($user->profile_photo && file_exists($uploadPath . '/' . $user->profile_photo)) {
                unlink($uploadPath . '/' . $user->profile_photo);
            }

            // Generate unique filename
            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move uploaded file
            $file->move($uploadPath, $filename);

            // Update user profile photo
            DB::table('users')
                ->where('user_id', $user->user_id)
                ->update(['profile_photo' => $filename]);

            return response()->json([
                'success' => true,
                'message' => 'Profile photo uploaded successfully',
                'data' => [
                    'profile_photo_url' => asset('uploads/profiles/' . $filename),
                    'filename' => $filename
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete profile photo
     */
    public function deleteProfilePhoto()
    {
        try {
            $user = Auth::user();

            if ($user->profile_photo) {
                $photoPath = public_path('uploads/profiles/' . $user->profile_photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }

                // Remove profile photo from database
                DB::table('users')
                    ->where('user_id', $user->user_id)
                    ->update(['profile_photo' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile photo deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting photo: ' . $e->getMessage()
            ], 500);
        }
    }
}
