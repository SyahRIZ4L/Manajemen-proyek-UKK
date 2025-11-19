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

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile Menu Toggle Button */
        #admin-mobile-toggle {
            border: 2px solid #667eea !important;
            background: #ffffff !important;
            color: #667eea !important;
            border-radius: 12px;
            padding: 12px;
            width: 48px;
            height: 48px;
            font-size: 1.4rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            display: none;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1001;
            cursor: pointer;
            /* Touch optimizations */
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        #admin-mobile-toggle:hover,
        #admin-mobile-toggle:focus {
            background: #667eea !important;
            color: white !important;
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
            outline: none;
        }

        #admin-mobile-toggle:active {
            transform: scale(0.95);
        }

        #admin-mobile-toggle i {
            transition: transform 0.3s ease;
        }

        /* Enhanced sidebar for mobile */
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
                visibility: hidden;
                opacity: 0;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                           opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                           visibility 0s linear 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
                visibility: visible;
                opacity: 1;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                           opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                           visibility 0s linear 0s;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            /* Show mobile toggle button */
            #admin-mobile-toggle {
                display: flex !important;
                min-width: 48px;
                min-height: 48px;
            }

            /* Touch-friendly immediate feedback */
            #admin-mobile-toggle:active {
                transform: scale(0.95);
                background: #667eea !important;
                color: white !important;
                transition: all 0.1s ease;
            }

            /* Prevent body scroll when menu is open */
            body.admin-menu-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
                height: 100%;
            }

            /* Sidebar profile adjustments for mobile */
            .sidebar-profile {
                position: relative;
                bottom: auto;
                margin-top: 20px;
            }

            /* Ensure proper content spacing */
            .content-header {
                margin-top: 10px;
            }
        }

        @media (min-width: 769px) {
            .main-content {
                margin-left: 280px;
            }

            #admin-mobile-toggle {
                display: none !important;
            }

            .sidebar {
                transform: translateX(0);
                visibility: visible;
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="admin-sidebar-overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="admin-sidebar">
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

                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-action logout-btn" title="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>


            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <!-- Mobile Menu Toggle -->
                    <button class="me-3"
                            id="admin-mobile-toggle"
                            type="button"
                            aria-label="Toggle admin menu"
                            aria-expanded="false"
                            aria-controls="sidebar"
                            title="Toggle Menu">
                        <i class="bi bi-list" id="admin-toggle-icon"></i>
                    </button>

                    <div>
                        <h2 id="content-title" class="mb-0">Admin Dashboard</h2>
                        <p class="text-muted mb-0" id="content-subtitle">Welcome to the administrative control center</p>
                    </div>
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
                            <h3 class="stat-number text-primary">0</h3>
                            <p class="stat-label">Active Projects</p>
                            <small class="text-muted">+3 this month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-clock-history stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning">0</h3>
                            <p class="stat-label">Pending Tasks</p>
                            <small class="text-muted">Due this week</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-check-circle-fill stat-icon text-success"></i>
                            <h3 class="stat-number text-success">0</h3>
                            <p class="stat-label">Completed</p>
                            <small class="text-muted">Tasks this month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-people-fill stat-icon text-info"></i>
                            <h3 class="stat-number text-info">0</h3>
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
                                            <h6 class="mb-1">-</h6>
                                            <small class="text-muted">-</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">-</span>
                                    </div>
                                </div>

                                <div class="recent-project-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="project-icon bg-success bg-opacity-10 text-success me-3">
                                            <i class="bi bi-code-square"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">-</h6>
                                            <small class="text-muted">-</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">-</span>
                                    </div>
                                </div>

                                <div class="recent-project-item">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="project-icon bg-warning bg-opacity-10 text-warning me-3">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">-</h6>
                                            <small class="text-muted">-</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">-</span>
                                    </div>
                                </div>

                                <div class="recent-project-item">
                                    <div class="d-flex align-items-center mb-0">
                                        <div class="project-icon bg-info bg-opacity-10 text-info me-3">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">-</h6>
                                            <small class="text-muted">-</small>
                                        </div>
                                        <span class="badge bg-info rounded-pill">-</span>
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
                                            <h6 class="mb-1">-</h6>
                                            <small class="text-muted">-</small>
                                            <div class="mt-1">
                                                <small class="text-danger">
                                                    <i class="bi bi-exclamation-circle me-1"></i>-
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">-</small>
                                    </div>
                                </div>

                                <div class="upcoming-task-item">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="task-priority medium me-3"></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">-</h6>
                                            <small class="text-muted">-</small>
                                            <div class="mt-1">
                                                <small class="text-warning">
                                                    <i class="bi bi-dash-circle me-1"></i>-
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">-</small>
                                    </div>
                                </div>

                                <div class="upcoming-task-item">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="task-priority low me-3"></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Documentation Update</h6>
                                            <small class="text-muted">-</small>
                                            <div class="mt-1">
                                                <small class="text-success">
                                                    <i class="bi bi-circle me-1"></i>-
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">-</small>
                                    </div>
                                </div>

                                <div class="upcoming-task-item">
                                    <div class="d-flex align-items-start mb-0">
                                        <div class="task-priority medium me-3"></div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">-</h6>
                                            <small class="text-muted">-</small>
                                            <div class="mt-1">
                                                <small class="text-warning">
                                                    <i class="bi bi-dash-circle me-1"></i>-
                                                </small>
                                            </div>
                                        </div>
                                        <small class="text-muted">-</small>
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
                            <h3 class="stat-number text-danger">0</h3>
                            <p class="stat-label">Near Deadline</p>
                            <small class="text-muted">Within 7 days</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="project-stat-card">
                            <i class="bi bi-clock-history stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning">0</h3>
                            <p class="stat-label">Recent</p>
                            <small class="text-muted">Last updated</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="project-stat-card">
                            <i class="bi bi-people-fill stat-icon text-info"></i>
                            <h3 class="stat-number text-info">0</h3>
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
                            <div class="project-status status-progress">-</div>
                            <div class="project-menu">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                            </div>
                        </div>
                        <div class="project-body">
                            <h5 class="project-title">-</h5>
                            <p class="project-description">-n</p>

                            <div class="project-progress">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">-</small>
                                    <small class="text-muted">-</small>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: 65%"></div>
                                </div>
                            </div>

                            <div class="project-info mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="project-team">
                                        <div class="avatar-group">
                                            <div class="avatar">-</div>
                                            <div class="avatar">-</div>
                                            <div class="avatar">-</div>
                                        </div>
                                    </div>
                                    <div class="project-deadline">
                                        <small class="text-danger">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            -
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
                            <h3 class="stat-number text-primary" id="reportTotalProjects">Loading...</h3>
                            <p class="stat-label">Total Projects</p>
                            <small class="text-muted">All projects</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <i class="bi bi-check-circle-fill stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="reportCompletedProjects">Loading...</h3>
                            <p class="stat-label">Completed Projects</p>
                            <small class="text-muted">Successfully finished</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <i class="bi bi-clock-history stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="reportInProgressProjects">Loading...</h3>
                            <p class="stat-label">In Progress</p>
                            <small class="text-muted">Currently active</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="report-stat-card">
                            <i class="bi bi-people-fill stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="reportActiveUsers">Loading...</h3>
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
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-person-circle me-2 text-primary"></i>
                                        Account Information
                                    </h5>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                        <i class="bi bi-pencil me-1"></i>Edit Profile
                                    </button>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Name:</strong>
                                    </div>
                                    <div class="col-sm-9 user-name">
                                        {{ $user->full_name ?? $user->name }}
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
                                    <div class="col-sm-9 user-email">
                                        {{ $user->email }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Phone:</strong>
                                    </div>
                                    <div class="col-sm-9 user-phone">
                                        {{ $user->phone ?? 'Belum diisi' }}
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
                                        <strong>Bio:</strong>
                                    </div>
                                    <div class="col-sm-9 user-bio">
                                        {{ $user->bio ?? 'Belum ada bio' }}
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
                                <h5 class="user-name">{{ $user->full_name ?? $user->name }}</h5>
                                <p class="text-muted">System Administrator</p>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="bi bi-pencil me-1"></i>Edit Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="bi bi-person-gear me-2"></i>
                        Edit Profil Admin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProfileForm">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person me-2"></i>Informasi Dasar
                                </h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="edit_full_name" name="full_name"
                                       value="{{ $user->full_name ?? $user->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="edit_username" name="username"
                                       value="{{ $user->username }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email"
                                       value="{{ $user->email }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone"
                                       value="{{ $user->phone }}" placeholder="08xxxxxxxxxx">
                            </div>

                            <!-- Personal Information -->
                            <div class="col-12 mt-3">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-badge me-2"></i>Informasi Personal
                                </h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_birth_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="edit_birth_date" name="birth_date"
                                       value="{{ $user->birth_date ? $user->birth_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="edit_gender" name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="edit_address" name="address" rows="2"
                                          placeholder="Masukkan alamat lengkap">{{ $user->address }}</textarea>
                            </div>

                            <!-- Professional Information -->
                            <div class="col-12 mt-3">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-briefcase me-2"></i>Informasi Profesional
                                </h6>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_bio" class="form-label">Bio/Deskripsi</label>
                                <textarea class="form-control" id="edit_bio" name="bio" rows="3"
                                          placeholder="Ceritakan tentang diri Anda sebagai administrator">{{ $user->bio }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_website" class="form-label">Website/Portfolio</label>
                                <input type="url" class="form-control" id="edit_website" name="website"
                                       value="{{ $user->website }}" placeholder="https://example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_skills" class="form-label">Keahlian</label>
                                <input type="text" class="form-control" id="edit_skills" name="skills"
                                       value="{{ is_array($user->skills) ? implode(',', $user->skills) : $user->skills }}"
                                       placeholder="PHP, Laravel, Management (pisahkan dengan koma)">
                            </div>

                            <!-- Password Change -->
                            <div class="col-12 mt-3">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-lock me-2"></i>Ubah Password (Opsional)
                                </h6>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="edit_password" name="password"
                                       placeholder="Masukkan password baru">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
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
                            <span class="btn-loading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Updating...
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
                            <span class="btn-loading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Completing...
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
                            <span class="btn-loading" style="display: none;">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Cancelling...
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
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Adding...
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
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Updating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Create Board Modal -->
    <div class="modal fade" id="adminCreateBoardModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2"></i>Create New Board
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="adminCreateBoardForm">
                    @csrf
                    <input type="hidden" id="adminBoardProjectId" name="project_id">
                    <div class="modal-body">
                        <div id="adminCreateBoardAlert" class="alert d-none"></div>

                        <div class="mb-3">
                            <label for="adminBoardName" class="form-label">Board Name *</label>
                            <input type="text" class="form-control" id="adminBoardName" name="board_name" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="adminBoardDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="adminBoardDescription" name="description" rows="3"
                                      placeholder="Enter board description (optional)"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="adminCreateBoardBtn">
                            <span class="btn-text">
                                <i class="bi bi-check-circle me-1"></i>Create Board
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Creating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Admin Edit Board Modal -->
    <div class="modal fade" id="adminEditBoardModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-pencil me-2"></i>Edit Board
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="adminEditBoardForm">
                    @csrf
                    <input type="hidden" id="adminEditBoardId" name="board_id">
                    <div class="modal-body">
                        <div id="adminEditBoardAlert" class="alert d-none"></div>

                        <div class="mb-3">
                            <label for="adminEditBoardName" class="form-label">Board Name *</label>
                            <input type="text" class="form-control" id="adminEditBoardName" name="board_name" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="adminEditBoardDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="adminEditBoardDescription" name="description" rows="3"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning" id="adminEditBoardBtn">
                            <span class="btn-text">
                                <i class="bi bi-check-circle me-1"></i>Update Board
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Updating...
                            </span>
                        </button>
                    </div>
                </form>
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
            } else if (section === 'reports') {
                if (typeof loadReportData === 'function') {
                    loadReportData();
                }
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

        function handleEditProject(event) {
            event.preventDefault();
            console.log('Handle edit project form submission started');

            const form = event.target;
            const formData = new FormData(form);
            const projectId = document.getElementById('editProjectId').value;
            const submitBtn = document.getElementById('editSubmitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

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

            // Show loading state
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';
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
                case 'on hold': return 'status-cancelled';
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

                    <!-- Project Boards Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="project-info-card">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0">
                                        <i class="bi bi-kanban me-2"></i>Project Boards
                                    </h5>
                                    <button class="btn btn-sm btn-primary" onclick="openAdminCreateBoardModal(${project.project_id})">
                                        <i class="bi bi-plus-lg me-1"></i>Create Board
                                    </button>
                                </div>

                                <!-- Board Statistics -->
                                <div class="row g-3 mb-4" id="projectBoardStats">
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center py-3">
                                                <i class="bi bi-circle fs-3 text-secondary"></i>
                                                <h4 class="mb-0 mt-2 text-secondary" id="todoCount">-</h4>
                                                <small class="text-muted">To Do</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center py-3">
                                                <i class="bi bi-arrow-clockwise fs-3 text-warning"></i>
                                                <h4 class="mb-0 mt-2 text-warning" id="inProgressCount">-</h4>
                                                <small class="text-muted">In Progress</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center py-3">
                                                <i class="bi bi-eye fs-3 text-info"></i>
                                                <h4 class="mb-0 mt-2 text-info" id="reviewCount">-</h4>
                                                <small class="text-muted">Review</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center py-3">
                                                <i class="bi bi-check-circle fs-3 text-success"></i>
                                                <h4 class="mb-0 mt-2 text-success" id="doneCount">-</h4>
                                                <small class="text-muted">Done</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boards List -->
                                <div id="projectBoardsList">
                                    <div class="text-center py-4">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <p class="mt-2 text-muted small">Loading boards...</p>
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

            // Load boards list
            loadProjectBoardsList(projectId);
        }

        function loadProjectBoardsList(projectId) {
            const boardsList = document.getElementById('projectBoardsList');

            fetch(`/api/projects/${projectId}/boards`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.boards && data.boards.length > 0) {
                        displayProjectBoards(data.boards, projectId);
                    } else {
                        boardsList.innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-kanban text-muted mb-3" style="font-size: 3rem;"></i>
                                <h6 class="text-muted">No Boards Yet</h6>
                                <p class="text-muted small">Create your first board to organize project tasks</p>
                                <button class="btn btn-primary mt-2" onclick="openAdminCreateBoardModal(${projectId})">
                                    <i class="bi bi-plus-lg me-1"></i>Create Board
                                </button>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading boards:', error);
                    boardsList.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Error loading boards
                        </div>
                    `;
                });
        }

        function displayProjectBoards(boards, projectId) {
            const boardsList = document.getElementById('projectBoardsList');
            let html = '<div class="row g-3">';

            boards.forEach(board => {
                const totalCards = parseInt(board.total_cards) || 0;
                const doneCards = parseInt(board.done_cards) || 0;
                const completionRate = totalCards > 0 ? Math.round((doneCards / totalCards) * 100) : 0;

                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h6 class="mb-0">
                                        <i class="bi bi-kanban me-2"></i>${board.board_name || board.name}
                                    </h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editAdminBoard(${board.board_id || board.id})">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteAdminBoard(${board.board_id || board.id}, ${projectId})">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">${board.description || 'No description'}</p>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted fw-semibold">Progress</small>
                                        <span class="badge bg-primary">${completionRate}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: ${completionRate}%"></div>
                                    </div>
                                </div>

                                <div class="row text-center g-2">
                                    <div class="col-3">
                                        <div class="small">
                                            <div class="fw-bold text-primary">${totalCards}</div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Total</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="small">
                                            <div class="fw-bold text-warning">${board.in_progress_cards || 0}</div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Progress</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="small">
                                            <div class="fw-bold text-info">${board.review_cards || 0}</div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Review</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="small">
                                            <div class="fw-bold text-success">${doneCards}</div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Done</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>Created ${formatDate(board.created_at)}
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            boardsList.innerHTML = html;
        }

        // Board Management Functions
        function openAdminCreateBoardModal(projectId) {
            document.getElementById('adminBoardProjectId').value = projectId;
            document.getElementById('adminCreateBoardForm').reset();
            document.getElementById('adminCreateBoardAlert').classList.add('d-none');

            const modal = new bootstrap.Modal(document.getElementById('adminCreateBoardModal'));
            modal.show();
        }

        function editAdminBoard(boardId) {
            fetch(`/api/boards/${boardId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const board = data.board;
                        document.getElementById('adminEditBoardId').value = board.board_id;
                        document.getElementById('adminEditBoardName').value = board.board_name;
                        document.getElementById('adminEditBoardDescription').value = board.description || '';

                        const modal = new bootstrap.Modal(document.getElementById('adminEditBoardModal'));
                        modal.show();
                    } else {
                        alert('Error loading board details');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading board details');
                });
        }

        function deleteAdminBoard(boardId, projectId) {
            if (confirm('Are you sure you want to delete this board? All cards in this board will also be deleted. This action cannot be undone.')) {
                fetch(`/api/boards/${boardId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Board deleted successfully');
                        loadProjectBoardsList(projectId);
                        loadProjectBoardData(projectId);
                    } else {
                        alert(data.message || 'Error deleting board');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting board');
                });
            }
        }

        // Handle Create Board Form
        document.getElementById('adminCreateBoardForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('adminCreateBoardBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            const alert = document.getElementById('adminCreateBoardAlert');

            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            btn.disabled = true;

            const formData = new FormData(this);

            fetch('/api/boards', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.textContent = 'Board created successfully!';
                    alert.className = 'alert alert-success';
                    alert.classList.remove('d-none');

                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('adminCreateBoardModal'));
                        modal.hide();
                        const projectId = document.getElementById('adminBoardProjectId').value;
                        loadProjectBoardsList(projectId);
                        loadProjectBoardData(projectId);
                    }, 1000);
                } else {
                    alert.textContent = data.message || 'Error creating board';
                    alert.className = 'alert alert-danger';
                    alert.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert.textContent = 'Error creating board';
                alert.className = 'alert alert-danger';
                alert.classList.remove('d-none');
            })
            .finally(() => {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                btn.disabled = false;
            });
        });

        // Handle Edit Board Form
        document.getElementById('adminEditBoardForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('adminEditBoardBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            const alert = document.getElementById('adminEditBoardAlert');
            const boardId = document.getElementById('adminEditBoardId').value;

            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            btn.disabled = true;

            const formData = new FormData(this);

            fetch(`/api/boards/${boardId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    board_name: formData.get('board_name'),
                    description: formData.get('description')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.textContent = 'Board updated successfully!';
                    alert.className = 'alert alert-success';
                    alert.classList.remove('d-none');

                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('adminEditBoardModal'));
                        modal.hide();
                        if (currentProjectId) {
                            loadProjectBoardsList(currentProjectId);
                        }
                    }, 1000);
                } else {
                    alert.textContent = data.message || 'Error updating board';
                    alert.className = 'alert alert-danger';
                    alert.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert.textContent = 'Error updating board';
                alert.className = 'alert alert-danger';
                alert.classList.remove('d-none');
            })
            .finally(() => {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                btn.disabled = false;
            });
        });

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
            const userId = formData.get('user_id');

            // First, check if the user is a Team Lead and if they're available
            checkTeamLeadAvailabilityBeforeAdd(userId, projectId)
                .then(canProceed => {
                    if (!canProceed) {
                        return; // User declined to proceed or other issue
                    }

                    // Proceed with adding member
                    return fetch(`/api/projects/${projectId}/members`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            user_id: userId
                            // Role will be taken from user's system role in backend
                        })
                    });
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

        // Team Lead Availability Management Functions
        async function checkTeamLeadAvailabilityBeforeAdd(userId, projectId) {
            try {
                // Check if user is Team Lead first
                const userResponse = await fetch(`/api/users/${userId}`);
                const userData = await userResponse.json();

                if (!userData.success || userData.data.role !== 'Team_Lead') {
                    return true; // Not a Team Lead, proceed normally
                }

                // Check Team Lead availability
                const availabilityResponse = await fetch(`/api/admin/team-leads/availability?team_lead_id=${userId}&project_id=${projectId}`);
                const availabilityData = await availabilityResponse.json();

                if (availabilityData.success && availabilityData.data.is_available) {
                    return true; // Team Lead is available
                }

                // Team Lead is busy, show alert and ask for confirmation
                const currentProject = availabilityData.data.current_project;

                const result = await showTeamLeadBusyAlert(
                    availabilityData.data.team_lead.full_name,
                    currentProject.project_name,
                    currentProject.status
                );

                return result; // Return user's decision

            } catch (error) {
                console.error('Error checking Team Lead availability:', error);
                return true; // Proceed on error to not block workflow
            }
        }

        function showTeamLeadBusyAlert(teamLeadName, currentProjectName, projectStatus) {
            return new Promise((resolve) => {
                // Create custom alert modal
                const alertModal = document.createElement('div');
                alertModal.className = 'modal fade';
                alertModal.innerHTML = `
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Team Lead Already Assigned
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning mb-3">
                                    <h6 class="alert-heading">⚠️ Team Lead Status: BUSY</h6>
                                    <hr>
                                    <p class="mb-2"><strong>${teamLeadName}</strong> is currently assigned as Team Lead for:</p>
                                    <ul class="mb-2">
                                        <li><strong>Project:</strong> ${currentProjectName}</li>
                                        <li><strong>Status:</strong> <span class="badge bg-info">${projectStatus}</span></li>
                                    </ul>
                                    <p class="mb-0"><strong>Recommendation:</strong> Wait for current project to complete, or reassign Team Lead if urgent.</p>
                                </div>
                                <p><strong>Do you want to force assign this Team Lead to the new project?</strong></p>
                                <div class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    This will remove them from their current project and assign them to this project.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-action="cancel" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg me-2"></i>Cancel
                                </button>
                                <button type="button" class="btn btn-warning" data-action="force">
                                    <i class="bi bi-arrow-repeat me-2"></i>Force Assign
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(alertModal);
                const modal = new bootstrap.Modal(alertModal);

                // Handle button clicks
                alertModal.addEventListener('click', (e) => {
                    const action = e.target.getAttribute('data-action');
                    if (action === 'cancel') {
                        resolve(false);
                        modal.hide();
                    } else if (action === 'force') {
                        resolve(true);
                        modal.hide();
                    }
                });

                // Clean up when modal is hidden
                alertModal.addEventListener('hidden.bs.modal', () => {
                    document.body.removeChild(alertModal);
                });

                modal.show();
            });
        }

        // Function to update Team Lead status when project is completed
        function updateTeamLeadStatusOnProjectComplete(projectId) {
            fetch(`/api/teamlead/status-update/project-complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ project_id: projectId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Team Lead status updated on project completion');
                } else {
                    console.warn('Failed to update Team Lead status:', data.message);
                }
            })
            .catch(error => {
                console.error('Error updating Team Lead status:', error);
            });
        }

        // Enhanced project completion function
        function completeProjectWithStatusUpdate(projectId) {
            // First complete the project
            fetch(`/api/projects/${projectId}/complete`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update Team Lead status
                    updateTeamLeadStatusOnProjectComplete(projectId);

                    // Show success message
                    showNotification('success', 'Project Completed', 'Project completed successfully and Team Lead status updated');

                    // Reload project data
                    loadProjectsData();
                } else {
                    showNotification('error', 'Error', data.message || 'Failed to complete project');
                }
            })
            .catch(error => {
                console.error('Error completing project:', error);
                showNotification('error', 'Error', 'An error occurred while completing project');
            });
        }

        // Edit Profile Form Handler
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Menyimpan...';
            submitBtn.disabled = true;

            fetch('{{ route("admin.profile.update") }}', {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();

                    // Show success notification
                    showNotification('success', 'Berhasil!', data.message);

                    // Update profile display
                    updateProfileDisplay(data.user);

                    // Reset form
                    this.reset();
                } else {
                    showNotification('error', 'Error!', data.message || 'Gagal memperbarui profil');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error!', 'Terjadi kesalahan saat memperbarui profil');
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            });
        });

        // Function to update profile display after successful edit
        function updateProfileDisplay(user) {
            // Update profile section
            const profileContent = document.getElementById('profile-content');
            if (profileContent) {
                // Update name displays
                const nameElements = profileContent.querySelectorAll('.user-name');
                nameElements.forEach(el => {
                    el.textContent = user.full_name || user.name;
                });

                // Update email
                const emailElement = profileContent.querySelector('.user-email');
                if (emailElement) {
                    emailElement.textContent = user.email;
                }

                // Update phone
                const phoneElement = profileContent.querySelector('.user-phone');
                if (phoneElement) {
                    phoneElement.textContent = user.phone || 'Belum diisi';
                }

                // Update bio
                const bioElement = profileContent.querySelector('.user-bio');
                if (bioElement) {
                    bioElement.textContent = user.bio || 'Belum ada bio';
                }
            }

            // Update sidebar profile
            const sidebarName = document.querySelector('.sidebar-header h5');
            if (sidebarName) {
                sidebarName.textContent = user.full_name || user.name;
            }
        }

        // Initialize Admin Mobile Menu
        function initializeAdminMobileMenu() {
            console.log('=== ADMIN MOBILE MENU INITIALIZATION START ===');

            const mobileMenuToggle = document.getElementById('admin-mobile-toggle');
            const sidebar = document.getElementById('admin-sidebar');
            const sidebarOverlay = document.getElementById('admin-sidebar-overlay');

            console.log('Admin mobile menu elements:', {
                toggle: !!mobileMenuToggle,
                sidebar: !!sidebar,
                overlay: !!sidebarOverlay,
                toggleDisplay: mobileMenuToggle ? window.getComputedStyle(mobileMenuToggle).display : 'not found',
                screenWidth: window.innerWidth
            });

            // Check if all required elements exist
            if (!mobileMenuToggle || !sidebar || !sidebarOverlay) {
                console.error('Admin mobile menu elements not found:', {
                    mobileMenuToggle: !!mobileMenuToggle,
                    sidebar: !!sidebar,
                    sidebarOverlay: !!sidebarOverlay
                });
                return;
            }

            // Function to toggle mobile menu
            function toggleAdminMobileMenu(e) {
                console.log('=== TOGGLE ADMIN MOBILE MENU FUNCTION CALLED ===');

                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                const isOpen = sidebar.classList.contains('show');
                console.log('Current admin menu state:', isOpen ? 'OPEN' : 'CLOSED');

                if (isOpen) {
                    closeAdminMobileMenu();
                } else {
                    openAdminMobileMenu();
                }
            }

            // Function to open mobile menu
            function openAdminMobileMenu() {
                console.log('=== OPENING ADMIN MOBILE MENU ===');

                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.classList.add('admin-menu-open');

                // Update toggle button icon
                const toggleIcon = mobileMenuToggle.querySelector('i');
                if (toggleIcon) {
                    toggleIcon.className = 'bi bi-x';
                }

                // Accessibility
                sidebar.setAttribute('aria-hidden', 'false');
                mobileMenuToggle.setAttribute('aria-expanded', 'true');

                console.log('Admin mobile menu opened successfully');
            }

            // Function to close mobile menu
            function closeAdminMobileMenu() {
                console.log('=== CLOSING ADMIN MOBILE MENU ===');

                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.classList.remove('admin-menu-open');

                // Update toggle button icon
                const toggleIcon = mobileMenuToggle.querySelector('i');
                if (toggleIcon) {
                    toggleIcon.className = 'bi bi-list';
                }

                // Accessibility
                sidebar.setAttribute('aria-hidden', 'true');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');

                console.log('Admin mobile menu closed successfully');
            }

            // Event listeners
            if (mobileMenuToggle) {
                console.log('Adding admin mobile toggle event listeners...');

                // Touch and click handling
                mobileMenuToggle.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleAdminMobileMenu(e);
                }, { passive: false });

                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleAdminMobileMenu(e);
                });

                console.log('Admin mobile toggle events added successfully');
            }

            // Overlay event listeners
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeAdminMobileMenu();
                });

                sidebarOverlay.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    closeAdminMobileMenu();
                }, { passive: false });
            }

            // Close menu when clicking on nav links (mobile only)
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setTimeout(closeAdminMobileMenu, 150);
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeAdminMobileMenu();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    closeAdminMobileMenu();
                }
            });

            // Initialize proper state
            if (window.innerWidth <= 768) {
                closeAdminMobileMenu();
            }

            // Debug functions
            window.testAdminMobileMenu = function() {
                toggleAdminMobileMenu();
            };

            console.log('=== ADMIN MOBILE MENU INITIALIZATION COMPLETE ===');
        }

        // Initialize admin mobile menu when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing admin mobile menu...');

            setTimeout(function() {
                try {
                    initializeAdminMobileMenu();
                } catch (error) {
                    console.error('Error initializing admin mobile menu:', error);
                }
            }, 100);
        });

    </script>
</body>
</html>
