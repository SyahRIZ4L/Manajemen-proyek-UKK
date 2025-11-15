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
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
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
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Custom scrollbar for sidebar navigation */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        .nav-item {
            margin: 0 15px 12px 15px;
        }
        .nav-item:last-child {
            margin-bottom: 20px;
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
            padding: 20px 15px;
            flex-shrink: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
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
            overflow: hidden;
            position: relative;
        }
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-placeholder i {
            font-size: 1.8rem;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
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

        /* Dropdown Menu Styles */
        .dropdown-menu {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            margin-top: 8px;
        }
        .dropdown-item {
            padding: 8px 16px;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 4px;
            color: #333;
        }
        .dropdown-item:hover {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            transform: translateX(4px);
        }
        .dropdown-item i {
            width: 20px;
            margin-right: 8px;
        }
        .dropdown-divider {
            margin: 8px 0;
            border-color: rgba(0, 0, 0, 0.1);
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
        .text-purple {
            color: #6f42c1 !important;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        /* Team Members Styling */
        .team-members-section {
            margin-top: 15px;
        }

        .team-member-item {
            padding: 8px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.02);
            transition: background-color 0.2s ease;
        }

        .team-member-item:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .member-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .member-info {
            min-width: 0;
        }

        .member-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .member-role {
            font-size: 0.7rem;
            color: #666;
            text-transform: capitalize;
        }

        .dark-theme .member-name {
            color: #e0e0e0;
        }

        .dark-theme .member-role {
            color: #aaa;
        }

        .dark-theme .team-member-item {
            background: rgba(255, 255, 255, 0.05);
        }

        .dark-theme .team-member-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .team-member-more {
            border-top: 1px solid #eee;
            margin-top: 8px;
        }

        .dark-theme .team-member-more {
            border-top-color: #444;
        }

        .role-summary {
            border-top: 1px solid #eee;
            font-style: italic;
        }

        .dark-theme .role-summary {
            border-top-color: #444;
        }

        .role-summary small {
            line-height: 1.2;
        }
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

        .board-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .board-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .stat-box {
            padding: 8px 4px;
        }
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
        }
        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .task-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card-item {
            transition: all 0.3s ease;
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
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
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

        .project-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            margin-top: 1rem;
        }

        .project-actions .btn {
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }

        .project-actions .btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        .project-actions .btn-success:hover {
            background: #198754;
            border-color: #198754;
        }

        .project-actions .btn-warning:hover {
            background: #fd7e14;
            border-color: #fd7e14;
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

        /* Reports Styles */
        .report-stat-card {
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
        .report-stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #667eea;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        /* Dark theme for reports */
        body.dark-theme .report-stat-card {
            background: #333333;
            border-color: #444444;
            color: #e0e0e0;
        }

        body.dark-theme .chart-container {
            background: #2d2d2d !important;
            color: #e0e0e0;
        }

        body.dark-theme .nav-tabs .nav-link {
            color: #a0a0a0;
        }

        body.dark-theme .nav-tabs .nav-link:hover {
            color: #667eea;
        }

        body.dark-theme .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.1);
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
            background-color: rgba(102, 126, 234, 0.05);
            border-left: 3px solid #667eea;
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
            background-color: rgba(102, 126, 234, 0.1);
        }

        body.dark-theme .notification-message {
            color: #a0a0a0;
        }

        body.dark-theme .notification-time {
            color: #707070;
        }

        /* New Team Lead UI Styles */
        .project-overview-card {
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .project-overview-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .progress-custom {
            height: 10px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-custom .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .detail-label {
            font-weight: 600;
            margin-left: 8px;
            margin-right: 8px;
            color: #495057;
        }

        .detail-value {
            color: #6c757d;
        }

        .stat-card {
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
        }

        .quick-actions-card {
            border-radius: 15px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #007bff, #6c757d);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
            padding-left: 25px;
        }

        .timeline-marker {
            position: absolute;
            left: -25px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .timeline-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 8px;
        }

        .timeline-title {
            font-weight: 600;
            color: #495057;
            margin: 0;
            font-size: 0.95rem;
        }

        .timeline-description {
            color: #6c757d;
            margin: 0;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .activity-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .activity-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
            font-size: 0.8rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            margin: 0 0 4px 0;
            font-size: 0.85rem;
            line-height: 1.4;
            color: #495057;
        }

        .timeline-card, .recent-activities-card {
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .timeline-card:hover, .recent-activities-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        /* Enhanced Assignment Styles */
        .form-select optgroup {
            font-weight: bold;
            color: #6c757d;
            background: #f8f9fa;
            padding: 8px 12px;
        }

        .form-select option {
            padding: 8px 12px;
            font-weight: normal;
        }

        .modal-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .modal-content {
            border-radius: 0.375rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        #assignmentPreview {
            margin-top: 15px;
            border-radius: 8px;
            border: 2px solid;
            transition: all 0.3s ease;
        }

        #assignmentPreview.alert-success {
            border-color: #d4edda;
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
        }

        #assignmentPreview.alert-warning {
            border-color: #fff3cd;
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        }

        #assignmentPreview.alert-danger {
            border-color: #f8d7da;
            background: linear-gradient(135deg, #f8d7da, #f1c0c7);
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

            .timeline {
                padding-left: 20px;
            }

            .timeline-marker {
                left: -20px;
            }

            .activity-avatar {
                width: 30px;
                height: 30px;
                font-size: 0.7rem;
            }
        }

        /* Enhanced Modal Styles */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header.bg-gradient {
            position: relative;
            overflow: hidden;
        }

        .modal-header.bg-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .input-group-text {
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-group-text,
        .form-select:focus + .input-group-text {
            background-color: #667eea !important;
            color: white;
            border-color: #667eea;
        }

        .form-control,
        .form-select {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .form-control:focus,
        .form-select:focus {
            border-left-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        #cardTitle {
            font-weight: 500;
            font-size: 1.1rem;
        }

        #titleCharCount {
            transition: color 0.3s ease;
        }

        #titleCharCount.warning {
            color: #ffc107 !important;
        }

        #titleCharCount.danger {
            color: #dc3545 !important;
            font-weight: bold;
        }

        #assignmentPreview {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #boardProjectInfo {
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .form-select option[data-color="success"] {
            background-color: #d4edda;
        }

        .form-select option[data-color="warning"] {
            background-color: #fff3cd;
        }

        .form-select option[data-color="danger"] {
            background-color: #f8d7da;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        /* Enhanced Modal Scrolling */
        .modal-dialog-scrollable {
            max-height: calc(100vh - 3rem);
        }

        .modal-dialog-scrollable .modal-content {
            max-height: calc(100vh - 3rem);
            overflow: hidden;
        }

        .modal-dialog-scrollable .modal-body {
            overflow-y: auto;
            max-height: calc(100vh - 200px);
        }

        /* Responsive Modal Heights */
        @media (max-height: 768px) {
            .modal-dialog-scrollable {
                max-height: calc(100vh - 1rem);
            }

            .modal-dialog-scrollable .modal-content {
                max-height: calc(100vh - 1rem);
            }

            .modal-dialog-scrollable .modal-body {
                max-height: calc(100vh - 150px);
            }
        }

        @media (max-height: 600px) {
            .modal-dialog-scrollable .modal-body {
                max-height: calc(100vh - 120px);
            }
        }

        .badge.bg-white {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }



        /* Input Group Focus Effects */
        .input-group.focused .input-group-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border-color: #667eea !important;
            transform: scale(1.05);
        }

        .input-group {
            transition: all 0.3s ease;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced Alert Styles */
        .alert {
            border-radius: 12px;
            border: none;
        }

        /* Card Hover in Modal */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
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
            <i class="bi bi-people-fill mb-2" style="font-size: 2.5rem;"></i>
            <h4>Team Lead Panel</h4>
            <p>{{ auth()->user()->name ?? auth()->user()->username }}</p>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#dashboard" onclick="showContent('dashboard', this)">
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
                    <a class="nav-link" href="#projects" onclick="showContent('projects', this)">
                        <div class="nav-icon">
                            <i class="bi bi-kanban-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">My Project</span>
                            <small class="nav-subtitle">Current Project</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#my-cards" onclick="showContent('my-cards', this)">
                        <div class="nav-icon">
                            <i class="bi bi-card-checklist"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">My Cards</span>
                            <small class="nav-subtitle">Cards I Created</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#assigned-cards" onclick="showContent('assigned-cards', this)">
                        <div class="nav-icon">
                            <i class="bi bi-clipboard-check-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">Assigned Cards</span>
                            <small class="nav-subtitle">Team Assignments</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#assigned-history" onclick="showContent('assigned-history', this)">
                        <div class="nav-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">Assigned History</span>
                            <small class="nav-subtitle">Assignment Log</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#card-time-logs" onclick="showContent('card-time-logs', this)">
                        <div class="nav-icon">
                            <i class="bi bi-stopwatch-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">Card Time Logs</span>
                            <small class="nav-subtitle">Time Tracking</small>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Profile Section -->
        <div class="sidebar-profile" style="position: absolute; bottom: 20px; left: 15px; right: 15px;">
            <div class="dropdown">
                <div class="profile-info d-flex align-items-center p-3" data-bs-toggle="dropdown" style="cursor: pointer; border-radius: 12px; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="profile-avatar">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile Photo">
                        @else
                            <div class="avatar-placeholder">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif
                    </div>
                    <div class="profile-details flex-grow-1 ms-3">
                        <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                        <small class="d-flex align-items-center">
                            <span class="status-dot me-1"></span>
                            {{ ucfirst(auth()->user()->status ?? 'active') }}
                        </small>
                    </div>
                    <i class="bi bi-chevron-up text-white"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end w-100">
                    <li><a class="dropdown-item" href="#" onclick="showContent('profile', this)"><i class="bi bi-person-gear"></i>Profile Settings</a></li>
                    <li><a class="dropdown-item" href="#" onclick="toggleTheme()"><i class="bi bi-palette"></i>Theme</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 id="content-title" class="mb-0">Team Lead Dashboard</h2>
                    <p class="text-muted mb-0" id="content-subtitle">Welcome to your Team Leadership center</p>
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
            <div id="dashboard-content" class="content-section">
                <!-- Main Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-folder-check stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="my-projects-count">0</h3>
                            <p class="stat-label">My Project</p>
                            <small class="text-muted" id="active-projects-text">Dashboard</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-people-fill stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="team-members-count">0</h3>
                            <p class="stat-label">Team Members</p>
                            <small class="text-muted">Under my leadership</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-list-task stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="pending-tasks-count">0</h3>
                            <p class="stat-label">Pending Tasks</p>
                            <small class="text-muted">Needs attention</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-exclamation-triangle-fill stat-icon text-danger"></i>
                            <h3 class="stat-number text-danger" id="overdue-tasks-count">0</h3>
                            <p class="stat-label">Overdue Tasks</p>
                            <small class="text-muted">Requires action</small>
                        </div>
                    </div>
                </div>

                <!-- Task Progress Overview -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <i class="bi bi-play-circle stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="in-progress-tasks-count">0</h3>
                            <p class="stat-label">In Progress</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <i class="bi bi-check-circle-fill stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="completed-tasks-count">0</h3>
                            <p class="stat-label">Completed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card text-center">
                            <i class="bi bi-bar-chart-fill stat-icon text-purple"></i>
                            <h3 class="stat-number text-purple" id="total-tasks-count">0</h3>
                            <p class="stat-label">Total Tasks</p>
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
                <!-- My Project Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">My Project</h4>
                        <p class="text-muted mb-0">Project assigned to your leadership with detailed insights</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="refreshMyProject()">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Refresh
                        </button>
                        <button class="btn btn-primary" onclick="viewProjectDetails()">
                            <i class="bi bi-eye me-2"></i>
                            View Details
                        </button>
                    </div>
                </div>

                <!-- Project Overview Section -->
                <div class="row g-4 mb-5">
                    <div class="col-md-8">
                        <!-- Main Project Card -->
                        <div class="card project-overview-card shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1" id="current-project-name">No Project Assigned</h5>
                                        <p class="text-muted mb-0" id="current-project-description">You currently have no project assigned to your leadership.</p>
                                    </div>
                                    <span class="badge bg-primary" id="current-project-status">Not Assigned</span>
                                </div>

                                <div class="project-progress-section mb-3" id="project-progress-section" style="display: none;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">Overall Progress</small>
                                        <small class="text-muted" id="project-progress-percentage">0%</small>
                                    </div>
                                    <div class="progress progress-custom mb-3">
                                        <div class="progress-bar" id="project-progress-bar" style="width: 0%"></div>
                                    </div>
                                </div>

                                <div class="project-details-grid" id="project-details" style="display: none;">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                                <span class="detail-label">Start Date:</span>
                                                <span class="detail-value" id="project-start-date">-</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <i class="bi bi-calendar-check text-success me-2"></i>
                                                <span class="detail-label">End Date:</span>
                                                <span class="detail-value" id="project-end-date">-</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <i class="bi bi-people text-info me-2"></i>
                                                <span class="detail-label">Team Members:</span>
                                                <span class="detail-value" id="project-team-count">0</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <i class="bi bi-list-task text-warning me-2"></i>
                                                <span class="detail-label">Total Tasks:</span>
                                                <span class="detail-value" id="project-task-count">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Quick Stats -->
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="stat-card bg-gradient-success text-white">
                                    <div class="stat-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4 class="stat-number" id="completed-tasks">0</h4>
                                        <p class="stat-label mb-0">Completed Tasks</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="stat-card bg-gradient-warning text-white">
                                    <div class="stat-icon">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4 class="stat-number" id="pending-tasks">0</h4>
                                        <p class="stat-label mb-0">Pending Tasks</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="stat-card bg-gradient-info text-white">
                                    <div class="stat-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h4 class="stat-number" id="active-members">0</h4>
                                        <p class="stat-label mb-0">Active Members</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="card quick-actions-card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h6 class="card-title mb-3">
                            <i class="bi bi-lightning-charge text-warning me-2"></i>
                            Quick Actions
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" onclick="openCreateCardModal()" id="btn-create-card">
                                    <i class="bi bi-plus-lg me-2"></i>
                                    New Card
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-primary w-100" onclick="openBoardListModal()" id="btn-manage-boards">
                                    <i class="bi bi-kanban me-2"></i>
                                    Manage Boards
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-success w-100" onclick="viewTeamMembers()" id="btn-view-team" disabled>
                                    <i class="bi bi-people me-2"></i>
                                    View Team
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success w-100" onclick="showAddTeamMemberModal()" id="btn-add-member" disabled>
                                    <i class="bi bi-person-plus me-2"></i>
                                    Add Member
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-info w-100" onclick="viewProjectReports()" id="btn-view-reports" disabled>
                                    <i class="bi bi-graph-up me-2"></i>
                                    Reports
                                </button>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-warning w-100" onclick="manageProject()" id="btn-manage-project" disabled>
                                    <i class="bi bi-gear me-2"></i>
                                    Manage
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Activity Timeline -->
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card timeline-card shadow-sm border-0">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-clock-history text-primary me-2"></i>
                                    Project Timeline
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="project-timeline" class="timeline">
                                    <!-- Timeline items will be loaded dynamically -->
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <h6 class="timeline-title">Project Started</h6>
                                                <small class="text-muted">2 days ago</small>
                                            </div>
                                            <p class="timeline-description">Project has been officially started and team members have been assigned.</p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <h6 class="timeline-title">Initial Planning Completed</h6>
                                                <small class="text-muted">1 day ago</small>
                                            </div>
                                            <p class="timeline-description">Project planning phase completed with all requirements documented.</p>
                                        </div>
                                    </div>

                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-warning"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-header">
                                                <h6 class="timeline-title">Development Phase Started</h6>
                                                <small class="text-muted">6 hours ago</small>
                                            </div>
                                            <p class="timeline-description">Development team has started working on the core features.</p>
                                        </div>
                                    </div>

                                    <div id="no-timeline-data" class="text-center py-4" style="display: none;">
                                        <i class="bi bi-clock text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="text-muted">No timeline data available</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card recent-activities-card shadow-sm border-0">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-activity text-success me-2"></i>
                                    Recent Activities
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="recent-activities" class="activity-list">
                                    <!-- Activities will be loaded dynamically -->
                                    <div class="activity-item">
                                        <div class="activity-avatar bg-primary text-white">
                                            <i class="bi bi-person-check"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text">New task assigned to <strong>John Doe</strong></p>
                                            <small class="text-muted">2 minutes ago</small>
                                        </div>
                                    </div>

                                    <div class="activity-item">
                                        <div class="activity-avatar bg-success text-white">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text"><strong>Sarah Smith</strong> completed "Design Review"</p>
                                            <small class="text-muted">1 hour ago</small>
                                        </div>
                                    </div>

                                    <div class="activity-item">
                                        <div class="activity-avatar bg-warning text-white">
                                            <i class="bi bi-chat-dots"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text">New comment added to "Frontend Development"</p>
                                            <small class="text-muted">3 hours ago</small>
                                        </div>
                                    </div>

                                    <div class="activity-item">
                                        <div class="activity-avatar bg-info text-white">
                                            <i class="bi bi-upload"></i>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-text"><strong>Mike Johnson</strong> uploaded files to task</p>
                                            <small class="text-muted">5 hours ago</small>
                                        </div>
                                    </div>

                                    <div id="no-activities-data" class="text-center py-3" style="display: none;">
                                        <i class="bi bi-activity text-muted mb-2" style="font-size: 1.5rem;"></i>
                                        <p class="text-muted small">No recent activities</p>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <button class="btn btn-outline-primary btn-sm" onclick="viewAllActivities()">
                                        View All Activities
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boards Content -->
            <div id="boards-content" class="content-section" style="display: none;">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Boards Management</h2>
                        <p class="text-muted mb-0">Manage project boards and track task progress</p>
                    </div>
                </div>

                <!-- Boards Grid -->
                <div id="boards-grid" class="row">
                    <!-- Board cards will be loaded here dynamically -->
                </div>



                <!-- Empty State -->
                <div id="boards-empty" class="text-center py-5" style="display: none;">
                    <i class="bi bi-kanban text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">No Boards Found</h4>
                    <p class="text-muted">You don't have any boards assigned to your projects yet.</p>
                </div>
            </div>

            <!-- Cards Content -->
            <div id="cards-content" class="content-section" style="display: none;">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Cards Management</h2>
                        <p class="text-muted mb-0">Manage project tasks and track progress</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" onclick="openCreateCardModal()">
                            <i class="bi bi-plus-lg me-2"></i>
                            Create Card
                        </button>
                        <select id="cardStatusFilter" class="form-select" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            <option value="done">Done</option>
                        </select>
                        <select id="cardProjectFilter" class="form-select" style="width: auto;">
                            <option value="">All Projects</option>
                        </select>
                    </div>
                </div>

                <!-- Cards Statistics -->
                <div id="cards-statistics" class="row mb-4">
                    <!-- Statistics cards will be loaded here dynamically -->
                </div>

                <!-- Cards Grid -->
                <div id="cards-grid" class="row">
                    <!-- Card items will be loaded here dynamically -->
                </div>



                <!-- Empty State -->
                <div id="cards-empty" class="text-center py-5" style="display: none;">
                    <i class="bi bi-card-list text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">No Cards Found</h4>
                    <p class="text-muted">You don't have any cards assigned to your projects yet.</p>
                </div>
            </div>

            <!-- My Cards Content -->
            <div id="my-cards-content" class="content-section" style="display: none;">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">My Cards</h2>
                        <p class="text-muted mb-0">Cards created by you across all projects</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" onclick="openCreateCardModal()">
                            <i class="bi bi-plus-lg me-2"></i>
                            Create New Card
                        </button>
                        <button class="btn btn-outline-primary" onclick="refreshMyCards()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- My Cards Statistics -->
                <div class="row mb-4" id="my-cards-statistics">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-list-ul display-6"></i>
                                <h4 class="mt-2 mb-0" id="myCardsTodoCount">0</h4>
                                <small>To Do</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-play-circle display-6"></i>
                                <h4 class="mt-2 mb-0" id="myCardsInProgressCount">0</h4>
                                <small>In Progress</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-eye display-6"></i>
                                <h4 class="mt-2 mb-0" id="myCardsReviewCount">0</h4>
                                <small>In Review</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-check-circle display-6"></i>
                                <h4 class="mt-2 mb-0" id="myCardsDoneCount">0</h4>
                                <small>Done</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status Filter</label>
                                <select id="myCardsStatusFilter" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="todo">To Do</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="review">Review</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Project Filter</label>
                                <select id="myCardsProjectFilter" class="form-select">
                                    <option value="">All Projects</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Priority Filter</label>
                                <select id="myCardsPriorityFilter" class="form-select">
                                    <option value="">All Priorities</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary me-2" onclick="applyMyCardsFilters()">
                                    <i class="bi bi-funnel me-1"></i>Apply
                                </button>
                                <button class="btn btn-outline-secondary" onclick="clearMyCardsFilters()">
                                    <i class="bi bi-x-circle me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Cards Grid -->
                <div id="my-cards-grid" class="row">
                    <!-- My cards will be loaded here dynamically -->
                </div>

                <!-- Loading State -->
                <div id="my-cards-loading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading your cards...</p>
                </div>

                <!-- Empty State -->
                <div id="my-cards-empty" class="text-center py-5" style="display: none;">
                    <i class="bi bi-card-checklist text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">No Cards Found</h4>
                    <p class="text-muted">You haven't created any cards yet. Start by creating your first card!</p>
                    <button class="btn btn-primary mt-3" onclick="openCreateCardModal()">
                        <i class="bi bi-plus-lg me-2"></i>Create Your First Card
                    </button>
                </div>
            </div>

            <!-- Assigned Cards Content - Card Review System -->
            <div id="assigned-cards-content" class="content-section" style="display: none;">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Card Reviews</h2>
                        <p class="text-muted mb-0">Review and approve cards submitted by developers/designers</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="refreshPendingReviews()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Review Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-clock-history display-6"></i>
                                <h4 class="mt-2 mb-0" id="pendingReviewsCount">0</h4>
                                <small>Pending Reviews</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-check-circle display-6"></i>
                                <h4 class="mt-2 mb-0" id="approvedTodayCount">0</h4>
                                <small>Approved Today</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-x-circle display-6"></i>
                                <h4 class="mt-2 mb-0" id="rejectedTodayCount">0</h4>
                                <small>Rejected Today</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up display-6"></i>
                                <h4 class="mt-2 mb-0" id="avgReviewTime">0h</h4>
                                <small>Avg Review Time</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Reviews Grid -->
                <div id="pending-reviews-grid" class="row">
                    <!-- Review cards will be loaded here dynamically -->
                </div>



                <!-- Empty State -->
                <div id="reviews-empty" class="text-center py-5" style="display: none;">
                    <i class="bi bi-clipboard-check text-muted mb-3" style="font-size: 4rem;"></i>
                    <h4 class="text-muted">No Pending Reviews</h4>
                    <p class="text-muted">All submitted cards have been reviewed!</p>
                </div>
            </div>

            <!-- Assigned History Content -->
            <div id="assigned-history-content" class="content-section" style="display: none;">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Assignment History</h4>
                        <p class="text-muted mb-0">Track assignment progress and history</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="exportAssignmentHistory()">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                        <button class="btn btn-primary" onclick="refreshAssignmentHistory()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="row mb-4" id="assignment-summary-stats">
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-clipboard-data stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="totalAssignments">0</h3>
                            <p class="stat-label">Total Assignments</p>
                            <small class="text-muted">All time</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-clipboard-plus stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="assignedCount">0</h3>
                            <p class="stat-label">Assigned</p>
                            <small class="text-muted">New assignments</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-play-circle stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="inProgressCount">0</h3>
                            <p class="stat-label">In Progress</p>
                            <small class="text-muted">Active work</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-check-circle stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="completedCount">0</h3>
                            <p class="stat-label">Completed</p>
                            <small class="text-muted">Finished tasks</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-clock stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="avgCompletionTime">0h</h3>
                            <p class="stat-label">Avg Time</p>
                            <small class="text-muted">To completion</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-exclamation-triangle stat-icon text-danger"></i>
                            <h3 class="stat-number text-danger" id="overdueCount">0</h3>
                            <p class="stat-label">Overdue</p>
                            <small class="text-muted">Past deadline</small>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filter-section mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="historyStatusFilter">
                                <option value="all">All Status</option>
                                <option value="assigned">Assigned</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Team Member</label>
                            <select class="form-select" id="historyUserFilter">
                                <option value="">All Members</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Project</label>
                            <select class="form-select" id="historyProjectFilter">
                                <option value="">All Projects</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="historyDateFrom" placeholder="From">
                                <input type="date" class="form-control" id="historyDateTo" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-primary me-2" onclick="applyAssignmentHistoryFilters()">
                                <i class="bi bi-funnel me-1"></i>Apply Filters
                            </button>
                            <button class="btn btn-outline-secondary" onclick="clearAssignmentHistoryFilters()">
                                <i class="bi bi-x-circle me-1"></i>Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Assignment History Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Assigned To</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Assigned Date</th>
                                <th>Duration</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assignmentHistoryTableBody">
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading assignment history...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        <span id="assignmentHistoryInfo">Showing 0 of 0 assignments</span>
                    </div>
                    <nav aria-label="Assignment history pagination">
                        <ul class="pagination pagination-sm mb-0" id="assignmentHistoryPagination">
                            <!-- Pagination will be generated here -->
                        </ul>
                    </nav>
                </div>

                <!-- No Results Message -->
                <div id="no-assignment-history" class="text-center py-5" style="display: none;">
                    <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Assignment History</h5>
                    <p class="text-muted">No assignment history found for the selected criteria.</p>
                </div>
            </div>

            <!-- Card Time Logs Content -->
            <div id="card-time-logs-content" class="content-section" style="display: none;">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Time Tracking</h4>
                        <p class="text-muted mb-0">Monitor team time logs and productivity</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="exportTimeLogs()">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                        <button class="btn btn-primary" onclick="refreshTimeLogs()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="row mb-4" id="time-logs-summary-stats">
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-clock stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="totalHours">0h</h3>
                            <p class="stat-label">Total Hours</p>
                            <small class="text-muted">Logged time</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-journal-text stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="totalLogs">0</h3>
                            <p class="stat-label">Total Entries</p>
                            <small class="text-muted">Time logs</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-people stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="activeUsers">0</h3>
                            <p class="stat-label">Active Users</p>
                            <small class="text-muted">Logging time</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-card-list stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="trackedCards">0</h3>
                            <p class="stat-label">Tracked Tasks</p>
                            <small class="text-muted">With time logs</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-graph-up stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="avgHoursPerLog">0h</h3>
                            <p class="stat-label">Avg Per Entry</p>
                            <small class="text-muted">Hours logged</small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="stat-card">
                            <i class="bi bi-calendar-day stat-icon text-secondary"></i>
                            <h3 class="stat-number text-secondary" id="activeDays">0</h3>
                            <p class="stat-label">Active Days</p>
                            <small class="text-muted">With logs</small>
                        </div>
                    </div>
                </div>

                <!-- Daily Activity Chart -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Daily Time Tracking (Last 30 Days)</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="dailyTimeChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Top Performers (30 Days)</h6>
                            </div>
                            <div class="card-body">
                                <div id="topPerformersList">
                                    <!-- Top performers will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filter-section mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Team Member</label>
                            <select class="form-select" id="timeLogUserFilter">
                                <option value="">All Members</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Project</label>
                            <select class="form-select" id="timeLogProjectFilter">
                                <option value="">All Projects</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Task</label>
                            <select class="form-select" id="timeLogCardFilter">
                                <option value="">All Tasks</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="timeLogDateFrom" placeholder="From">
                                <input type="date" class="form-control" id="timeLogDateTo" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-primary me-2" onclick="applyTimeLogFilters()">
                                <i class="bi bi-funnel me-1"></i>Apply Filters
                            </button>
                            <button class="btn btn-outline-secondary" onclick="clearTimeLogFilters()">
                                <i class="bi bi-x-circle me-1"></i>Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Time Logs Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>User</th>
                                <th>Hours</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="timeLogsTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Loading time logs...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        <span id="timeLogsInfo">Showing 0 of 0 time logs</span>
                    </div>
                    <nav aria-label="Time logs pagination">
                        <ul class="pagination pagination-sm mb-0" id="timeLogsPagination">
                            <!-- Pagination will be generated here -->
                        </ul>
                    </nav>
                </div>

                <!-- No Results Message -->
                <div id="no-time-logs" class="text-center py-5" style="display: none;">
                    <i class="bi bi-clock text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Time Logs Found</h5>
                    <p class="text-muted">No time logs found for the selected criteria.</p>
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
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Reports & Analytics</h4>
                        <p class="text-muted mb-0">View comprehensive reports and generate detailed analytics insights</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" onclick="exportReport()">
                            <i class="bi bi-download me-2"></i>Export Report
                        </button>
                        <button class="btn btn-primary" onclick="refreshReports()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <i class="bi bi-folder-fill stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="reportTotalProjects">0</h3>
                            <p class="stat-label">Total Projects</p>
                            <small class="text-muted">All projects</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <i class="bi bi-check-circle-fill stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="reportCompletedProjects">0</h3>
                            <p class="stat-label">Completed Projects</p>
                            <small class="text-muted">Successfully finished</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <i class="bi bi-clock-history stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="reportInProgressProjects">0</h3>
                            <p class="stat-label">In Progress</p>
                            <small class="text-muted">Currently active</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <i class="bi bi-people-fill stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="reportActiveUsers">0</h3>
                            <p class="stat-label">Active Users</p>
                            <small class="text-muted">Registered users</small>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-funnel me-2 text-primary"></i>
                            Report Filters
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="reportDateFrom" class="form-label">Date From</label>
                                <input type="date" class="form-control" id="reportDateFrom">
                            </div>
                            <div class="col-md-3">
                                <label for="reportDateTo" class="form-label">Date To</label>
                                <input type="date" class="form-control" id="reportDateTo">
                            </div>
                            <div class="col-md-3">
                                <label for="reportStatus" class="form-label">Project Status</label>
                                <select class="form-select" id="reportStatus">
                                    <option value="">All Status</option>
                                    <option value="Planning">Planning</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="reportRole" class="form-label">User Role</label>
                                <select class="form-select" id="reportRole">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="team_lead">Team Lead</option>
                                    <option value="member">Member</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary" onclick="applyReportFilters()">
                                <i class="bi bi-search me-2"></i>Apply Filters
                            </button>
                            <button class="btn btn-outline-secondary ms-2" onclick="clearReportFilters()">
                                <i class="bi bi-x-circle me-2"></i>Clear Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Report Tabs -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <ul class="nav nav-tabs card-header-tabs" id="reportTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="projects-tab" data-bs-toggle="tab"
                                        data-bs-target="#projects-report" type="button" role="tab">
                                    <i class="bi bi-kanban me-2"></i>Projects Report
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="users-tab" data-bs-toggle="tab"
                                        data-bs-target="#users-report" type="button" role="tab">
                                    <i class="bi bi-people me-2"></i>Users Report
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="timeline-tab" data-bs-toggle="tab"
                                        data-bs-target="#timeline-report" type="button" role="tab">
                                    <i class="bi bi-graph-up me-2"></i>Timeline Report
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="reportTabContent">
                            <!-- Projects Report -->
                            <div class="tab-pane fade show active" id="projects-report" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Projects Overview</h5>
                                    <button class="btn btn-sm btn-outline-primary" onclick="exportReport('projects')">
                                        <i class="bi bi-download me-1"></i>Export CSV
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Project ID</th>
                                                <th>Project Name</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>Deadline</th>
                                                <th>Members</th>
                                                <th>Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody id="projectsReportTable">
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted">Loading projects report...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="projectsPagination" class="d-flex justify-content-center mt-3"></div>
                            </div>

                            <!-- Users Report -->
                            <div class="tab-pane fade" id="users-report" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Users Overview</h5>
                                    <button class="btn btn-sm btn-outline-primary" onclick="exportReport('users')">
                                        <i class="bi bi-download me-1"></i>Export CSV
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>User ID</th>
                                                <th>Username</th>
                                                <th>Full Name</th>
                                                <th>Role</th>
                                                <th>Projects Created</th>
                                                <th>Projects Member</th>
                                                <th>Joined Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="usersReportTable">
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted">Loading users report...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div id="usersPagination" class="d-flex justify-content-center mt-3"></div>
                            </div>

                            <!-- Timeline Report -->
                            <div class="tab-pane fade" id="timeline-report" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Project Timeline</h5>
                                    <small class="text-muted">Shows project creation and completion trends</small>
                                </div>

                                <!-- Chart placeholder -->
                                <div class="mb-4">
                                    <div class="chart-container bg-light rounded p-4 text-center" style="height: 300px;">
                                        <i class="bi bi-bar-chart display-1 text-muted"></i>
                                        <p class="text-muted mt-3">Timeline Chart</p>
                                        <small class="text-muted">Chart visualization will be displayed here</small>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Event Type</th>
                                                <th>Count</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody id="timelineReportTable">
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    <p class="mt-2 text-muted">Loading timeline report...</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
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

                                <!-- Display Mode -->
                                <div id="profile-display-mode">
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Name:</strong>
                                        </div>
                                        <div class="col-sm-9" id="display-name">
                                            {{ $user->full_name ?? $user->username }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Username:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $user->username }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Email:</strong>
                                        </div>
                                        <div class="col-sm-9" id="display-email">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Role:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <span class="badge bg-info">Team Lead</span>
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

                                <!-- Edit Mode -->
                                <div id="profile-edit-mode" style="display: none;">
                                    <form id="profile-update-form">
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <label class="form-label"><strong>Full Name:</strong></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="edit-full-name" name="full_name" value="{{ $user->full_name ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <label class="form-label"><strong>Email:</strong></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="edit-email" name="email" value="{{ $user->email }}" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <label class="form-label"><strong>Phone:</strong></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="edit-phone" name="phone" value="{{ $user->phone ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <label class="form-label"><strong>Bio:</strong></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="edit-bio" name="bio" rows="3">{{ $user->bio ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <label class="form-label"><strong>Address:</strong></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="edit-address" name="address" rows="2">{{ $user->address ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <label class="form-label"><strong>Status:</strong></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="form-select" id="edit-status" name="status">
                                                    <option value="active" {{ ($user->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="available" {{ ($user->status ?? '') == 'available' ? 'selected' : '' }}>Available</option>
                                                    <option value="busy" {{ ($user->status ?? '') == 'busy' ? 'selected' : '' }}>Busy</option>
                                                    <option value="inactive" {{ ($user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-success me-2">
                                                    <i class="bi bi-check-lg me-1"></i>Save Changes
                                                </button>
                                                <button type="button" class="btn btn-secondary" onclick="cancelEditProfile()">
                                                    <i class="bi bi-x-lg me-1"></i>Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="profile-avatar-large mb-3 position-relative">
                                    <img id="profile-photo-display"
                                         src="{{ $user->profile_photo ? asset('uploads/profiles/' . $user->profile_photo) : asset('uploads/profiles/default-avatar.png') }}"
                                         alt="Profile Photo"
                                         class="rounded-circle"
                                         style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #667eea;">
                                    <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle"
                                            onclick="document.getElementById('profile-photo-input').click()"
                                            style="width: 35px; height: 35px;">
                                        <i class="bi bi-camera"></i>
                                    </button>
                                    <input type="file" id="profile-photo-input" accept="image/*" style="display: none;" onchange="uploadProfilePhoto(this)">
                                </div>
                                <h5 id="profile-display-name">{{ $user->full_name ?? $user->username }}</h5>
                                <p class="text-muted">Team Lead</p>
                                <span class="badge bg-{{ ($user->status ?? 'active') == 'available' ? 'success' : (($user->status ?? 'active') == 'busy' ? 'warning' : 'info') }} mb-2">
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-outline-primary btn-sm" onclick="toggleEditProfile()">
                                        <i class="bi bi-pencil me-1"></i>Edit Profile
                                    </button>
                                    @if($user->profile_photo)
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteProfilePhoto()">
                                        <i class="bi bi-trash me-1"></i>Delete Photo
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Additional Profile Info -->
                        <div class="card border-0 shadow-sm mt-3">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="bi bi-info-circle me-2 text-info"></i>
                                    Quick Info
                                </h6>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted">Projects Led:</small>
                                        <div><strong>{{ $user->projects_count ?? 0 }}</strong></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Team Size:</small>
                                        <div><strong>{{ $user->team_size ?? 0 }}</strong></div>
                                    </div>
                                </div>
                                @if($user->phone)
                                <div class="mb-2">
                                    <small class="text-muted">Phone:</small>
                                    <div><strong>{{ $user->phone }}</strong></div>
                                </div>
                                @endif
                                @if($user->bio)
                                <div class="mb-2">
                                    <small class="text-muted">Bio:</small>
                                    <div>{{ Str::limit($user->bio, 100) }}</div>
                                </div>
                                @endif
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
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>
                        Edit Project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProjectForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editProjectId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editProjectName" class="form-label">Project Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editProjectName" name="project_name" required maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editProjectDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editProjectDescription" name="description" rows="4" placeholder="Describe your project..."></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="editProjectDeadline" class="form-label">Deadline</label>
                                <input type="date" class="form-control" id="editProjectDeadline" name="deadline">
                                <small class="form-text text-muted">Optional: Set a deadline for this project</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Alert for form messages -->
                        <div id="editFormAlert" class="alert" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                            <span class="btn-text">Update Project</span>
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

    <!-- Complete Project Modal -->
    <div class="modal fade" id="completeProjectModal" tabindex="-1" aria-labelledby="completeProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="completeProjectModalLabel">
                        <i class="bi bi-check-circle me-2"></i>Complete Project
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="completeProjectForm">
                    <div class="modal-body">
                        <div id="completeProjectAlert" class="alert d-none"></div>

                        <div class="mb-3">
                            <p class="mb-3">Are you sure you want to mark this project as completed? This action will finalize the project status.</p>

                            <label for="completionNotes" class="form-label">Completion Notes (Optional)</label>
                            <textarea class="form-control" id="completionNotes" name="completion_notes" rows="4"
                                      placeholder="Add any notes about the project completion..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> Once completed, the project status cannot be reverted.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="completeProjectBtn">
                            <span class="btn-text">
                                <i class="bi bi-check-circle me-1"></i>Complete Project
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cancel Project Modal -->
    <div class="modal fade" id="cancelProjectModal" tabindex="-1" aria-labelledby="cancelProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="cancelProjectModalLabel">
                        <i class="bi bi-x-circle me-2"></i>Cancel Project
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelProjectForm">
                    <div class="modal-body">
                        <div id="cancelProjectAlert" class="alert d-none"></div>

                        <div class="mb-3">
                            <p class="mb-3">Are you sure you want to cancel this project? This will put the project on hold.</p>

                            <label for="cancellationReason" class="form-label">Cancellation Reason *</label>
                            <textarea class="form-control" id="cancellationReason" name="cancellation_reason" rows="4"
                                      placeholder="Please provide a reason for cancelling this project..." required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> Cancelled projects can be reactivated later if needed.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning" id="cancelProjectBtn">
                            <span class="btn-text">
                                <i class="bi bi-x-circle me-1"></i>Cancel Project
                            </span>
                        </button>
                    </div>
                </form>
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
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Add Team Member
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="addMemberAlert" class="alert d-none"></div>

                    <form id="addMemberForm">
                        <div class="mb-3">
                            <label for="roleFilter" class="form-label">
                                <i class="bi bi-funnel me-1"></i>Filter by Role
                            </label>
                            <select class="form-select" id="roleFilter" onchange="filterUsersByRole()">
                                <option value="">All Roles</option>
                                <option value="Project_Admin">Project Admin</option>
                                <option value="Team_Lead">Team Lead</option>
                                <option value="Developer">Developer</option>
                                <option value="Designer">Designer</option>
                                <option value="member">Member</option>
                            </select>
                            <small class="form-text text-muted">Select a role to filter available users</small>
                        </div>

                        <div class="mb-3">
                            <label for="memberUser" class="form-label">Select User *</label>
                            <select class="form-select" id="memberUser" name="user_id" required>
                                <option value="">Loading users...</option>
                            </select>
                            <div class="invalid-feedback"></div>
                            <small class="form-text text-muted">
                                <span id="userCountInfo"></span>
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addMemberForm" class="btn btn-primary" id="addMemberSubmitBtn">
                        <span class="btn-text">
                            <i class="bi bi-check-circle me-2"></i>Add Member
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Role Modal -->
    <div class="modal fade" id="changeRoleModal" tabindex="-1" aria-labelledby="changeRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeRoleModalLabel">
                        <i class="bi bi-pencil me-2"></i>Change Member Role
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="changeRoleAlert" class="alert d-none"></div>

                    <div class="mb-3">
                        <label class="form-label">Member</label>
                        <div id="memberInfo" class="d-flex align-items-center p-3 bg-light rounded">
                            <!-- Member info will be loaded here -->
                        </div>
                    </div>

                    <form id="changeRoleForm">
                        <div class="mb-3">
                            <label for="newRole" class="form-label">New Role *</label>
                            <select class="form-select" id="newRole" name="role" required>
                                <option value="">Select Role</option>
                                <option value="member">Member</option>
                                <option value="team_lead">Team Lead</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="changeRoleForm" class="btn btn-primary" id="changeRoleSubmitBtn">
                        <span class="btn-text">
                            <i class="bi bi-check-circle me-2"></i>Update Role
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js for time tracking charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function showContent(section, element = null) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from all nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });

            // Show selected content
            const contentElement = document.getElementById(section + '-content');
            if (contentElement) {
                contentElement.style.display = 'block';
            }

            // Add active class to clicked nav link
            if (element) {
                const navLink = element.closest('.nav-link') || element.closest('.btn-action');
                if (navLink) {
                    navLink.classList.add('active');
                }
            }

            // Load data when section is opened
            if (section === 'users') {
                loadUserStatistics();
                loadUsersList();
            } else if (section === 'projects') {
                loadCurrentProject();
            } else if (section === 'my-cards') {
                loadMyCards();
            } else if (section === 'assigned-cards') {
                loadPendingReviews();
            } else if (section === 'assigned-history') {
                loadAssignmentHistory();
            } else if (section === 'card-time-logs') {
                loadCardTimeLogs();
            } else if (section === 'reports') {
                if (typeof loadReportData === 'function') {
                    loadReportData();
                }
            }

            // Update header based on section
            const titles = {
                'dashboard': 'Team Lead Dashboard',
                'projects': 'My Project',
                'boards': 'Project Boards',
                'cards': 'Task Cards',
                'my-cards': 'My Cards',
                'assigned-cards': 'Assigned Cards',
                'assigned-history': 'Assignment History',
                'card-time-logs': 'Time Tracking',
                'profile': 'Profile Settings'
            };

            const subtitles = {
                'dashboard': 'Welcome to the administrative control center',
                'projects': 'Create, manage, and oversee project activities',
                'boards': 'View and manage project boards',
                'cards': 'Manage task cards and assignments',
                'my-cards': 'Cards created by you across all projects',
                'assigned-cards': 'Monitor and manage assigned tasks',
                'assigned-history': 'Track assignment progress and history',
                'card-time-logs': 'Monitor team time logs and productivity',
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
        // NOTE: Main DOMContentLoaded is at the end of the script

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

            // Load boards when boards tab is opened
            const boardsTab = document.querySelector('a[href="#boards"]');
            if (boardsTab) {
                boardsTab.addEventListener('click', function() {
                    loadBoards();
                });
            }

            // Load cards when cards tab is opened
            const cardsTab = document.querySelector('a[href="#cards"]');
            if (cardsTab) {
                cardsTab.addEventListener('click', function() {
                    loadCards();
                });
            }

            // Load assigned cards when assigned-cards tab is opened
            const assignedCardsTab = document.querySelector('a[href="#assigned-cards"]');
            if (assignedCardsTab) {
                assignedCardsTab.addEventListener('click', function() {
                    loadAssignedCards();
                });
            }

            // Cards filter event handlers
            document.addEventListener('DOMContentLoaded', function() {
                const statusFilter = document.getElementById('cardStatusFilter');
                const projectFilter = document.getElementById('cardProjectFilter');

                if (statusFilter) {
                    statusFilter.addEventListener('change', filterCards);
                }
                if (projectFilter) {
                    projectFilter.addEventListener('change', filterCards);
                }

                // Assigned cards filter event handlers
                const assignmentStatusFilter = document.getElementById('assignmentStatusFilter');
                const assigneeFilter = document.getElementById('assigneeFilter');

                if (assignmentStatusFilter) {
                    assignmentStatusFilter.addEventListener('change', filterAssignedCards);
                }
                if (assigneeFilter) {
                    assigneeFilter.addEventListener('change', filterAssignedCards);
                }
            });

            // Handle create project form submission
            const createProjectForm = document.getElementById('createProjectForm');
            if (createProjectForm) {
                createProjectForm.addEventListener('submit', handleCreateProject);
            }

            // Handle edit project form submission
            const editProjectForm = document.getElementById('editProjectForm');
            if (editProjectForm) {
                editProjectForm.addEventListener('submit', handleEditProject);
            }

            // Reset form when modal is closed
            const createProjectModal = document.getElementById('createProjectModal');
            if (createProjectModal) {
                createProjectModal.addEventListener('hidden.bs.modal', resetCreateProjectForm);
            }

            // Reset edit form when modal is closed
            const editProjectModal = document.getElementById('editProjectModal');
            if (editProjectModal) {
                editProjectModal.addEventListener('hidden.bs.modal', resetEditProjectForm);
            }
        }

        function loadProjectStatistics() {
            fetch('/api/teamlead/statistics')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update Team Lead specific statistics
                        const stats = data.data;

                        // Main dashboard cards
                        document.getElementById('my-projects-count').textContent = stats.my_projects || 0;
                        document.getElementById('team-members-count').textContent = stats.my_team_members || 0;
                        document.getElementById('pending-tasks-count').textContent = stats.pending_tasks || 0;
                        document.getElementById('overdue-tasks-count').textContent = stats.overdue_tasks || 0;

                        // Task progress cards
                        document.getElementById('in-progress-tasks-count').textContent = stats.in_progress_tasks || 0;
                        document.getElementById('completed-tasks-count').textContent = stats.completed_tasks || 0;
                        document.getElementById('total-tasks-count').textContent = stats.total_tasks || 0;

                        // Update active projects text
                        const activeProjectsText = `${stats.active_projects || 0} active, ${stats.completed_projects || 0} completed`;
                        document.getElementById('active-projects-text').textContent = activeProjectsText;

                        // Legacy support for other components that might still reference old IDs
                        if (document.getElementById('totalProjects')) {
                            document.getElementById('totalProjects').textContent = stats.my_projects || 0;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading project statistics:', error);
                    // Show error state
                    document.getElementById('my-projects-count').textContent = '?';
                    document.getElementById('team-members-count').textContent = '?';
                    document.getElementById('pending-tasks-count').textContent = '?';
                    document.getElementById('overdue-tasks-count').textContent = '?';
                    document.getElementById('active-projects-text').textContent = 'Error loading data';
                });
        }

        function handleCreateProject(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitBtn');

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

        function handleEditProject(event) {
            event.preventDefault();
            console.log('Handle edit project form submission started');

            const form = event.target;
            const formData = new FormData(form);
            const projectId = document.getElementById('editProjectId').value;
            const submitBtn = document.getElementById('editSubmitBtn');

            console.log('Project ID:', projectId);
            console.log('Original form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }

            // Add method spoofing for Laravel
            formData.append('_method', 'PUT');

            console.log('Form data after adding _method:');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }

            submitBtn.disabled = true;

            // Clear previous errors
            clearFormErrors('editProjectForm');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                             document.querySelector('input[name="_token"]')?.value;

            console.log('CSRF Token:', csrfToken);

            const requestUrl = `/api/projects/${projectId}`;
            console.log('Request URL:', requestUrl);

            fetch(requestUrl, {
                method: 'POST', // Use POST with _method=PUT for Laravel
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                console.log('Response status:', response.status, response.statusText);
                console.log('Response headers:', response.headers);

                // Get response text first to see raw response
                return response.text().then(text => {
                    console.log('Raw response text:', text);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}. Response: ${text}`);
                    }

                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error(`Invalid JSON response: ${text}`);
                    }
                });
            })
            .then(data => {
                console.log('Parsed response data:', data);
                if (data.success) {
                    // Show success message
                    showEditFormAlert('success', data.message || 'Project updated successfully!');

                    // Close modal and refresh data after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('editProjectModal')).hide();

                        // Refresh statistics and project list
                        if (typeof loadProjectStatistics === 'function') {
                            loadProjectStatistics();
                        }
                        if (typeof loadProjectsList === 'function') {
                            loadProjectsList();
                        }
                    }, 1500);
                } else {
                    // Show error message
                    showEditFormAlert('danger', data.message || 'Failed to update project');

                    // Show validation errors
                    if (data.errors) {
                        showValidationErrors(data.errors, 'editProjectForm');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating project:', error);
                showEditFormAlert('danger', 'Network error: ' + error.message);
            })
            .finally(() => {
                // Reset button state
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                submitBtn.disabled = false;
            });
        }

        function resetEditProjectForm() {
            const form = document.getElementById('editProjectForm');
            if (form) {
                form.reset();
                clearFormErrors('editProjectForm');
                hideAlert('editFormAlert');
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

        function showEditFormAlert(type, message) {
            const alertDiv = document.getElementById('editFormAlert');
            if (alertDiv) {
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                alertDiv.style.display = 'block';
            }
        }

        function hideAlert(alertId) {
            const alertDiv = document.getElementById(alertId);
            if (alertDiv) {
                alertDiv.style.display = 'none';
            }
        }

        function clearFormErrors(formId = null) {
            const scope = formId ? document.getElementById(formId) : document;
            scope.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            scope.querySelectorAll('.invalid-feedback').forEach(el => {
                el.textContent = '';
            });
        }

        function showValidationErrors(errors, formId = null) {
            Object.keys(errors).forEach(field => {
                const scope = formId ? document.getElementById(formId) : document;
                const input = scope.querySelector(`[name="${field}"]`);
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

            fetch('/api/teamlead/projects')
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
                        <h5 class="mt-3">No Projects Assigned</h5>
                        <p class="text-muted">You don't have any projects assigned to your leadership yet</p>
                    </div>
                `;
                return;
            }

            // Store projects data globally for access by other functions
            window.currentProjectsData = projects;

            // Update statistics based on loaded data
            updateProjectStatistics(projects);

            projectsContainer.innerHTML = projects.map(project => {
                const statusClass = getStatusClass(project.current_status || project.status);
                const currentStatus = project.current_status || project.status;
                const formattedStartDate = project.start_date ? new Date(project.start_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Not set';
                const formattedEndDate = project.end_date ? new Date(project.end_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Not set';

                // Calculate progress percentage
                const totalTasks = parseInt(project.total_tasks) || 0;
                const completedTasks = parseInt(project.completed_tasks) || 0;
                const progressPercentage = totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;

                // Task breakdown
                const pendingTasks = parseInt(project.pending_tasks) || 0;
                const inProgressTasks = parseInt(project.in_progress_tasks) || 0;
                const overdueTasks = parseInt(project.overdue_tasks) || 0;
                const teamMemberCount = parseInt(project.team_member_count) || 0;

                return `
                    <div class="col-lg-6 col-xl-4">
                        <div class="project-card mb-4" data-status="${currentStatus.toLowerCase()}" data-name="${project.project_name}">
                            <div class="project-header">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="project-status ${statusClass}">${currentStatus}</div>
                                    <div class="project-menu">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewProjectDetails(${project.project_id})">
                                                    <i class="bi bi-eye me-2"></i>View Details
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="manageProjectTeam(${project.project_id})">
                                                    <i class="bi bi-people me-2"></i>Manage Team
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="viewProjectTasks(${project.project_id})">
                                                    <i class="bi bi-list-task me-2"></i>View Tasks
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="project-body">
                                <h5 class="project-title mb-2">${project.project_name}</h5>
                                <p class="project-description text-muted small mb-3">${project.description || 'No description available'}</p>

                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="text-primary fw-bold">${progressPercentage}%</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: ${progressPercentage}%" aria-valuenow="${progressPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <!-- Task Statistics -->
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-primary">${totalTasks}</div>
                                            <small class="text-muted">Total Tasks</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-2 bg-light rounded">
                                            <div class="fw-bold text-info">${teamMemberCount}</div>
                                            <small class="text-muted">Team Size</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Task Breakdown -->
                                <div class="task-breakdown mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-success"><i class="bi bi-check-circle-fill me-1"></i>${completedTasks} Completed</small>
                                        <small class="text-warning"><i class="bi bi-clock me-1"></i>${inProgressTasks} In Progress</small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-secondary"><i class="bi bi-pause-circle me-1"></i>${pendingTasks} Pending</small>
                                        ${overdueTasks > 0 ? `<small class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>${overdueTasks} Overdue</small>` : '<small class="text-muted">No overdue</small>'}
                                    </div>
                                </div>

                                <!-- Team Members -->
                                <div class="team-members-section">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted fw-bold">Team Members</small>
                                        <small class="text-primary">${teamMemberCount} members</small>
                                    </div>
                                    <div class="team-members-list">
                                        ${generateTeamMembersList(project.team_members || [])}
                                    </div>
                                </div>

                                <!-- Project Deadline -->
                                <div class="project-deadline mt-3 pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Deadline</small>
                                        <small class="fw-bold ${project.current_status === 'overdue' ? 'text-danger' : 'text-primary'}">${project.deadline ? new Date(project.deadline).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Not set'}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function updateProjectStatistics(projects) {
            // Calculate statistics from loaded project data
            const myTotalProjects = projects.length;
            const overdueProjects = projects.filter(p => p.current_status === 'overdue').length;
            const upcomingProjects = projects.filter(p => p.current_status === 'upcoming').length;
            const totalTeamMembers = projects.reduce((sum, p) => sum + (parseInt(p.team_member_count) || 0), 0);

            // Update My Projects statistics
            if (document.getElementById('my-total-projects')) {
                document.getElementById('my-total-projects').textContent = myTotalProjects;
            }
            if (document.getElementById('project-overdue-count')) {
                document.getElementById('project-overdue-count').textContent = overdueProjects;
            }
            if (document.getElementById('project-upcoming-count')) {
                document.getElementById('project-upcoming-count').textContent = upcomingProjects;
            }
            if (document.getElementById('total-team-members')) {
                document.getElementById('total-team-members').textContent = totalTeamMembers;
            }
        }

        function refreshMyProject() {
            loadCurrentProject();
        }

        function loadCurrentProject() {
            // Load detailed project data
            fetch('/api/teamlead/project-detail', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Project detail data:', data);
                    if (data.success) {
                        if (data.project) {
                            updateProjectOverview(data.project, data.statistics);
                            loadProjectTimeline();
                            loadRecentActivities();
                        } else {
                            updateProjectOverview(null);
                        }
                    } else {
                        console.error('Error loading current project:', data.message);
                        updateProjectOverview(null);
                    }
                })
                .catch(error => {
                    console.error('Error loading current project:', error);
                    updateProjectOverview(null);
                });
        }

        function loadProjectTimeline() {
            fetch('/api/teamlead/project-timeline')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTimeline(data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading timeline:', error);
                });
        }

        function loadRecentActivities() {
            fetch('/api/teamlead/recent-activities')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateRecentActivities(data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading activities:', error);
                });
        }

        function updateProjectOverview(project, statistics) {
            if (project && project.project_id) {
                // Update project info
                document.getElementById('current-project-name').textContent = project.project_name || 'Unknown Project';
                document.getElementById('current-project-description').textContent = project.description || 'No description available';
                document.getElementById('current-project-status').textContent = project.status || 'Unknown';

                // Show project details section
                document.getElementById('project-progress-section').style.display = 'block';
                document.getElementById('project-details').style.display = 'block';

                // Update progress using statistics
                const progress = statistics ? statistics.progress_percentage || 0 : 0;
                document.getElementById('project-progress-percentage').textContent = progress + '%';
                document.getElementById('project-progress-bar').style.width = progress + '%';

                // Update project details
                document.getElementById('project-start-date').textContent = project.start_date || '-';
                document.getElementById('project-end-date').textContent = project.end_date || '-';

                if (statistics) {
                    document.getElementById('project-team-count').textContent = statistics.members.total || '0';
                    document.getElementById('project-task-count').textContent = statistics.cards.total || '0';

                    // Update stats
                    document.getElementById('completed-tasks').textContent = statistics.cards.completed || '0';
                    document.getElementById('pending-tasks').textContent = (statistics.cards.todo + statistics.cards.in_progress) || '0';
                    document.getElementById('active-members').textContent = statistics.members.total || '0';
                }

                // Enable action buttons
                enableProjectActions();
            } else {
                // No project assigned
                document.getElementById('current-project-name').textContent = 'No Project Assigned';
                document.getElementById('current-project-description').textContent = 'You currently have no project assigned to your leadership.';
                document.getElementById('current-project-status').textContent = 'Not Assigned';

                // Hide project details
                document.getElementById('project-progress-section').style.display = 'none';
                document.getElementById('project-details').style.display = 'none';

                // Reset stats
                document.getElementById('completed-tasks').textContent = '0';
                document.getElementById('pending-tasks').textContent = '0';
                document.getElementById('active-members').textContent = '0';

                // Disable action buttons
                disableProjectActions();
            }
        }

        function enableProjectActions() {
            document.getElementById('btn-create-card').disabled = false;
            document.getElementById('btn-manage-boards').disabled = false;
            document.getElementById('btn-view-team').disabled = false;
            document.getElementById('btn-add-member').disabled = false;
            document.getElementById('btn-view-reports').disabled = false;
            document.getElementById('btn-manage-project').disabled = false;
        }

        function disableProjectActions() {
            document.getElementById('btn-create-card').disabled = true;
            document.getElementById('btn-manage-boards').disabled = true;
            document.getElementById('btn-view-team').disabled = true;
            document.getElementById('btn-add-member').disabled = true;
            document.getElementById('btn-view-reports').disabled = true;
            document.getElementById('btn-manage-project').disabled = true;
        }

        function viewProjectDetails() {
            alert('View Project Details - This will open detailed project information');
        }

        function viewProjectBoard() {
            showContent('boards');
        }

        function viewTeamMembers() {
            // Load and show current project members
            loadCurrentProjectMembers();
        }

        function showAddTeamMemberModal() {
            // Fetch available users
            fetch('/api/teamlead/available-users', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAddTeamMemberModal(data.users, data.project);
                } else {
                    alert('Error loading available users: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error loading available users:', error);
                alert('Error loading available users');
            });
        }

        function displayAddTeamMemberModal(users, project) {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-person-plus me-2"></i>Add Team Member to ${project.project_name}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted mb-3">Select users to add to your project team:</p>
                            ${users.length === 0 ?
                                '<div class="text-center py-4"><i class="bi bi-people text-muted mb-3" style="font-size: 3rem;"></i><h5 class="text-muted">No Available Users</h5><p class="text-muted">All eligible users are already assigned to this project.</p></div>'
                                :
                                `<div class="row">
                                    ${users.map(user => `
                                        <div class="col-md-6 mb-3">
                                            <div class="card user-card h-100" onclick="selectUserForProject(${user.id}, '${user.full_name}', '${user.role}')">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="user-avatar me-3" style="background-color: ${getRoleColor(user.role)};">
                                                            ${user.full_name.split(' ').map(n => n.charAt(0)).join('').toUpperCase()}
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">${user.full_name}</h6>
                                                            <span class="badge" style="background-color: ${getRoleColor(user.role)};">${formatRole(user.role)}</span>
                                                            <small class="text-muted d-block">${user.email}</small>
                                                        </div>
                                                        <div class="text-end">
                                                            <i class="bi bi-plus-circle text-success" style="font-size: 1.2rem;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>`
                            }
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();

            // Add CSS for user cards
            const style = document.createElement('style');
            style.textContent = `
                .user-card {
                    cursor: pointer;
                    transition: all 0.2s;
                    border: 1px solid #e0e0e0;
                }
                .user-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    border-color: #007bff;
                }
                .user-avatar {
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                    color: white;
                    font-weight: bold;
                    font-size: 0.8rem;
                }
            `;
            document.head.appendChild(style);

            // Remove modal from DOM when hidden
            modal.addEventListener('hidden.bs.modal', () => {
                document.body.removeChild(modal);
                document.head.removeChild(style);
            });
        }

        function selectUserForProject(userId, userName, userRole) {
            if (confirm(`Add ${userName} (${formatRole(userRole)}) to your project?`)) {
                fetch('/api/teamlead/add-user-to-project', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = document.querySelector('.modal.show');
                        if (modal) {
                            bootstrap.Modal.getInstance(modal).hide();
                        }

                        // Refresh project data
                        loadCurrentProject();

                        alert(`${userName} has been successfully added to the project!`);
                    } else {
                        alert('Error adding user: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error adding user:', error);
                    alert('Error adding user to project');
                });
            }
        }

        function loadCurrentProjectMembers() {
            fetch('/api/teamlead/project-members', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayProjectMembersModal(data.members, data.project);
                } else {
                    alert('Error loading project members: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error loading project members:', error);
                alert('Error loading project members');
            });
        }

        function displayProjectMembersModal(members, project) {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-people me-2"></i>Team Members - ${project.project_name}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                ${members.map(member => `
                                    <div class="col-md-6 mb-3">
                                        <div class="card member-card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="member-avatar me-3" style="background-color: ${getRoleColor(member.role)};">
                                                        ${member.full_name.split(' ').map(n => n.charAt(0)).join('').toUpperCase()}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">${member.full_name}</h6>
                                                        <span class="badge" style="background-color: ${getRoleColor(member.role)};">${formatRole(member.role)}</span>
                                                        <small class="text-muted d-block">${member.email}</small>
                                                        <small class="text-muted">Joined: ${formatDate(member.created_at)}</small>
                                                    </div>
                                                    ${member.role !== 'Team_Lead' ? `
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                                <i class="bi bi-three-dots"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item text-danger" href="#" onclick="removeMemberFromProject(${member.user_id}, '${member.full_name}')">
                                                                    <i class="bi bi-person-dash me-2"></i>Remove from Project
                                                                </a></li>
                                                            </ul>
                                                        </div>
                                                    ` : ''}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="showAddTeamMemberModal(); bootstrap.Modal.getInstance(this.closest('.modal')).hide();">
                                <i class="bi bi-person-plus me-1"></i>Add More Members
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();

            // Remove modal from DOM when hidden
            modal.addEventListener('hidden.bs.modal', () => {
                document.body.removeChild(modal);
            });
        }

        function removeMemberFromProject(userId, userName) {
            if (confirm(`Remove ${userName} from the project?`)) {
                fetch('/api/teamlead/remove-user-from-project', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close current modal and reload project members
                        const modal = document.querySelector('.modal.show');
                        if (modal) {
                            bootstrap.Modal.getInstance(modal).hide();
                        }

                        // Refresh project data
                        loadCurrentProject();

                        alert(`${userName} has been removed from the project.`);
                    } else {
                        alert('Error removing user: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error removing user:', error);
                    alert('Error removing user from project');
                });
            }
        }

        function viewProjectReports() {
            alert('View Project Reports - This will show project analytics and reports');
        }

        function manageProject() {
            alert('Manage Project - This will open project management interface');
        }

        function viewAllActivities() {
            alert('View All Activities - This will show complete activity log');
        }

        function updateTimeline(timelineData) {
            const timelineContainer = document.getElementById('project-timeline');
            const noTimelineData = document.getElementById('no-timeline-data');

            if (!timelineData || timelineData.length === 0) {
                timelineContainer.style.display = 'none';
                noTimelineData.style.display = 'block';
                return;
            }

            // Clear existing timeline items (except template ones)
            const existingItems = timelineContainer.querySelectorAll('.timeline-item');
            existingItems.forEach(item => item.remove());

            timelineData.forEach(item => {
                const timelineItem = document.createElement('div');
                timelineItem.className = 'timeline-item';

                const markerClass = getTimelineMarkerClass(item.activity_type);
                const timeAgo = getTimeAgo(item.activity_date);

                timelineItem.innerHTML = `
                    <div class="timeline-marker ${markerClass}"></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h6 class="timeline-title">${item.title}</h6>
                            <small class="text-muted">${timeAgo}</small>
                        </div>
                        <p class="timeline-description">${item.description || item.title}</p>
                        ${item.user_name ? `<small class="text-muted">by ${item.user_name}</small>` : ''}
                    </div>
                `;

                timelineContainer.appendChild(timelineItem);
            });

            timelineContainer.style.display = 'block';
            noTimelineData.style.display = 'none';
        }

        function updateRecentActivities(activitiesData) {
            const activitiesContainer = document.getElementById('recent-activities');
            const noActivitiesData = document.getElementById('no-activities-data');

            if (!activitiesData || activitiesData.length === 0) {
                activitiesContainer.style.display = 'none';
                noActivitiesData.style.display = 'block';
                return;
            }

            // Clear existing activity items (except template ones)
            const existingItems = activitiesContainer.querySelectorAll('.activity-item');
            existingItems.forEach(item => item.remove());

            activitiesData.forEach(activity => {
                const activityItem = document.createElement('div');
                activityItem.className = 'activity-item';

                const avatarClass = getActivityAvatarClass(activity.type);
                const timeAgo = getTimeAgo(activity.activity_time);

                activityItem.innerHTML = `
                    <div class="activity-avatar ${avatarClass}">
                        ${getActivityIcon(activity.type)}
                    </div>
                    <div class="activity-content">
                        <p class="activity-text">${activity.description}</p>
                        <small class="text-muted">${timeAgo}</small>
                    </div>
                `;

                activitiesContainer.appendChild(activityItem);
            });

            activitiesContainer.style.display = 'block';
            noActivitiesData.style.display = 'none';
        }

        function getTimelineMarkerClass(type) {
            switch(type) {
                case 'created': return 'bg-primary';
                case 'started': return 'bg-success';
                case 'completed': return 'bg-info';
                case 'card_update': return 'bg-warning';
                default: return 'bg-secondary';
            }
        }

        function getActivityAvatarClass(type) {
            switch(type) {
                case 'assignment': return 'bg-primary text-white';
                case 'completion': return 'bg-success text-white';
                case 'comment': return 'bg-warning text-white';
                case 'time_log': return 'bg-info text-white';
                default: return 'bg-secondary text-white';
            }
        }

        function getActivityIcon(type) {
            switch(type) {
                case 'assignment': return '<i class="bi bi-person-check"></i>';
                case 'completion': return '<i class="bi bi-check-circle"></i>';
                case 'comment': return '<i class="bi bi-chat-dots"></i>';
                case 'time_log': return '<i class="bi bi-clock"></i>';
                default: return '<i class="bi bi-activity"></i>';
            }
        }

        function getTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'just now';
            if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' minutes ago';
            if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
            if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 86400) + ' days ago';

            return date.toLocaleDateString();
        }

        // Create Card Functions
        function openCreateCardModal() {
            // Load boards and team members data
            loadBoardsForCard();
            loadTeamMembersForAssignment();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('createCardModal'));
            modal.show();
        }

        function loadBoardsForCard() {
            fetch('/api/teamlead/boards-for-card', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
                .then(response => {
                    console.log('Boards API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Boards API data:', data);
                    if (data.success && data.data) {
                        const boardSelect = document.getElementById('cardBoard');
                        boardSelect.innerHTML = '<option value="">Select Board</option>';

                        data.data.forEach(board => {
                            const option = document.createElement('option');
                            option.value = board.board_id;
                            option.textContent = `${board.board_name} (${board.card_count} cards)`;
                            boardSelect.appendChild(option);
                        });
                        console.log(`Loaded ${data.data.length} boards`);
                    } else {
                        console.error('Boards API failed:', data.message || 'Unknown error');
                        showNotification('error', 'Error', 'Failed to load boards: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error loading boards:', error);
                    showNotification('error', 'Error', 'Failed to load boards');
                });
        }

        function loadTeamMembersForAssignment(boardId = null) {
            // Build URL with board_id parameter if provided
            let url = '/api/teamlead/team-members';
            if (boardId) {
                url += `?board_id=${boardId}`;
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
                .then(response => {
                    console.log('Team members API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Team members API data:', data);
                    if (data.success && data.data) {
                        const assignSelect = document.getElementById('cardAssignTo');

                        // Add project context info
                        let headerText = '🚫 No Assignment - Keep Unassigned';
                        if (data.project && data.project.project_name) {
                            headerText += ` (Project: ${data.project.project_name})`;
                        }
                        assignSelect.innerHTML = `<option value="">${headerText}</option>`;

                        // Show project context in form help text if available
                        const assignToLabel = document.querySelector('label[for="cardAssignTo"]');
                        if (assignToLabel && data.project) {
                            const projectInfo = assignToLabel.parentNode.querySelector('.form-text');
                            if (projectInfo) {
                                projectInfo.innerHTML = `
                                    <i class="bi bi-info-circle me-1"></i>
                                    Showing members from project: <strong>${data.project.project_name}</strong>
                                    (${data.data.length} members available). Members are sorted by workload (lowest first).
                                `;
                            }
                        }

                        // Separate developers and designers for better organization
                        const developers = data.data.filter(member => member.role === 'Developer');
                        const designers = data.data.filter(member => member.role === 'Designer');
                        const members = data.data.filter(member => member.role === 'Member');

                        // Sort by workload (Low -> Medium -> High)
                        const sortByWorkload = (a, b) => {
                            const workloadOrder = { 'Low': 1, 'Medium': 2, 'High': 3 };
                            const workloadDiff = workloadOrder[a.workload_level] - workloadOrder[b.workload_level];
                            if (workloadDiff !== 0) return workloadDiff;
                            // If same workload, sort by name
                            return a.full_name.localeCompare(b.full_name);
                        };

                        developers.sort(sortByWorkload);
                        designers.sort(sortByWorkload);
                        members.sort(sortByWorkload);

                        // Add developers section
                        if (developers.length > 0) {
                            const devGroup = document.createElement('optgroup');
                            devGroup.label = '👨‍💻 Developers';
                            developers.forEach(member => {
                                const option = createMemberOption(member);
                                devGroup.appendChild(option);
                            });
                            assignSelect.appendChild(devGroup);
                        }

                        // Add designers section
                        if (designers.length > 0) {
                            const designGroup = document.createElement('optgroup');
                            designGroup.label = '🎨 Designers';
                            designers.forEach(member => {
                                const option = createMemberOption(member);
                                designGroup.appendChild(option);
                            });
                            assignSelect.appendChild(designGroup);
                        }

                        // Add members section
                        if (members.length > 0) {
                            const memberGroup = document.createElement('optgroup');
                            memberGroup.label = '👤 Members';
                            members.forEach(member => {
                                const option = createMemberOption(member);
                                memberGroup.appendChild(option);
                            });
                            assignSelect.appendChild(memberGroup);
                        }

                        // Add event listener for assignment preview
                        assignSelect.addEventListener('change', function() {
                            updateAssignmentPreview(this.value);
                        });

                        console.log(`Loaded ${data.data.length} team members`);
                    } else {
                        console.error('Team members API failed:', data.message || 'Unknown error');
                        showNotification('error', 'Error', 'Failed to load team members: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error loading team members:', error);
                    showNotification('error', 'Error', 'Failed to load team members');
                });
        }

        function createMemberOption(member) {
            const option = document.createElement('option');
            option.value = member.user_id;

            // Create workload indicator
            let workloadIcon = '🟢'; // Low workload
            if (member.workload_level === 'High') {
                workloadIcon = '🔴';
            } else if (member.workload_level === 'Medium') {
                workloadIcon = '🟡';
            }

            // Build option text with comprehensive info
            const optionText = `${workloadIcon} ${member.full_name} - ${member.active_tasks} active, ${member.completed_tasks || 0} completed (${member.efficiency_score || 0}% efficiency)`;

            option.textContent = optionText;
            option.setAttribute('data-member-data', JSON.stringify(member));

            // Add styling based on workload
            if (member.workload_level === 'High') {
                option.style.color = '#dc3545'; // Red for high workload
                option.style.fontWeight = 'bold';
            } else if (member.workload_level === 'Low') {
                option.style.color = '#198754'; // Green for low workload
            }

            return option;
        }

        function updateAssignmentPreview(selectedUserId) {
            const assignmentPreview = document.getElementById('assignmentPreview');
            const assignmentDetails = document.getElementById('assignmentDetails');

            if (!selectedUserId) {
                assignmentPreview.style.display = 'none';
                return;
            }

            const selectedOption = document.querySelector(`#cardAssignTo option[value="${selectedUserId}"]`);
            if (!selectedOption) return;

            const memberData = JSON.parse(selectedOption.getAttribute('data-member-data'));

            // Create detailed assignment preview
            let recommendation = '';
            let alertClass = 'alert-info';

            if (memberData.workload_level === 'Low') {
                recommendation = `✅ Excellent choice! ${memberData.full_name} has low workload and ${memberData.efficiency_score || 0}% efficiency.`;
                alertClass = 'alert-success';
            } else if (memberData.workload_level === 'Medium') {
                recommendation = `⚠️ Good option. ${memberData.full_name} has moderate workload but maintains ${memberData.efficiency_score || 0}% efficiency.`;
                alertClass = 'alert-warning';
            } else {
                recommendation = `🚨 Consider carefully. ${memberData.full_name} is heavily loaded with ${memberData.active_tasks} active tasks.`;
                alertClass = 'alert-danger';
            }

            assignmentDetails.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>👤 ${memberData.full_name}</strong><br>
                        <small class="text-muted">${memberData.role} • ${memberData.username}</small>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <span>Active Tasks:</span>
                            <strong class="text-primary">${memberData.active_tasks}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Completed:</span>
                            <strong class="text-success">${memberData.completed_tasks || 0}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Efficiency:</span>
                            <strong class="text-info">${memberData.efficiency_score || 0}%</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Avg Hours:</span>
                            <strong class="text-warning">${memberData.avg_completion_hours || 'N/A'}</strong>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="alert ${alertClass} mb-0">
                            <strong>Recommendation:</strong> ${recommendation}
                        </div>
                    </div>
                </div>
            `;

            // Update alert class
            assignmentPreview.className = `alert ${alertClass}`;
            assignmentPreview.style.display = 'block';
        }

        function submitCreateCard() {
            const form = document.getElementById('createCardForm');
            const formData = new FormData(form);
            const createBtn = document.getElementById('createCardBtn');

            createBtn.disabled = true;

            // Convert FormData to JSON
            const data = {};
            formData.forEach((value, key) => {
                if (value.trim() !== '') {
                    data[key] = value;
                }
            });

            fetch('/api/teamlead/cards', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // If user is assigned, create assignment
                    if (formData.get('assigned_to') && formData.get('assigned_to').trim() !== '') {
                        const assignmentData = {
                            card_id: data.card_id,
                            user_id: formData.get('assigned_to')
                        };

                        // Create assignment
                        fetch('/api/teamlead/cards/assign', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify(assignmentData)
                        })
                        .then(response => response.json())
                        .then(assignData => {
                            if (assignData.success) {
                                showNotification('success', 'Card created and assigned successfully!', `${data.message} Assigned to ${assignData.assigned_user.full_name}.`);
                            } else {
                                showNotification('warning', 'Card created but assignment failed', `Card was created successfully but couldn't assign to user: ${assignData.message}`);
                            }
                        })
                        .catch(error => {
                            console.error('Error assigning card:', error);
                            showNotification('warning', 'Card created but assignment failed', 'Card was created successfully but assignment failed');
                        });
                    } else {
                        // Success notification for card creation only
                        showNotification('success', 'Card created successfully!', data.message);
                    }

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createCardModal'));
                    modal.hide();

                    // Reset form
                    form.reset();
                    document.getElementById('assignmentPreview').style.display = 'none';

                    // Refresh cards list if on cards page
                    if (typeof loadCards === 'function') {
                        loadCards();
                    }

                    // Refresh current project data
                    loadCurrentProject();

                } else {
                    showNotification('error', 'Error creating card', data.message);
                }
            })
            .catch(error => {
                console.error('Error creating card:', error);
                showNotification('error', 'Error creating card', 'An unexpected error occurred');
            })
            .finally(() => {
                // Re-enable button
                createBtn.disabled = false;
                createBtn.innerHTML = '<i class="bi bi-plus-lg me-2"></i>Create Card';
            });
        }

        function showNotification(type, title, message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';

            notification.innerHTML = `
                <strong>${title}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        function initializeCreateCardForm() {
            const assignSelect = document.getElementById('cardAssignTo');
            const assignmentPreview = document.getElementById('assignmentPreview');
            const assignmentDetails = document.getElementById('assignmentDetails');
            const boardSelect = document.getElementById('cardBoard');
            const cardTitle = document.getElementById('cardTitle');
            const titleCharCount = document.getElementById('titleCharCount');
            const cardPriority = document.getElementById('cardPriority');
            const boardProjectInfo = document.getElementById('boardProjectInfo');
            const boardProjectName = document.getElementById('boardProjectName');

            // Character counter for title with animation
            if (cardTitle && titleCharCount) {
                cardTitle.addEventListener('input', function() {
                    const length = this.value.length;
                    const maxLength = 100;
                    titleCharCount.textContent = `${length}/${maxLength}`;

                    // Add warning colors
                    titleCharCount.classList.remove('warning', 'danger');
                    if (length > 80) {
                        titleCharCount.classList.add('danger');
                    } else if (length > 60) {
                        titleCharCount.classList.add('warning');
                    }

                    // Add typing animation
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 100);
                });
            }

            // Priority selection with visual feedback
            if (cardPriority) {
                cardPriority.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const color = selectedOption.getAttribute('data-color');

                    // Animate selection
                    this.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);

                    // Visual feedback
                    if (color) {
                        this.className = `form-select border-start-0 ps-0 border-${color}`;
                        setTimeout(() => {
                            this.className = 'form-select border-start-0 ps-0';
                        }, 1000);
                    }
                });
            }

            // Add event listener for board selection
            if (boardSelect) {
                boardSelect.addEventListener('change', function() {
                    const selectedBoardId = this.value;

                    // Show loading state
                    assignSelect.classList.add('loading');
                    assignSelect.disabled = true;

                    if (selectedBoardId) {
                        // Get selected board details
                        const selectedOption = this.options[this.selectedIndex];
                        const boardName = selectedOption.textContent;

                        // Show project info
                        if (boardProjectInfo && boardProjectName) {
                            boardProjectName.textContent = `This card will be added to: ${boardName}`;
                            boardProjectInfo.style.display = 'block';
                        }

                        // Reload team members based on selected board
                        loadTeamMembersForAssignment(selectedBoardId);

                        // Enable assign select after loading
                        setTimeout(() => {
                            assignSelect.classList.remove('loading');
                            assignSelect.disabled = false;
                        }, 500);
                    } else {
                        // Reset assignment select when no board is selected
                        assignSelect.innerHTML = '<option value="">� No Assignment - Keep Unassigned</option>';
                        assignmentPreview.style.display = 'none';
                        if (boardProjectInfo) {
                            boardProjectInfo.style.display = 'none';
                        }
                        assignSelect.classList.remove('loading');
                        assignSelect.disabled = false;
                    }
                });
            }

            if (assignSelect) {
                assignSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        const memberData = JSON.parse(selectedOption.getAttribute('data-member-data') || '{}');

                        // Enhanced assignment preview with animations
                        assignmentDetails.innerHTML = `
                            <div class="d-flex align-items-center p-3 rounded" style="background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <div class="me-3">
                                    <div class="avatar-placeholder text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                                        <span class="fs-4 fw-bold">${memberData.full_name ? memberData.full_name.charAt(0).toUpperCase() : 'U'}</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">${memberData.full_name || 'Unknown'}</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge ${memberData.role === 'Developer' ? 'bg-primary' : 'bg-info'}">${memberData.role || 'Member'}</span>
                                        <small class="text-muted">
                                            <i class="bi bi-clipboard-check me-1"></i>${memberData.active_assignments || 0} active tasks
                                        </small>
                                        ${memberData.workload_level ? `
                                            <span class="badge bg-${memberData.workload_color || 'secondary'} rounded-pill">
                                                ${memberData.workload_level} Workload
                                            </span>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                </div>
                            </div>
                        `;
                        assignmentPreview.style.display = 'block';

                        // Scroll to preview smoothly
                        setTimeout(() => {
                            assignmentPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 100);
                    } else {
                        assignmentPreview.style.display = 'none';
                    }
                });
            }

            // Handle form submission
            const createCardForm = document.getElementById('createCardForm');
            if (createCardForm) {
                createCardForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitCreateCard();
                });

                // Add smooth focus effects
                const formInputs = createCardForm.querySelectorAll('.form-control, .form-select');
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
            }
        }

        function viewProjectDetails(projectId) {
            // Navigate to detailed project view
            console.log('Viewing project details for ID:', projectId);
            alert('Project details view - This will be implemented to show comprehensive project information');
        }

        function manageProjectTeam(projectId) {
            // Find the project in current data
            const projectData = window.currentProjectsData?.find(p => p.project_id === projectId);
            if (projectData && projectData.team_members) {
                showTeamMembersModal(projectData);
            } else {
                alert('Team management - This will be implemented to manage project team members');
            }
        }

        function showTeamMembersModal(project) {
            // Create a simple modal to show all team members
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Team Members - ${project.project_name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                ${project.team_members.map(member => `
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="member-avatar me-3" style="background-color: ${getRoleColor(member.role)}; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; font-weight: bold;">
                                                        ${member.full_name.split(' ').map(n => n.charAt(0)).join('').toUpperCase()}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">${member.full_name}</h6>
                                                        <p class="mb-1 text-muted">${formatRole(member.role)}</p>
                                                        <small class="text-muted">${member.email}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();

            // Remove modal from DOM when hidden
            modal.addEventListener('hidden.bs.modal', () => {
                document.body.removeChild(modal);
            });
        }

        function viewProjectTasks(projectId) {
            // Navigate to project tasks/board view
            console.log('Viewing tasks for project ID:', projectId);
            alert('Project tasks view - This will be implemented to show project boards and tasks');
        }

        function generateTeamMembersList(teamMembers) {
            if (!teamMembers || teamMembers.length === 0) {
                return '<small class="text-muted">No team members assigned</small>';
            }

            // Group members by role for better organization
            const membersByRole = {};
            teamMembers.forEach(member => {
                if (!membersByRole[member.role]) {
                    membersByRole[member.role] = [];
                }
                membersByRole[member.role].push(member);
            });

            // Show max 4 members, then show "and X more"
            const maxVisible = 4;
            const totalMembers = teamMembers.length;
            const visibleMembers = teamMembers.slice(0, maxVisible);
            const remainingCount = totalMembers - maxVisible;

            let membersList = visibleMembers.map(member => {
                const roleColor = getRoleColor(member.role);
                const initials = member.full_name.split(' ').map(n => n.charAt(0)).join('').toUpperCase();

                return `
                    <div class="team-member-item d-flex align-items-center mb-1">
                        <div class="member-avatar me-2" style="background-color: ${roleColor};">
                            ${initials}
                        </div>
                        <div class="member-info flex-grow-1">
                            <div class="member-name">${member.full_name}</div>
                            <div class="member-role">${formatRole(member.role)}</div>
                        </div>
                    </div>
                `;
            }).join('');

            // Add "and X more" if there are remaining members
            if (remainingCount > 0) {
                membersList += `
                    <div class="team-member-more text-center py-2">
                        <small class="text-muted">and ${remainingCount} more member${remainingCount > 1 ? 's' : ''}</small>
                    </div>
                `;
            }

            // Add role summary
            const roleSummary = Object.keys(membersByRole).map(role => {
                const count = membersByRole[role].length;
                return `${count} ${formatRole(role)}${count > 1 ? 's' : ''}`;
            }).join(', ');

            return `
                <div class="team-members-list">
                    ${membersList}
                </div>
                <div class="role-summary mt-2 pt-2 border-top">
                    <small class="text-muted">${roleSummary}</small>
                </div>
            `;
        }

        function getRoleColor(role) {
            const colors = {
                'Developer': '#28a745',
                'Designer': '#dc3545',
                'Member': '#6c757d',
                'Project_Admin': '#007bff',
                'Team_Lead': '#17a2b8'
            };
            return colors[role] || '#6c757d';
        }

        function formatRole(role) {
            return role.replace(/_/g, ' ');
        }

        function getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'planning': return 'status-todo';
                case 'in progress': return 'status-progress';
                case 'on hold': return 'status-cancelled';
                case 'completed': return 'status-done';
                default: return 'status-todo';
            }
        }

        function loadBoards() {
            const boardsGrid = document.getElementById('boards-grid');
            const emptyElement = document.getElementById('boards-empty');

            emptyElement.style.display = 'none';
            boardsGrid.innerHTML = '';

            fetch('/api/teamlead/boards')
                .then(response => response.json())
                .then(data => {

                    if (data.success && data.boards.length > 0) {
                        let boardsHTML = '';

                        data.boards.forEach(board => {
                            const totalCards = board.total_cards || 0;
                            const completedCards = board.done_cards || 0;
                            const progress = totalCards > 0 ? Math.round((completedCards / totalCards) * 100) : 0;

                            boardsHTML += `
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card board-card h-100" onclick="viewBoard(${board.id})">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">${board.project_name}</h6>
                                            <span class="badge bg-primary">${board.name}</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center mb-3">
                                                <div class="col-4">
                                                    <div class="stat-box">
                                                        <div class="stat-number text-warning">${board.todo_cards || 0}</div>
                                                        <div class="stat-label">To Do</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="stat-box">
                                                        <div class="stat-number text-info">${board.in_progress_cards || 0}</div>
                                                        <div class="stat-label">In Progress</div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="stat-box">
                                                        <div class="stat-number text-success">${board.done_cards || 0}</div>
                                                        <div class="stat-label">Done</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="progress mb-2" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                     style="width: ${progress}%" aria-valuenow="${progress}"
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">${completedCards}/${totalCards} tasks completed</small>
                                                <span class="badge ${progress === 100 ? 'bg-success' : progress > 50 ? 'bg-warning' : 'bg-secondary'}">${progress}%</span>
                                            </div>

                                            ${board.overdue_cards > 0 ? `
                                                <div class="mt-2">
                                                    <small class="text-danger">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                        ${board.overdue_cards} overdue tasks
                                                    </small>
                                                </div>
                                            ` : ''}
                                        </div>
                                        <div class="card-footer text-muted">
                                            <small>
                                                <i class="bi bi-calendar"></i>
                                                Last updated: ${new Date(board.updated_at).toLocaleDateString()}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        boardsGrid.innerHTML = boardsHTML;
                    } else {
                        emptyElement.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error loading boards:', error);
                    boardsGrid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Failed to load boards. Please try again.
                            </div>
                        </div>
                    `;
                });
        }

        function viewBoard(boardId) {
            // TODO: Implement board detail view
            console.log('View board:', boardId);
        }

        function loadCards() {
            const cardsGrid = document.getElementById('cards-grid');
            const cardsStatistics = document.getElementById('cards-statistics');
            const emptyElement = document.getElementById('cards-empty');
            const projectFilter = document.getElementById('cardProjectFilter');

            emptyElement.style.display = 'none';
            cardsGrid.innerHTML = '';
            cardsStatistics.innerHTML = '';

            fetch('/api/teamlead/cards')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {

                    if (data.success && data.cards && data.cards.length > 0) {
                        // Populate project filter
                        populateProjectFilter(data.cards, projectFilter);

                        // Display statistics
                        displayCardsStatistics(data.statistics);

                        // Display cards
                        displayCards(data.cards);
                    } else {
                        emptyElement.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error loading cards:', error);
                    cardsGrid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Failed to load cards: ${error.message}
                            </div>
                        </div>
                    `;
                });
        }

        function displayCardsStatistics(statistics) {
            const statisticsContainer = document.getElementById('cards-statistics');

            const statsHTML = `
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-primary">${statistics.total_cards}</div>
                            <div class="stat-label">Total Cards</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-warning">${statistics.todo_cards}</div>
                            <div class="stat-label">To Do</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-info">${statistics.in_progress_cards}</div>
                            <div class="stat-label">In Progress</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-secondary">${statistics.review_cards}</div>
                            <div class="stat-label">Review</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-success">${statistics.done_cards}</div>
                            <div class="stat-label">Done</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-danger">${statistics.overdue_cards}</div>
                            <div class="stat-label">Overdue</div>
                        </div>
                    </div>
                </div>
            `;

            statisticsContainer.innerHTML = statsHTML;
        }

        function displayCards(cards) {
            const cardsGrid = document.getElementById('cards-grid');
            let cardsHTML = '';

            cards.forEach(card => {
                const dueDate = card.due_date ? new Date(card.due_date).toLocaleDateString() : 'No due date';
                const isOverdue = card.is_overdue == 1;
                const priority = card.priority || 'medium';
                const assignedTo = card.assigned_to || 'Unassigned';
                const totalSubtasks = parseInt(card.total_subtasks) || 0;
                const completedSubtasks = parseInt(card.completed_subtasks) || 0;
                const subtasksProgress = totalSubtasks > 0 ? Math.round((completedSubtasks / totalSubtasks) * 100) : 0;

                cardsHTML += `
                    <div class="col-lg-4 col-md-6 mb-4 card-item" data-status="${card.status || ''}" data-project="${card.project_name || ''}">
                        <div class="card task-card h-100 ${isOverdue ? 'border-danger' : ''}" onclick="viewCard(${card.card_id || 0})">
                            <div class="card-header d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">${card.title || 'Untitled'}</h6>
                                    <small class="text-muted">${card.project_name || 'Unknown Project'} • ${card.board_name || 'Unknown Board'}</small>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge ${getStatusBadgeClass(card.status)} mb-1">${formatStatus(card.status)}</span>
                                    <span class="badge ${getPriorityBadgeClass(priority)}">${priority.toUpperCase()}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                ${card.description ? `<p class="card-text small text-muted">${card.description.substring(0, 100)}${card.description.length > 100 ? '...' : ''}</p>` : ''}

                                ${totalSubtasks > 0 ? `
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">Subtasks</small>
                                            <small class="text-muted">${completedSubtasks}/${totalSubtasks}</small>
                                        </div>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: ${subtasksProgress}%" aria-valuenow="${subtasksProgress}"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                ` : ''}

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle me-1"></i>
                                        <small class="text-muted">${assignedTo}</small>
                                    </div>
                                    ${(parseInt(card.comments_count) > 0) ? `
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-chat-dots me-1"></i>
                                            <small class="text-muted">${card.comments_count}</small>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i>
                                        ${dueDate}
                                    </small>
                                    ${isOverdue ? '<small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Overdue</small>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            cardsGrid.innerHTML = cardsHTML;
        }

        function populateProjectFilter(cards, selectElement) {
            const projects = [...new Set(cards.map(card => card.project_name))];

            selectElement.innerHTML = '<option value="">All Projects</option>';
            projects.forEach(project => {
                selectElement.innerHTML += `<option value="${project}">${project}</option>`;
            });
        }

        function getStatusBadgeClass(status) {
            switch(status) {
                case 'todo': return 'bg-warning';
                case 'in_progress': return 'bg-info';
                case 'review': return 'bg-secondary';
                case 'done': return 'bg-success';
                default: return 'bg-light';
            }
        }

        function getPriorityBadgeClass(priority) {
            switch(priority.toLowerCase()) {
                case 'high': return 'bg-danger';
                case 'medium': return 'bg-warning';
                case 'low': return 'bg-success';
                default: return 'bg-secondary';
            }
        }

        function formatStatus(status) {
            return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function viewCard(cardId) {
            // TODO: Implement card detail view
            console.log('View card:', cardId);
        }

        // My Cards Functions
        function loadMyCards() {
            const myCardsGrid = document.getElementById('my-cards-grid');
            const loadingElement = document.getElementById('my-cards-loading');
            const emptyElement = document.getElementById('my-cards-empty');

            // Show loading state
            loadingElement.style.display = 'block';
            emptyElement.style.display = 'none';
            myCardsGrid.innerHTML = '';

            fetch('/api/teamlead/my-cards')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    loadingElement.style.display = 'none';

                    if (data.success && data.data) {
                        // Update statistics
                        updateMyCardsStatistics(data.data);

                        // Display cards by status
                        displayMyCards(data.data);

                        // Populate project filter
                        populateMyCardsProjectFilter(data.data);
                    } else {
                        emptyElement.style.display = 'block';
                        updateMyCardsStatistics({
                            todo: [],
                            in_progress: [],
                            review: [],
                            done: []
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading my cards:', error);
                    loadingElement.style.display = 'none';
                    myCardsGrid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Failed to load your cards: ${error.message}
                            </div>
                        </div>
                    `;
                });
        }

        function updateMyCardsStatistics(cardsByStatus) {
            document.getElementById('myCardsTodoCount').textContent = cardsByStatus.todo ? cardsByStatus.todo.length : 0;
            document.getElementById('myCardsInProgressCount').textContent = cardsByStatus.in_progress ? cardsByStatus.in_progress.length : 0;
            document.getElementById('myCardsReviewCount').textContent = cardsByStatus.review ? cardsByStatus.review.length : 0;
            document.getElementById('myCardsDoneCount').textContent = cardsByStatus.done ? cardsByStatus.done.length : 0;
        }

        function displayMyCards(cardsByStatus) {
            const myCardsGrid = document.getElementById('my-cards-grid');
            let html = '';

            // Group cards by status and display them
            const statusOrder = ['todo', 'in_progress', 'review', 'done'];
            const statusLabels = {
                'todo': 'To Do',
                'in_progress': 'In Progress',
                'review': 'Review',
                'done': 'Done'
            };

            statusOrder.forEach(status => {
                const cards = cardsByStatus[status] || [];
                if (cards.length > 0) {
                    html += `
                        <div class="col-12 mb-4">
                            <h5 class="mb-3"><i class="bi bi-circle-fill text-${getStatusColor(status)} me-2"></i>${statusLabels[status]} (${cards.length})</h5>
                            <div class="row">
                    `;

                    cards.forEach(card => {
                        html += createMyCardHTML(card);
                    });

                    html += `
                            </div>
                        </div>
                    `;
                }
            });

            if (html === '') {
                document.getElementById('my-cards-empty').style.display = 'block';
            } else {
                myCardsGrid.innerHTML = html;
            }
        }

        function createMyCardHTML(card) {
            const priorityColor = getPriorityColor(card.priority);
            const statusColor = getStatusColor(card.status);

            return `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <small class="text-muted">${card.project_name || 'No Project'}</small>
                            <span class="badge bg-${priorityColor}">${card.priority}</span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title mb-2">${card.title || card.card_title}</h6>
                            <p class="card-text text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                ${card.description || 'No description'}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-${statusColor}">${card.status.replace('_', ' ')}</span>
                                <small class="text-muted">${card.board_name || 'No Board'}</small>
                            </div>
                            ${card.due_date ? `
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        Due: ${formatDate(card.due_date)}
                                    </small>
                                </div>
                            ` : ''}
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Created: ${formatDate(card.created_at)}
                                </small>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" onclick="viewCardDetails(${card.id || card.card_id})" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="editCard(${card.id || card.card_id})" title="Edit Card">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function populateMyCardsProjectFilter(cardsByStatus) {
            const projectFilter = document.getElementById('myCardsProjectFilter');
            const projects = new Set();

            // Collect all unique projects
            Object.values(cardsByStatus).forEach(cards => {
                cards.forEach(card => {
                    if (card.project_name) {
                        projects.add(JSON.stringify({
                            id: card.project_id || card.id,
                            name: card.project_name
                        }));
                    }
                });
            });

            // Clear and populate filter
            projectFilter.innerHTML = '<option value="">All Projects</option>';
            Array.from(projects).forEach(projectStr => {
                const project = JSON.parse(projectStr);
                const option = document.createElement('option');
                option.value = project.id;
                option.textContent = project.name;
                projectFilter.appendChild(option);
            });
        }

        function refreshMyCards() {
            loadMyCards();
        }

        function applyMyCardsFilters() {
            // This would filter the displayed cards based on selected filters
            // Implementation would depend on specific requirements
            loadMyCards(); // For now, just reload
        }

        function clearMyCardsFilters() {
            document.getElementById('myCardsStatusFilter').value = '';
            document.getElementById('myCardsProjectFilter').value = '';
            document.getElementById('myCardsPriorityFilter').value = '';
            loadMyCards();
        }

        // Card Review Functions
        function loadAssignedCards() {
            loadPendingReviews();
        }

        function loadPendingReviews() {
            const reviewsGrid = document.getElementById('pending-reviews-grid');
            const emptyElement = document.getElementById('reviews-empty');

            emptyElement.style.display = 'none';
            reviewsGrid.innerHTML = '';

            fetch('/api/teamlead/pending-reviews')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {

                    if (data.success && data.data && data.data.length > 0) {
                        // Update statistics
                        updateReviewStatistics(data.data);

                        // Display pending reviews
                        displayPendingReviews(data.data);
                    } else {
                        emptyElement.style.display = 'block';
                        updateReviewStatistics([]);
                    }
                })
                .catch(error => {
                    console.error('Error loading pending reviews:', error);
                    reviewsGrid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Failed to load pending reviews: ${error.message}
                            </div>
                        </div>
                    `;
                });
        }

        function updateReviewStatistics(reviews) {
            document.getElementById('pendingReviewsCount').textContent = reviews.length;
            document.getElementById('approvedTodayCount').textContent = '0'; // TODO: implement
            document.getElementById('rejectedTodayCount').textContent = '0'; // TODO: implement
            document.getElementById('avgReviewTime').textContent = '2h'; // TODO: implement
        }

        function displayPendingReviews(reviews) {
            const reviewsGrid = document.getElementById('pending-reviews-grid');

            reviewsGrid.innerHTML = reviews.map(card => {
                const submitDate = new Date(card.submitted_at);
                const timeAgo = getTimeAgo(submitDate);
                const priorityClass = getPriorityClass(card.priority);

                return `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-warning">
                            <div class="card-header bg-warning bg-opacity-10">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-${priorityClass}">${card.priority.toUpperCase()}</span>
                                    <small class="text-muted">${timeAgo}</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">${card.title}</h6>
                                <p class="card-text small text-muted">${card.description}</p>

                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> ${card.submitted_by_name}<br>
                                        <i class="bi bi-kanban"></i> ${card.board_name}<br>
                                        <i class="bi bi-folder"></i> ${card.project_name}
                                    </small>
                                </div>

                                ${card.comment ? `
                                    <div class="alert alert-light p-2 mb-3">
                                        <small><i class="bi bi-chat-left-text"></i> <strong>Comment:</strong><br>
                                        ${card.comment}</small>
                                    </div>
                                ` : ''}
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success btn-sm" onclick="approveCard(${card.card_id}, '${card.title}')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="rejectCard(${card.card_id}, '${card.title}')">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function refreshPendingReviews() {
            loadPendingReviews();
        }

        let currentReviewCard = null;
        let currentReviewAction = null;

        function approveCard(cardId, cardTitle) {
            showFeedbackModal(cardId, cardTitle, 'approve');
        }

        function rejectCard(cardId, cardTitle) {
            showFeedbackModal(cardId, cardTitle, 'reject');
        }

        function showFeedbackModal(cardId, cardTitle, action) {
            currentReviewCard = cardId;
            currentReviewAction = action;

            // Update modal content based on action
            const modal = document.getElementById('cardFeedbackModal');
            const header = document.getElementById('feedbackModalHeader');
            const title = document.getElementById('cardFeedbackModalLabel');
            const cardTitleEl = document.getElementById('feedbackCardTitle');
            const cardInfoEl = document.getElementById('feedbackCardInfo');
            const feedbackRequired = document.getElementById('feedbackRequired');
            const feedbackHelp = document.getElementById('feedbackHelp');
            const submitBtn = document.getElementById('submitFeedbackBtn');
            const approveInfo = document.getElementById('approveInfo');
            const rejectInfo = document.getElementById('rejectInfo');
            const feedbackText = document.getElementById('feedbackText');

            // Reset form
            feedbackText.value = '';

            if (action === 'approve') {
                header.className = 'modal-header bg-success text-white';
                title.innerHTML = '<i class="bi bi-check-circle me-2"></i>Approve Card';
                feedbackRequired.textContent = '(optional)';
                feedbackHelp.textContent = 'Add positive feedback or comments (optional).';
                submitBtn.className = 'btn btn-success';
                submitBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Approve Card';
                approveInfo.classList.remove('d-none');
                rejectInfo.classList.add('d-none');
                feedbackText.placeholder = 'Great work! Add any positive feedback...';
            } else {
                header.className = 'modal-header bg-danger text-white';
                title.innerHTML = '<i class="bi bi-x-circle me-2"></i>Reject Card';
                feedbackRequired.textContent = '(required)';
                feedbackHelp.textContent = 'Please provide specific feedback on what needs to be improved.';
                submitBtn.className = 'btn btn-danger';
                submitBtn.innerHTML = '<i class="bi bi-x-lg me-2"></i>Reject Card';
                approveInfo.classList.add('d-none');
                rejectInfo.classList.remove('d-none');
                feedbackText.placeholder = 'Please explain what needs to be improved...';
                feedbackText.required = true;
            }

            cardTitleEl.textContent = cardTitle;
            cardInfoEl.textContent = `Card ID: ${cardId} • Action: ${action.charAt(0).toUpperCase() + action.slice(1)}`;

            // Show modal
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        }

        // Handle feedback form submission
        document.getElementById('cardFeedbackForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const feedback = document.getElementById('feedbackText').value.trim();

            // Validate required feedback for reject
            if (currentReviewAction === 'reject' && !feedback) {
                showNotification('error', 'Feedback Required', 'Please provide feedback when rejecting a card.');
                return;
            }

            const endpoint = currentReviewAction === 'approve' ? 'approve' : 'reject';

            fetch(`/api/teamlead/cards/${currentReviewCard}/${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ feedback: feedback })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const actionText = currentReviewAction === 'approve' ? 'approved' : 'rejected';
                    showNotification('success', `Card ${actionText}`, data.message);

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('cardFeedbackModal')).hide();

                    // Refresh the list
                    loadPendingReviews();
                } else {
                    showNotification('error', 'Error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error', `Failed to ${currentReviewAction} card`);
            });
        });

        // Show notification function
        function showNotification(type, title, message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            notification.innerHTML = `
                <strong>${title}:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }        function getPriorityClass(priority) {
            switch(priority) {
                case 'high': return 'danger';
                case 'medium': return 'warning';
                case 'low': return 'info';
                default: return 'secondary';
            }
        }

        function getTimeAgo(date) {
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 60) {
                return `${diffMins}m ago`;
            } else if (diffHours < 24) {
                return `${diffHours}h ago`;
            } else {
                return `${diffDays}d ago`;
            }
        }

        function displayAssignmentStatistics(statistics) {
            const statisticsContainer = document.getElementById('assignment-statistics');

            const statsHTML = `
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-primary">${statistics.total_assignments}</div>
                            <div class="stat-label">Total Assignments</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-warning">${statistics.assigned_status}</div>
                            <div class="stat-label">Assigned</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-info">${statistics.in_progress_status}</div>
                            <div class="stat-label">In Progress</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-success">${statistics.completed_status}</div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-danger">${statistics.overdue_assignments}</div>
                            <div class="stat-label">Overdue</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6 mb-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="stat-number text-secondary">${statistics.unique_assignees}</div>
                            <div class="stat-label">Team Members</div>
                        </div>
                    </div>
                </div>
            `;

            statisticsContainer.innerHTML = statsHTML;
        }

        function displayAssignedCards(assignedCards) {
            const assignedCardsGrid = document.getElementById('assigned-cards-grid');
            let cardsHTML = '';

            assignedCards.forEach(assignment => {
                const dueDate = assignment.due_date ? new Date(assignment.due_date).toLocaleDateString() : 'No due date';
                const assignedDate = new Date(assignment.assigned_at).toLocaleDateString();
                const isOverdue = assignment.is_overdue == 1;
                const priority = assignment.priority || 'medium';
                const totalSubtasks = parseInt(assignment.total_subtasks) || 0;
                const completedSubtasks = parseInt(assignment.completed_subtasks) || 0;
                const subtasksProgress = totalSubtasks > 0 ? Math.round((completedSubtasks / totalSubtasks) * 100) : 0;

                cardsHTML += `
                    <div class="col-lg-4 col-md-6 mb-4 assignment-item" data-status="${assignment.assignment_status}" data-assignee="${assignment.assigned_to}">
                        <div class="card assignment-card h-100 ${isOverdue ? 'border-danger' : ''}">
                            <div class="card-header d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">${assignment.title || 'Untitled'}</h6>
                                    <small class="text-muted">${assignment.project_name || 'Unknown Project'} • ${assignment.board_name || 'Unknown Board'}</small>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge ${getAssignmentStatusBadgeClass(assignment.assignment_status)} mb-1">${formatAssignmentStatus(assignment.assignment_status)}</span>
                                    <span class="badge ${getPriorityBadgeClass(priority)}">${priority.toUpperCase()}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                ${assignment.description ? `<p class="card-text small text-muted">${assignment.description.substring(0, 100)}${assignment.description.length > 100 ? '...' : ''}</p>` : ''}

                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-person-fill me-2 text-primary"></i>
                                        <strong>${assignment.assigned_to}</strong>
                                    </div>
                                    <small class="text-muted">Assigned on: ${assignedDate}</small>
                                </div>

                                ${totalSubtasks > 0 ? `
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted">Subtasks Progress</small>
                                            <small class="text-muted">${completedSubtasks}/${totalSubtasks}</small>
                                        </div>
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: ${subtasksProgress}%" aria-valuenow="${subtasksProgress}"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                ` : ''}

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge ${getStatusBadgeClass(assignment.card_status)}">${formatStatus(assignment.card_status)}</span>
                                    ${(parseInt(assignment.comments_count) > 0) ? `
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-chat-dots me-1"></i>
                                            <small class="text-muted">${assignment.comments_count}</small>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i>
                                        Due: ${dueDate}
                                    </small>
                                    ${isOverdue ? '<small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Overdue</small>' : ''}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewAssignmentDetail(${assignment.card_id}, '${assignment.assigned_username}')">
                                        <i class="bi bi-eye"></i> View Details
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="viewCard(${assignment.card_id})">
                                        <i class="bi bi-card-text"></i> Card
                                    </button>
                                </div>

                                <!-- Approval/Rejection Actions -->
                                ${assignment.assignment_status === 'completed' && assignment.approval_status === 'pending' ? `
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-warning fw-bold">
                                                <i class="bi bi-clock-history"></i> Pending Review
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-success flex-fill" onclick="approveCard(${assignment.card_id}, '${assignment.assigned_username}')">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                            <button class="btn btn-sm btn-danger flex-fill" onclick="rejectCard(${assignment.card_id}, '${assignment.assigned_username}')">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                ` : assignment.approval_status === 'approved' ? `
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex align-items-center text-success">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            <small class="fw-bold">Approved</small>
                                        </div>
                                    </div>
                                ` : assignment.approval_status === 'rejected' ? `
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex align-items-center text-danger">
                                            <i class="bi bi-x-circle-fill me-2"></i>
                                            <small class="fw-bold">Rejected</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="requestRevision(${assignment.card_id}, '${assignment.assigned_username}')">
                                            <i class="bi bi-arrow-clockwise"></i> Request Revision
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });

            assignedCardsGrid.innerHTML = cardsHTML;
        }

        function populateAssigneeFilter(assignments, selectElement) {
            const assignees = [...new Set(assignments.map(assignment => assignment.assigned_to))];

            selectElement.innerHTML = '<option value="">All Assignees</option>';
            assignees.forEach(assignee => {
                selectElement.innerHTML += `<option value="${assignee}">${assignee}</option>`;
            });
        }

        function getAssignmentStatusBadgeClass(status) {
            switch(status) {
                case 'assigned': return 'bg-warning';
                case 'in_progress': return 'bg-info';
                case 'completed': return 'bg-success';
                default: return 'bg-secondary';
            }
        }

        function formatAssignmentStatus(status) {
            return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function filterAssignedCards() {
            const statusFilter = document.getElementById('assignmentStatusFilter').value;
            const assigneeFilter = document.getElementById('assigneeFilter').value;
            const assignmentItems = document.querySelectorAll('.assignment-item');

            assignmentItems.forEach(item => {
                const assignmentStatus = item.getAttribute('data-status');
                const assignee = item.getAttribute('data-assignee');

                const statusMatch = !statusFilter || assignmentStatus === statusFilter;
                const assigneeMatch = !assigneeFilter || assignee === assigneeFilter;

                if (statusMatch && assigneeMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function viewAssignmentDetail(cardId, username) {
            // TODO: Implement assignment detail view
            console.log('View assignment detail:', cardId, username);
        }

        // Assigned History Functions
        let currentHistoryPage = 1;
        let currentHistoryFilters = {};

        function loadAssignmentHistory(page = 1, filters = {}) {
            currentHistoryPage = page;
            currentHistoryFilters = filters;

            const params = new URLSearchParams({
                page: page,
                limit: 20,
                ...filters
            });

            fetch('/api/teamlead/assigned-history?' + params)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayAssignmentHistory(data);
                        updateHistoryStatistics(data.summary);
                        updateHistoryPagination(data.pagination);
                        populateHistoryFilters(data.filters);
                    } else {
                        console.error('Error loading assignment history:', data.message);
                        showNoAssignmentHistory();
                    }
                })
                .catch(error => {
                    console.error('Error loading assignment history:', error);
                    showNoAssignmentHistory();
                });
        }

        function displayAssignmentHistory(data) {
            const tableBody = document.getElementById('assignmentHistoryTableBody');
            const noHistoryDiv = document.getElementById('no-assignment-history');

            if (!data.assignment_history || data.assignment_history.length === 0) {
                showNoAssignmentHistory();
                return;
            }

            noHistoryDiv.style.display = 'none';

            tableBody.innerHTML = data.assignment_history.map(history => {
                const assignedDate = new Date(history.assigned_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                const duration = calculateDuration(history.assigned_at, history.completed_at);
                const statusBadge = getAssignmentStatusBadge(history.assignment_status);
                const priorityBadge = getPriorityBadge(history.priority);

                return `
                    <tr class="history-item" data-status="${history.assignment_status}" data-user="${history.user_id}" data-project="${history.project_id}">
                        <td>
                            <div>
                                <strong>${history.title}</strong>
                                <br>
                                <small class="text-muted">${history.board_name}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2">
                                    <i class="bi bi-person-circle text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">${history.assigned_to}</div>
                                    <small class="text-muted">@${history.assigned_username}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>${history.project_name}</strong>
                            </div>
                        </td>
                        <td>${statusBadge}</td>
                        <td>
                            <div>
                                <div>${assignedDate}</div>
                                ${history.started_at ? `<small class="text-muted">Started: ${new Date(history.started_at).toLocaleDateString()}</small>` : ''}
                                ${history.completed_at ? `<small class="text-success d-block">Completed: ${new Date(history.completed_at).toLocaleDateString()}</small>` : ''}
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>${duration}</strong>
                                ${history.is_overdue ? '<br><small class="text-danger">Overdue</small>' : ''}
                            </div>
                        </td>
                        <td>${priorityBadge}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewHistoryDetail(${history.card_id})" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="viewAssignmentTimeline(${history.card_id}, ${history.user_id})" title="View Timeline">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            updateHistoryInfo(data.pagination);
        }

        function updateHistoryStatistics(summary) {
            if (!summary) return;

            document.getElementById('totalAssignments').textContent = summary.total_assignments || 0;
            document.getElementById('assignedCount').textContent = summary.assigned_count || 0;
            document.getElementById('inProgressCount').textContent = summary.in_progress_count || 0;
            document.getElementById('completedCount').textContent = summary.completed_count || 0;
            document.getElementById('overdueCount').textContent = summary.overdue_count || 0;

            const avgHours = summary.avg_completion_hours ? Math.round(summary.avg_completion_hours) : 0;
            document.getElementById('avgCompletionTime').textContent = avgHours + 'h';
        }

        function updateHistoryPagination(pagination) {
            const paginationContainer = document.getElementById('assignmentHistoryPagination');

            if (pagination.total_pages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            paginationHTML += `
                <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadAssignmentHistory(${pagination.current_page - 1}, currentHistoryFilters)">Previous</a>
                </li>
            `;

            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.total_pages, pagination.current_page + 2);

            if (startPage > 1) {
                paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadAssignmentHistory(1, currentHistoryFilters)">1</a></li>`;
                if (startPage > 2) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                    <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadAssignmentHistory(${i}, currentHistoryFilters)">${i}</a>
                    </li>
                `;
            }

            if (endPage < pagination.total_pages) {
                if (endPage < pagination.total_pages - 1) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadAssignmentHistory(${pagination.total_pages}, currentHistoryFilters)">${pagination.total_pages}</a></li>`;
            }

            // Next button
            paginationHTML += `
                <li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadAssignmentHistory(${pagination.current_page + 1}, currentHistoryFilters)">Next</a>
                </li>
            `;

            paginationContainer.innerHTML = paginationHTML;
        }

        function populateHistoryFilters(filters) {
            // Populate team members filter
            const userFilter = document.getElementById('historyUserFilter');
            userFilter.innerHTML = '<option value="">All Members</option>';
            filters.team_members.forEach(member => {
                userFilter.innerHTML += `<option value="${member.user_id}">${member.full_name} (@${member.username})</option>`;
            });

            // Populate projects filter
            const projectFilter = document.getElementById('historyProjectFilter');
            projectFilter.innerHTML = '<option value="">All Projects</option>';
            filters.projects.forEach(project => {
                projectFilter.innerHTML += `<option value="${project.project_id}">${project.project_name}</option>`;
            });
        }

        function updateHistoryInfo(pagination) {
            const start = ((pagination.current_page - 1) * pagination.per_page) + 1;
            const end = Math.min(pagination.current_page * pagination.per_page, pagination.total_count);
            const info = `Showing ${start}-${end} of ${pagination.total_count} assignments`;
            document.getElementById('assignmentHistoryInfo').textContent = info;
        }

        function showNoAssignmentHistory() {
            document.getElementById('assignmentHistoryTableBody').innerHTML = '';
            document.getElementById('no-assignment-history').style.display = 'block';
            document.getElementById('assignmentHistoryInfo').textContent = 'Showing 0 of 0 assignments';
            document.getElementById('assignmentHistoryPagination').innerHTML = '';
        }

        function applyAssignmentHistoryFilters() {
            const filters = {
                status: document.getElementById('historyStatusFilter').value,
                user_id: document.getElementById('historyUserFilter').value,
                project_id: document.getElementById('historyProjectFilter').value,
                date_from: document.getElementById('historyDateFrom').value,
                date_to: document.getElementById('historyDateTo').value
            };

            // Remove empty filters
            Object.keys(filters).forEach(key => {
                if (!filters[key] || filters[key] === 'all') {
                    delete filters[key];
                }
            });

            loadAssignmentHistory(1, filters);
        }

        function clearAssignmentHistoryFilters() {
            document.getElementById('historyStatusFilter').value = 'all';
            document.getElementById('historyUserFilter').value = '';
            document.getElementById('historyProjectFilter').value = '';
            document.getElementById('historyDateFrom').value = '';
            document.getElementById('historyDateTo').value = '';

            loadAssignmentHistory(1, {});
        }

        function refreshAssignmentHistory() {
            loadAssignmentHistory(currentHistoryPage, currentHistoryFilters);
        }

        function exportAssignmentHistory() {
            const filters = currentHistoryFilters;
            const params = new URLSearchParams(filters);
            params.append('export', 'csv');

            window.open('/api/teamlead/assigned-history?' + params, '_blank');
        }

        function calculateDuration(startDate, endDate) {
            const start = new Date(startDate);
            const end = endDate ? new Date(endDate) : new Date();
            const diffMs = end - start;
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffDays = Math.floor(diffHours / 24);

            if (diffDays > 0) {
                return `${diffDays}d ${diffHours % 24}h`;
            } else {
                return `${diffHours}h`;
            }
        }

        function getAssignmentStatusBadge(status) {
            const statusConfig = {
                'assigned': { class: 'bg-info', text: 'Assigned' },
                'in_progress': { class: 'bg-warning', text: 'In Progress' },
                'completed': { class: 'bg-success', text: 'Completed' }
            };

            const config = statusConfig[status] || { class: 'bg-secondary', text: status };
            return `<span class="badge ${config.class}">${config.text}</span>`;
        }

        function getPriorityBadge(priority) {
            const priorityConfig = {
                'low': { class: 'bg-success', text: 'Low' },
                'medium': { class: 'bg-warning', text: 'Medium' },
                'high': { class: 'bg-danger', text: 'High' }
            };

            const config = priorityConfig[priority] || { class: 'bg-secondary', text: priority };
            return `<span class="badge ${config.class}">${config.text}</span>`;
        }

        function viewHistoryDetail(cardId) {
            // TODO: Implement history detail view
            console.log('View history detail:', cardId);
        }

        function viewAssignmentTimeline(cardId, userId) {
            // TODO: Implement assignment timeline view
            console.log('View assignment timeline:', cardId, userId);
        }

        // Card Time Logs Functions
        let currentTimeLogsPage = 1;
        let currentTimeLogsFilters = {};
        let dailyTimeChart = null;

        function loadCardTimeLogs(page = 1, filters = {}) {
            currentTimeLogsPage = page;
            currentTimeLogsFilters = filters;

            const params = new URLSearchParams({
                page: page,
                limit: 20,
                ...filters
            });

            fetch('/api/teamlead/card-time-logs?' + params)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayTimeLogs(data);
                        updateTimeLogsStatistics(data.summary);
                        updateTimeLogsPagination(data.pagination);
                        populateTimeLogsFilters(data.filters);
                        updateDailyTimeChart(data.daily_stats);
                        updateTopPerformers(data.top_performers);
                    } else {
                        console.error('Error loading time logs:', data.message);
                        showNoTimeLogs();
                    }
                })
                .catch(error => {
                    console.error('Error loading time logs:', error);
                    showNoTimeLogs();
                });
        }

        function displayTimeLogs(data) {
            const tableBody = document.getElementById('timeLogsTableBody');
            const noTimeLogsDiv = document.getElementById('no-time-logs');

            if (!data.time_logs || data.time_logs.length === 0) {
                showNoTimeLogs();
                return;
            }

            noTimeLogsDiv.style.display = 'none';

            tableBody.innerHTML = data.time_logs.map(log => {
                const logDate = new Date(log.logged_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const priorityBadge = getPriorityBadge(log.priority);
                const statusBadge = getCardStatusBadge(log.card_status);

                return `
                    <tr class="time-log-item" data-user="${log.user_id}" data-project="${log.project_id}" data-card="${log.card_id}">
                        <td>
                            <div>
                                <strong>${log.task_title}</strong>
                                <br>
                                <small class="text-muted">${log.board_name}</small>
                                <br>
                                ${statusBadge} ${priorityBadge}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2">
                                    <i class="bi bi-person-circle text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">${log.full_name}</div>
                                    <small class="text-muted">@${log.username}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-center">
                                <span class="badge bg-primary fs-6">${log.hours_logged}h</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                ${log.description ? `<p class="mb-0 small">${log.description.length > 100 ? log.description.substring(0, 100) + '...' : log.description}</p>` : '<em class="text-muted">No description</em>'}
                            </div>
                        </td>
                        <td>
                            <div>
                                <div>${logDate}</div>
                                <small class="text-muted">${new Date(log.logged_at).toLocaleDateString()}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>${log.project_name}</strong>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="viewTimeLogDetail(${log.time_log_id})" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary" onclick="viewCardDetail(${log.card_id})" title="View Task">
                                    <i class="bi bi-card-text"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            updateTimeLogsInfo(data.pagination);
        }

        function updateTimeLogsStatistics(summary) {
            if (!summary) return;

            document.getElementById('totalHours').textContent = (summary.total_hours || 0) + 'h';
            document.getElementById('totalLogs').textContent = summary.total_logs || 0;
            document.getElementById('activeUsers').textContent = summary.unique_users || 0;
            document.getElementById('trackedCards').textContent = summary.unique_cards || 0;
            document.getElementById('activeDays').textContent = summary.unique_days || 0;

            const avgHours = summary.avg_hours_per_log ? parseFloat(summary.avg_hours_per_log).toFixed(1) : 0;
            document.getElementById('avgHoursPerLog').textContent = avgHours + 'h';
        }

        function updateTimeLogsPagination(pagination) {
            const paginationContainer = document.getElementById('timeLogsPagination');

            if (pagination.total_pages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            paginationHTML += `
                <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadCardTimeLogs(${pagination.current_page - 1}, currentTimeLogsFilters)">Previous</a>
                </li>
            `;

            // Page numbers
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.total_pages, pagination.current_page + 2);

            if (startPage > 1) {
                paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadCardTimeLogs(1, currentTimeLogsFilters)">1</a></li>`;
                if (startPage > 2) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                    <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadCardTimeLogs(${i}, currentTimeLogsFilters)">${i}</a>
                    </li>
                `;
            }

            if (endPage < pagination.total_pages) {
                if (endPage < pagination.total_pages - 1) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadCardTimeLogs(${pagination.total_pages}, currentTimeLogsFilters)">${pagination.total_pages}</a></li>`;
            }

            // Next button
            paginationHTML += `
                <li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadCardTimeLogs(${pagination.current_page + 1}, currentTimeLogsFilters)">Next</a>
                </li>
            `;

            paginationContainer.innerHTML = paginationHTML;
        }

        function populateTimeLogsFilters(filters) {
            // Populate team members filter
            const userFilter = document.getElementById('timeLogUserFilter');
            userFilter.innerHTML = '<option value="">All Members</option>';
            filters.team_members.forEach(member => {
                userFilter.innerHTML += `<option value="${member.user_id}">${member.full_name} (@${member.username})</option>`;
            });

            // Populate projects filter
            const projectFilter = document.getElementById('timeLogProjectFilter');
            projectFilter.innerHTML = '<option value="">All Projects</option>';
            filters.projects.forEach(project => {
                projectFilter.innerHTML += `<option value="${project.project_id}">${project.project_name}</option>`;
            });

            // Populate cards filter
            const cardFilter = document.getElementById('timeLogCardFilter');
            cardFilter.innerHTML = '<option value="">All Tasks</option>';
            filters.cards.forEach(card => {
                cardFilter.innerHTML += `<option value="${card.card_id}">${card.card_title} (${card.board_name})</option>`;
            });
        }

        function updateTimeLogsInfo(pagination) {
            const start = ((pagination.current_page - 1) * pagination.per_page) + 1;
            const end = Math.min(pagination.current_page * pagination.per_page, pagination.total_count);
            const info = `Showing ${start}-${end} of ${pagination.total_count} time logs`;
            document.getElementById('timeLogsInfo').textContent = info;
        }

        function updateDailyTimeChart(dailyStats) {
            const ctx = document.getElementById('dailyTimeChart').getContext('2d');

            // Destroy existing chart if it exists
            if (dailyTimeChart) {
                dailyTimeChart.destroy();
            }

            const labels = dailyStats.map(stat => new Date(stat.log_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            const hours = dailyStats.map(stat => parseFloat(stat.daily_hours));

            dailyTimeChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels.reverse(),
                    datasets: [{
                        label: 'Hours Logged',
                        data: hours.reverse(),
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Hours'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        function updateTopPerformers(topPerformers) {
            const container = document.getElementById('topPerformersList');

            if (!topPerformers || topPerformers.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No data available</p>';
                return;
            }

            container.innerHTML = topPerformers.map((performer, index) => `
                <div class="d-flex align-items-center mb-3">
                    <div class="performer-rank me-3">
                        <span class="badge ${index === 0 ? 'bg-warning' : index === 1 ? 'bg-light text-dark' : 'bg-secondary'}">${index + 1}</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium">${performer.full_name}</div>
                        <small class="text-muted">@${performer.username}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary">${performer.total_hours}h</div>
                        <small class="text-muted">${performer.total_logs} logs</small>
                    </div>
                </div>
            `).join('');
        }

        function showNoTimeLogs() {
            document.getElementById('timeLogsTableBody').innerHTML = '';
            document.getElementById('no-time-logs').style.display = 'block';
            document.getElementById('timeLogsInfo').textContent = 'Showing 0 of 0 time logs';
            document.getElementById('timeLogsPagination').innerHTML = '';
        }

        function applyTimeLogFilters() {
            const filters = {
                user_id: document.getElementById('timeLogUserFilter').value,
                project_id: document.getElementById('timeLogProjectFilter').value,
                card_id: document.getElementById('timeLogCardFilter').value,
                date_from: document.getElementById('timeLogDateFrom').value,
                date_to: document.getElementById('timeLogDateTo').value
            };

            // Remove empty filters
            Object.keys(filters).forEach(key => {
                if (!filters[key]) {
                    delete filters[key];
                }
            });

            loadCardTimeLogs(1, filters);
        }

        function clearTimeLogFilters() {
            document.getElementById('timeLogUserFilter').value = '';
            document.getElementById('timeLogProjectFilter').value = '';
            document.getElementById('timeLogCardFilter').value = '';
            document.getElementById('timeLogDateFrom').value = '';
            document.getElementById('timeLogDateTo').value = '';

            loadCardTimeLogs(1, {});
        }

        function refreshTimeLogs() {
            loadCardTimeLogs(currentTimeLogsPage, currentTimeLogsFilters);
        }

        function exportTimeLogs() {
            const filters = currentTimeLogsFilters;
            const params = new URLSearchParams(filters);
            params.append('export', 'csv');

            window.open('/api/teamlead/card-time-logs?' + params, '_blank');
        }

        function getCardStatusBadge(status) {
            const statusConfig = {
                'to_do': { class: 'bg-secondary', text: 'To Do' },
                'in_progress': { class: 'bg-warning', text: 'In Progress' },
                'review': { class: 'bg-info', text: 'Review' },
                'done': { class: 'bg-success', text: 'Done' }
            };

            const config = statusConfig[status] || { class: 'bg-secondary', text: status };
            return `<span class="badge ${config.class}">${config.text}</span>`;
        }

        function viewTimeLogDetail(timeLogId) {
            // TODO: Implement time log detail view
            console.log('View time log detail:', timeLogId);
        }

        function viewCardDetail(cardId) {
            // TODO: Implement card detail view
            console.log('View card detail:', cardId);
        }

        function filterCards() {
            const statusFilter = document.getElementById('cardStatusFilter').value;
            const projectFilter = document.getElementById('cardProjectFilter').value;
            const cardItems = document.querySelectorAll('.card-item');

            cardItems.forEach(item => {
                const cardStatus = item.getAttribute('data-status');
                const cardProject = item.getAttribute('data-project');

                const statusMatch = !statusFilter || cardStatus === statusFilter;
                const projectMatch = !projectFilter || cardProject === projectFilter;

                if (statusMatch && projectMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
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
            // Store current project ID and status for member management
            currentProjectId = project.project_id;
            currentProjectStatus = project.status;

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
                                <div class="text-white mb-3">
                                    <small class="d-block opacity-75">Created by</small>
                                    <strong>${project.creator ? project.creator.full_name : 'Unknown'}</strong>
                                </div>
                                ${project.status !== 'Completed' && project.status !== 'On Hold' ? `
                                    <div class="project-actions">
                                        <button class="btn btn-success btn-sm me-2" onclick="showCompleteProjectModal(${project.project_id})"
                                                title="Mark project as completed">
                                            <i class="bi bi-check-circle me-1"></i>Complete Project
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="showCancelProjectModal(${project.project_id})"
                                                title="Cancel project">
                                            <i class="bi bi-x-circle me-1"></i>Cancel Project
                                        </button>
                                    </div>
                                ` : project.status === 'On Hold' ? `
                                    <div class="project-actions">
                                        <button class="btn btn-info btn-sm" onclick="reactivateProject(${project.project_id})"
                                                title="Reactivate cancelled project">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Reactivate Project
                                        </button>
                                    </div>
                                ` : ''}
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
                                ${project.completed_at ? `
                                    <div class="info-item">
                                        <span class="info-label">Completed Date</span>
                                        <span class="info-value text-success">
                                            ${new Date(project.completed_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </span>
                                    </div>
                                    ${project.completed_by_user ? `
                                        <div class="info-item">
                                            <span class="info-label">Completed By</span>
                                            <span class="info-value text-success">${project.completed_by_user.full_name}</span>
                                        </div>
                                    ` : ''}
                                ` : ''}
                                ${project.cancelled_at ? `
                                    <div class="info-item">
                                        <span class="info-label">Cancelled Date</span>
                                        <span class="info-value text-warning">
                                            ${new Date(project.cancelled_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            })}
                                        </span>
                                    </div>
                                    ${project.cancelled_by_user ? `
                                        <div class="info-item">
                                            <span class="info-label">Cancelled By</span>
                                            <span class="info-value text-warning">${project.cancelled_by_user.full_name}</span>
                                        </div>
                                    ` : ''}
                                ` : ''}
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

                    ${project.completion_notes || project.cancellation_reason ? `
                        <!-- Status Notes Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="project-info-card">
                                    ${project.completion_notes ? `
                                        <h5 class="mb-3 text-success">
                                            <i class="bi bi-check-circle me-2"></i>Completion Notes
                                        </h5>
                                        <div class="alert alert-success">
                                            <p class="mb-0">${project.completion_notes}</p>
                                        </div>
                                    ` : ''}
                                    ${project.cancellation_reason ? `
                                        <h5 class="mb-3 text-warning">
                                            <i class="bi bi-x-circle me-2"></i>Cancellation Reason
                                        </h5>
                                        <div class="alert alert-warning">
                                            <p class="mb-0">${project.cancellation_reason}</p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    ` : ''}

                    <!-- Team Members Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="project-info-card">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">
                                        <i class="bi bi-people-fill me-2"></i>Team Members
                                    </h5>
                                    ${project.status !== 'Completed' ? `
                                        <button class="btn btn-sm btn-primary" onclick="addTeamMember(${project.project_id})">
                                            <i class="bi bi-person-plus me-1"></i>Add Member
                                        </button>
                                    ` : `
                                        <button class="btn btn-sm btn-secondary" disabled title="Cannot add members to completed project">
                                            <i class="bi bi-person-plus me-1"></i>Add Member
                                        </button>
                                    `}
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
                        ${currentProjectStatus !== 'Completed' ? `
                            <button class="btn btn-primary btn-sm" onclick="addTeamMember(currentProjectId)">
                                <i class="bi bi-person-plus me-1"></i>Add First Member
                            </button>
                        ` : `
                            <small class="text-muted">Cannot add members to completed project</small>
                        `}
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
                                                ${currentProjectStatus !== 'Completed' ? `
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="removeMember(${member.member_id}, '${member.user.full_name}')">
                                                        <i class="bi bi-person-dash me-2"></i>Remove from Project
                                                    </a></li>
                                                ` : ''}
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

        // Global variable to store current project ID and available users
        let currentProjectId = null;
        let currentProjectStatus = null;
        let availableUsersData = [];

        function addTeamMember(projectId) {
            currentProjectId = projectId;

            // Reset filter
            document.getElementById('roleFilter').value = '';

            // Clear previous alert messages
            hideMemberAlert('addMemberAlert');

            // Reset form
            const form = document.getElementById('addMemberForm');
            if (form) {
                form.reset();
            }

            // Clear any validation errors
            clearMemberFormErrors('addMemberForm');

            // Load available users
            fetch(`/api/projects/${projectId}/available-users`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        availableUsersData = data.data;
                        populateUserSelect(availableUsersData);
                    } else {
                        availableUsersData = [];
                        const userSelect = document.getElementById('memberUser');
                        userSelect.innerHTML = '<option value="">No available users</option>';
                        document.getElementById('userCountInfo').textContent = 'No users available to add';
                    }

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('addMemberModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error loading available users:', error);
                    showAlert('addMemberAlert', 'error', 'Failed to load available users');
                });
        }

        function populateUserSelect(users) {
            const userSelect = document.getElementById('memberUser');
            const userCountInfo = document.getElementById('userCountInfo');

            if (users.length > 0) {
                userSelect.innerHTML = `
                    <option value="">Select User</option>
                    ${users.map(user => `
                        <option value="${user.user_id}" data-role="${user.role}">
                            ${user.full_name} (${user.email}) - ${user.role}
                        </option>
                    `).join('')}
                `;
                userCountInfo.textContent = `${users.length} user(s) available`;
            } else {
                userSelect.innerHTML = '<option value="">No users match the selected role</option>';
                userCountInfo.textContent = 'No users found';
            }
        }

        function filterUsersByRole() {
            const selectedRole = document.getElementById('roleFilter').value;

            if (!selectedRole) {
                // Show all users if no filter selected
                populateUserSelect(availableUsersData);
            } else {
                // Filter users by selected role
                const filteredUsers = availableUsersData.filter(user => user.role === selectedRole);
                populateUserSelect(filteredUsers);
            }
        }

        function viewMemberDetails(userId) {
            // Reuse existing viewUserDetail function
            viewUserDetail(userId);
        }

        function changeMemberRole(memberId, memberName, currentRole) {
            // Store current member data
            window.currentMember = { id: memberId, name: memberName, role: currentRole };

            // Update member info display
            document.getElementById('memberInfo').innerHTML = `
                <div class="member-avatar me-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; font-weight: bold;">
                    ${memberName.split(' ').map(n => n[0]).join('').toUpperCase()}
                </div>
                <div>
                    <h6 class="mb-1">${memberName}</h6>
                    <small class="text-muted">Current role: ${currentRole}</small>
                </div>
            `;

            // Set current role in select
            const roleSelect = document.getElementById('newRole');
            roleSelect.value = currentRole;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('changeRoleModal'));
            modal.show();
        }

        function removeMember(memberId, memberName) {
            if (confirm(`Are you sure you want to remove ${memberName} from this project?`)) {
                const projectId = currentProjectId;

                fetch(`/api/projects/${projectId}/members/${memberId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('projectDetailContent', 'success', data.message || 'Member removed successfully');
                        // Reload team data
                        loadProjectTeamData(projectId);
                    } else {
                        showAlert('projectDetailContent', 'error', data.message || 'Failed to remove member');
                    }
                })
                .catch(error => {
                    console.error('Error removing member:', error);
                    showAlert('projectDetailContent', 'error', 'An error occurred while removing member');
                });
            }
        }

        function editProject(projectId) {
            console.log('Attempting to edit project:', projectId);

            // Get project data first
            fetch(`/api/projects/${projectId}`)
                .then(response => {
                    console.log('Fetch response status:', response.status, response.statusText);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Project data received:', data);
                    if (data.success) {
                        const project = data.data;

                        // Populate the edit form - menggunakan project_id karena itu primary key sebenarnya
                        document.getElementById('editProjectId').value = project.project_id;
                        document.getElementById('editProjectName').value = project.project_name;
                        document.getElementById('editProjectDescription').value = project.description || '';

                        // Format deadline untuk input date
                        if (project.deadline) {
                            const deadlineDate = new Date(project.deadline);
                            const formattedDate = deadlineDate.toISOString().split('T')[0];
                            document.getElementById('editProjectDeadline').value = formattedDate;
                        } else {
                            document.getElementById('editProjectDeadline').value = '';
                        }

                        // Clear any previous form errors
                        clearFormErrors('editProjectForm');
                        hideAlert('editFormAlert');

                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById('editProjectModal'));
                        modal.show();
                    } else {
                        alert('Failed to load project data: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error loading project:', error);
                    alert('Failed to load project data. Error: ' + error.message);
                });
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

            // Convert FormData to JSON object for PUT request
            const userData = {
                username: formData.get('username'),
                full_name: formData.get('full_name'),
                email: formData.get('email'),
                role: formData.get('role'),
                current_task_status: formData.get('current_task_status')
            };

            // Only include password if provided
            const password = formData.get('password');
            if (password && password.trim() !== '') {
                userData.password = password;
            }

            // Debug log
            console.log('Sending user data:', userData);
            console.log('User ID:', userId);

            fetch(`/api/users/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                   document.querySelector('input[name="_token"]')?.value
                },
                body: JSON.stringify(userData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.status === 403) {
                    throw new Error('Access denied. Admin privileges required.');
                }
                if (response.status === 401) {
                    throw new Error('Authentication required. Please login again.');
                }
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message
                    showEditUserFormAlert('success', data.message || 'User updated successfully!');

                    // Close modal after delay
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        loadUsersList(); // Refresh user list

                        // Refresh project team data if project detail modal is open
                        if (currentProjectId && document.getElementById('projectDetailModal').classList.contains('show')) {
                            loadProjectTeamData(currentProjectId);
                        }
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
            initializeMemberManagement();

            // Load current project data
            loadCurrentProject();

            // Initialize create card form handlers
            initializeCreateCardForm();
        });

        // Member Management System
        function initializeMemberManagement() {
            // Add Member Form
            const addMemberForm = document.getElementById('addMemberForm');
            if (addMemberForm) {
                addMemberForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitAddMemberForm();
                });
            }

            // Note: Change Role feature removed - roles are automatically taken from user system
            // Keeping the form for potential future use

            // Reset alerts when modals are hidden
            const addMemberModal = document.getElementById('addMemberModal');
            if (addMemberModal) {
                addMemberModal.addEventListener('hidden.bs.modal', function() {
                    hideMemberAlert('addMemberAlert');
                    clearMemberFormErrors('addMemberForm');
                });
            }
        }

        function submitAddMemberForm() {
            const form = document.getElementById('addMemberForm');
            const formData = new FormData(form);
            const submitBtn = document.getElementById('addMemberSubmitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Clear previous errors
            clearMemberFormErrors('addMemberForm');
            hideMemberAlert('addMemberAlert');

            const projectId = currentProjectId;

            fetch(`/api/projects/${projectId}/members`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: formData.get('user_id')
                    // Role will be taken from user's system role in backend
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMemberAlert('addMemberAlert', 'success', data.message || 'Member added successfully');
                    form.reset();

                    // Close modal after success
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addMemberModal'));
                        modal.hide();

                        // Reload team data
                        loadProjectTeamData(projectId);
                    }, 1500);
                } else {
                    if (data.errors) {
                        showMemberValidationErrors('addMemberForm', data.errors);
                    } else {
                        showMemberAlert('addMemberAlert', 'error', data.message || 'Failed to add member');
                    }
                }
            })
            .catch(error => {
                console.error('Error adding member:', error);
                showMemberAlert('addMemberAlert', 'error', 'An error occurred while adding member');
            })
            .finally(() => {
                // Hide loading state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            });
        }

        function submitChangeRoleForm() {
            const form = document.getElementById('changeRoleForm');
            const formData = new FormData(form);
            const submitBtn = document.getElementById('changeRoleSubmitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            submitBtn.disabled = true;

            // Clear previous errors
            clearMemberFormErrors('changeRoleForm');
            hideMemberAlert('changeRoleAlert');

            const memberId = window.currentMember.id;
            const projectId = currentProjectId;

            fetch(`/api/projects/${projectId}/members/${memberId}/role`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    role: formData.get('role')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMemberAlert('changeRoleAlert', 'success', data.message || 'Role updated successfully');

                    // Close modal after success
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('changeRoleModal'));
                        modal.hide();

                        // Reload team data
                        loadProjectTeamData(projectId);
                    }, 1500);
                } else {
                    if (data.errors) {
                        showMemberValidationErrors('changeRoleForm', data.errors);
                    } else {
                        showMemberAlert('changeRoleAlert', 'error', data.message || 'Failed to update role');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating role:', error);
                showMemberAlert('changeRoleAlert', 'error', 'An error occurred while updating role');
            })
            .finally(() => {
                // Hide loading state
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                submitBtn.disabled = false;
            });
        }

        // Utility functions for member management
        function showMemberAlert(alertId, type, message) {
            const alertDiv = document.getElementById(alertId);
            if (alertDiv) {
                alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type}`;
                alertDiv.textContent = message;
                alertDiv.classList.remove('d-none');
            }
        }

        function hideMemberAlert(alertId) {
            const alertDiv = document.getElementById(alertId);
            if (alertDiv) {
                alertDiv.classList.add('d-none');
            }
        }

        function clearMemberFormErrors(formId) {
            const form = document.getElementById(formId);
            if (form) {
                form.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                form.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                });
            }
        }

        function showMemberValidationErrors(formId, errors) {
            const form = document.getElementById(formId);
            if (form) {
                Object.keys(errors).forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    const feedback = form.querySelector(`[name="${fieldName}"] + .invalid-feedback`);

                    if (field) {
                        field.classList.add('is-invalid');
                        if (feedback) {
                            feedback.textContent = errors[fieldName][0];
                        }
                    }
                });
            }
        }

        // Project Status Management Functions
        let currentProjectIdForStatus = null;

        function showCompleteProjectModal(projectId) {
            currentProjectIdForStatus = projectId;
            const modal = new bootstrap.Modal(document.getElementById('completeProjectModal'));
            modal.show();
        }

        function showCancelProjectModal(projectId) {
            currentProjectIdForStatus = projectId;
            const modal = new bootstrap.Modal(document.getElementById('cancelProjectModal'));
            modal.show();
        }

        // Handle Complete Project Form
        document.getElementById('completeProjectForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('completeProjectBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');

            // Show loading state
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';
            btn.disabled = true;

            const formData = {
                completion_notes: document.getElementById('completionNotes').value
            };

            fetch(`/api/projects/${currentProjectIdForStatus}/complete`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('completeProjectAlert', 'success', data.message || 'Project completed successfully');
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('completeProjectModal'));
                        modal.hide();
                        viewProjectDetail(currentProjectIdForStatus);
                        if (typeof loadProjectsList === 'function') {
                            loadProjectsList();
                        }
                    }, 1500);
                } else {
                    showAlert('completeProjectAlert', 'error', data.message || 'Failed to complete project');
                }
            })
            .catch(error => {
                console.error('Error completing project:', error);
                showAlert('completeProjectAlert', 'error', 'An error occurred while completing the project');
            })
            .finally(() => {
                // Reset loading state
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                btn.disabled = false;
            });
        });

        // Handle Cancel Project Form
        document.getElementById('cancelProjectForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('cancelProjectBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');

            // Show loading state
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';
            btn.disabled = true;

            const formData = {
                cancellation_reason: document.getElementById('cancellationReason').value
            };

            fetch(`/api/projects/${currentProjectIdForStatus}/cancel`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('cancelProjectAlert', 'success', data.message || 'Project cancelled successfully');
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('cancelProjectModal'));
                        modal.hide();
                        viewProjectDetail(currentProjectIdForStatus);
                        if (typeof loadProjectsList === 'function') {
                            loadProjectsList();
                        }
                    }, 1500);
                } else {
                    showAlert('cancelProjectAlert', 'error', data.message || 'Failed to cancel project');
                }
            })
            .catch(error => {
                console.error('Error cancelling project:', error);
                showAlert('cancelProjectAlert', 'error', 'An error occurred while cancelling the project');
            })
            .finally(() => {
                // Reset loading state
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
                btn.disabled = false;
            });
        });

        function reactivateProject(projectId) {
            if (!confirm('Are you sure you want to reactivate this project? This will change the status back to In Progress.')) {
                return;
            }

            fetch(`/api/projects/${projectId}/reactivate`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('projectDetailContent', 'success', data.message || 'Project reactivated successfully');
                    setTimeout(() => {
                        viewProjectDetail(projectId);
                    }, 1500);
                    if (typeof loadProjectsList === 'function') {
                        loadProjectsList();
                    }
                } else {
                    showAlert('projectDetailContent', 'error', data.message || 'Failed to reactivate project');
                }
            })
            .catch(error => {
                console.error('Error reactivating project:', error);
                showAlert('projectDetailContent', 'error', 'An error occurred while reactivating the project');
            });
        }

        // Report Management Functions
        let activeReport = 'projects';

        function initializeReportManagement() {
            // Set default date range (last 30 days)
            const today = new Date();
            const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));

            document.getElementById('reportDateFrom').value = thirtyDaysAgo.toISOString().split('T')[0];
            document.getElementById('reportDateTo').value = today.toISOString().split('T')[0];

            // Initialize tab change listeners
            const reportTabs = document.querySelectorAll('#reportTabs button');
            reportTabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    activeReport = event.target.getAttribute('data-bs-target').replace('#', '').replace('-report', '');
                    loadReportData();
                });
            });

            // Load initial data
            if (document.getElementById('reports-content').style.display !== 'none') {
                loadReportStatistics();
                loadReportData();
            }
        }

        function loadReportData() {
            loadReportStatistics();

            switch(activeReport) {
                case 'projects':
                    loadProjectsReport();
                    break;
                case 'users':
                    loadUsersReport();
                    break;
                case 'timeline':
                    loadTimelineReport();
                    break;
            }
        }

        function refreshReports() {
            showAlert('reports-content', 'info', 'Refreshing reports...');
            loadReportData();
        }

        function clearReportFilters() {
            document.getElementById('reportDateFrom').value = '';
            document.getElementById('reportDateTo').value = '';
            document.getElementById('reportStatus').value = '';
            document.getElementById('reportRole').value = '';
            loadReportData();
            showAlert('reports-content', 'info', 'Filters cleared successfully!');
        }

        function exportReport(type = null) {
            const reportType = type || activeReport;
            const dateFrom = document.getElementById('reportDateFrom').value;
            const dateTo = document.getElementById('reportDateTo').value;
            const status = document.getElementById('reportStatus').value;
            const role = document.getElementById('reportRole').value;

            const params = new URLSearchParams({
                type: reportType,
                date_from: dateFrom,
                date_to: dateTo,
                status: status,
                role: role
            });

            // Download file
            const url = `/admin/reports/export?${params.toString()}`;
            const link = document.createElement('a');
            link.href = url;
            link.download = `${reportType}_report_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function applyReportFilters() {
            loadReportData();
            showAlert('reports-content', 'info', 'Filters applied successfully!');
        }

        function loadReportStatistics() {
            const dateFrom = document.getElementById('reportDateFrom').value;
            const dateTo = document.getElementById('reportDateTo').value;
            const status = document.getElementById('reportStatus').value;

            const params = new URLSearchParams({
                date_from: dateFrom,
                date_to: dateTo,
                status: status
            });

            fetch(`/api/reports/statistics?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('reportTotalProjects').textContent = data.data.total_projects || 0;
                        document.getElementById('reportCompletedProjects').textContent = data.data.completed_projects || 0;
                        document.getElementById('reportInProgressProjects').textContent = data.data.in_progress_projects || 0;
                        document.getElementById('reportActiveUsers').textContent = data.data.active_users || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading report statistics:', error);
                });
        }

        function loadProjectsReport() {
            const dateFrom = document.getElementById('reportDateFrom').value;
            const dateTo = document.getElementById('reportDateTo').value;
            const status = document.getElementById('reportStatus').value;

            const params = new URLSearchParams({
                date_from: dateFrom,
                date_to: dateTo,
                status: status
            });

            fetch(`/api/reports/projects?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('projectsReportTable');
                        tbody.innerHTML = '';

                        if (data.data.data.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-folder-x display-6"></i>
                                        <p class="mt-2">No projects found</p>
                                    </td>
                                </tr>
                            `;
                            return;
                        }

                        data.data.data.forEach(project => {
                            const statusBadge = getProjectStatusBadge(project.status);
                            const duration = project.duration_days ? `${project.duration_days} days` : 'N/A';

                            tbody.innerHTML += `
                                <tr>
                                    <td>${project.project_id}</td>
                                    <td>${project.project_name}</td>
                                    <td>${statusBadge}</td>
                                    <td>${formatDate(project.start_date)}</td>
                                    <td>${formatDate(project.deadline)}</td>
                                    <td>${project.member_count}</td>
                                    <td>${duration}</td>
                                </tr>
                            `;
                        });

                        // Update pagination
                        updatePagination('projectsPagination', data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading projects report:', error);
                    document.getElementById('projectsReportTable').innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-4 text-danger">
                                <i class="bi bi-exclamation-circle display-6"></i>
                                <p class="mt-2">Error loading projects report</p>
                            </td>
                        </tr>
                    `;
                });
        }

        function loadUsersReport() {
            const role = document.getElementById('reportRole').value;

            const params = new URLSearchParams({
                role: role
            });

            fetch(`/api/reports/users?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('usersReportTable');
                        tbody.innerHTML = '';

                        if (data.data.data.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="bi bi-person-x display-6"></i>
                                        <p class="mt-2">No users found</p>
                                    </td>
                                </tr>
                            `;
                            return;
                        }

                        data.data.data.forEach(user => {
                            const roleBadge = getUserRoleBadge(user.role);

                            tbody.innerHTML += `
                                <tr>
                                    <td>${user.user_id}</td>
                                    <td>${user.username}</td>
                                    <td>${user.full_name}</td>
                                    <td>${roleBadge}</td>
                                    <td>${user.projects_created}</td>
                                    <td>${user.projects_member}</td>
                                    <td>${formatDate(user.created_at)}</td>
                                </tr>
                            `;
                        });

                        // Update pagination
                        updatePagination('usersPagination', data.data);
                    }
                })
                .catch(error => {
                    console.error('Error loading users report:', error);
                    document.getElementById('usersReportTable').innerHTML = `
                        <tr>
                            <td colspan="7" class="text-center py-4 text-danger">
                                <i class="bi bi-exclamation-circle display-6"></i>
                                <p class="mt-2">Error loading users report</p>
                            </td>
                        </tr>
                    `;
                });
        }

        function loadTimelineReport() {
            const dateFrom = document.getElementById('reportDateFrom').value;
            const dateTo = document.getElementById('reportDateTo').value;

            const params = new URLSearchParams({
                date_from: dateFrom,
                date_to: dateTo
            });

            fetch(`/api/reports/timeline?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('timelineReportTable');
                        tbody.innerHTML = '';

                        if (data.data.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-calendar-x display-6"></i>
                                        <p class="mt-2">No timeline data found</p>
                                    </td>
                                </tr>
                            `;
                            return;
                        }

                        data.data.forEach(item => {
                            const eventType = item.type === 'created' ? 'Project Created' : 'Project Completed';
                            const eventIcon = item.type === 'created' ? 'bi-plus-circle text-primary' : 'bi-check-circle text-success';

                            tbody.innerHTML += `
                                <tr>
                                    <td>${formatDate(item.date)}</td>
                                    <td>
                                        <i class="bi ${eventIcon} me-2"></i>
                                        ${eventType}
                                    </td>
                                    <td>${item.count}</td>
                                    <td>${item.count} project(s) ${item.type}</td>
                                </tr>
                            `;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading timeline report:', error);
                    document.getElementById('timelineReportTable').innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4 text-danger">
                                <i class="bi bi-exclamation-circle display-6"></i>
                                <p class="mt-2">Error loading timeline report</p>
                            </td>
                        </tr>
                    `;
                });
        }

        function getProjectStatusBadge(status) {
            const statusMap = {
                'Planning': { class: 'bg-info', text: 'Planning' },
                'In Progress': { class: 'bg-primary', text: 'In Progress' },
                'On Hold': { class: 'bg-warning', text: 'On Hold' },
                'Completed': { class: 'bg-success', text: 'Completed' }
            };
            const s = statusMap[status] || { class: 'bg-secondary', text: status };
            return `<span class="badge ${s.class}">${s.text}</span>`;
        }

        function getUserRoleBadge(role) {
            const roleMap = {
                'admin': { class: 'bg-danger', text: 'Admin' },
                'team_lead': { class: 'bg-warning', text: 'Team Lead' },
                'member': { class: 'bg-info', text: 'Member' }
            };
            const r = roleMap[role] || { class: 'bg-secondary', text: role };
            return `<span class="badge ${r.class}">${r.text}</span>`;
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function updatePagination(containerId, paginationData) {
            const container = document.getElementById(containerId);
            if (!paginationData.links || paginationData.links.length <= 3) {
                container.innerHTML = '';
                return;
            }

            let paginationHtml = '<nav><ul class="pagination justify-content-center">';

            paginationData.links.forEach(link => {
                const isActive = link.active ? 'active' : '';
                const isDisabled = !link.url ? 'disabled' : '';

                paginationHtml += `
                    <li class="page-item ${isActive} ${isDisabled}">
                        <button class="page-link" onclick="loadReportPage('${link.url}')" ${!link.url ? 'disabled' : ''}>
                            ${link.label}
                        </button>
                    </li>
                `;
            });

            paginationHtml += '</ul></nav>';
            container.innerHTML = paginationHtml;
        }

        function loadReportPage(url) {
            if (!url) return;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the appropriate report based on active tab
                        switch(activeReport) {
                            case 'projects':
                                loadProjectsReport();
                                break;
                            case 'users':
                                loadUsersReport();
                                break;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading page:', error);
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
            initializeMemberManagement();
            initializeNotifications();
        });

        // Notification Management
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

    <!-- Create Card Modal -->
    <div class="modal fade" id="createCardModal" tabindex="-1" aria-labelledby="createCardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="modal-title d-flex align-items-center" id="createCardModalLabel">
                        <span class="badge bg-white text-primary me-2 p-2 rounded-circle">
                            <i class="bi bi-plus-circle fs-5"></i>
                        </span>
                        <div>
                            <div class="fw-bold">Create New Card</div>
                            <small class="opacity-75" style="font-size: 0.85rem;">Add a new task to your board</small>
                        </div>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createCardForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Board Selection -->
                            <div class="col-md-6">
                                <label for="cardBoard" class="form-label">
                                    <i class="bi bi-kanban me-1"></i>
                                    Board <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="cardBoard" name="board_id" required>
                                    <option value="">Select Board</option>
                                </select>
                                <div class="form-text">Choose the board where this card will be placed</div>
                            </div>

                            <!-- Priority -->
                            <div class="col-md-6">
                                <label for="cardPriority" class="form-label">
                                    <i class="bi bi-flag me-1"></i>
                                    Priority <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="cardPriority" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="low">🟢 Low</option>
                                    <option value="medium">🟡 Medium</option>
                                    <option value="high">� High</option>
                                </select>
                            </div>

                            <!-- Card Title -->
                            <div class="col-12">
                                <label for="cardTitle" class="form-label">
                                    <i class="bi bi-card-text me-1"></i>
                                    Card Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="cardTitle" name="card_title"
                                       placeholder="Enter card title" maxlength="100" required>
                                <div class="form-text">Brief, descriptive title for the task</div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="cardDescription" class="form-label">
                                    <i class="bi bi-text-paragraph me-1"></i>
                                    Description
                                </label>
                                <textarea class="form-control" id="cardDescription" name="description"
                                          rows="4" placeholder="Describe the task in detail..."></textarea>
                                <div class="form-text">Detailed description of what needs to be done</div>
                            </div>

                            <!-- Assign To -->
                            <div class="col-12">
                                <label for="cardAssignTo" class="form-label">
                                    <i class="bi bi-person-check me-1"></i>
                                    Assign To Team Member
                                </label>
                                <select class="form-select" id="cardAssignTo" name="assigned_to">
                                    <option value="">🚫 No Assignment - Keep Unassigned</option>
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Choose 1 developer or designer to assign this task. Members are sorted by workload (lowest first).
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6">
                                <label for="cardDueDate" class="form-label">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Due Date
                                </label>
                                <input type="date" class="form-control" id="cardDueDate" name="due_date"
                                       min="<?php echo date('Y-m-d', strtotime('tomorrow')); ?>">
                                <div class="form-text">Optional deadline for completion</div>
                            </div>

                            <!-- Assignment Preview -->
                            <div class="col-12" id="assignmentPreview" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Assignment Preview
                                    </h6>
                                    <div id="assignmentDetails"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="createCardBtn">
                            <i class="bi bi-plus-lg me-2"></i>
                            Create Card
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Board Modal -->
    <div class="modal fade" id="createBoardModal" tabindex="-1" aria-labelledby="createBoardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBoardModalLabel">
                        <i class="bi bi-kanban me-2"></i>
                        Create New Board
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createBoardForm">
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Board Name -->
                            <div class="col-12">
                                <label for="boardName" class="form-label">
                                    <i class="bi bi-kanban me-1"></i>
                                    Board Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="boardName" name="board_name"
                                       placeholder="Enter board name (e.g., Frontend, Backend, Testing)" maxlength="255" required>
                                <div class="form-text">Choose a descriptive name for organizing related tasks</div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="boardDescription" class="form-label">
                                    <i class="bi bi-text-paragraph me-1"></i>
                                    Description
                                </label>
                                <textarea class="form-control" id="boardDescription" name="description"
                                          rows="3" placeholder="Describe the purpose of this board..."></textarea>
                                <div class="form-text">Optional description explaining what this board is for</div>
                            </div>

                            <!-- Board Examples -->
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-lightbulb me-2"></i>
                                        Board Examples
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>By Technology:</strong>
                                            <ul class="mb-0">
                                                <li>Frontend Development</li>
                                                <li>Backend Development</li>
                                                <li>Database</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>By Phase:</strong>
                                            <ul class="mb-0">
                                                <li>Planning & Design</li>
                                                <li>Development</li>
                                                <li>Testing & QA</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="createBoardBtn">
                            <i class="bi bi-plus-lg me-2"></i>
                            Create Board
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Board Detail Modal -->
    <div class="modal fade" id="boardDetailModal" tabindex="-1" aria-labelledby="boardDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="boardDetailModalLabel">
                        <i class="bi bi-kanban me-2"></i>
                        Board Detail
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="boardDetailContent">
                        <!-- Board detail content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Board List Modal -->
    <div class="modal fade" id="boardListModal" tabindex="-1" aria-labelledby="boardListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="boardListModalLabel">
                        <i class="bi bi-kanban me-2"></i>
                        Project Boards Management
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="mb-1">Manage Project Boards</h6>
                            <p class="text-muted mb-0">Create, edit, and organize boards for better task management</p>
                        </div>
                        <button class="btn btn-primary" onclick="openCreateBoardModal()">
                            <i class="bi bi-plus-lg me-2"></i>
                            New Board
                        </button>
                    </div>
                    <div id="boardListContent">
                        <!-- Board list content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Board Management Functions
        function openCreateBoardModal() {
            const modal = new bootstrap.Modal(document.getElementById('createBoardModal'));
            document.getElementById('createBoardForm').reset();
            modal.show();
        }

        function submitCreateBoard() {
            const form = document.getElementById('createBoardForm');
            const formData = new FormData(form);
            const createBtn = document.getElementById('createBoardBtn');

            // Disable button and show loading
            createBtn.disabled = true;
            createBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Creating...';

            // Convert FormData to JSON
            const data = {};
            formData.forEach((value, key) => {
                if (value.trim() !== '') {
                    data[key] = value;
                }
            });

            fetch('/api/teamlead/boards', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', 'Board created successfully!', data.message);

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createBoardModal'));
                    modal.hide();

                    // Reset form
                    form.reset();

                    // Refresh boards list if open
                    if (document.getElementById('boardListModal').classList.contains('show')) {
                        loadBoardsList();
                    }

                    // Refresh project data
                    loadCurrentProject();
                } else {
                    showNotification('error', 'Error creating board', data.message);
                }
            })
            .catch(error => {
                console.error('Error creating board:', error);
                showNotification('error', 'Error creating board', 'An unexpected error occurred');
            })
            .finally(() => {
                // Re-enable button
                createBtn.disabled = false;
                createBtn.innerHTML = '<i class="bi bi-plus-lg me-2"></i>Create Board';
            });
        }

        function openBoardListModal() {
            const modal = new bootstrap.Modal(document.getElementById('boardListModal'));
            modal.show();
            loadBoardsList();
        }

        function loadBoardsList() {
            fetch('/api/teamlead/boards', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Boards data:', data);
                if (data.success) {
                    displayBoardsList(data.boards);
                } else {
                    document.getElementById('boardListContent').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading boards:', error);
                document.getElementById('boardListContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error loading boards
                    </div>
                `;
            });
        }

        function displayBoardsList(boards) {
            const content = document.getElementById('boardListContent');

            if (boards.length === 0) {
                content.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-kanban display-1 text-muted"></i>
                        <h5 class="mt-3">No Boards Yet</h5>
                        <p class="text-muted">Create your first board to start organizing tasks</p>
                        <button class="btn btn-primary" onclick="openCreateBoardModal()">
                            <i class="bi bi-plus-lg me-2"></i>
                            Create First Board
                        </button>
                    </div>
                `;
                return;
            }

            let html = '<div class="row">';
            boards.forEach(board => {
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 board-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="card-title mb-0">
                                        <i class="bi bi-kanban me-2"></i>
                                        ${board.name}
                                    </h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="viewBoardDetail(${board.id})">
                                                <i class="bi bi-eye me-2"></i>View Detail
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="editBoard(${board.id})">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteBoard(${board.id}, '${board.name}')">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>

                                <p class="card-text text-muted small">
                                    ${board.description || 'No description'}
                                </p>

                                <div class="row text-center mt-3">
                                    <div class="col-3">
                                        <div class="text-primary">
                                            <strong>${board.total_cards || 0}</strong>
                                            <small class="d-block">Total</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-secondary">
                                            <strong>${board.todo_cards || 0}</strong>
                                            <small class="d-block">To Do</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-warning">
                                            <strong>${board.in_progress_cards || 0}</strong>
                                            <small class="d-block">Progress</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-success">
                                            <strong>${board.done_cards || 0}</strong>
                                            <small class="d-block">Done</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">
                                    Created: ${new Date(board.created_at).toLocaleDateString()}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            content.innerHTML = html;
        }

        function viewBoardDetail(boardId) {
            fetch(`/api/teamlead/boards/${boardId}/detail`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayBoardDetail(data);
                } else {
                    showNotification('error', 'Error', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading board detail:', error);
                showNotification('error', 'Error', 'Failed to load board detail');
            });
        }

        function displayBoardDetail(data) {
            const modal = new bootstrap.Modal(document.getElementById('boardDetailModal'));
            const content = document.getElementById('boardDetailContent');

            document.getElementById('boardDetailModalLabel').innerHTML = `
                <i class="bi bi-kanban me-2"></i>
                ${data.board.board_name}
            `;

            let html = `
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h6>Description</h6>
                        <p class="text-muted">${data.board.description || 'No description provided'}</p>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Statistics</h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <strong class="text-primary">${data.statistics.total_cards}</strong>
                                        <small class="d-block">Total Cards</small>
                                    </div>
                                    <div class="col-6">
                                        <strong class="text-success">${data.statistics.assigned_cards}</strong>
                                        <small class="d-block">Assigned</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">📋 To Do (${data.statistics.todo_cards})</h6>
                            </div>
                            <div class="card-body">
            `;

            data.cards_by_status['To Do'].forEach(card => {
                html += `
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1">${card.title}</h6>
                            <small class="text-muted">
                                Priority: ${card.priority}
                                ${card.assigned_user_name ? `| Assigned to: ${card.assigned_user_name}` : ''}
                            </small>
                        </div>
                    </div>
                `;
            });

            html += `
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">⚡ In Progress (${data.statistics.in_progress_cards})</h6>
                            </div>
                            <div class="card-body">
            `;

            data.cards_by_status['In Progress'].forEach(card => {
                html += `
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1">${card.title}</h6>
                            <small class="text-muted">
                                Priority: ${card.priority}
                                ${card.assigned_user_name ? `| Assigned to: ${card.assigned_user_name}` : ''}
                            </small>
                        </div>
                    </div>
                `;
            });

            html += `
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">✅ Done (${data.statistics.done_cards})</h6>
                            </div>
                            <div class="card-body">
            `;

            data.cards_by_status['Done'].forEach(card => {
                html += `
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <h6 class="card-title mb-1">${card.title}</h6>
                            <small class="text-muted">
                                Priority: ${card.priority}
                                ${card.assigned_user_name ? `| Assigned to: ${card.assigned_user_name}` : ''}
                            </small>
                        </div>
                    </div>
                `;
            });

            html += `
                            </div>
                        </div>
                    </div>
                </div>
            `;

            content.innerHTML = html;
            modal.show();
        }

        function deleteBoard(boardId, boardName) {
            if (confirm(`Are you sure you want to delete board "${boardName}"? This action cannot be undone.`)) {
                fetch(`/api/teamlead/boards/${boardId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Board deleted successfully', data.message);
                        loadBoardsList(); // Refresh the list
                        loadCurrentProject(); // Refresh project data
                    } else {
                        showNotification('error', 'Error deleting board', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting board:', error);
                    showNotification('error', 'Error', 'Failed to delete board');
                });
            }
        }

        // Initialize create board form
        document.getElementById('createBoardForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitCreateBoard();
        });
    </script>

    <!-- Feedback Modal for Card Review -->
    <div class="modal fade" id="cardFeedbackModal" tabindex="-1" aria-labelledby="cardFeedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="feedbackModalHeader">
                    <h5 class="modal-title" id="cardFeedbackModalLabel">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Card Review
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cardFeedbackForm">
                    <div class="modal-body">
                        <div class="card border-0 bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-1" id="feedbackCardTitle">Card Title</h6>
                                <small class="text-muted" id="feedbackCardInfo">Card info</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="feedbackText" class="form-label">
                                <i class="bi bi-chat-square-text me-1"></i>
                                Feedback <span id="feedbackRequired" class="text-danger"></span>
                            </label>
                            <textarea class="form-control" id="feedbackText" name="feedback" rows="4"
                                    placeholder="Add your feedback here..."></textarea>
                            <div class="form-text" id="feedbackHelp">
                                Provide constructive feedback to help the team member improve.
                            </div>
                        </div>

                        <div class="alert alert-info d-none" id="approveInfo">
                            <i class="bi bi-check-circle me-2"></i>
                            This card will be marked as <strong>Done</strong> and moved to completed status.
                        </div>

                        <div class="alert alert-warning d-none" id="rejectInfo">
                            <i class="bi bi-arrow-repeat me-2"></i>
                            This card will be returned to <strong>In Progress</strong> status for revision.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn" id="submitFeedbackBtn">
                            <i class="bi bi-check-lg me-2"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Profile Edit Functions
        function toggleEditProfile() {
            document.getElementById('profile-display-mode').style.display = 'none';
            document.getElementById('profile-edit-mode').style.display = 'block';
        }

        function cancelEditProfile() {
            document.getElementById('profile-display-mode').style.display = 'block';
            document.getElementById('profile-edit-mode').style.display = 'none';
        }

        // Handle profile update form submission
        document.getElementById('profile-update-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            try {
                submitBtn.disabled = true;

                const response = await fetch('/api/profile/update', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Update display values
                    document.getElementById('display-name').textContent = formData.get('full_name') || '{{ $user->username }}';
                    document.getElementById('display-email').textContent = formData.get('email');
                    document.getElementById('profile-display-name').textContent = formData.get('full_name') || '{{ $user->username }}';

                    // Show success message
                    showNotification('Profile updated successfully', 'success');

                    // Switch back to display mode
                    cancelEditProfile();
                } else {
                    showNotification(result.message || 'Failed to update profile', 'error');
                }

            } catch (error) {
                console.error('Error updating profile:', error);
                showNotification('An error occurred while updating profile', 'error');
            } finally {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });

        // Upload profile photo
        async function uploadProfilePhoto(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('File size must be less than 2MB', 'error');
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showNotification('Please select a valid image file', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('profile_photo', file);

                try {
                    const response = await fetch('/api/profile/upload-photo', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Update profile photo display
                        document.getElementById('profile-photo-display').src = result.data.profile_photo_url;
                        showNotification('Profile photo updated successfully', 'success');

                        // Reload page to show delete button
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(result.message || 'Failed to upload photo', 'error');
                    }

                } catch (error) {
                    console.error('Error uploading photo:', error);
                    showNotification('An error occurred while uploading photo', 'error');
                }
            }
        }

        // Delete profile photo
        async function deleteProfilePhoto() {
            if (confirm('Are you sure you want to delete your profile photo?')) {
                try {
                    const response = await fetch('/api/profile/delete-photo', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Reset to default avatar
                        document.getElementById('profile-photo-display').src = '/uploads/profiles/default-avatar.png';
                        showNotification('Profile photo deleted successfully', 'success');

                        // Reload page to hide delete button
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(result.message || 'Failed to delete photo', 'error');
                    }

                } catch (error) {
                    console.error('Error deleting photo:', error);
                    showNotification('An error occurred while deleting photo', 'error');
                }
            }
        }

        // Utility Functions for Cards
        function getStatusColor(status) {
            const colors = {
                'todo': 'info',
                'in_progress': 'warning',
                'review': 'primary',
                'done': 'success'
            };
            return colors[status] || 'secondary';
        }

        function getPriorityColor(priority) {
            const colors = {
                'low': 'success',
                'medium': 'warning',
                'high': 'danger',
                'urgent': 'dark'
            };
            return colors[priority] || 'secondary';
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';

            const date = new Date(dateString);
            const now = new Date();
            const diffTime = Math.abs(now - date);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 1) {
                return 'Yesterday';
            } else if (diffDays <= 7) {
                return `${diffDays} days ago`;
            } else {
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }
        }

        function viewCardDetails(cardId) {
            // Implementation for viewing card details
            console.log('Viewing card details for ID:', cardId);
            // This would open a modal or navigate to card details page
        }

        function editCard(cardId) {
            // Implementation for editing card
            console.log('Editing card with ID:', cardId);
            // This would open an edit modal or form
        }
    </script>
</body>
</html>
