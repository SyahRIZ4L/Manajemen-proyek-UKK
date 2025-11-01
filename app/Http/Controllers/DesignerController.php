<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckPermission;

class DesignerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'Designer') {
                return redirect()->route('home')->with('error', 'Akses ditolak. Anda bukan Designer.');
            }
            return $next($request);
        });
    }

    /**
     * Designer Dashboard
     */
    public function dashboard()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_assigned_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk melihat dashboard designer.');
        }

        $user = Auth::user();
        $userRole = 'Designer';

        // Get assigned design tasks
        $designTasks = $this->getDesignTasks($user);

        // Get design statistics
        $designStats = $this->getDesignStats($user);

        // Get recent design uploads
        $recentDesigns = $this->getRecentDesigns($user);

        // Get pending design reviews
        $pendingReviews = $this->getPendingReviews($user);

        return view('designer.dashboard', compact(
            'user',
            'userRole',
            'designTasks',
            'designStats',
            'recentDesigns',
            'pendingReviews'
        ));
    }

    /**
     * My Design Tasks
     */
    public function myTasks()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_assigned_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $tasks = $this->getDesignTasks($user);

        return view('designer.tasks', compact('tasks'));
    }

    /**
     * Update own task status
     */
    public function updateTaskStatus(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'update_own_task_status')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'status' => 'required|in:pending,in_progress,review,completed'
        ]);

        try {
            $user = Auth::user();

            // Verify task is assigned to user
            $task = DB::table('cards')->where('id', $validated['task_id'])->first();
            if ($task->assigned_to != $user->user_id) {
                return response()->json(['success' => false, 'message' => 'Task ini tidak ditugaskan kepada Anda']);
            }

            DB::table('cards')->where('id', $validated['task_id'])->update([
                'status' => $validated['status'],
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Status task berhasil diupdate']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Upload design files
     */
    public function uploadDesignFile(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'upload_design_files')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'design_file' => 'required|file|mimes:jpg,jpeg,png,gif,svg,psd,ai,figma|max:20480', // 20MB max
            'description' => 'nullable|string|max:255'
        ]);

        try {
            $user = Auth::user();

            // Verify task is assigned to user
            $task = DB::table('cards')->where('id', $validated['task_id'])->first();
            if ($task->assigned_to != $user->user_id) {
                return response()->json(['success' => false, 'message' => 'Task ini tidak ditugaskan kepada Anda']);
            }

            // Store file
            $file = $request->file('design_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('design_uploads', $filename, 'public');

            // Save to database (assuming you have a design_files table)
            DB::table('design_files')->insert([
                'card_id' => $validated['task_id'],
                'user_id' => $user->user_id,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'description' => $validated['description'],
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Design file berhasil diupload']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Create mockup
     */
    public function createMockup(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'create_mockups')) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'mockup_type' => 'required|in:wireframe,mockup,prototype',
            'dimensions' => 'required|string', // e.g., "1920x1080"
            'platform' => 'required|in:web,mobile,tablet,desktop'
        ]);

        try {
            $user = Auth::user();

            // Create mockup task
            $taskId = DB::table('cards')->insertGetId([
                'board_id' => 1, // Design board
                'title' => '[DESIGN] ' . $validated['title'],
                'description' => $validated['description'] . "\n\nType: " . $validated['mockup_type'] . "\nDimensions: " . $validated['dimensions'] . "\nPlatform: " . $validated['platform'],
                'priority' => 'medium',
                'status' => 'in_progress',
                'assigned_to' => $user->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Mockup task berhasil dibuat');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Design portfolio page
     */
    public function portfolio()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'manage_design_assets')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $user = Auth::user();
        $designAssets = $this->getDesignAssets($user);
        $completedProjects = $this->getCompletedDesignProjects($user);

        return view('designer.portfolio', compact('designAssets', 'completedProjects'));
    }

    /**
     * Brand guidelines page
     */
    public function brandGuidelines()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'maintain_brand_guidelines')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $brandGuidelines = $this->getBrandGuidelines();
        $styleGuides = $this->getStyleGuides();

        return view('designer.brand', compact('brandGuidelines', 'styleGuides'));
    }

    /**
     * User research page
     */
    public function userResearch()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'conduct_user_research')) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $userPersonas = $this->getUserPersonas();
        $userFlows = $this->getUserFlows();
        $researchData = $this->getResearchData();

        return view('designer.research', compact('userPersonas', 'userFlows', 'researchData'));
    }

    /**
     * Submit design for review
     */
    public function submitForReview(Request $request)
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'review_design_feedback')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak']);
        }

        $validated = $request->validate([
            'task_id' => 'required|exists:cards,id',
            'review_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $user = Auth::user();

            // Verify task is assigned to user
            $task = DB::table('cards')->where('id', $validated['task_id'])->first();
            if ($task->assigned_to != $user->user_id) {
                return response()->json(['success' => false, 'message' => 'Task ini tidak ditugaskan kepada Anda']);
            }

            // Update task status to review
            DB::table('cards')->where('id', $validated['task_id'])->update([
                'status' => 'review',
                'updated_at' => now()
            ]);

            // Add review comment if provided
            if ($validated['review_notes']) {
                DB::table('comments')->insert([
                    'card_id' => $validated['task_id'],
                    'user_id' => $user->user_id,
                    'content' => 'Submitted for review: ' . $validated['review_notes'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Design berhasil disubmit untuk review']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper Methods
     */

    /**
     * Get design tasks assigned to designer
     */
    private function getDesignTasks($user)
    {
        return DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->where('cards.assigned_to', $user->user_id)
            ->where(function($query) {
                $query->where('cards.title', 'LIKE', '[DESIGN]%')
                      ->orWhere('boards.board_name', 'LIKE', '%design%')
                      ->orWhere('boards.board_name', 'LIKE', '%ui%')
                      ->orWhere('boards.board_name', 'LIKE', '%ux%');
            })
            ->select('cards.*', 'projects.project_name', 'boards.board_name')
            ->orderBy('cards.priority', 'desc')
            ->orderBy('cards.due_date', 'asc')
            ->get();
    }

    /**
     * Get design statistics
     */
    private function getDesignStats($user)
    {
        $tasks = DB::table('cards')
            ->where('assigned_to', $user->user_id)
            ->where('title', 'LIKE', '[DESIGN]%');

        return [
            'total_designs' => $tasks->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'under_review' => $tasks->where('status', 'review')->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'uploaded_files' => DB::table('design_files')->where('user_id', $user->user_id)->count()
        ];
    }

    /**
     * Get recent design uploads
     */
    private function getRecentDesigns($user)
    {
        return DB::table('design_files')
            ->join('cards', 'design_files.card_id', '=', 'cards.id')
            ->where('design_files.user_id', $user->user_id)
            ->select('design_files.*', 'cards.title as task_title')
            ->orderBy('design_files.created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get designs pending review
     */
    private function getPendingReviews($user)
    {
        return DB::table('cards')
            ->where('assigned_to', $user->user_id)
            ->where('status', 'review')
            ->where('title', 'LIKE', '[DESIGN]%')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    /**
     * Get design assets
     */
    private function getDesignAssets($user)
    {
        return DB::table('design_files')
            ->join('cards', 'design_files.card_id', '=', 'cards.id')
            ->where('design_files.user_id', $user->user_id)
            ->where('cards.status', 'completed')
            ->select('design_files.*', 'cards.title as project_title')
            ->orderBy('design_files.created_at', 'desc')
            ->get();
    }

    /**
     * Get completed design projects
     */
    private function getCompletedDesignProjects($user)
    {
        return DB::table('cards')
            ->join('boards', 'cards.board_id', '=', 'boards.board_id')
            ->join('projects', 'boards.project_id', '=', 'projects.project_id')
            ->where('cards.assigned_to', $user->user_id)
            ->where('cards.status', 'completed')
            ->where('cards.title', 'LIKE', '[DESIGN]%')
            ->select('projects.project_name', 'cards.title', 'cards.completed_at')
            ->orderBy('cards.completed_at', 'desc')
            ->get();
    }

    /**
     * Get brand guidelines (mock data)
     */
    private function getBrandGuidelines()
    {
        return [
            [
                'title' => 'Color Palette',
                'description' => 'Primary and secondary colors',
                'content' => 'Primary: #3498db, Secondary: #2ecc71'
            ],
            [
                'title' => 'Typography',
                'description' => 'Font families and sizes',
                'content' => 'Heading: Montserrat, Body: Open Sans'
            ],
            [
                'title' => 'Logo Usage',
                'description' => 'Logo variations and usage guidelines',
                'content' => 'Minimum size: 24px, Clear space: 2x logo height'
            ]
        ];
    }

    /**
     * Get style guides (mock data)
     */
    private function getStyleGuides()
    {
        return [
            [
                'component' => 'Buttons',
                'description' => 'Button styles and states',
                'variations' => ['Primary', 'Secondary', 'Danger', 'Ghost']
            ],
            [
                'component' => 'Forms',
                'description' => 'Input field designs',
                'variations' => ['Text Input', 'Select', 'Checkbox', 'Radio']
            ]
        ];
    }

    /**
     * Get user personas (mock data)
     */
    private function getUserPersonas()
    {
        return [
            [
                'name' => 'Sarah Developer',
                'age' => '28',
                'role' => 'Frontend Developer',
                'goals' => 'Efficient workflow, clean UI',
                'frustrations' => 'Complex interfaces, slow loading'
            ],
            [
                'name' => 'Mike Manager',
                'age' => '35',
                'role' => 'Project Manager',
                'goals' => 'Team oversight, progress tracking',
                'frustrations' => 'Lack of visibility, scattered information'
            ]
        ];
    }

    /**
     * Get user flows (mock data)
     */
    private function getUserFlows()
    {
        return [
            [
                'flow' => 'Login Flow',
                'steps' => ['Landing Page', 'Login Form', 'Dashboard'],
                'pain_points' => 'Forgot password process'
            ],
            [
                'flow' => 'Task Creation',
                'steps' => ['Dashboard', 'New Task', 'Form Fill', 'Confirmation'],
                'pain_points' => 'Too many required fields'
            ]
        ];
    }

    /**
     * Get research data (mock data)
     */
    private function getResearchData()
    {
        return [
            'user_satisfaction' => 85,
            'task_completion_rate' => 92,
            'average_task_time' => '2.5 minutes',
            'most_used_features' => ['Dashboard', 'Task List', 'Profile'],
            'improvement_areas' => ['Mobile responsiveness', 'Loading speed', 'Navigation']
        ];
    }
}
