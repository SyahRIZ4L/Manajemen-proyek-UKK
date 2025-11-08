<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Team Lead Panel - Manajemen Proyek</title>
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
            border-left-color: #28a745;
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
            background: rgba(40, 167, 69, 0.1);
            border-color: rgba(40, 167, 69, 0.3);
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            border-left: 4px solid #28a745;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }

        .welcome-card h3 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-card p {
            opacity: 0.9;
            margin: 0;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

        /* Recent Activity Styles */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
            font-size: 1rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .activity-desc {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0;
        }

        .activity-time {
            font-size: 0.8rem;
            color: #adb5bd;
        }

        /* Notification Styles */
        .notification-dropdown {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
        }

        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: rgba(40, 167, 69, 0.05);
            border-left: 3px solid #28a745;
        }

        .notification-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .notification-message {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #adb5bd;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .notification-icon.task-update {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .notification-icon.status-change {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .notification-icon.project-update {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        /* Dark theme for notifications */
        body.dark-theme .notification-dropdown {
            background: #2d2d2d;
            color: #e0e0e0;
        }

        body.dark-theme .notification-item {
            border-bottom-color: #444444;
        }

        body.dark-theme .notification-item:hover {
            background-color: #3a3a3a;
        }

        body.dark-theme .notification-item.unread {
            background-color: rgba(40, 167, 69, 0.1);
        }

        body.dark-theme .notification-message {
            color: #a0a0a0;
        }

        body.dark-theme .notification-time {
            color: #707070;
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

            .stat-card, .quick-action-card {
                margin-bottom: 15px;
            }
        }

        /* Project Management Styles */
        .project-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #28a745;
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

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-planning {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .status-progress {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .status-hold {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-completed {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        /* Team Management Styles */
        .team-member-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .team-member-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .member-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .member-name {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .member-role {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .member-status {
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-busy {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .status-idle {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Team Lead Panel</h4>
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
                        <div class="nav-subtitle">Overview & Statistics</div>
                    </div>
                </a>
            </div>

            <!-- My Projects -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="projects">
                    <div class="nav-icon">
                        <i class="bi bi-folder"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">My Projects</div>
                        <div class="nav-subtitle">Manage assigned projects</div>
                    </div>
                </a>
            </div>

            <!-- Team Management -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="team">
                    <div class="nav-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Team Management</div>
                        <div class="nav-subtitle">Manage team members</div>
                    </div>
                </a>
            </div>

            <!-- Task Management -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="tasks">
                    <div class="nav-icon">
                        <i class="bi bi-list-task"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Task Management</div>
                        <div class="nav-subtitle">Track & assign tasks</div>
                    </div>
                </a>
            </div>

            <!-- Reports -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="reports">
                    <div class="nav-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Reports</div>
                        <div class="nav-subtitle">Project analytics</div>
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
                        <div class="nav-subtitle">Account settings</div>
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
                    <h2 id="content-title" class="mb-0">Team Lead Dashboard</h2>
                    <p class="text-muted mb-0" id="content-subtitle">Manage your team and projects effectively</p>
                </div>
                <div class="header-actions">
                    <!-- Notification Bell -->
                    <div class="dropdown me-3">
                        <button class="btn btn-light position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count" style="display: none;">
                                0
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                <button class="btn btn-sm btn-link p-0" id="mark-all-read">Mark all as read</button>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div id="notification-list">
                                <div class="text-center py-3 text-muted">
                                    <i class="bi bi-bell-slash"></i>
                                    <p class="mb-0 mt-2">No notifications</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <p>Here's what's happening with your team and projects today.</p>
                </div>

                <!-- Main Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-folder-check stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="my-projects-count">0</h3>
                            <p class="stat-label">My Projects</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-people stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="team-members-count">0</h3>
                            <p class="stat-label">Team Members</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-list-task stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="active-tasks-count">0</h3>
                            <p class="stat-label">Active Tasks</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-check-circle stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="completion-rate">0%</h3>
                            <p class="stat-label">Completion Rate</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row g-4 mb-5">
                    <div class="col-12">
                        <h4 class="mb-4">Quick Actions</h4>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="projects">
                            <div class="quick-action-icon">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <h6 class="quick-action-title">New Project</h6>
                            <p class="quick-action-desc">Create a new project</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="team">
                            <div class="quick-action-icon">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <h6 class="quick-action-title">Add Member</h6>
                            <p class="quick-action-desc">Invite team member</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="tasks">
                            <div class="quick-action-icon">
                                <i class="bi bi-clipboard-plus"></i>
                            </div>
                            <h6 class="quick-action-title">Assign Task</h6>
                            <p class="quick-action-desc">Create and assign tasks</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="reports">
                            <div class="quick-action-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h6 class="quick-action-title">View Reports</h6>
                            <p class="quick-action-desc">Analyze performance</p>
                        </a>
                    </div>
                </div>

                <!-- Recent Projects and Team Activity -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Projects</h5>
                            </div>
                            <div class="card-body" id="recent-projects">
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-folder display-6"></i>
                                    <p class="mt-2">Loading projects...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Team Activity</h5>
                            </div>
                            <div class="card-body" id="team-activity">
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-clock-history display-6"></i>
                                    <p class="mt-2">Loading activities...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Content -->
            <div id="projects-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>My Projects</h4>
                    <button class="btn btn-success" id="create-project-btn">
                        <i class="bi bi-plus-circle"></i> Create New Project
                    </button>
                </div>
                <div id="projects-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-folder display-4"></i>
                        <p class="mt-3">Loading projects...</p>
                    </div>
                </div>
            </div>

            <!-- Team Content -->
            <div id="team-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Team Management</h4>
                    <button class="btn btn-success" id="add-member-btn">
                        <i class="bi bi-person-plus"></i> Add Team Member
                    </button>
                </div>
                <div id="team-members-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people display-4"></i>
                        <p class="mt-3">Loading team members...</p>
                    </div>
                </div>
            </div>

            <!-- Tasks Content -->
            <div id="tasks-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Task Management</h4>
                    <button class="btn btn-success" id="create-task-btn">
                        <i class="bi bi-plus-circle"></i> Create New Task
                    </button>
                </div>
                <div id="tasks-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-list-task display-4"></i>
                        <p class="mt-3">Loading tasks...</p>
                    </div>
                </div>
            </div>

            <!-- Reports Content -->
            <div id="reports-content" class="content-section">
                <h4 class="mb-4">Team Reports</h4>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <i class="bi bi-graph-up stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="team-productivity">85%</h3>
                            <p class="stat-label">Team Productivity</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <i class="bi bi-calendar-check stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="on-time-delivery">92%</h3>
                            <p class="stat-label">On-time Delivery</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <i class="bi bi-trophy stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="team-score">4.8</h3>
                            <p class="stat-label">Team Score</p>
                        </div>
                    </div>
                </div>
                <div id="reports-charts">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bar-chart display-4"></i>
                        <p class="mt-3">Loading reports...</p>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div id="profile-content" class="content-section">
                <h4 class="mb-4">Profile Settings</h4>
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
                                    <button type="submit" class="btn btn-success">Update Profile</button>
                                </form>
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
                                    <span>Projects Led:</span>
                                    <strong id="profile-projects-count">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Team Members:</span>
                                    <strong id="profile-team-count">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Tasks Completed:</span>
                                    <strong id="profile-tasks-completed">0</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Member Since:</span>
                                    <strong>{{ Auth::user()->created_at->format('M Y') }}</strong>
                                </div>
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
                        // Trigger navigation
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

            // Initialize notifications
            initializeNotifications();

            // Load initial content
            loadSectionContent('dashboard');
        });

        // Load content based on section
        function loadSectionContent(section) {
            switch(section) {
                case 'dashboard':
                    loadDashboardData();
                    break;
                case 'projects':
                    loadMyProjects();
                    break;
                case 'team':
                    loadTeamMembers();
                    break;
                case 'tasks':
                    loadTasks();
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
            // Load statistics
            loadTeamLeadStatistics();
            loadRecentProjects();
            loadTeamActivity();
        }

        function loadTeamLeadStatistics() {
            // Mock data for now - will be replaced with actual API calls
            document.getElementById('my-projects-count').textContent = '8';
            document.getElementById('team-members-count').textContent = '12';
            document.getElementById('active-tasks-count').textContent = '24';
            document.getElementById('completion-rate').textContent = '78%';
        }

        function loadRecentProjects() {
            const container = document.getElementById('recent-projects');
            // Mock data for recent projects
            container.innerHTML = `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-folder"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">E-Commerce Platform</div>
                        <div class="activity-desc">Development in progress</div>
                    </div>
                    <div class="activity-time">2 hours ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-folder-check"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Mobile App UI</div>
                        <div class="activity-desc">Design completed</div>
                    </div>
                    <div class="activity-time">1 day ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-folder"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Data Migration</div>
                        <div class="activity-desc">Testing phase</div>
                    </div>
                    <div class="activity-time">3 days ago</div>
                </div>
            `;
        }

        function loadTeamActivity() {
            const container = document.getElementById('team-activity');
            // Mock data for team activity
            container.innerHTML = `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">John completed task</div>
                        <div class="activity-desc">Frontend development finished</div>
                    </div>
                    <div class="activity-time">30 min ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-upload"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Sarah submitted design</div>
                        <div class="activity-desc">UI mockups uploaded</div>
                    </div>
                    <div class="activity-time">2 hours ago</div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-chat-text"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Team meeting scheduled</div>
                        <div class="activity-desc">Tomorrow at 10 AM</div>
                    </div>
                    <div class="activity-time">4 hours ago</div>
                </div>
            `;
        }

        function loadMyProjects() {
            const container = document.getElementById('projects-list');
            // Mock project data
            container.innerHTML = `
                <div class="project-card">
                    <div class="project-header">
                        <div>
                            <h5 class="project-title">E-Commerce Platform</h5>
                            <div class="project-meta">
                                <span><i class="bi bi-calendar"></i> Due: Dec 15, 2024</span>
                                <span><i class="bi bi-people"></i> 5 members</span>
                            </div>
                        </div>
                        <span class="status-badge status-progress">In Progress</span>
                    </div>
                    <p class="text-muted mb-3">Building a comprehensive e-commerce solution with modern tech stack.</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width: 65%"></div>
                    </div>
                    <small class="text-muted">65% Complete</small>
                </div>

                <div class="project-card">
                    <div class="project-header">
                        <div>
                            <h5 class="project-title">Mobile App Development</h5>
                            <div class="project-meta">
                                <span><i class="bi bi-calendar"></i> Due: Jan 30, 2025</span>
                                <span><i class="bi bi-people"></i> 3 members</span>
                            </div>
                        </div>
                        <span class="status-badge status-planning">Planning</span>
                    </div>
                    <p class="text-muted mb-3">Cross-platform mobile application for inventory management.</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-warning" style="width: 25%"></div>
                    </div>
                    <small class="text-muted">25% Complete</small>
                </div>
            `;
        }

        function loadTeamMembers() {
            const container = document.getElementById('team-members-list');
            container.innerHTML = `
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="team-member-card">
                            <div class="member-avatar">JD</div>
                            <div class="member-name">John Doe</div>
                            <div class="member-role">Frontend Developer</div>
                            <span class="member-status status-active">Active</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="team-member-card">
                            <div class="member-avatar">SS</div>
                            <div class="member-name">Sarah Smith</div>
                            <div class="member-role">UI/UX Designer</div>
                            <span class="member-status status-busy">Busy</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="team-member-card">
                            <div class="member-avatar">MJ</div>
                            <div class="member-name">Mike Johnson</div>
                            <div class="member-role">Backend Developer</div>
                            <span class="member-status status-active">Active</span>
                        </div>
                    </div>
                </div>
            `;
        }

        function loadTasks() {
            const container = document.getElementById('tasks-list');
            container.innerHTML = `
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Active Tasks</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Assigned To</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Frontend Development</td>
                                        <td>John Doe</td>
                                        <td>Dec 10, 2024</td>
                                        <td><span class="badge bg-warning">In Progress</span></td>
                                        <td><button class="btn btn-sm btn-outline-primary">View</button></td>
                                    </tr>
                                    <tr>
                                        <td>Database Design</td>
                                        <td>Mike Johnson</td>
                                        <td>Dec 8, 2024</td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td><button class="btn btn-sm btn-outline-primary">View</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;
        }

        function loadReports() {
            // Reports content already has mock data in HTML
            console.log('Reports loaded');
        }

        function loadProfileData() {
            // Profile data is already populated from server-side
            document.getElementById('profile-projects-count').textContent = '8';
            document.getElementById('profile-team-count').textContent = '12';
            document.getElementById('profile-tasks-completed').textContent = '145';
        }

        // Notification Management (same as admin panel)
        function initializeNotifications() {
            loadNotificationCount();
            setInterval(loadNotificationCount, 30000); // Check every 30 seconds

            // Load recent notifications when dropdown is opened
            document.getElementById('notificationDropdown').addEventListener('click', function() {
                loadRecentNotifications();
            });

            // Mark all as read
            document.getElementById('mark-all-read').addEventListener('click', function() {
                markAllNotificationsAsRead();
            });
        }

        function loadNotificationCount() {
            fetch('/api/notifications/count', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const badge = document.getElementById('notification-count');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            });
        }

        function loadRecentNotifications() {
            fetch('/api/notifications/recent?limit=10', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderNotifications(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
        }

        function renderNotifications(notifications) {
            const container = document.getElementById('notification-list');

            if (!notifications || notifications.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-bell-slash"></i>
                        <p class="mb-0 mt-2">No notifications</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = notifications.map(notification => `
                <div class="notification-item ${!notification.is_read ? 'unread' : ''}" data-id="${notification.notification_id}">
                    <div class="d-flex">
                        <div class="notification-icon ${notification.type} me-3">
                            <i class="bi ${getNotificationIcon(notification.type)}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${notification.time_ago}</div>
                        </div>
                    </div>
                </div>
            `).join('');

            // Add click listeners to mark as read
            container.querySelectorAll('.notification-item.unread').forEach(item => {
                item.addEventListener('click', function() {
                    markNotificationAsRead(this.dataset.id);
                    this.classList.remove('unread');
                });
            });
        }

        function getNotificationIcon(type) {
            const iconMap = {
                'task_update': 'bi-list-task',
                'status_change': 'bi-arrow-repeat',
                'project_update': 'bi-folder'
            };
            return iconMap[type] || 'bi-bell';
        }

        function markNotificationAsRead(notificationId) {
            fetch(`/api/notifications/${notificationId}/read`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotificationCount();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        function markAllNotificationsAsRead() {
            fetch('/api/notifications/read-all', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotificationCount();
                    loadRecentNotifications();
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        }
    </script>
</body>
</html>
