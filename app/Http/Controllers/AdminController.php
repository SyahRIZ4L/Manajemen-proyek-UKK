<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Admin Dashboard - Minimal & Clean
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Simple admin check
        $adminEmails = [
            'admin@test.com',
            'admin@example.com',
            'syahrizal@admin.com'
        ];

        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        // Minimal dashboard data
        $stats = [
            'projects' => 8,
            'members' => 12,
            'tasks' => 35,
            'completed' => 28
        ];

        return view('admin.dashboard', compact('stats', 'user'));
    }

    /**
     * Projects Management
     */
    public function projects()
    {
        $user = Auth::user();

        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        // Sample projects data
        $projects = [
            ['id' => 1, 'name' => 'Website Project', 'status' => 'Active', 'progress' => 75],
            ['id' => 2, 'name' => 'Mobile App', 'status' => 'Planning', 'progress' => 25],
            ['id' => 3, 'name' => 'API Development', 'status' => 'Completed', 'progress' => 100],
        ];

        return view('admin.projects', compact('projects', 'user'));
    }

    /**
     * Admin Panel - Full Control Interface
     */
    public function panel()
    {
        $user = Auth::user();

        // Simple admin check
        $adminEmails = [
            'admin@test.com',
            'admin@example.com',
            'syahrizal@admin.com'
        ];

        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied - Admin only');
        }

        return view('admin.panel', compact('user'));
    }

    /**
     * Users Management
     */
    public function users()
    {
        $user = Auth::user();

        $adminEmails = ['admin@test.com', 'admin@example.com', 'syahrizal@admin.com'];
        $isAdmin = in_array($user->email, $adminEmails) || $user->role === 'Project_Admin';

        if (!$isAdmin) {
            return redirect()->route('home')->with('error', 'Access denied');
        }

        // Sample users data
        $users = [
            ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Developer'],
            ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'Team_Lead'],
            ['id' => 3, 'name' => 'Mike Johnson', 'email' => 'mike@example.com', 'role' => 'Member'],
        ];

        return view('admin.users', compact('users', 'user'));
    }
}
