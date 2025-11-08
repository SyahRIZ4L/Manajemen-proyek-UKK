<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Developer Panel - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Light Theme (Default) */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            transition: all 0.3s ease;
        }

        /* Dark Theme */
        body.dark-theme {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        body.dark-theme .main-content {
            background-color: #1a1a1a;
        }

        body.dark-theme .content-header {
            background: #2d2d2d;
            color: #e0e0e0;
            border-left-color: #0d6efd;
        }

        body.dark-theme .content-area {
            background: #2d2d2d;
            color: #e0e0e0;
        }

        body.dark-theme .card {
            background: #333333 !important;
            color: #e0e0e0 !important;
            border-color: #444444 !important;
        }

        body.dark-theme .alert-info {
            background: rgba(13, 110, 253, 0.1);
            border-color: rgba(13, 110, 253, 0.3);
            color: #e0e0e0;
        }

        /* Dark theme for feature elements */
        body.dark-theme .feature-card {
            background: #333333;
            border-color: #444444;
            color: #e0e0e0;
        }

        body.dark-theme .feature-stats {
            background: #2d2d2d;
            color: #e0e0e0;
        }

        body.dark-theme .welcome-card {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            color: white;
            padding: 0;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .sidebar-header p {
            margin: 5px 0 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .sidebar-nav {
            padding: 30px 0;
        }

        .nav-item {
            margin: 0 15px 12px 15px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .nav-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        .nav-link:hover .nav-icon, .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .nav-icon i {
            font-size: 1.2rem;
        }

        .nav-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-title {
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
        }

        .nav-subtitle {
            font-size: 0.8rem;
            opacity: 0.7;
            margin: 2px 0 0 0;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }

        .content-header {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #0d6efd;
        }

        .content-area {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            min-height: 600px;
        }

        .back-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-2px);
        }

        /* Dashboard Statistics Styles */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.95rem;
            margin: 0;
            font-weight: 500;
        }

        .welcome-card {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.3);
        }

        .welcome-card h3 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-card p {
            opacity: 0.9;
            margin: 0;
        }

        /* Task Card Styles */
        .task-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .task-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
        }

        .task-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .task-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .priority-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .priority-high {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .priority-medium {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .priority-low {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-todo {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .status-progress {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .status-review {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-done {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        /* Code Editor Styles */
        .code-editor {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 20px;
            color: #d4d4d4;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            margin: 15px 0;
            overflow-x: auto;
        }

        .code-line {
            margin: 5px 0;
        }

        .line-number {
            color: #858585;
            margin-right: 15px;
            user-select: none;
        }

        /* Quick Actions Styles */
        .quick-action-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid #f0f0f0;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            color: inherit;
            text-decoration: none;
        }

        .quick-action-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            color: white;
            font-size: 1.5rem;
        }

        .quick-action-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .quick-action-desc {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
        }

        /* Activity Timeline */
        .activity-timeline {
            position: relative;
            padding-left: 30px;
        }

        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .activity-item {
            position: relative;
            padding: 15px 0;
            margin-bottom: 20px;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 20px;
            width: 12px;
            height: 12px;
            background: #0d6efd;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .activity-content {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .activity-desc {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #adb5bd;
        }

        /* Content Sections */
        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .stat-card, .quick-action-card, .task-card {
                margin-bottom: 15px;
            }
        }

        /* Project Card Styles */
        .project-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #0d6efd;
            transition: all 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .project-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
        }

        .project-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .project-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Skill Badge Styles */
        .skill-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin: 2px;
            display: inline-block;
        }

        .skill-primary {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .skill-secondary {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .skill-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .skill-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .skill-info {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Developer Panel</h4>
            <p>{{ Auth::user()->full_name }}</p>
        </div>

        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-item">
                <a href="#" class="nav-link active" data-section="dashboard">
                    <div class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Dashboard</div>
                        <div class="nav-subtitle">Overview & Tasks</div>
                    </div>
                </a>
            </div>

            <!-- My Tasks -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="tasks">
                    <div class="nav-icon">
                        <i class="bi bi-list-task"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">My Tasks</div>
                        <div class="nav-subtitle">Assigned work items</div>
                    </div>
                </a>
            </div>

            <!-- Projects -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="projects">
                    <div class="nav-icon">
                        <i class="bi bi-folder"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Projects</div>
                        <div class="nav-subtitle">View project details</div>
                    </div>
                </a>
            </div>

            <!-- Code Repository -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="repository">
                    <div class="nav-icon">
                        <i class="bi bi-git"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Repository</div>
                        <div class="nav-subtitle">Code management</div>
                    </div>
                </a>
            </div>

            <!-- Time Tracking -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="timetrack">
                    <div class="nav-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Time Tracking</div>
                        <div class="nav-subtitle">Log work hours</div>
                    </div>
                </a>
            </div>

            <!-- Reports -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="reports">
                    <div class="nav-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">My Reports</div>
                        <div class="nav-subtitle">Performance metrics</div>
                    </div>
                </a>
            </div>

            <!-- Profile -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="profile">
                    <div class="nav-icon">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Profile</div>
                        <div class="nav-subtitle">Skills & settings</div>
                    </div>
                </a>
            </div>
        </nav>

        <!-- Logout Button -->
        <div style="position: absolute; bottom: 20px; left: 15px; right: 15px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-100 text-center" style="border: none; background: rgba(255, 255, 255, 0.1);">
                    <div class="nav-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Logout</div>
                    </div>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 id="content-title" class="mb-0">Developer Dashboard</h2>
                    <p class="text-muted mb-0" id="content-subtitle">Manage your development tasks and projects</p>
                </div>
                <div class="header-actions">
                    <!-- Theme Toggle -->
                    <button class="btn btn-light" id="theme-toggle">
                        <i class="bi bi-moon-fill" id="theme-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Dashboard Content -->
            <div id="dashboard-content" class="content-section active">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <h3>Welcome back, {{ Auth::user()->full_name }}!</h3>
                    <p>Ready to code? Here's your development workspace overview.</p>
                </div>

                <!-- Main Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-list-check stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="assigned-tasks-count">0</h3>
                            <p class="stat-label">Assigned Tasks</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-play-circle stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="active-tasks-count">0</h3>
                            <p class="stat-label">In Progress</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-check-circle stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="completed-tasks-count">0</h3>
                            <p class="stat-label">Completed</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-clock-history stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="hours-logged">0h</h3>
                            <p class="stat-label">Hours This Week</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row g-4 mb-5">
                    <div class="col-12">
                        <h4 class="mb-4">Quick Actions</h4>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="tasks">
                            <div class="quick-action-icon">
                                <i class="bi bi-play-circle"></i>
                            </div>
                            <h6 class="quick-action-title">Start Task</h6>
                            <p class="quick-action-desc">Begin working on task</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="timetrack">
                            <div class="quick-action-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <h6 class="quick-action-title">Log Time</h6>
                            <p class="quick-action-desc">Record work hours</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="repository">
                            <div class="quick-action-icon">
                                <i class="bi bi-git"></i>
                            </div>
                            <h6 class="quick-action-title">Commit Code</h6>
                            <p class="quick-action-desc">Push your changes</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="reports">
                            <div class="quick-action-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h6 class="quick-action-title">View Progress</h6>
                            <p class="quick-action-desc">Check your metrics</p>
                        </a>
                    </div>
                </div>

                <!-- Current Tasks and Recent Activity -->
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Current Tasks</h5>
                            </div>
                            <div class="card-body" id="current-tasks">
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-list-task display-6"></i>
                                    <p class="mt-2">Loading tasks...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Activity</h5>
                            </div>
                            <div class="card-body" id="recent-activity">
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-clock-history display-6"></i>
                                    <p class="mt-2">Loading activities...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks Content -->
            <div id="tasks-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>My Tasks</h4>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="taskFilter" id="all-tasks" checked>
                        <label class="btn btn-outline-primary" for="all-tasks">All</label>

                        <input type="radio" class="btn-check" name="taskFilter" id="todo-tasks">
                        <label class="btn btn-outline-primary" for="todo-tasks">To Do</label>

                        <input type="radio" class="btn-check" name="taskFilter" id="progress-tasks">
                        <label class="btn btn-outline-primary" for="progress-tasks">In Progress</label>

                        <input type="radio" class="btn-check" name="taskFilter" id="done-tasks">
                        <label class="btn btn-outline-primary" for="done-tasks">Done</label>
                    </div>
                </div>
                <div id="tasks-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-list-task display-4"></i>
                        <p class="mt-3">Loading tasks...</p>
                    </div>
                </div>
            </div>

            <!-- Projects Content -->
            <div id="projects-content" class="content-section">
                <h4 class="mb-4">My Projects</h4>
                <div id="projects-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-folder display-4"></i>
                        <p class="mt-3">Loading projects...</p>
                    </div>
                </div>
            </div>

            <!-- Repository Content -->
            <div id="repository-content" class="content-section">
                <h4 class="mb-4">Code Repository</h4>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Commits</h5>
                            </div>
                            <div class="card-body">
                                <div class="activity-timeline">
                                    <div class="activity-item">
                                        <div class="activity-content">
                                            <div class="activity-title">Fix authentication bug</div>
                                            <div class="activity-desc">Resolved login redirect issue</div>
                                            <div class="activity-time">2 hours ago</div>
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <div class="activity-content">
                                            <div class="activity-title">Add user validation</div>
                                            <div class="activity-desc">Implement form validation rules</div>
                                            <div class="activity-time">1 day ago</div>
                                        </div>
                                    </div>
                                    <div class="activity-item">
                                        <div class="activity-content">
                                            <div class="activity-title">Update API endpoints</div>
                                            <div class="activity-desc">Refactor project management API</div>
                                            <div class="activity-time">2 days ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Code Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <h4 class="text-primary mb-1">247</h4>
                                        <small class="text-muted">Commits</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h4 class="text-success mb-1">8.5k</h4>
                                        <small class="text-muted">Lines Added</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-warning mb-1">23</h4>
                                        <small class="text-muted">Pull Requests</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-info mb-1">15</h4>
                                        <small class="text-muted">Repositories</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Code Sample -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Code</h5>
                    </div>
                    <div class="card-body">
                        <div class="code-editor">
                            <div class="code-line">
                                <span class="line-number">1</span>
                                <span style="color: #569cd6;">public function</span> <span style="color: #dcdcaa;">authenticate</span>(<span style="color: #4ec9b0;">Request</span> <span style="color: #9cdcfe;">$request</span>)
                            </div>
                            <div class="code-line">
                                <span class="line-number">2</span>
                                {
                            </div>
                            <div class="code-line">
                                <span class="line-number">3</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #9cdcfe;">$credentials</span> = <span style="color: #9cdcfe;">$request</span>-><span style="color: #dcdcaa;">validate</span>([
                            </div>
                            <div class="code-line">
                                <span class="line-number">4</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #ce9178;">'email'</span> => <span style="color: #ce9178;">'required|email'</span>,
                            </div>
                            <div class="code-line">
                                <span class="line-number">5</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #ce9178;">'password'</span> => <span style="color: #ce9178;">'required'</span>
                            </div>
                            <div class="code-line">
                                <span class="line-number">6</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;]);
                            </div>
                            <div class="code-line">
                                <span class="line-number">7</span>
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Tracking Content -->
            <div id="timetrack-content" class="content-section">
                <h4 class="mb-4">Time Tracking</h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Log Time</h5>
                            </div>
                            <div class="card-body">
                                <form id="time-log-form">
                                    <div class="mb-3">
                                        <label class="form-label">Task</label>
                                        <select class="form-select">
                                            <option>Select a task...</option>
                                            <option>Frontend Development</option>
                                            <option>API Integration</option>
                                            <option>Bug Fixes</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Hours</label>
                                        <input type="number" class="form-control" step="0.5" min="0" max="12">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" rows="3" placeholder="What did you work on?"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Log Time</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">This Week's Time Log</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Task</th>
                                                <th>Hours</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Nov 2, 2024</td>
                                                <td>Frontend Development</td>
                                                <td>4.5h</td>
                                                <td>Implemented user dashboard UI</td>
                                            </tr>
                                            <tr>
                                                <td>Nov 1, 2024</td>
                                                <td>API Integration</td>
                                                <td>3.0h</td>
                                                <td>Connected payment gateway</td>
                                            </tr>
                                            <tr>
                                                <td>Oct 31, 2024</td>
                                                <td>Bug Fixes</td>
                                                <td>2.5h</td>
                                                <td>Fixed authentication issues</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Content -->
            <div id="reports-content" class="content-section">
                <h4 class="mb-4">My Performance Reports</h4>
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-check-circle stat-icon text-success"></i>
                            <h3 class="stat-number text-success">96%</h3>
                            <p class="stat-label">Task Completion</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-clock stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary">42h</h3>
                            <p class="stat-label">This Month</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-speedometer2 stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning">4.8</h3>
                            <p class="stat-label">Performance Score</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-trophy stat-icon text-info"></i>
                            <h3 class="stat-number text-info">12</h3>
                            <p class="stat-label">Completed Projects</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div id="profile-content" class="content-section">
                <h4 class="mb-4">Developer Profile</h4>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <form id="profile-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" class="form-control" value="{{ Auth::user()->full_name }}" name="full_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" value="{{ Auth::user()->username }}" name="username" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" name="email">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="{{ Auth::user()->role }}" readonly>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>

                        <!-- Skills Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Technical Skills</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6>Programming Languages</h6>
                                    <span class="skill-badge skill-primary">PHP</span>
                                    <span class="skill-badge skill-primary">JavaScript</span>
                                    <span class="skill-badge skill-secondary">Python</span>
                                    <span class="skill-badge skill-success">Java</span>
                                </div>
                                <div class="mb-3">
                                    <h6>Frameworks</h6>
                                    <span class="skill-badge skill-warning">Laravel</span>
                                    <span class="skill-badge skill-info">React</span>
                                    <span class="skill-badge skill-primary">Vue.js</span>
                                    <span class="skill-badge skill-secondary">Node.js</span>
                                </div>
                                <div class="mb-3">
                                    <h6>Tools & Technologies</h6>
                                    <span class="skill-badge skill-success">Git</span>
                                    <span class="skill-badge skill-primary">Docker</span>
                                    <span class="skill-badge skill-warning">MySQL</span>
                                    <span class="skill-badge skill-info">Redis</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Tasks Completed:</span>
                                    <strong id="profile-tasks-completed">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Code Commits:</span>
                                    <strong id="profile-commits">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Hours Logged:</span>
                                    <strong id="profile-hours">0h</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Member Since:</span>
                                    <strong>{{ Auth::user()->created_at->format('M Y') }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Current Status -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Current Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-success">Available</span>
                                    </div>
                                    <div>
                                        <small class="text-muted">Ready for new tasks</small>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary btn-sm">Update Status</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation functionality
            const navLinks = document.querySelectorAll('.nav-link[data-section]');
            const contentSections = document.querySelectorAll('.content-section');
            const contentTitle = document.getElementById('content-title');
            const contentSubtitle = document.getElementById('content-subtitle');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetSection = this.getAttribute('data-section');

                    // Update active nav
                    navLinks.forEach(navLink => navLink.classList.remove('active'));
                    this.classList.add('active');

                    // Update content
                    contentSections.forEach(section => section.classList.remove('active'));
                    document.getElementById(targetSection + '-content').classList.add('active');

                    // Update header
                    const title = this.querySelector('.nav-title').textContent;
                    const subtitle = this.querySelector('.nav-subtitle').textContent;
                    contentTitle.textContent = title;
                    contentSubtitle.textContent = subtitle;

                    // Load content based on section
                    loadSectionContent(targetSection);
                });
            });

            // Quick action clicks
            document.querySelectorAll('.quick-action-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = this.getAttribute('data-section');
                    if (section) {
                        const navLink = document.querySelector(`.nav-link[data-section="${section}"]`);
                        if (navLink) {
                            navLink.click();
                        }
                    }
                });
            });

            // Theme toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-theme');

                if (document.body.classList.contains('dark-theme')) {
                    themeIcon.className = 'bi bi-sun-fill';
                    localStorage.setItem('theme', 'dark');
                } else {
                    themeIcon.className = 'bi bi-moon-fill';
                    localStorage.setItem('theme', 'light');
                }
            });

            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                themeIcon.className = 'bi bi-sun-fill';
            } else {
                themeIcon.className = 'bi bi-moon-fill';
            }

            // Load initial content
            loadSectionContent('dashboard');
        });

        // Load content based on section
        function loadSectionContent(section) {
            switch(section) {
                case 'dashboard':
                    loadDashboardData();
                    break;
                case 'tasks':
                    loadTasks();
                    break;
                case 'projects':
                    loadProjects();
                    break;
                case 'repository':
                    loadRepositoryData();
                    break;
                case 'timetrack':
                    loadTimeTrackingData();
                    break;
                case 'reports':
                    loadReports();
                    break;
                case 'profile':
                    loadProfileData();
                    break;
            }
        }

        // Dashboard data loading
        function loadDashboardData() {
            loadDeveloperStatistics();
            loadCurrentTasks();
            loadRecentActivity();
        }

        function loadDeveloperStatistics() {
            // Mock data for developer statistics
            document.getElementById('assigned-tasks-count').textContent = '15';
            document.getElementById('active-tasks-count').textContent = '4';
            document.getElementById('completed-tasks-count').textContent = '67';
            document.getElementById('hours-logged').textContent = '32h';
        }

        function loadCurrentTasks() {
            const container = document.getElementById('current-tasks');
            container.innerHTML = `
                <div class="task-card">
                    <div class="task-header">
                        <div>
                            <h6 class="task-title">Implement User Authentication</h6>
                            <div class="task-meta">
                                <span><i class="bi bi-calendar"></i> Due: Nov 5, 2024</span>
                                <span><i class="bi bi-folder"></i> E-Commerce Platform</span>
                            </div>
                        </div>
                        <div>
                            <span class="priority-badge priority-high">High</span>
                            <span class="status-badge status-progress">In Progress</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Develop secure login and registration system with JWT authentication.</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-primary" style="width: 60%"></div>
                    </div>
                    <small class="text-muted">60% Complete</small>
                </div>

                <div class="task-card">
                    <div class="task-header">
                        <div>
                            <h6 class="task-title">Fix API Response Format</h6>
                            <div class="task-meta">
                                <span><i class="bi bi-calendar"></i> Due: Nov 3, 2024</span>
                                <span><i class="bi bi-folder"></i> Mobile App Backend</span>
                            </div>
                        </div>
                        <div>
                            <span class="priority-badge priority-medium">Medium</span>
                            <span class="status-badge status-review">Review</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Standardize API response format across all endpoints.</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-warning" style="width: 90%"></div>
                    </div>
                    <small class="text-muted">90% Complete - Pending Review</small>
                </div>
            `;
        }

        function loadRecentActivity() {
            const container = document.getElementById('recent-activity');
            container.innerHTML = `
                <div class="activity-timeline">
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-title">Committed code</div>
                            <div class="activity-desc">Auth module updates</div>
                            <div class="activity-time">30 min ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-title">Task updated</div>
                            <div class="activity-desc">Status changed to Review</div>
                            <div class="activity-time">2 hours ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-title">Time logged</div>
                            <div class="activity-desc">4.5 hours development</div>
                            <div class="activity-time">1 day ago</div>
                        </div>
                    </div>
                </div>
            `;
        }

        function loadTasks() {
            const container = document.getElementById('tasks-list');
            container.innerHTML = `
                <div class="task-card">
                    <div class="task-header">
                        <div>
                            <h6 class="task-title">Database Schema Design</h6>
                            <div class="task-meta">
                                <span><i class="bi bi-calendar"></i> Due: Nov 8, 2024</span>
                                <span><i class="bi bi-person"></i> Assigned by: Team Lead</span>
                            </div>
                        </div>
                        <div>
                            <span class="priority-badge priority-high">High</span>
                            <span class="status-badge status-todo">To Do</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Design and implement database schema for the new inventory module.</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm">Start Task</button>
                        <button class="btn btn-outline-secondary btn-sm">View Details</button>
                    </div>
                </div>

                <div class="task-card">
                    <div class="task-header">
                        <div>
                            <h6 class="task-title">Frontend Component Library</h6>
                            <div class="task-meta">
                                <span><i class="bi bi-calendar"></i> Due: Nov 10, 2024</span>
                                <span><i class="bi bi-person"></i> Assigned by: Project Manager</span>
                            </div>
                        </div>
                        <div>
                            <span class="priority-badge priority-medium">Medium</span>
                            <span class="status-badge status-progress">In Progress</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Build reusable React components for the design system.</p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-primary" style="width: 45%"></div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success btn-sm">Continue</button>
                        <button class="btn btn-outline-secondary btn-sm">Log Time</button>
                    </div>
                </div>
            `;
        }

        function loadProjects() {
            const container = document.getElementById('projects-list');
            container.innerHTML = `
                <div class="project-card">
                    <div class="project-header">
                        <div>
                            <h5 class="project-title">E-Commerce Platform</h5>
                            <div class="project-meta">
                                <span><i class="bi bi-calendar"></i> Deadline: Dec 15, 2024</span>
                                <span><i class="bi bi-people"></i> 8 members</span>
                            </div>
                        </div>
                        <span class="status-badge status-progress">In Progress</span>
                    </div>
                    <p class="text-muted mb-3">Full-stack e-commerce solution with modern tech stack.</p>
                    <div class="d-flex justify-content-between mb-2">
                        <small>Progress</small>
                        <small>65%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-primary" style="width: 65%"></div>
                    </div>
                </div>

                <div class="project-card">
                    <div class="project-header">
                        <div>
                            <h5 class="project-title">Mobile App Backend</h5>
                            <div class="project-meta">
                                <span><i class="bi bi-calendar"></i> Deadline: Jan 20, 2025</span>
                                <span><i class="bi bi-people"></i> 5 members</span>
                            </div>
                        </div>
                        <span class="status-badge status-todo">Planning</span>
                    </div>
                    <p class="text-muted mb-3">REST API development for inventory management mobile app.</p>
                    <div class="d-flex justify-content-between mb-2">
                        <small>Progress</small>
                        <small>25%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: 25%"></div>
                    </div>
                </div>
            `;
        }

        function loadRepositoryData() {
            // Repository data is already in HTML
            console.log('Repository data loaded');
        }

        function loadTimeTrackingData() {
            // Time tracking data is already in HTML
            console.log('Time tracking data loaded');
        }

        function loadReports() {
            // Reports data is already in HTML
            console.log('Reports loaded');
        }

        function loadProfileData() {
            // Profile data is already populated from server-side
            document.getElementById('profile-tasks-completed').textContent = '67';
            document.getElementById('profile-commits').textContent = '247';
            document.getElementById('profile-hours').textContent = '340h';
        }
    </script>
</body>
</html>
