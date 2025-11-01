<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Manajemen Proyek</title>
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
            border-left-color: #667eea;
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
            background: rgba(102, 126, 234, 0.1);
            border-color: rgba(102, 126, 234, 0.3);
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 18px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 16px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }
        .nav-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .nav-link:hover .nav-icon, .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.1);
        }
        .nav-icon i {
            font-size: 1.3rem;
            color: white;
        }
        .nav-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .nav-title {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.2;
            color: white;
        }
        .nav-subtitle {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2px;
            font-weight: 400;
        }

        /* Profile Section Styles */
        .sidebar-profile {
            position: absolute;
            bottom: 20px;
            left: 15px;
            right: 15px;
        }
        .profile-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .profile-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .profile-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .profile-avatar i {
            font-size: 1.8rem;
        }
        .profile-details h6 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        .profile-details small {
            color: rgba(255, 255, 255, 0.7);
        }
        .profile-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .btn-action {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }
        .btn-action:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            transform: translateY(-2px);
        }
        .logout-btn:hover {
            background: rgba(220, 53, 69, 0.3);
            border-color: rgba(220, 53, 69, 0.5);
        }

        /* Theme Toggle Button */
        .theme-toggle:hover {
            background: rgba(255, 193, 7, 0.3);
            border-color: rgba(255, 193, 7, 0.5);
        }

        /* Back Button Section */
        .back-section {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        .back-btn-new {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 15px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
            width: 100%;
        }
        .back-btn-new:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            border-left: 4px solid #667eea;
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
            background: #ffffff;
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: 1px solid #f0f0f0;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }
        .stat-card:hover::before {
            left: 100%;
        }
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-color: #e0e0e0;
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 1rem;
            margin-bottom: 5px;
            font-weight: 500;
            color: #6c757d;
        }

        /* Project and Task Styles */
        .project-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .recent-project-item {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
        }
        .recent-project-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .upcoming-task-item {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
        }
        .upcoming-task-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .task-priority {
            width: 4px;
            height: 40px;
            border-radius: 2px;
            margin-top: 2px;
        }
        .task-priority.high {
            background-color: #dc3545;
        }
        .task-priority.medium {
            background-color: #ffc107;
        }
        .task-priority.low {
            background-color: #28a745;
        }

        /* Project Management Styles */
        .project-stat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 25px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: 1px solid #f0f0f0;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .project-stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .search-container .input-group-text {
            background: white;
            border: 1px solid #dee2e6;
        }
        .search-container .form-control {
            border: 1px solid #dee2e6;
        }
        .search-container .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .project-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .project-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .project-header {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 20px 20px 0 20px;
        }

        .project-status {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-todo {
            background: #f8f9fa;
            color: #6c757d;
        }
        .status-progress {
            background: #fff3cd;
            color: #856404;
        }
        .status-review {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-done {
            background: #d4edda;
            color: #155724;
        }

        .project-body {
            padding: 20px;
        }

        .project-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .project-description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .avatar-group {
            display: flex;
            align-items: center;
            gap: -8px;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid white;
            margin-left: -8px;
        }
        .avatar:first-child {
            margin-left: 0;
        }

        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .welcome-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        .feature-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: 1px solid #f0f0f0;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }
        .feature-card:hover::before {
            left: 100%;
        }
        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-color: #e0e0e0;
        }
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
            color: white;
            transition: all 0.3s ease;
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        .feature-stats {
            margin-top: 15px;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 10px;
            display: inline-block;
        }
        .coming-soon {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-top: 15px;
        }

        /* User Management Styles */
        .user-stat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 25px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: 1px solid #f0f0f0;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .user-stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .role-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .role-project-admin {
            background: #dc3545;
            color: white;
        }
        .role-team-lead {
            background: #fd7e14;
            color: white;
        }
        .role-developer {
            background: #198754;
            color: white;
        }
        .role-designer {
            background: #6f42c1;
            color: white;
        }
        .role-member {
            background: #6c757d;
            color: white;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 15px;
        }
        .status-working {
            background: #d4edda;
            color: #155724;
        }
        .status-idle {
            background: #f8d7da;
            color: #721c24;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-action {
            padding: 6px 10px;
            margin: 0 2px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-1px);
        }
        .btn-action.btn-view {
            background: #e3f2fd;
            color: #1976d2;
        }
        .btn-action.btn-edit {
            background: #fff3e0;
            color: #f57c00;
        }
        .btn-action.btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        /* Project Detail Styles */
        .project-detail-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            margin: -1rem -1rem 0 -1rem;
        }

        .project-status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .project-status-badge.planning {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 2px solid rgba(108, 117, 125, 0.3);
        }

        .project-status-badge.in-progress {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 2px solid rgba(255, 193, 7, 0.3);
        }

        .project-status-badge.on-hold {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 2px solid rgba(220, 53, 69, 0.3);
        }

        .project-status-badge.completed {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 2px solid rgba(25, 135, 84, 0.3);
        }

        .team-member-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .team-member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .board-stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .board-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .board-stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .board-stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .board-stat-label {
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
        }

        .project-info-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            height: 100%;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
        }

        .info-value {
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <i class="bi bi-gear-wide-connected mb-2" style="font-size: 2.5rem;"></i>
            <h4>Admin Panel</h4>
            <p>{{ $user->name }}</p>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#dashboard" onclick="showContent('dashboard')">
                        <div class="nav-icon">
                            <i class="bi bi-grid-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">Dashboard</span>
                            <small class="nav-subtitle">Overview & Analytics</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#projects" onclick="showContent('projects')">
                        <div class="nav-icon">
                            <i class="bi bi-kanban-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">Projects</span>
                            <small class="nav-subtitle">Manage Projects</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#users" onclick="showContent('users')">
                        <div class="nav-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">User Management</span>
                            <small class="nav-subtitle">Users & Permissions</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#reports" onclick="showContent('reports')">
                        <div class="nav-icon">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">Reports</span>
                            <small class="nav-subtitle">Analytics & Insights</small>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Profile & Logout Section -->
        <div class="sidebar-profile">
            <div class="profile-card">
                <div class="profile-info">
                    <div class="profile-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="profile-details">
                        <h6 class="mb-1">{{ $user->name }}</h6>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
                <div class="profile-actions">
                    <a href="#profile" onclick="showContent('profile')" class="btn-action" title="Profile Settings">
                        <i class="bi bi-person-gear"></i>
                    </a>
                    <button onclick="toggleTheme()" class="btn-action theme-toggle" title="Toggle Theme">
                        <i class="bi bi-moon-fill" id="theme-icon"></i>
                    </button>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-action logout-btn" title="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>

                <!-- Back Button Section -->
                <div class="back-section">
                    <a href="{{ route('home') }}" class="back-btn-new">
                        <i class="bi bi-arrow-left me-2"></i>
                        <span>Back to the front</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <h2 id="content-title">Admin Dashboard</h2>
            <p class="text-muted mb-0" id="content-subtitle">Welcome to the administrative control center</p>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Dashboard Content -->
            <div id="dashboard-content" class="content-section">
                <!-- Main Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-folder-check stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary">12</h3>
                            <p class="stat-label">Active Projects</p>
                            <small class="text-muted">+3 this month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-clock-history stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning">23</h3>
                            <p class="stat-label">Pending Tasks</p>
                            <small class="text-muted">Due this week</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-check-circle-fill stat-icon text-success"></i>
                            <h3 class="stat-number text-success">89</h3>
                            <p class="stat-label">Completed</p>
                            <small class="text-muted">Tasks this month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-people-fill stat-icon text-info"></i>
                            <h3 class="stat-number text-info">24</h3>
                            <p class="stat-label">Team Members</p>
                            <small class="text-muted">Active users</small>
                        </div>
                    </div>
                </div>

                <!-- Recent Projects & Upcoming Tasks -->
                <div class="row g-4">
                    <!-- Recent Projects -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="mb-0">
                                    <i class="bi bi-folder2-open me-2 text-primary"></i>
                                    Recent Projects
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="recent-project-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="project-icon bg-primary bg-opacity-10 text-primary me-3">
                                            <i class="bi bi-kanban"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Website Redesign</h6>
                                            <small class="text-muted">Updated 2 hours ago</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">Active</span>
                                    </div>
                                </div>

                                <div class="recent-project-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="project-icon bg-success bg-opacity-10 text-success me-3">
                                            <i class="bi bi-code-square"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Mobile App Development</h6>
                                            <small class="text-muted">Updated 5 hours ago</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">Active</span>
                                    </div>
                                </div>

                                <div class="recent-project-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="project-icon bg-warning bg-opacity-10 text-warning me-3">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Analytics Dashboard</h6>
                                            <small class="text-muted">Updated 1 day ago</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">In Progress</span>
                                    </div>
                                </div>

                                <div class="recent-project-item">
                                    <div class="d-flex align-items-center mb-0">
                                        <div class="project-icon bg-info bg-opacity-10 text-info me-3">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Security Audit</h6>
                                            <small class="text-muted">Updated 2 days ago</small>
                                        </div>
                                        <span class="badge bg-info rounded-pill">Review</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Tasks -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h5 class="mb-0">
                                    <i class="bi bi-calendar-check me-2 text-warning"></i>
                                    Upcoming Tasks
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="upcoming-task-item">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="task-priority high me-3"></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Database Migration</h6>
                                            <small class="text-muted">Due: Tomorrow</small>
                                            <div class="mt-1">
                                                <small class="text-danger">
                                                    <i class="bi bi-exclamation-circle me-1"></i>High Priority
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">2h</small>
                                    </div>
                                </div>

                                <div class="upcoming-task-item">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="task-priority medium me-3"></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">User Testing Session</h6>
                                            <small class="text-muted">Due: Oct 31</small>
                                            <div class="mt-1">
                                                <small class="text-warning">
                                                    <i class="bi bi-dash-circle me-1"></i>Medium Priority
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">4h</small>
                                    </div>
                                </div>

                                <div class="upcoming-task-item">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="task-priority low me-3"></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Documentation Update</h6>
                                            <small class="text-muted">Due: Nov 5</small>
                                            <div class="mt-1">
                                                <small class="text-success">
                                                    <i class="bi bi-circle me-1"></i>Low Priority
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">1h</small>
                                    </div>
                                </div>

                                <div class="upcoming-task-item">
                                    <div class="d-flex align-items-start mb-0">
                                        <div class="task-priority medium me-3"></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Code Review Meeting</h6>
                                            <small class="text-muted">Due: Nov 8</small>
                                            <div class="mt-1">
                                                <small class="text-warning">
                                                    <i class="bi bi-dash-circle me-1"></i>Medium Priority
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">3h</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Content -->
            <div id="projects-content" class="content-section" style="display: none;">
                <!-- Create Project Button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Project Management</h4>
                        <p class="text-muted mb-0">Create, manage, and oversee all project activities</p>
                    </div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create New Project
                    </button>
                </div>

                <!-- Project Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="project-stat-card">
                            <i class="bi bi-folder-fill stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="totalProjects">18</h3>
                            <p class="stat-label">Total Projects</p>
                            <small class="text-muted">All projects</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="project-stat-card">
                            <i class="bi bi-calendar-x stat-icon text-danger"></i>
                            <h3 class="stat-number text-danger">3</h3>
                            <p class="stat-label">Near Deadline</p>
                            <small class="text-muted">Within 7 days</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="project-stat-card">
                            <i class="bi bi-clock-history stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning">5</h3>
                            <p class="stat-label">Recent</p>
                            <small class="text-muted">Last updated</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="project-stat-card">
                            <i class="bi bi-people-fill stat-icon text-info"></i>
                            <h3 class="stat-number text-info">24</h3>
                            <p class="stat-label">Team Members</p>
                            <small class="text-muted">Active members</small>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="search-container">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search projects..." id="searchProject">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sortByName">
                            <option value="">Sort by Name</option>
                            <option value="asc">A to Z</option>
                            <option value="desc">Z to A</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sortByStatus">
                            <option value="">All Status</option>
                            <option value="Planning">Planning</option>
                            <option value="In Progress">In Progress</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Project List -->
                <div class="projects-grid" id="projectsList">
                    <!-- Project Card 1 -->
                    <div class="project-card" data-status="progress" data-name="Website Redesign">
                        <div class="project-header">
                            <div class="project-status status-progress">In Progress</div>
                            <div class="project-menu">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                            </div>
                        </div>
                        <div class="project-body">
                            <h5 class="project-title">Website Redesign</h5>
                            <p class="project-description">Complete overhaul of company website with modern UI/UX design</p>

                            <div class="project-progress">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="text-muted">65%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: 65%"></div>
                                </div>
                            </div>

                            <div class="project-info mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="project-team">
                                        <div class="avatar-group">
                                            <div class="avatar">JD</div>
                                            <div class="avatar">AS</div>
                                            <div class="avatar">+3</div>
                                        </div>
                                    </div>
                                    <div class="project-deadline">
                                        <small class="text-danger">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Nov 15
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Card 2 -->
                    <div class="project-card" data-status="todo" data-name="Mobile App">
                        <div class="project-header">
                            <div class="project-status status-todo">To Do</div>
                            <div class="project-menu">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                            </div>
                        </div>
                        <div class="project-body">
                            <h5 class="project-title">Mobile App Development</h5>
                            <p class="project-description">Native mobile application for iOS and Android platforms</p>

                            <div class="project-progress">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="text-muted">0%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-secondary" style="width: 0%"></div>
                                </div>
                            </div>

                            <div class="project-info mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="project-team">
                                        <div class="avatar-group">
                                            <div class="avatar">MK</div>
                                            <div class="avatar">LT</div>
                                        </div>
                                    </div>
                                    <div class="project-deadline">
                                        <small class="text-primary">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Dec 20
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Card 3 -->
                    <div class="project-card" data-status="review" data-name="Analytics Dashboard">
                        <div class="project-header">
                            <div class="project-status status-review">Review</div>
                            <div class="project-menu">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                            </div>
                        </div>
                        <div class="project-body">
                            <h5 class="project-title">Analytics Dashboard</h5>
                            <p class="project-description">Real-time analytics and reporting dashboard for business metrics</p>

                            <div class="project-progress">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="text-muted">95%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: 95%"></div>
                                </div>
                            </div>

                            <div class="project-info mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="project-team">
                                        <div class="avatar-group">
                                            <div class="avatar">RF</div>
                                            <div class="avatar">GH</div>
                                            <div class="avatar">UI</div>
                                        </div>
                                    </div>
                                    <div class="project-deadline">
                                        <small class="text-success">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Nov 5
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Card 4 -->
                    <div class="project-card" data-status="done" data-name="Security Audit">
                        <div class="project-header">
                            <div class="project-status status-done">Done</div>
                            <div class="project-menu">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                            </div>
                        </div>
                        <div class="project-body">
                            <h5 class="project-title">Security Audit</h5>
                            <p class="project-description">Comprehensive security assessment and vulnerability testing</p>

                            <div class="project-progress">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Progress</small>
                                    <small class="text-muted">100%</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>

                            <div class="project-info mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="project-team">
                                        <div class="avatar-group">
                                            <div class="avatar">PQ</div>
                                            <div class="avatar">RS</div>
                                        </div>
                                    </div>
                                    <div class="project-deadline">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Completed
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Management Content -->
            <div id="users-content" class="content-section" style="display: none;">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">User Management</h4>
                        <p class="text-muted mb-0">Manage user accounts, roles, and permissions</p>
                    </div>
                    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-person-plus me-2"></i>
                        Add New User
                    </button>
                </div>

                <!-- User Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="user-stat-card">
                            <i class="bi bi-people-fill stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="totalUsers">0</h3>
                            <p class="stat-label">Total Users</p>
                            <small class="text-muted">All registered users</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="user-stat-card">
                            <i class="bi bi-person-check stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="activeUsers">0</h3>
                            <p class="stat-label">Active Users</p>
                            <small class="text-muted">Currently working</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="user-stat-card">
                            <i class="bi bi-clock-history stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="recentUsers">0</h3>
                            <p class="stat-label">Recent Users</p>
                            <small class="text-muted">Last 7 days</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="user-stat-card">
                            <i class="bi bi-shield-check stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="adminUsers">0</h3>
                            <p class="stat-label">Admins</p>
                            <small class="text-muted">Admin accounts</small>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="search-container">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search users..." id="searchUser">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterByRole">
                            <option value="">All Roles</option>
                            <option value="Project_Admin">Project Admin</option>
                            <option value="Team_Lead">Team Lead</option>
                            <option value="Developer">Developer</option>
                            <option value="Designer">Designer</option>
                            <option value="member">Member</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="filterByStatus">
                            <option value="">All Status</option>
                            <option value="working">Working</option>
                            <option value="idle">Idle</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="clearUserFilters()">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="mb-0">
                            <i class="bi bi-table me-2 text-primary"></i>
                            User List
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">
                                            <i class="bi bi-person me-1"></i>User
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-envelope me-1"></i>Email
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-shield me-1"></i>Role
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-activity me-1"></i>Status
                                        </th>
                                        <th scope="col">
                                            <i class="bi bi-calendar me-1"></i>Joined
                                        </th>
                                        <th scope="col" class="text-end">
                                            <i class="bi bi-gear me-1"></i>Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <!-- Users will be loaded here -->
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading users...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- No Results Message -->
                        <div id="noUsersResults" class="text-center py-5" style="display: none;">
                            <div class="mb-4">
                                <i class="bi bi-people" style="font-size: 4rem; color: #dee2e6;"></i>
                            </div>
                            <h5 class="text-muted">No Users Found</h5>
                            <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                <i class="bi bi-person-plus me-2"></i>
                                Add First User
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports Content -->
            <div id="reports-content" class="content-section" style="display: none;">
                <h4>Reports & Analytics</h4>
                <p class="text-muted">View comprehensive reports and generate detailed analytics insights.</p>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Reports and analytics features are currently under development.
                </div>
            </div>

            <!-- Profile Content -->
            <div id="profile-content" class="content-section" style="display: none;">
                <h4>Profile Settings</h4>
                <p class="text-muted">Manage your account settings and preferences.</p>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-person-circle me-2 text-primary"></i>
                                    Account Information
                                </h5>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Name:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $user->name }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Email:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $user->email }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Role:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        <span class="badge bg-danger">Administrator</span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Registered:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $user->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="profile-avatar-large mb-3">
                                    <i class="bi bi-person-circle" style="font-size: 4rem; color: #667eea;"></i>
                                </div>
                                <h5>{{ $user->name }}</h5>
                                <p class="text-muted">System Administrator</p>
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProjectModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Create New Project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createProjectForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="projectName" class="form-label">Project Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="projectName" name="project_name" required maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="projectDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="projectDescription" name="description" rows="4" placeholder="Describe your project..."></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="projectDeadline" class="form-label">Deadline</label>
                                <input type="date" class="form-control" id="projectDeadline" name="deadline" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                <small class="form-text text-muted">Optional: Set a deadline for this project</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Alert for form messages -->
                        <div id="formAlert" class="alert" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="btn-text">Create Project</span>
                            <span class="btn-loading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Creating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Project Detail Modal -->
    <div class="modal fade" id="projectDetailModal" tabindex="-1" aria-labelledby="projectDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectDetailModalLabel">
                        <i class="bi bi-folder-open me-2"></i>Project Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="projectDetailContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Add New User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="createUserFormAlert" class="alert d-none"></div>

                    <form id="createUserForm">
                        @csrf

                        <div class="mb-3">
                            <label for="createUsername" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="createUsername" name="username" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="createFullName" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="createFullName" name="full_name" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="createEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="createEmail" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="createPassword" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="createPassword" name="password" required>
                            <small class="form-text text-muted">Minimum 6 characters</small>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="createRole" class="form-label">Role *</label>
                            <select class="form-select" id="createRole" name="role" required>
                                <option value="">Select Role</option>
                                <option value="Project_Admin">Project Admin</option>
                                <option value="Team_Lead">Team Lead</option>
                                <option value="Developer">Developer</option>
                                <option value="Designer">Designer</option>
                                <option value="member">Member</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="createUserForm" class="btn btn-primary" id="createUserSubmitBtn">
                        <span class="btn-text">
                            <i class="bi bi-check-circle me-2"></i>Create User
                        </span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Detail Modal -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">
                        <i class="bi bi-person me-2"></i>User Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userDetailContent">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="editUserFromDetail()">
                        <i class="bi bi-pencil me-2"></i>Edit User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">
                        <i class="bi bi-pencil me-2"></i>Edit User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editUserFormAlert" class="alert d-none"></div>

                    <form id="editUserForm">
                        @csrf
                        <input type="hidden" id="editUserId" name="user_id">

                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editFullName" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="editFullName" name="full_name" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="password">
                            <small class="form-text text-muted">Leave empty to keep current password</small>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role *</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="Project_Admin">Project Admin</option>
                                <option value="Team_Lead">Team Lead</option>
                                <option value="Developer">Developer</option>
                                <option value="Designer">Designer</option>
                                <option value="member">Member</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status *</label>
                            <select class="form-select" id="editStatus" name="current_task_status" required>
                                <option value="idle">Idle</option>
                                <option value="working">Working</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editUserForm" class="btn btn-primary" id="editUserSubmitBtn">
                        <span class="btn-text">
                            <i class="bi bi-check-circle me-2"></i>Update User
                        </span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Updating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete User Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteUserModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-person-x text-danger" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Delete User?</h5>
                        <p class="text-muted">Are you sure you want to delete this user? This action cannot be undone.</p>
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>User:</strong> <span id="deleteUserName"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteUserBtn" onclick="confirmDeleteUser()">
                        <span class="btn-text">
                            <i class="bi bi-trash me-2"></i>Delete User
                        </span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showContent(section) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from all nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });

            // Show selected content
            document.getElementById(section + '-content').style.display = 'block';

            // Add active class to clicked nav link
            event.target.closest('.nav-link').classList.add('active');

            // Load data when section is opened
            if (section === 'users') {
                loadUserStatistics();
                loadUsersList();
            } else if (section === 'projects') {
                loadProjectStatistics();
            }

            // Update header based on section
            const titles = {
                'dashboard': 'Admin Dashboard',
                'projects': 'Project Management',
                'users': 'User Management',
                'reports': 'Reports & Analytics',
                'profile': 'Profile Settings'
            };

            const subtitles = {
                'dashboard': 'Welcome to the administrative control center',
                'projects': 'Create, manage, and oversee project activities',
                'users': 'Manage user accounts, roles, and permissions',
                'reports': 'View comprehensive reports and analytics insights',
                'profile': 'Manage your account settings and preferences'
            };

            document.getElementById('content-title').textContent = titles[section];
            document.getElementById('content-subtitle').textContent = subtitles[section];
        }

        // Theme Toggle Function
        function toggleTheme() {
            const body = document.body;
            const themeIcon = document.getElementById('theme-icon');

            body.classList.toggle('dark-theme');

            if (body.classList.contains('dark-theme')) {
                themeIcon.className = 'bi bi-sun-fill';
                localStorage.setItem('theme', 'dark');
            } else {
                themeIcon.className = 'bi bi-moon-fill';
                localStorage.setItem('theme', 'light');
            }
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.getElementById('theme-icon');

            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                themeIcon.className = 'bi bi-sun-fill';
            } else {
                themeIcon.className = 'bi bi-moon-fill';
            }

            // Initialize project filters
            initializeProjectFilters();
        });

        // Project Filter Functions
        function initializeProjectFilters() {
            const searchInput = document.getElementById('searchProject');
            const sortByName = document.getElementById('sortByName');
            const sortByStatus = document.getElementById('sortByStatus');

            if (searchInput) {
                searchInput.addEventListener('input', filterProjects);
            }
            if (sortByName) {
                sortByName.addEventListener('change', filterProjects);
            }
            if (sortByStatus) {
                sortByStatus.addEventListener('change', filterProjects);
            }
        }

        function filterProjects() {
            const searchTerm = document.getElementById('searchProject').value.toLowerCase();
            const nameSort = document.getElementById('sortByName').value;
            const statusFilter = document.getElementById('sortByStatus').value;
            const projectCards = document.querySelectorAll('.project-card');

            let visibleCards = Array.from(projectCards);

            // Filter by search term
            if (searchTerm) {
                visibleCards = visibleCards.filter(card => {
                    const title = card.querySelector('.project-title').textContent.toLowerCase();
                    const description = card.querySelector('.project-description').textContent.toLowerCase();
                    return title.includes(searchTerm) || description.includes(searchTerm);
                });
            }

            // Filter by status
            if (statusFilter) {
                visibleCards = visibleCards.filter(card => {
                    return card.dataset.status === statusFilter;
                });
            }

            // Sort by name
            if (nameSort) {
                visibleCards.sort((a, b) => {
                    const nameA = a.dataset.name.toLowerCase();
                    const nameB = b.dataset.name.toLowerCase();

                    if (nameSort === 'asc') {
                        return nameA.localeCompare(nameB);
                    } else {
                        return nameB.localeCompare(nameA);
                    }
                });
            }

            // Hide all cards first
            projectCards.forEach(card => {
                card.style.display = 'none';
            });

            // Show filtered and sorted cards
            const projectsGrid = document.getElementById('projectsList');
            if (projectsGrid) {
                // Clear and re-append in new order
                visibleCards.forEach(card => {
                    card.style.display = 'block';
                    projectsGrid.appendChild(card);
                });
            }

            // Show "no results" message if needed
            if (visibleCards.length === 0) {
                showNoResults();
            } else {
                hideNoResults();
            }
        }

        function showNoResults() {
            let noResultsDiv = document.getElementById('noResults');
            if (!noResultsDiv) {
                noResultsDiv = document.createElement('div');
                noResultsDiv.id = 'noResults';
                noResultsDiv.className = 'text-center py-5';
                noResultsDiv.innerHTML = `
                    <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
                    <h5 class="mt-3 text-muted">No projects found</h5>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                `;
                document.getElementById('projectsList').appendChild(noResultsDiv);
            }
            noResultsDiv.style.display = 'block';
        }

        function hideNoResults() {
            const noResultsDiv = document.getElementById('noResults');
            if (noResultsDiv) {
                noResultsDiv.style.display = 'none';
            }
        }

        // Project Management Functions
        function initializeProjectManagement() {
            // Load project statistics when projects tab is opened
            const projectsTab = document.querySelector('a[href="#projects"]');
            if (projectsTab) {
                projectsTab.addEventListener('click', function() {
                    loadProjectStatistics();
                    loadProjectsList();
                });
            }

            // Handle create project form submission
            const createProjectForm = document.getElementById('createProjectForm');
            if (createProjectForm) {
                createProjectForm.addEventListener('submit', handleCreateProject);
            }

            // Reset form when modal is closed
            const createProjectModal = document.getElementById('createProjectModal');
            if (createProjectModal) {
                createProjectModal.addEventListener('hidden.bs.modal', resetCreateProjectForm);
            }
        }

        function loadProjectStatistics() {
            fetch('/api/projects/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update statistics
                        const stats = data.data;
                        document.getElementById('totalProjects').textContent = stats.total_projects || 0;
                        document.querySelector('.project-stat-card:nth-child(2) .stat-number').textContent = stats.near_deadline || 0;
                        document.querySelector('.project-stat-card:nth-child(3) .stat-number').textContent = stats.recent_projects || 0;
                        document.querySelector('.project-stat-card:nth-child(4) .stat-number').textContent = stats.total_members || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading project statistics:', error);
                });
        }

        function handleCreateProject(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';
            submitBtn.disabled = true;

            // Clear previous errors
            clearFormErrors();

            fetch('/api/projects', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showFormAlert('success', data.message || 'Project created successfully!');

                    // Reset form and close modal after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('createProjectModal')).hide();
                        loadProjectStatistics(); // Refresh statistics
                        loadProjectsList(); // Refresh project list if needed
                    }, 1500);
                } else {
                    // Show error message
                    showFormAlert('danger', data.message || 'Failed to create project');

                    // Show validation errors
                    if (data.errors) {
                        showValidationErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error creating project:', error);
                showFormAlert('danger', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button state
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                submitBtn.disabled = false;
            });
        }

        function resetCreateProjectForm() {
            const form = document.getElementById('createProjectForm');
            if (form) {
                form.reset();
                clearFormErrors();
                hideFormAlert();
            }
        }

        function showFormAlert(type, message) {
            const alertDiv = document.getElementById('formAlert');
            if (alertDiv) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.style.display = 'block';
            }
        }

        function hideFormAlert() {
            const alertDiv = document.getElementById('formAlert');
            if (alertDiv) {
                alertDiv.style.display = 'none';
            }
        }

        function clearFormErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.textContent = '';
            });
        }

        function showValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                const feedback = input?.nextElementSibling;

                if (input && feedback && feedback.classList.contains('invalid-feedback')) {
                    input.classList.add('is-invalid');
                    feedback.textContent = errors[field][0];
                }
            });
        }

        function loadProjectsList() {
            const projectsContainer = document.getElementById('projectsList');

            // Show loading
            projectsContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading projects...</p>
                </div>
            `;

            fetch('/api/projects')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProjects(data.data);
                        loadProjectStatistics(); // Also refresh statistics
                    } else {
                        projectsContainer.innerHTML = `
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Error Loading Projects</h5>
                                <p class="text-muted">Unable to load project data</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading projects:', error);
                    projectsContainer.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-wifi-off text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Network Error</h5>
                            <p class="text-muted">Unable to connect to server</p>
                        </div>
                    `;
                });
        }

        function displayProjects(projects) {
            const projectsContainer = document.getElementById('projectsList');

            if (projects.length === 0) {
                projectsContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-folder2-open text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">No Projects Found</h5>
                        <p class="text-muted">Start by creating your first project</p>
                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            Create New Project
                        </button>
                    </div>
                `;
                return;
            }

            projectsContainer.innerHTML = projects.map(project => {
                const statusClass = getStatusClass(project.status);
                const formattedDeadline = project.deadline ? new Date(project.deadline).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : 'No deadline';
                const deadlineClass = project.deadline && new Date(project.deadline) < new Date() ? 'text-danger' : 'text-primary';

                return `
                    <div class="project-card" data-status="${project.status.toLowerCase()}" data-name="${project.project_name}">
                        <div class="project-header">
                            <div class="project-status ${statusClass}">${project.status}</div>
                            <div class="project-menu">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewProject(${project.project_id})">
                                            <i class="bi bi-eye me-2"></i>View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="editProject(${project.project_id})">
                                            <i class="bi bi-pencil me-2"></i>Edit
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteProject(${project.project_id}, '${project.project_name}')">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="project-body">
                            <h5 class="project-title">${project.project_name}</h5>
                            <p class="project-description">${project.description || 'No description available'}</p>

                            <div class="project-info mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="project-team">
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>
                                            ${project.creator ? project.creator.full_name : 'Unknown'}
                                        </small>
                                    </div>
                                    <div class="project-deadline">
                                        <small class="${deadlineClass}">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            ${formattedDeadline}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'planning': return 'status-todo';
                case 'in progress': return 'status-progress';
                case 'on hold': return 'status-review';
                case 'completed': return 'status-done';
                default: return 'status-todo';
            }
        }

        function viewProject(projectId) {
            const modal = new bootstrap.Modal(document.getElementById('projectDetailModal'));
            const content = document.getElementById('projectDetailContent');

            // Show loading
            content.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading project details...</p>
                </div>
            `;

            modal.show();

            // Fetch project details
            fetch(`/api/projects/${projectId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProjectDetail(data.data);
                    } else {
                        content.innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Error Loading Project</h5>
                                <p class="text-muted">${data.message || 'Unable to load project details'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading project details:', error);
                    content.innerHTML = `
                        <div class="text-center py-5">
                            <i class="bi bi-wifi-off text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Network Error</h5>
                            <p class="text-muted">Unable to connect to server</p>
                        </div>
                    `;
                });
        }

        function displayProjectDetail(project) {
            const content = document.getElementById('projectDetailContent');
            const statusClass = project.status.toLowerCase().replace(' ', '-');
            const formattedDeadline = project.deadline ? new Date(project.deadline).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }) : 'No deadline set';
            const createdDate = new Date(project.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            content.innerHTML = `
                <!-- Project Header -->
                <div class="project-detail-header">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-2">${project.project_name}</h2>
                                <p class="mb-3 opacity-75">${project.description || 'No description available'}</p>
                                <span class="project-status-badge ${statusClass}">${project.status}</span>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="text-white">
                                    <small class="d-block opacity-75">Created by</small>
                                    <strong>${project.creator ? project.creator.full_name : 'Unknown'}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Content -->
                <div class="container-fluid py-4">
                    <!-- Project Info and Team Overview -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="project-info-card">
                                <h5 class="mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Project Information
                                </h5>
                                <div class="info-item">
                                    <span class="info-label">Status</span>
                                    <span class="info-value">
                                        <span class="project-status-badge ${statusClass}">${project.status}</span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Created Date</span>
                                    <span class="info-value">${createdDate}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Deadline</span>
                                    <span class="info-value">${formattedDeadline}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Project ID</span>
                                    <span class="info-value">#${project.project_id}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="project-info-card">
                                <h5 class="mb-3">
                                    <i class="bi bi-people me-2"></i>Team Overview
                                </h5>
                                <div id="teamOverview">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <p class="mt-2 text-muted small">Loading team data...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Members Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="project-info-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">
                                        <i class="bi bi-people-fill me-2"></i>Team Members
                                    </h5>
                                    <button class="btn btn-sm btn-primary" onclick="addTeamMember(${project.project_id})">
                                        <i class="bi bi-person-plus me-1"></i>Add Member
                                    </button>
                                </div>
                                <div id="teamMembersList">
                                    <div class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <p class="mt-2 text-muted small">Loading team members...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Board Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="project-info-card">
                                <h5 class="mb-4">
                                    <i class="bi bi-kanban me-2"></i>Project Board
                                </h5>
                                <div class="row g-4" id="projectBoardStats">
                                    <div class="col-md-3">
                                        <div class="board-stat-card">
                                            <i class="bi bi-circle board-stat-icon text-secondary"></i>
                                            <div class="board-stat-number text-secondary" id="todoCount">-</div>
                                            <div class="board-stat-label">To Do</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="board-stat-card">
                                            <i class="bi bi-arrow-clockwise board-stat-icon text-warning"></i>
                                            <div class="board-stat-number text-warning" id="inProgressCount">-</div>
                                            <div class="board-stat-label">In Progress</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="board-stat-card">
                                            <i class="bi bi-eye board-stat-icon text-info"></i>
                                            <div class="board-stat-number text-info" id="reviewCount">-</div>
                                            <div class="board-stat-label">Review</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="board-stat-card">
                                            <i class="bi bi-check-circle board-stat-icon text-success"></i>
                                            <div class="board-stat-number text-success" id="doneCount">-</div>
                                            <div class="board-stat-label">Done</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Load team data
            loadProjectTeamData(project.project_id);
            loadProjectBoardData(project.project_id);
        }

        function loadProjectTeamData(projectId) {
            // Load team overview
            fetch(`/api/projects/${projectId}/members`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayTeamOverview(data.data);
                        displayTeamMembersList(data.data);
                    } else {
                        document.getElementById('teamOverview').innerHTML = `
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-people"></i>
                                <p class="mb-0 small">No team data available</p>
                            </div>
                        `;
                        document.getElementById('teamMembersList').innerHTML = `
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-people"></i>
                                <p class="mb-0">No team members assigned</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading team data:', error);
                    document.getElementById('teamOverview').innerHTML = `
                        <div class="text-center py-3 text-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            <p class="mb-0 small">Error loading team data</p>
                        </div>
                    `;
                });
        }

        function loadProjectBoardData(projectId) {
            // Load board statistics
            fetch(`/api/projects/${projectId}/board-stats`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const stats = data.data;
                        document.getElementById('todoCount').textContent = stats.todo || 0;
                        document.getElementById('inProgressCount').textContent = stats.in_progress || 0;
                        document.getElementById('reviewCount').textContent = stats.review || 0;
                        document.getElementById('doneCount').textContent = stats.done || 0;
                    } else {
                        // Show zeros if no data
                        document.getElementById('todoCount').textContent = '0';
                        document.getElementById('inProgressCount').textContent = '0';
                        document.getElementById('reviewCount').textContent = '0';
                        document.getElementById('doneCount').textContent = '0';
                    }
                })
                .catch(error => {
                    console.error('Error loading board data:', error);
                    // Show zeros on error
                    document.getElementById('todoCount').textContent = '0';
                    document.getElementById('inProgressCount').textContent = '0';
                    document.getElementById('reviewCount').textContent = '0';
                    document.getElementById('doneCount').textContent = '0';
                });
        }

        function displayTeamOverview(members) {
            const teamOverview = document.getElementById('teamOverview');

            if (!members || members.length === 0) {
                teamOverview.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">No team members</p>
                        <small>Add members to start collaborating</small>
                    </div>
                `;
                return;
            }

            const totalMembers = members.length;
            const roleStats = members.reduce((acc, member) => {
                acc[member.role] = (acc[member.role] || 0) + 1;
                return acc;
            }, {});

            teamOverview.innerHTML = `
                <div class="info-item">
                    <span class="info-label">Total Members</span>
                    <span class="info-value"><strong>${totalMembers}</strong></span>
                </div>
                ${Object.entries(roleStats).map(([role, count]) => `
                    <div class="info-item">
                        <span class="info-label">${role}</span>
                        <span class="info-value">${count}</span>
                    </div>
                `).join('')}
            `;
        }

        function displayTeamMembersList(members) {
            const teamMembersList = document.getElementById('teamMembersList');

            if (!members || members.length === 0) {
                teamMembersList.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-people" style="font-size: 3rem;"></i>
                        <h6 class="mt-3">No Team Members</h6>
                        <p class="mb-3">This project doesn't have any team members yet.</p>
                        <button class="btn btn-primary btn-sm" onclick="addTeamMember()">
                            <i class="bi bi-person-plus me-1"></i>Add First Member
                        </button>
                    </div>
                `;
                return;
            }

            teamMembersList.innerHTML = `
                <div class="row g-3">
                    ${members.map(member => {
                        const initials = member.user.full_name.split(' ').map(n => n[0]).join('').toUpperCase();
                        const joinedDate = new Date(member.joined_at).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        });

                        return `
                            <div class="col-md-6 col-lg-4">
                                <div class="team-member-card">
                                    <div class="d-flex align-items-center">
                                        <div class="member-avatar me-3">
                                            ${initials}
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">${member.user.full_name}</h6>
                                            <p class="text-muted mb-1 small">${member.role}</p>
                                            <small class="text-muted">Joined ${joinedDate}</small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewMemberDetails(${member.user.user_id})">
                                                    <i class="bi bi-eye me-2"></i>View Profile
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="changeMemberRole(${member.member_id})">
                                                    <i class="bi bi-pencil me-2"></i>Change Role
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="removeMember(${member.member_id}, '${member.user.full_name}')">
                                                    <i class="bi bi-person-dash me-2"></i>Remove
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
        }

        function addTeamMember(projectId) {
            alert('Add team member functionality will be implemented soon');
        }

        function viewMemberDetails(userId) {
            // Reuse existing viewUserDetail function
            viewUserDetail(userId);
        }

        function changeMemberRole(memberId) {
            alert('Change member role functionality will be implemented soon');
        }

        function removeMember(memberId, memberName) {
            if (confirm(`Are you sure you want to remove ${memberName} from this project?`)) {
                alert('Remove member functionality will be implemented soon');
            }
        }

        function editProject(projectId) {
            alert('Edit project functionality will be implemented soon');
        }

        function deleteProject(projectId, projectName) {
            if (confirm(`Are you sure you want to delete project "${projectName}"?`)) {
                fetch(`/api/projects/${projectId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                       document.querySelector('input[name="_token"]')?.value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Project deleted successfully!');
                        loadProjectsList(); // Refresh project list
                        loadProjectStatistics(); // Refresh statistics
                    } else {
                        alert('Failed to delete project: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error deleting project:', error);
                    alert('Network error. Please try again.');
                });
            }
        }

        // User Management Functions Implementation
        function loadUserStatistics() {
            fetch('/api/users/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const stats = data.data;
                        document.getElementById('totalUsers').textContent = stats.total_users || 0;
                        document.getElementById('activeUsers').textContent = stats.active_users || 0;
                        document.getElementById('recentUsers').textContent = stats.recent_users || 0;
                        document.getElementById('adminUsers').textContent = stats.role_stats?.Project_Admin || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading user statistics:', error);
                });
        }

        function loadUsersList() {
            fetch('/api/users')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayUsers(data.data);
                        loadUserStatistics(); // Also refresh statistics
                    }
                })
                .catch(error => {
                    console.error('Error loading users:', error);
                });
        }

        function displayUsers(users) {
            const usersTableBody = document.getElementById('usersTableBody');
            const noResults = document.getElementById('noUsersResults');

            if (users.length === 0) {
                usersTableBody.innerHTML = '';
                noResults.style.display = 'block';
                return;
            }

            noResults.style.display = 'none';

            usersTableBody.innerHTML = users.map(user => {
                const initials = user.full_name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
                const roleClass = `role-${user.role.toLowerCase().replace('_', '')}`;
                const statusClass = `status-${user.current_task_status}`;

                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">${initials}</div>
                                <div>
                                    <div class="fw-semibold">${user.full_name}</div>
                                    <small class="text-muted">@${user.username}</small>
                                </div>
                            </div>
                        </td>
                        <td>${user.email}</td>
                        <td>
                            <span class="role-badge ${roleClass}">${user.role.replace('_', ' ')}</span>
                        </td>
                        <td>
                            <span class="status-badge ${statusClass}">${user.current_task_status}</span>
                        </td>
                        <td>
                            <small class="text-muted">${new Date(user.created_at).toLocaleDateString()}</small>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-action btn-view" onclick="viewUserDetail(${user.user_id})" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-action btn-edit" onclick="editUser(${user.user_id})" title="Edit User">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-action btn-delete" onclick="deleteUser(${user.user_id}, '${user.full_name.replace(/'/g, '\\\'')}')" title="Delete User">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function viewUserDetail(userId) {
            const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
            const content = document.getElementById('userDetailContent');

            // Show loading
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading user details...</p>
                </div>
            `;

            modal.show();

            // Fetch user details
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;
                        const initials = user.full_name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
                        const roleClass = `role-${user.role.toLowerCase().replace('_', '')}`;
                        const statusClass = `status-${user.current_task_status}`;

                        content.innerHTML = `
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 1.5rem;">${initials}</div>
                                    <h5>${user.full_name}</h5>
                                    <p class="text-muted">@${user.username}</p>
                                    <span class="role-badge ${roleClass}">${user.role.replace('_', ' ')}</span>
                                </div>
                                <div class="col-md-8">
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label class="form-label fw-bold">Email:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="text-muted">${user.email}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label class="form-label fw-bold">Role:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="role-badge ${roleClass}">${user.role.replace('_', ' ')}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label class="form-label fw-bold">Status:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <span class="status-badge ${statusClass}">${user.current_task_status}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label class="form-label fw-bold">Joined:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="text-muted">${new Date(user.created_at).toLocaleDateString()}</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <label class="form-label fw-bold">Last Updated:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <p class="text-muted">${new Date(user.updated_at).toLocaleDateString()}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Store user ID for edit function
                        document.getElementById('userDetailModal').dataset.userId = userId;
                    } else {
                        content.innerHTML = `
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Error Loading User</h5>
                                <p class="text-muted">${data.message || 'Unable to load user details'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading user details:', error);
                    content.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-wifi-off text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Network Error</h5>
                            <p class="text-muted">Unable to connect to server</p>
                        </div>
                    `;
                });
        }

        function editUser(userId) {
            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));

            // Fetch user data
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;

                        // Populate form
                        document.getElementById('editUserId').value = user.user_id;
                        document.getElementById('editUsername').value = user.username;
                        document.getElementById('editFullName').value = user.full_name;
                        document.getElementById('editEmail').value = user.email;
                        document.getElementById('editRole').value = user.role;
                        document.getElementById('editStatus').value = user.current_task_status;
                        document.getElementById('editPassword').value = ''; // Clear password field

                        // Clear any previous errors
                        clearEditUserFormErrors();
                        hideEditUserFormAlert();

                        modal.show();
                    } else {
                        showCreateUserFormAlert('danger', data.message || 'Unable to load user data');
                    }
                })
                .catch(error => {
                    console.error('Error loading user:', error);
                    showCreateUserFormAlert('danger', 'Network error. Please try again.');
                });
        }

        function editUserFromDetail() {
            const userId = document.getElementById('userDetailModal').dataset.userId;
            if (userId) {
                bootstrap.Modal.getInstance(document.getElementById('userDetailModal')).hide();
                setTimeout(() => editUser(userId), 300);
            }
        }

        function deleteUser(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserModal').dataset.userId = userId;

            const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            modal.show();
        }

        function confirmDeleteUser() {
            const userId = document.getElementById('deleteUserModal').dataset.userId;
            const deleteBtn = document.getElementById('confirmDeleteUserBtn');
            const btnText = deleteBtn.querySelector('.btn-text');
            const btnLoading = deleteBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            deleteBtn.disabled = true;

            fetch(`/api/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide modal
                    bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();

                    // Show success message
                    showCreateUserFormAlert('success', 'User deleted successfully!');

                    // Refresh user list
                    loadUsersList();
                } else {
                    showCreateUserFormAlert('danger', data.message || 'Failed to delete user');
                }
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                showCreateUserFormAlert('danger', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                deleteBtn.disabled = false;
            });
        }

        // Form handling functions
        function handleCreateUser(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('createUserSubmitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Clear previous errors
            clearCreateUserFormErrors();

            fetch('/api/users', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showCreateUserFormAlert('success', data.message || 'User created successfully!');

                    // Reset form and close modal after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('createUserModal')).hide();
                        loadUsersList(); // Refresh user list
                    }, 1500);
                } else {
                    // Show error message
                    showCreateUserFormAlert('danger', data.message || 'Failed to create user');

                    // Show validation errors
                    if (data.errors) {
                        showCreateUserValidationErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error creating user:', error);
                showCreateUserFormAlert('danger', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            });
        }

        function handleEditUser(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const userId = formData.get('user_id');
            const submitBtn = document.getElementById('editUserSubmitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Clear previous errors
            clearEditUserFormErrors();

            fetch(`/api/users/${userId}`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showEditUserFormAlert('success', data.message || 'User updated successfully!');

                    // Close modal after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        loadUsersList(); // Refresh user list
                    }, 1500);
                } else {
                    // Show error message
                    showEditUserFormAlert('danger', data.message || 'Failed to update user');

                    // Show validation errors
                    if (data.errors) {
                        showEditUserValidationErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error updating user:', error);
                showEditUserFormAlert('danger', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            });
        }

        // User filter functions
        function initializeUserFilters() {
            const searchInput = document.getElementById('searchUser');
            const roleFilter = document.getElementById('filterByRole');
            const statusFilter = document.getElementById('filterByStatus');

            if (searchInput) {
                searchInput.addEventListener('input', filterUsers);
            }
            if (roleFilter) {
                roleFilter.addEventListener('change', filterUsers);
            }
            if (statusFilter) {
                statusFilter.addEventListener('change', filterUsers);
            }
        }

        function filterUsers() {
            const searchTerm = document.getElementById('searchUser').value.toLowerCase();
            const roleFilter = document.getElementById('filterByRole').value;
            const statusFilter = document.getElementById('filterByStatus').value;

            // Build query parameters
            const params = new URLSearchParams();
            if (searchTerm) params.append('search', searchTerm);
            if (roleFilter) params.append('role', roleFilter);
            if (statusFilter) params.append('status', statusFilter);

            // Fetch filtered users
            fetch(`/api/users?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayUsers(data.data);
                    }
                })
                .catch(error => {
                    console.error('Error filtering users:', error);
                });
        }

        function clearUserFilters() {
            document.getElementById('searchUser').value = '';
            document.getElementById('filterByRole').value = '';
            document.getElementById('filterByStatus').value = '';
            loadUsersList();
        }

        // Form utility functions
        function showCreateUserFormAlert(type, message) {
            const alertDiv = document.getElementById('createUserFormAlert');
            if (alertDiv) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.classList.remove('d-none');
            }
        }

        function hideCreateUserFormAlert() {
            const alertDiv = document.getElementById('createUserFormAlert');
            if (alertDiv) {
                alertDiv.classList.add('d-none');
            }
        }

        function clearCreateUserFormErrors() {
            document.querySelectorAll('#createUserModal .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('#createUserModal .invalid-feedback').forEach(el => {
                el.textContent = '';
            });
        }

        function showCreateUserValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`#createUserModal [name="${field}"]`);
                const feedback = input?.nextElementSibling;

                if (input && feedback && feedback.classList.contains('invalid-feedback')) {
                    input.classList.add('is-invalid');
                    feedback.textContent = errors[field][0];
                }
            });
        }

        function showEditUserFormAlert(type, message) {
            const alertDiv = document.getElementById('editUserFormAlert');
            if (alertDiv) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.classList.remove('d-none');
            }
        }

        function hideEditUserFormAlert() {
            const alertDiv = document.getElementById('editUserFormAlert');
            if (alertDiv) {
                alertDiv.classList.add('d-none');
            }
        }

        function clearEditUserFormErrors() {
            document.querySelectorAll('#editUserModal .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('#editUserModal .invalid-feedback').forEach(el => {
                el.textContent = '';
            });
        }

        function showEditUserValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`#editUserModal [name="${field}"]`);
                const feedback = input?.nextElementSibling;

                if (input && feedback && feedback.classList.contains('invalid-feedback')) {
                    input.classList.add('is-invalid');
                    feedback.textContent = errors[field][0];
                }
            });
        }

        // User Management Functions
        function initializeUserManagement() {
            // Load user statistics when users tab is opened
            const usersTab = document.querySelector('a[href="#users"]');
            if (usersTab) {
                usersTab.addEventListener('click', function() {
                    loadUserStatistics();
                    loadUsersList(); // Also load actual users
                });
            }

            // Handle create user form submission
            const createUserForm = document.getElementById('createUserForm');
            if (createUserForm) {
                createUserForm.addEventListener('submit', handleCreateUser);
            }

            // Handle edit user form submission
            const editUserForm = document.getElementById('editUserForm');
            if (editUserForm) {
                editUserForm.addEventListener('submit', handleEditUser);
            }

            // Reset forms when modals are closed
            const createUserModal = document.getElementById('createUserModal');
            if (createUserModal) {
                createUserModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('createUserForm').reset();
                    clearCreateUserFormErrors();
                    hideCreateUserFormAlert();
                });
            }

            const editUserModal = document.getElementById('editUserModal');
            if (editUserModal) {
                editUserModal.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('editUserForm').reset();
                    clearEditUserFormErrors();
                    hideEditUserFormAlert();
                });
            }

            // Initialize user filters
            initializeUserFilters();
        }

        function initializeUserFilters() {
            const searchInput = document.getElementById('searchUser');
            const roleFilter = document.getElementById('filterByRole');
            const statusFilter = document.getElementById('filterByStatus');

            if (searchInput) {
                searchInput.addEventListener('input', filterUsers);
            }
            if (roleFilter) {
                roleFilter.addEventListener('change', filterUsers);
            }
            if (statusFilter) {
                statusFilter.addEventListener('change', filterUsers);
            }
        }

        function loadUserStatistics() {
            fetch('/api/users/stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const stats = data.data;
                        document.getElementById('totalUsers').textContent = stats.total_users || 0;
                        document.getElementById('activeUsers').textContent = stats.active_users || 0;
                        document.getElementById('recentUsers').textContent = stats.recent_users || 0;

                        // Calculate admin users from role stats
                        const adminCount = (stats.role_stats && stats.role_stats.Project_Admin) || 0;
                        document.getElementById('adminUsers').textContent = adminCount;
                    }
                })
                .catch(error => {
                    console.error('Error loading user statistics:', error);
                });
        }

        function loadUsersList() {
            const tableBody = document.getElementById('usersTableBody');

            // Show loading
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading users...</p>
                    </td>
                </tr>
            `;

            fetch('/api/users')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayUsers(data.data);
                        loadUserStatistics(); // Also refresh statistics
                    } else {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                                    <p class="mt-2 text-muted">Error loading users</p>
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading users:', error);
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-wifi-off text-danger" style="font-size: 2rem;"></i>
                                <p class="mt-2 text-muted">Network error</p>
                            </td>
                        </tr>
                    `;
                });
        }

        function displayUsers(users) {
            const tableBody = document.getElementById('usersTableBody');
            const noResults = document.getElementById('noUsersResults');

            if (users.length === 0) {
                tableBody.innerHTML = '';
                noResults.style.display = 'block';
                return;
            }

            noResults.style.display = 'none';

            tableBody.innerHTML = users.map(user => {
                const initials = user.full_name.split(' ').map(n => n[0]).join('').toUpperCase();
                const roleClass = `role-${user.role.toLowerCase().replace('_', '-')}`;
                const statusClass = `status-${user.current_task_status}`;

                return `
                    <tr data-user-id="${user.user_id}" data-role="${user.role}" data-status="${user.current_task_status}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">${initials}</div>
                                <div>
                                    <div class="fw-bold">${user.full_name}</div>
                                    <small class="text-muted">@${user.username}</small>
                                </div>
                            </div>
                        </td>
                        <td>${user.email}</td>
                        <td>
                            <span class="role-badge ${roleClass}">${user.role.replace('_', ' ')}</span>
                        </td>
                        <td>
                            <span class="status-badge ${statusClass}">${user.current_task_status}</span>
                        </td>
                        <td>
                            <small class="text-muted">${new Date(user.created_at).toLocaleDateString()}</small>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-primary me-1" onclick="viewUserDetail(${user.user_id})" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning me-1" onclick="editUser(${user.user_id})" title="Edit User">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.user_id}, '${user.full_name.replace(/'/g, '\\\'')}')" title="Delete User">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterUsers() {
            const searchTerm = document.getElementById('searchUser').value.toLowerCase();
            const roleFilter = document.getElementById('filterByRole').value;
            const statusFilter = document.getElementById('filterByStatus').value;
            const rows = document.querySelectorAll('#usersTableBody tr[data-user-id]');

            let visibleCount = 0;

            rows.forEach(row => {
                const nameCell = row.cells[0].textContent.toLowerCase();
                const emailCell = row.cells[1].textContent.toLowerCase();
                const userRole = row.dataset.role;
                const userStatus = row.dataset.status;

                let isVisible = true;

                // Filter by search term
                if (searchTerm && !nameCell.includes(searchTerm) && !emailCell.includes(searchTerm)) {
                    isVisible = false;
                }

                // Filter by role
                if (roleFilter && userRole !== roleFilter) {
                    isVisible = false;
                }

                // Filter by status
                if (statusFilter && userStatus !== statusFilter) {
                    isVisible = false;
                }

                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            // Show/hide no results message
            const noResults = document.getElementById('noUsersResults');
            const tableContainer = document.querySelector('#usersTableBody').closest('.table-responsive');

            if (visibleCount === 0 && rows.length > 0) {
                tableContainer.style.display = 'none';
                noResults.style.display = 'block';
            } else {
                tableContainer.style.display = '';
                noResults.style.display = 'none';
            }
        }

        function clearUserFilters() {
            document.getElementById('searchUser').value = '';
            document.getElementById('filterByRole').value = '';
            document.getElementById('filterByStatus').value = '';
            filterUsers();
        }

        // User Action Functions
        function viewUserDetail(userId) {
            const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
            const content = document.getElementById('userDetailContent');

            // Show loading
            content.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading user details...</p>
                </div>
            `;

            modal.show();

            // Fetch user details
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;
                        const initials = user.full_name.split(' ').map(n => n[0]).join('').toUpperCase();

                        content.innerHTML = `
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 1.5rem;">
                                        ${initials}
                                    </div>
                                    <h5>${user.full_name}</h5>
                                    <p class="text-muted">@${user.username}</p>
                                    <span class="role-badge role-${user.role.toLowerCase().replace('_', '-')}">${user.role.replace('_', ' ')}</span>
                                </div>
                                <div class="col-md-8">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Email:</strong></div>
                                        <div class="col-sm-8">${user.email}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Status:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="status-badge status-${user.current_task_status}">${user.current_task_status}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Joined:</strong></div>
                                        <div class="col-sm-8">${new Date(user.created_at).toLocaleDateString()}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Last Updated:</strong></div>
                                        <div class="col-sm-8">${new Date(user.updated_at).toLocaleDateString()}</div>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Store user ID for edit function
                        document.getElementById('userDetailModal').dataset.userId = userId;
                    } else {
                        content.innerHTML = `
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">Error Loading User</h5>
                                <p class="text-muted">${data.message || 'Unable to load user details'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading user details:', error);
                    content.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-wifi-off text-danger" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Network Error</h5>
                            <p class="text-muted">Unable to connect to server</p>
                        </div>
                    `;
                });
        }

        function editUser(userId) {
            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));

            // Fetch user data
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;

                        // Populate form
                        document.getElementById('editUserId').value = user.user_id;
                        document.getElementById('editUsername').value = user.username;
                        document.getElementById('editFullName').value = user.full_name;
                        document.getElementById('editEmail').value = user.email;
                        document.getElementById('editPassword').value = '';
                        document.getElementById('editRole').value = user.role;
                        document.getElementById('editStatus').value = user.current_task_status;

                        // Clear any previous errors
                        clearEditUserFormErrors();
                        hideEditUserFormAlert();

                        modal.show();
                    } else {
                        showCreateUserFormAlert('danger', data.message || 'Unable to load user data');
                    }
                })
                .catch(error => {
                    console.error('Error loading user:', error);
                    showCreateUserFormAlert('danger', 'Network error. Please try again.');
                });
        }

        function editUserFromDetail() {
            const userId = document.getElementById('userDetailModal').dataset.userId;
            if (userId) {
                bootstrap.Modal.getInstance(document.getElementById('userDetailModal')).hide();
                setTimeout(() => editUser(userId), 300);
            }
        }

        function deleteUser(userId, userName) {
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserModal').dataset.userId = userId;

            const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            modal.show();
        }

        function confirmDeleteUser() {
            const userId = document.getElementById('deleteUserModal').dataset.userId;
            const deleteBtn = document.getElementById('confirmDeleteUserBtn');
            const btnText = deleteBtn.querySelector('.btn-text');
            const btnLoading = deleteBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            deleteBtn.disabled = true;

            fetch(`/api/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide modal
                    bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();

                    // Show success message
                    showCreateUserFormAlert('success', 'User deleted successfully!');

                    // Refresh user list
                    loadUsersList();
                } else {
                    showCreateUserFormAlert('danger', data.message || 'Failed to delete user');
                }
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                showCreateUserFormAlert('danger', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                deleteBtn.disabled = false;
            });
        }

        // Form handling functions
        function handleCreateUser(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('createUserSubmitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Clear previous errors
            clearCreateUserFormErrors();

            fetch('/api/users', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showCreateUserFormAlert('success', data.message || 'User created successfully!');

                    // Reset form and close modal after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('createUserModal')).hide();
                        loadUsersList(); // Refresh user list
                    }, 1500);
                } else {
                    // Show error message
                    showCreateUserFormAlert('danger', data.message || 'Failed to create user');

                    // Show validation errors
                    if (data.errors) {
                        showCreateUserValidationErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error creating user:', error);
                showCreateUserFormAlert('danger', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            });
        }

        function handleEditUser(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const userId = formData.get('user_id');
            const submitBtn = document.getElementById('editUserSubmitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Clear previous errors
            clearEditUserFormErrors();

            fetch(`/api/users/${userId}`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showEditUserFormAlert('success', data.message || 'User updated successfully!');

                    // Close modal after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        loadUsersList(); // Refresh user list
                    }, 1500);
                } else {
                    // Show error message
                    showEditUserFormAlert('danger', data.message || 'Failed to update user');

                    // Show validation errors
                    if (data.errors) {
                        showEditUserValidationErrors(data.errors);
                    }
                }
            })
            .catch(error => {
                console.error('Error updating user:', error);
                showEditUserFormAlert('danger', 'Network error. Please try again.');
            })
            .finally(() => {
                // Reset button state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            });
        }

        // Utility functions for user forms
        function showCreateUserFormAlert(type, message) {
            const alertDiv = document.getElementById('createUserFormAlert');
            if (alertDiv) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.classList.remove('d-none');
            }
        }

        function hideCreateUserFormAlert() {
            const alertDiv = document.getElementById('createUserFormAlert');
            if (alertDiv) {
                alertDiv.classList.add('d-none');
            }
        }

        function clearCreateUserFormErrors() {
            document.querySelectorAll('#createUserModal .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('#createUserModal .invalid-feedback').forEach(el => {
                el.textContent = '';
            });
        }

        function showCreateUserValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`#createUserModal [name="${field}"]`);
                const feedback = input?.nextElementSibling?.classList.contains('invalid-feedback') ?
                                input.nextElementSibling : input?.parentNode.querySelector('.invalid-feedback');

                if (input && feedback) {
                    input.classList.add('is-invalid');
                    feedback.textContent = errors[field][0];
                }
            });
        }

        function showEditUserFormAlert(type, message) {
            const alertDiv = document.getElementById('editUserFormAlert');
            if (alertDiv) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.classList.remove('d-none');
            }
        }

        function hideEditUserFormAlert() {
            const alertDiv = document.getElementById('editUserFormAlert');
            if (alertDiv) {
                alertDiv.classList.add('d-none');
            }
        }

        function clearEditUserFormErrors() {
            document.querySelectorAll('#editUserModal .is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('#editUserModal .invalid-feedback').forEach(el => {
                el.textContent = '';
            });
        }

        function showEditUserValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`#editUserModal [name="${field}"]`);
                const feedback = input?.nextElementSibling?.classList.contains('invalid-feedback') ?
                                input.nextElementSibling : input?.parentNode.querySelector('.invalid-feedback');

                if (input && feedback) {
                    input.classList.add('is-invalid');
                    feedback.textContent = errors[field][0];
                }
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.getElementById('theme-icon');

            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                themeIcon.className = 'bi bi-sun-fill';
            } else {
                themeIcon.className = 'bi bi-moon-fill';
            }

            // Initialize all modules
            initializeProjectFilters();
            initializeProjectManagement();
            initializeUserManagement();
        });
    </script>
</body>
</html>
