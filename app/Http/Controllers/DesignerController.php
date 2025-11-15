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

    /**
     * Designer Panel (New Design-focused Interface)
     */
    public function panel()
    {
        if (!CheckPermission::hasPermission(Auth::user(), 'view_assigned_tasks')) {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki permission untuk melihat panel designer.');
        }

        return view('designer.panel');
    }

    /**
     * Get designer statistics for panel API
     */
    public function getDesignerStatistics()
    {
        $user = Auth::user();

        // Mock data for now - replace with actual queries later
        $statistics = [
            'active_projects' => 8,
            'design_assets' => 45,
            'completed_designs' => 127,
            'pending_feedback' => 3,
            'client_rating' => 4.8,
            'total_designs' => 172,
            'portfolio_views' => 2847
        ];

        return response()->json($statistics);
    }

    /**
     * Get design assets for designer panel API
     */
    public function getPanelDesignAssets(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        // Mock data for design assets
        $assets = [
            [
                'id' => 1,
                'title' => 'Homepage Hero Section',
                'type' => 'web',
                'status' => 'approved',
                'updated_at' => '2024-11-01',
                'file_types' => ['PSD', 'AI'],
                'description' => 'Modern hero section design with animated elements and call-to-action buttons.'
            ],
            [
                'id' => 2,
                'title' => 'Mobile App Icons Set',
                'type' => 'mobile',
                'status' => 'in_progress',
                'updated_at' => '2024-10-30',
                'file_types' => ['SVG', 'PNG'],
                'description' => 'Complete icon set for fitness mobile application with multiple sizes and variants.'
            ],
            [
                'id' => 3,
                'title' => 'Brand Identity Package',
                'type' => 'brand',
                'status' => 'review',
                'updated_at' => '2024-10-28',
                'file_types' => ['AI', 'PDF'],
                'description' => 'Complete brand identity including logo, business cards, and letterhead designs.'
            ]
        ];

        // Filter assets based on type if specified
        if ($filter !== 'all') {
            $assets = array_filter($assets, function($asset) use ($filter) {
                return $asset['type'] === $filter;
            });
        }

        return response()->json(array_values($assets));
    }

    /**
     * Get design projects for designer panel
     */
    public function getDesignProjects()
    {
        $user = Auth::user();

        // Mock data for design projects
        $projects = [
            [
                'id' => 1,
                'title' => 'E-Commerce Website Redesign',
                'client' => 'TechCorp Ltd.',
                'type' => 'web',
                'status' => 'in_design',
                'deadline' => '2024-11-15',
                'progress' => 70,
                'description' => 'Complete redesign of product catalog and checkout process with modern UI/UX principles.'
            ],
            [
                'id' => 2,
                'title' => 'Mobile App UI Kit',
                'client' => 'StartupXYZ',
                'type' => 'mobile',
                'status' => 'review',
                'deadline' => '2024-11-10',
                'progress' => 90,
                'description' => 'Complete UI component library for fitness tracking mobile application.'
            ],
            [
                'id' => 3,
                'title' => 'Corporate Branding Package',
                'client' => 'Business Solutions Inc.',
                'type' => 'brand',
                'status' => 'in_design',
                'deadline' => '2024-12-01',
                'progress' => 55,
                'description' => 'Complete brand identity including logo, color palette, typography, and brand guidelines.'
            ]
        ];

        return response()->json($projects);
    }

    /**
     * Get gallery items for designer panel
     */
    public function getGalleryItems()
    {
        $user = Auth::user();

        // Mock data for gallery items
        $galleryItems = [
            [
                'id' => 1,
                'title' => 'E-commerce Homepage',
                'type' => 'web',
                'description' => 'Modern shopping website design',
                'image_url' => '/images/gallery/ecommerce-homepage.jpg',
                'likes' => 24,
                'views' => 156
            ],
            [
                'id' => 2,
                'title' => 'Fitness App UI',
                'type' => 'mobile',
                'description' => 'Mobile app interface design',
                'image_url' => '/images/gallery/fitness-app.jpg',
                'likes' => 18,
                'views' => 98
            ],
            [
                'id' => 3,
                'title' => 'Brand Identity',
                'type' => 'brand',
                'description' => 'Complete branding package',
                'image_url' => '/images/gallery/brand-identity.jpg',
                'likes' => 31,
                'views' => 203
            ],
            [
                'id' => 4,
                'title' => 'Dashboard UI',
                'type' => 'ui',
                'description' => 'Analytics dashboard interface',
                'image_url' => '/images/gallery/dashboard-ui.jpg',
                'likes' => 27,
                'views' => 174
            ]
        ];

        return response()->json($galleryItems);
    }

    /**
     * Get client feedback for designer panel
     */
    public function getClientFeedback()
    {
        $user = Auth::user();

        // Mock data for client feedback
        $feedback = [
            [
                'id' => 1,
                'client_name' => 'Sarah Johnson',
                'project_title' => 'E-Commerce Website Redesign',
                'message' => 'Love the new color scheme! Could we make the CTA button slightly larger? Overall great work on the homepage design.',
                'rating' => 5,
                'created_at' => '2024-11-02 10:30:00',
                'status' => 'pending'
            ],
            [
                'id' => 2,
                'client_name' => 'Mike Chen',
                'project_title' => 'Mobile App UI Kit',
                'message' => 'The mobile responsive design looks fantastic. The user flow is much clearer now. Ready to approve this version.',
                'rating' => 5,
                'created_at' => '2024-11-01 14:15:00',
                'status' => 'approved'
            ],
            [
                'id' => 3,
                'client_name' => 'Emily Davis',
                'project_title' => 'Corporate Branding',
                'message' => 'Can we explore a darker theme option? The current design is great but our brand might benefit from a premium dark mode.',
                'rating' => 4,
                'created_at' => '2024-10-30 09:45:00',
                'status' => 'pending'
            ]
        ];

        return response()->json($feedback);
    }

    /**
     * Get recent activities for designer panel
     */
    public function getDesignerActivities()
    {
        $user = Auth::user();

        // Mock data for recent activities
        $activities = [
            [
                'id' => 1,
                'type' => 'design_uploaded',
                'title' => 'Design uploaded',
                'description' => 'Landing page mockup',
                'created_at' => '2024-11-02 11:15:00'
            ],
            [
                'id' => 2,
                'type' => 'feedback_received',
                'title' => 'Feedback received',
                'description' => 'Client review on homepage',
                'created_at' => '2024-11-02 08:30:00'
            ],
            [
                'id' => 3,
                'type' => 'design_revised',
                'title' => 'Design revised',
                'description' => 'Updated color scheme',
                'created_at' => '2024-11-01 16:20:00'
            ],
            [
                'id' => 4,
                'type' => 'project_started',
                'title' => 'Project started',
                'description' => 'Mobile app redesign project',
                'created_at' => '2024-11-01 09:00:00'
            ]
        ];

        return response()->json($activities);
    }
}
