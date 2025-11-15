<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Designer Panel - Manajemen Proyek</title>
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
            border-left-color: #ff6b6b;
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
            background: rgba(255, 107, 107, 0.1);
            border-color: rgba(255, 107, 107, 0.3);
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
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
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
            border-left: 4px solid #ff6b6b;
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
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
        }

        .welcome-card h3 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-card p {
            opacity: 0.9;
            margin: 0;
        }

        /* Design Asset Card Styles */
        .asset-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #ff6b6b;
            transition: all 0.3s ease;
        }

        .asset-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .asset-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .asset-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .asset-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .type-ui {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
        }

        .type-brand {
            background: rgba(54, 162, 235, 0.1);
            color: #36a2eb;
        }

        .type-web {
            background: rgba(255, 205, 86, 0.1);
            color: #ffcd56;
        }

        .type-mobile {
            background: rgba(75, 192, 192, 0.1);
            color: #4bc0c0;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-concept {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .status-design {
            background: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
        }

        .status-review {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-approved {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        /* Gallery Styles */
        .design-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .gallery-image {
            width: 100%;
            height: 150px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 3rem;
        }

        .gallery-content {
            padding: 15px;
        }

        .gallery-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .gallery-desc {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 10px;
        }

        /* Tools Grid */
        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .tool-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .tool-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .tool-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            color: white;
            font-size: 1.5rem;
        }

        .tool-name {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .tool-desc {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Color Palette */
        .color-palette {
            display: flex;
            gap: 10px;
            margin: 15px 0;
        }

        .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .color-swatch:hover {
            transform: scale(1.1);
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

            .stat-card, .asset-card, .tool-card {
                margin-bottom: 15px;
            }

            .design-gallery {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        /* Project Brief Styles */
        .brief-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #ff6b6b;
        }

        .brief-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .brief-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .brief-meta {
            display: flex;
            gap: 15px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Feedback Card */
        .feedback-card {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .feedback-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .feedback-author {
            font-weight: 600;
            color: #856404;
        }

        .feedback-time {
            font-size: 0.8rem;
            color: #856404;
            opacity: 0.7;
            margin-left: auto;
        }

        .feedback-content {
            color: #856404;
            font-size: 0.9rem;
        }

        /* Profile Dropdown Styles */
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

        /* Notification System Styles */
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            min-width: 380px;
            max-width: 400px;
            z-index: 1050;
            margin-top: 8px;
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .notification-dropdown.show {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .notification-dropdown .dropdown-header {
            padding: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            margin: 0;
        }

        .notification-dropdown .dropdown-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 8px;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 16px;
            margin: 4px 0;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .notification-item:hover {
            background: rgba(0, 123, 255, 0.08);
            transform: translateX(4px);
            border-color: rgba(0, 123, 255, 0.2);
        }

        .notification-item.unread {
            background: rgba(0, 123, 255, 0.05);
            border-left: 4px solid #007bff;
        }

        .notification-item.unread::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 12px;
            width: 8px;
            height: 8px;
            background: #007bff;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.2); }
            100% { opacity: 1; transform: scale(1); }
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
            font-size: 16px;
            color: white;
        }

        .notification-icon.info { background: linear-gradient(135deg, #36d1dc, #5b86e5); }
        .notification-icon.success { background: linear-gradient(135deg, #a8edea, #fed6e3); color: #28a745; }
        .notification-icon.warning { background: linear-gradient(135deg, #ffeaa7, #fab1a0); color: #ffc107; }
        .notification-icon.error { background: linear-gradient(135deg, #ff7675, #fd79a8); }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0 0 4px 0;
            color: #2c3e50;
        }

        .notification-message {
            font-size: 0.8rem;
            color: #6c757d;
            margin: 0 0 6px 0;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 0.7rem;
            color: #999;
        }

        .notification-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .notification-actions .btn {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
            animation: notificationPulse 2s infinite;
        }

        @keyframes notificationPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .empty-notifications {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-notifications i {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* Dark theme notification styles */
        body.dark-theme .notification-dropdown {
            background: rgba(45, 45, 45, 0.95);
            border-color: rgba(255, 255, 255, 0.1);
        }

        body.dark-theme .notification-item {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
        }

        body.dark-theme .notification-item:hover {
            background: rgba(0, 123, 255, 0.15);
        }

        body.dark-theme .notification-title {
            color: #e0e0e0;
        }

        body.dark-theme .notification-message {
            color: #b0b0b0;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Designer Panel</h4>
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

            <!-- My Cards -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="my-cards">
                    <div class="nav-icon">
                        <i class="bi bi-card-list"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">My Cards</div>
                        <div class="nav-subtitle">Assigned design tasks</div>
                    </div>
                </a>
            </div>

            <!-- My Subtasks -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="my-subtasks">
                    <div class="nav-icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">My Subtasks</div>
                        <div class="nav-subtitle">Task breakdown items</div>
                    </div>
                </a>
            </div>

            <!-- Time Log -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="time-log">
                    <div class="nav-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Time Log</div>
                        <div class="nav-subtitle">Track work hours</div>
                    </div>
                </a>
            </div>

            <!-- Notifications -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="notifications">
                    <div class="nav-icon">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Notifications</div>
                        <div class="nav-subtitle">Messages & Updates</div>
                    </div>
                </a>
            </div>

            <!-- Profile -->
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
                    <li><a class="dropdown-item" href="#" data-section="profile"><i class="bi bi-person-gear"></i>Profile Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-palette"></i>Theme</a></li>
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
                    <h2 id="content-title" class="mb-0">Designer Dashboard</h2>
                    <p class="text-muted mb-0" id="content-subtitle">Create and manage your design projects</p>
                </div>
                <div class="header-actions d-flex gap-2">
                    <!-- Notifications Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-light position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                                0
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span>Notifications</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="markAllAsRead()">
                                    <i class="bi bi-check2-all"></i> Mark all read
                                </button>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <div id="notificationsList">
                                <li class="px-3 py-2 text-center text-muted">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="mt-2">Loading notifications...</div>
                                </li>
                            </div>
                            <li><hr class="dropdown-divider"></li>
                            <li class="text-center">
                                <a class="dropdown-item" href="#" onclick="showAllNotifications()">
                                    <i class="bi bi-list"></i> View All Notifications
                                </a>
                            </li>
                        </ul>
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
                    <h3>Welcome back, {{ auth()->user()->full_name ?? auth()->user()->username ?? 'Designer' }}!</h3>
                    <p>Ready to create something amazing? Let's bring your ideas to life.</p>
                </div>

                <!-- Main Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-palette stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="active-projects-count">0</h3>
                            <p class="stat-label">Active Projects</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-images stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="design-assets-count">0</h3>
                            <p class="stat-label">Design Assets</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-check-circle stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="completed-designs-count">0</h3>
                            <p class="stat-label">Completed Designs</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-chat-left-text stat-icon text-info"></i>
                            <h3 class="stat-number text-info" id="pending-feedback-count">0</h3>
                            <p class="stat-label">Pending Feedback</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row g-4 mb-5">
                    <div class="col-12">
                        <h4 class="mb-4">Quick Actions</h4>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="tool-card" data-section="assets">
                            <div class="tool-icon">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <h6 class="tool-name">New Design</h6>
                            <p class="tool-desc">Create new asset</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="tool-card" data-section="gallery">
                            <div class="tool-icon">
                                <i class="bi bi-upload"></i>
                            </div>
                            <h6 class="tool-name">Upload Work</h6>
                            <p class="tool-desc">Add to portfolio</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="tool-card" data-section="feedback">
                            <div class="tool-icon">
                                <i class="bi bi-eye"></i>
                            </div>
                            <h6 class="tool-name">Review Feedback</h6>
                            <p class="tool-desc">Check comments</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="tool-card" data-section="tools">
                            <div class="tool-icon">
                                <i class="bi bi-palette2"></i>
                            </div>
                            <h6 class="tool-name">Design Tools</h6>
                            <p class="tool-desc">Access utilities</p>
                        </a>
                    </div>
                </div>

                <!-- Current Projects and Recent Work -->
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Current Projects</h5>
                            </div>
                            <div class="card-body" id="current-projects">
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-palette display-6"></i>
                                    <p class="mt-2">Loading projects...</p>
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

            <!-- My Cards Content -->
            <div id="my-cards-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">My Cards</h4>
                        <p class="text-muted mb-0">Design tasks assigned to you</p>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select" id="cardStatusFilter" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="to_do">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            <option value="done">Done</option>
                        </select>
                        <button class="btn btn-outline-primary" onclick="refreshMyCards()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Cards Statistics -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-soft text-primary rounded-circle me-3">
                                        <i class="bi bi-card-list font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="totalCards">0</h5>
                                        <p class="text-muted mb-0">Total Cards</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-warning bg-soft text-warning rounded-circle me-3">
                                        <i class="bi bi-play-circle font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="inProgressCards">0</h5>
                                        <p class="text-muted mb-0">In Progress</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info bg-soft text-info rounded-circle me-3">
                                        <i class="bi bi-eye font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="reviewCards">0</h5>
                                        <p class="text-muted mb-0">In Review</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success bg-soft text-success rounded-circle me-3">
                                        <i class="bi bi-check-circle font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="completedCards">0</h5>
                                        <p class="text-muted mb-0">Completed</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cards List -->
                <div id="my-cards-list" class="row">
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading your cards...</p>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="no-cards" class="text-center py-5" style="display: none;">
                    <i class="bi bi-card-list text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Cards Assigned</h5>
                    <p class="text-muted">You don't have any design tasks assigned yet.</p>
                </div>
            </div>

            <!-- My Subtasks Content -->
            <div id="my-subtasks-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">My Subtasks</h4>
                        <p class="text-muted mb-0">Task breakdown items assigned to you</p>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select" id="subtaskStatusFilter" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                        <button class="btn btn-outline-primary" onclick="refreshMySubtasks()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Subtasks List -->
                <div id="my-subtasks-list">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading your subtasks...</p>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="no-subtasks" class="text-center py-5" style="display: none;">
                    <i class="bi bi-list-check text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Subtasks Assigned</h5>
                    <p class="text-muted">You don't have any subtasks assigned yet.</p>
                </div>
            </div>

            <!-- Time Log Content -->
            <div id="time-log-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Time Tracking</h4>
                        <p class="text-muted mb-0">Log and track your work hours</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logTimeModal">
                            <i class="bi bi-plus-circle me-1"></i>Log Time
                        </button>
                        <button class="btn btn-outline-primary" onclick="refreshTimeLogs()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Time Statistics -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-soft text-primary rounded-circle me-3">
                                        <i class="bi bi-clock-history font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="totalHoursLogged">0h</h5>
                                        <p class="text-muted mb-0">Total Hours</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success bg-soft text-success rounded-circle me-3">
                                        <i class="bi bi-calendar-week font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="thisWeekHours">0h</h5>
                                        <p class="text-muted mb-0">This Week</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info bg-soft text-info rounded-circle me-3">
                                        <i class="bi bi-calendar-day font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="todayHours">0h</h5>
                                        <p class="text-muted mb-0">Today</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-warning bg-soft text-warning rounded-circle me-3">
                                        <i class="bi bi-graph-up font-size-18"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="mb-0" id="avgDailyHours">0h</h5>
                                        <p class="text-muted mb-0">Daily Average</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Logs List -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Time Logs</h5>
                        <div class="d-flex gap-2">
                            <input type="date" class="form-control" id="dateFilter" style="width: auto;">
                            <select class="form-select" id="cardFilter" style="width: auto;">
                                <option value="">All Cards</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Card</th>
                                        <th>Hours</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="time-logs-table">
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading time logs...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="no-time-logs" class="text-center py-5" style="display: none;">
                    <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Time Logs Found</h5>
                    <p class="text-muted">Start logging your work hours to track productivity.</p>
                </div>
            </div>

            <!-- Profile Content -->
            <div id="profile-content" class="content-section">
                <h4 class="mb-4">Designer Profile</h4>
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
                                            <input type="text" class="form-control" value="{{ auth()->user()->full_name ?? 'Not Set' }}" name="full_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" value="{{ auth()->user()->username ?? 'designer' }}" name="username" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ auth()->user()->email ?? 'designer@example.com' }}" name="email">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="Designer" readonly>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Portfolio Website</label>
                                            <input type="url" class="form-control" placeholder="https://your-portfolio.com">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Bio</label>
                                            <textarea class="form-control" rows="3" placeholder="Tell us about your design experience..."></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>

                        <!-- Skills Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Design Skills</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6>Design Software</h6>
                                    <span class="type-badge type-ui">Adobe Creative Suite</span>
                                    <span class="type-badge type-brand">Figma</span>
                                    <span class="type-badge type-web">Sketch</span>
                                    <span class="type-badge type-mobile">Adobe XD</span>
                                </div>
                                <div class="mb-3">
                                    <h6>Specializations</h6>
                                    <span class="type-badge type-ui">UI/UX Design</span>
                                    <span class="type-badge type-brand">Brand Identity</span>
                                    <span class="type-badge type-web">Web Design</span>
                                    <span class="type-badge type-mobile">Mobile Design</span>
                                </div>
                                <div class="mb-3">
                                    <h6>Additional Skills</h6>
                                    <span class="type-badge type-ui">Typography</span>
                                    <span class="type-badge type-brand">Logo Design</span>
                                    <span class="type-badge type-web">Prototyping</span>
                                    <span class="type-badge type-mobile">User Research</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Portfolio Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Designs Created:</span>
                                    <strong id="profile-designs-created">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Projects Completed:</span>
                                    <strong id="profile-projects-completed">0</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Client Rating:</span>
                                    <strong id="profile-rating">★★★★★</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Member Since:</span>
                                    <strong>{{ auth()->user()->created_at ? auth()->user()->created_at->format('M Y') : 'Unknown' }}</strong>
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
                                        <small class="text-muted">Ready for new projects</small>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary btn-sm">Update Status</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications Section -->
        <div id="notifications-content" class="content-section" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4>Notification History</h4>
                    <p class="text-muted mb-0">Manage your notifications and stay updated</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                        <i class="bi bi-check-all"></i> Mark All Read
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="clearAllNotifications()">
                        <i class="bi bi-trash"></i> Clear All
                    </button>
                </div>
            </div>

            <!-- Filter Tabs -->
            <ul class="nav nav-tabs mb-4" id="notificationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        All Notifications
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab">
                        Unread <span class="badge bg-primary ms-1" id="unread-count">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="read-tab" data-bs-toggle="tab" data-bs-target="#read" type="button" role="tab">
                        Read
                    </button>
                </li>
            </ul>

            <!-- Notification Content -->
            <div class="tab-content" id="notificationTabContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <div id="all-notifications-list" class="notification-list">
                        <!-- Notifications will be loaded here -->
                        <div class="text-center py-5" id="no-notifications-all">
                            <i class="bi bi-bell-slash fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No notifications found</p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="unread" role="tabpanel">
                    <div id="unread-notifications-list" class="notification-list">
                        <!-- Unread notifications will be loaded here -->
                        <div class="text-center py-5" id="no-notifications-unread">
                            <i class="bi bi-bell-slash fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No unread notifications</p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="read" role="tabpanel">
                    <div id="read-notifications-list" class="notification-list">
                        <!-- Read notifications will be loaded here -->
                        <div class="text-center py-5" id="no-notifications-read">
                            <i class="bi bi-bell-slash fs-1 text-muted"></i>
                            <p class="text-muted mt-3">No read notifications</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Notification pagination" class="mt-4">
                <ul class="pagination justify-content-center" id="notification-pagination">
                    <!-- Pagination will be loaded here -->
                </ul>
            </nav>
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
            document.querySelectorAll('.tool-card').forEach(card => {
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

            // Dropdown profile navigation
            document.querySelectorAll('.dropdown-item[data-section]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetSection = this.getAttribute('data-section');

                    // Update active nav
                    navLinks.forEach(navLink => navLink.classList.remove('active'));

                    // Update content
                    contentSections.forEach(section => section.classList.remove('active'));
                    document.getElementById(targetSection + '-content').classList.add('active');

                    // Update header for profile
                    if (targetSection === 'profile') {
                        contentTitle.textContent = 'Designer Profile';
                        contentSubtitle.textContent = 'Manage your account settings';
                    }

                    // Load content
                    loadSectionContent(targetSection);
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
                case 'assets':
                    loadDesignAssets();
                    break;
                case 'projects':
                    loadDesignProjects();
                    break;
                case 'gallery':
                    loadDesignGallery();
                    break;
                case 'tools':
                    loadDesignTools();
                    break;
                case 'feedback':
                    loadClientFeedback();
                    break;
                case 'time-log':
                    loadTimeLogData();
                    break;
                case 'notifications':
                    loadNotifications();
                    break;
                case 'profile':
                    loadProfileData();
                    break;
            }
        }

        // Dashboard data loading
        function loadDashboardData() {
            loadDesignerStatistics();
            loadCurrentProjects();
            loadRecentActivity();
        }

        function loadDesignerStatistics() {
            // Mock data for designer statistics
            document.getElementById('active-projects-count').textContent = '8';
            document.getElementById('design-assets-count').textContent = '45';
            document.getElementById('completed-designs-count').textContent = '127';
            document.getElementById('pending-feedback-count').textContent = '3';
        }

        function loadCurrentProjects() {
            const container = document.getElementById('current-projects');
            container.innerHTML = `
                <div class="brief-card">
                    <div class="brief-header">
                        <div>
                            <h6 class="brief-title">E-Commerce Website Redesign</h6>
                            <div class="brief-meta">
                                <span><i class="bi bi-calendar"></i> Due: Nov 15, 2024</span>
                                <span><i class="bi bi-building"></i> TechCorp Ltd.</span>
                            </div>
                        </div>
                        <div>
                            <span class="type-badge type-web">Web Design</span>
                            <span class="status-badge status-design">In Design</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Complete redesign of product catalog and checkout process with modern UI/UX principles.</p>
                    <div class="progress mb-2">
                        <div class="progress-bar" style="width: 70%; background: linear-gradient(90deg, #ff6b6b, #ff8e53);"></div>
                    </div>
                    <small class="text-muted">70% Complete</small>
                </div>

                <div class="brief-card">
                    <div class="brief-header">
                        <div>
                            <h6 class="brief-title">Mobile App UI Kit</h6>
                            <div class="brief-meta">
                                <span><i class="bi bi-calendar"></i> Due: Nov 10, 2024</span>
                                <span><i class="bi bi-building"></i> StartupXYZ</span>
                            </div>
                        </div>
                        <div>
                            <span class="type-badge type-mobile">Mobile UI</span>
                            <span class="status-badge status-review">Review</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Complete UI component library for fitness tracking mobile application.</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-warning" style="width: 90%;"></div>
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
                            <div class="activity-title">Design uploaded</div>
                            <div class="activity-desc">Landing page mockup</div>
                            <div class="activity-time">45 min ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-title">Feedback received</div>
                            <div class="activity-desc">Client review on homepage</div>
                            <div class="activity-time">3 hours ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-title">Design revised</div>
                            <div class="activity-desc">Updated color scheme</div>
                            <div class="activity-time">1 day ago</div>
                        </div>
                    </div>
                </div>
            `;
        }

        function loadDesignAssets() {
            const container = document.getElementById('assets-list');
            container.innerHTML = `
                <div class="asset-card">
                    <div class="asset-header">
                        <div>
                            <h6 class="asset-title">Homepage Hero Section</h6>
                            <div class="asset-meta">
                                <span><i class="bi bi-calendar"></i> Updated: Nov 1, 2024</span>
                                <span><i class="bi bi-file-earmark"></i> PSD, AI</span>
                            </div>
                        </div>
                        <div>
                            <span class="type-badge type-web">Web Design</span>
                            <span class="status-badge status-approved">Approved</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Modern hero section design with animated elements and call-to-action buttons.</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm">Download</button>
                        <button class="btn btn-outline-secondary btn-sm">Preview</button>
                    </div>
                </div>

                <div class="asset-card">
                    <div class="asset-header">
                        <div>
                            <h6 class="asset-title">Mobile App Icons Set</h6>
                            <div class="asset-meta">
                                <span><i class="bi bi-calendar"></i> Updated: Oct 30, 2024</span>
                                <span><i class="bi bi-file-earmark"></i> SVG, PNG</span>
                            </div>
                        </div>
                        <div>
                            <span class="type-badge type-mobile">Mobile UI</span>
                            <span class="status-badge status-design">In Progress</span>
                        </div>
                    </div>
                    <p class="text-muted mb-3">Complete icon set for fitness mobile application with multiple sizes and variants.</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning btn-sm">Continue</button>
                        <button class="btn btn-outline-secondary btn-sm">Share</button>
                    </div>
                </div>
            `;
        }

        function loadDesignProjects() {
            const container = document.getElementById('projects-list');
            container.innerHTML = `
                <div class="brief-card">
                    <div class="brief-header">
                        <div>
                            <h5 class="brief-title">Corporate Branding Package</h5>
                            <div class="brief-meta">
                                <span><i class="bi bi-calendar"></i> Deadline: Dec 1, 2024</span>
                                <span><i class="bi bi-people"></i> 3 designers</span>
                            </div>
                        </div>
                        <span class="status-badge status-design">In Design</span>
                    </div>
                    <p class="text-muted mb-3">Complete brand identity including logo, color palette, typography, and brand guidelines.</p>
                    <div class="d-flex justify-content-between mb-2">
                        <small>Progress</small>
                        <small>55%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: 55%; background: linear-gradient(90deg, #ff6b6b, #ff8e53);"></div>
                    </div>
                </div>

                <div class="brief-card">
                    <div class="brief-header">
                        <div>
                            <h5 class="brief-title">SaaS Dashboard UI</h5>
                            <div class="brief-meta">
                                <span><i class="bi bi-calendar"></i> Deadline: Jan 15, 2025</span>
                                <span><i class="bi bi-people"></i> 2 designers</span>
                            </div>
                        </div>
                        <span class="status-badge status-concept">Concept</span>
                    </div>
                    <p class="text-muted mb-3">Modern dashboard interface for analytics platform with data visualization components.</p>
                    <div class="d-flex justify-content-between mb-2">
                        <small>Progress</small>
                        <small>20%</small>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-secondary" style="width: 20%;"></div>
                    </div>
                </div>
            `;
        }

        function loadDesignGallery() {
            const container = document.getElementById('design-gallery');
            container.innerHTML = `
                <div class="gallery-item">
                    <div class="gallery-image">
                        <i class="bi bi-image"></i>
                    </div>
                    <div class="gallery-content">
                        <h6 class="gallery-title">E-commerce Homepage</h6>
                        <p class="gallery-desc">Modern shopping website design</p>
                        <span class="type-badge type-web">Web Design</span>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="gallery-content">
                        <h6 class="gallery-title">Fitness App UI</h6>
                        <p class="gallery-desc">Mobile app interface design</p>
                        <span class="type-badge type-mobile">Mobile UI</span>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <i class="bi bi-palette"></i>
                    </div>
                    <div class="gallery-content">
                        <h6 class="gallery-title">Brand Identity</h6>
                        <p class="gallery-desc">Complete branding package</p>
                        <span class="type-badge type-brand">Branding</span>
                    </div>
                </div>

                <div class="gallery-item">
                    <div class="gallery-image">
                        <i class="bi bi-layout-text-window"></i>
                    </div>
                    <div class="gallery-content">
                        <h6 class="gallery-title">Dashboard UI</h6>
                        <p class="gallery-desc">Analytics dashboard interface</p>
                        <span class="type-badge type-ui">UI Design</span>
                    </div>
                </div>
            `;
        }

        function loadDesignTools() {
            // Tools are already loaded in HTML
            console.log('Design tools loaded');
        }

        function loadClientFeedback() {
            const container = document.getElementById('feedback-list');
            container.innerHTML = `
                <div class="feedback-card">
                    <div class="feedback-header">
                        <span class="feedback-author">Sarah Johnson</span>
                        <span class="feedback-time">2 hours ago</span>
                    </div>
                    <div class="feedback-content">
                        "Love the new color scheme! Could we make the CTA button slightly larger? Overall great work on the homepage design."
                    </div>
                </div>

                <div class="feedback-card">
                    <div class="feedback-header">
                        <span class="feedback-author">Mike Chen</span>
                        <span class="feedback-time">1 day ago</span>
                    </div>
                    <div class="feedback-content">
                        "The mobile responsive design looks fantastic. The user flow is much clearer now. Ready to approve this version."
                    </div>
                </div>

                <div class="feedback-card">
                    <div class="feedback-header">
                        <span class="feedback-author">Emily Davis</span>
                        <span class="feedback-time">3 days ago</span>
                    </div>
                    <div class="feedback-content">
                        "Can we explore a darker theme option? The current design is great but our brand might benefit from a premium dark mode."
                    </div>
                </div>
            `;
        }

        function loadProfileData() {
            // Profile data is already populated from server-side
            document.getElementById('profile-designs-created').textContent = '127';
            document.getElementById('profile-projects-completed').textContent = '34';
            document.getElementById('profile-rating').textContent = '★★★★★';
        }

        function toggleTheme() {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Notification System Functions
        let notificationDropdown = null;

        function toggleNotificationDropdown() {
            if (!notificationDropdown) {
                notificationDropdown = document.getElementById('notificationDropdown');
            }

            if (notificationDropdown.classList.contains('show')) {
                notificationDropdown.classList.remove('show');
            } else {
                notificationDropdown.classList.add('show');
                loadNotificationDropdown();
            }
        }

        function loadNotificationDropdown() {
            fetch('/api/notifications?limit=5')
                .then(response => response.json())
                .then(data => {
                    updateNotificationDropdown(data.notifications || []);
                    updateNotificationBadge(data.unread_count || 0);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        function updateNotificationDropdown(notifications) {
            const container = document.getElementById('notification-dropdown-list');
            if (!container) return;

            if (notifications.length === 0) {
                container.innerHTML = `
                    <div class="empty-notifications">
                        <i class="bi bi-bell-slash"></i>
                        <p>No notifications</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = notifications.map(notification => `
                <div class="notification-item ${notification.read_at ? '' : 'unread'}" onclick="markNotificationAsRead('${notification.id}')">
                    <div class="notification-icon ${notification.type}">
                        <i class="bi bi-${getNotificationIcon(notification.type)}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${notification.data.title || 'Notification'}</div>
                        <div class="notification-message">${notification.data.message || ''}</div>
                        <div class="notification-time">${formatNotificationTime(notification.created_at)}</div>
                    </div>
                </div>
            `).join('');
        }

        function loadNotifications() {
            fetch('/api/notifications')
                .then(response => response.json())
                .then(data => {
                    updateAllNotificationTabs(data.notifications || []);
                    updateNotificationBadge(data.unread_count || 0);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        function updateAllNotificationTabs(notifications) {
            const allNotifications = notifications;
            const unreadNotifications = notifications.filter(n => !n.read_at);
            const readNotifications = notifications.filter(n => n.read_at);

            updateNotificationTab('all', allNotifications);
            updateNotificationTab('unread', unreadNotifications);
            updateNotificationTab('read', readNotifications);

            // Update unread count badge in tab
            document.getElementById('unread-count').textContent = unreadNotifications.length;
        }

        function updateNotificationTab(type, notifications) {
            const container = document.getElementById(`${type}-notifications-list`);
            const emptyState = document.getElementById(`no-notifications-${type}`);

            if (!container) return;

            if (notifications.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash fs-1 text-muted"></i>
                        <p class="text-muted mt-3">No ${type === 'all' ? '' : type} notifications found</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = notifications.map(notification => `
                <div class="notification-item ${notification.read_at ? '' : 'unread'}" data-id="${notification.id}">
                    <div class="notification-icon ${notification.type}">
                        <i class="bi bi-${getNotificationIcon(notification.type)}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${notification.data.title || 'Notification'}</div>
                        <div class="notification-message">${notification.data.message || ''}</div>
                        <div class="notification-time">${formatNotificationTime(notification.created_at)}</div>
                        <div class="notification-actions">
                            ${!notification.read_at ? '<button class="btn btn-outline-primary btn-sm" onclick="markAsRead(\'' + notification.id + '\')">Mark as Read</button>' : ''}
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteNotification('${notification.id}')">Delete</button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function getNotificationIcon(type) {
            const icons = {
                'info': 'info-circle',
                'success': 'check-circle',
                'warning': 'exclamation-triangle',
                'error': 'x-circle',
                'task': 'list-task',
                'comment': 'chat-dots',
                'project': 'folder'
            };
            return icons[type] || 'bell';
        }

        function formatNotificationTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInHours = (now - date) / (1000 * 60 * 60);

            if (diffInHours < 1) {
                return 'Just now';
            } else if (diffInHours < 24) {
                return `${Math.floor(diffInHours)}h ago`;
            } else if (diffInHours < 48) {
                return 'Yesterday';
            } else {
                return date.toLocaleDateString();
            }
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }

        function markAsRead(notificationId) {
            fetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    loadNotificationDropdown();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        function markNotificationAsRead(notificationId) {
            markAsRead(notificationId);
        }

        function deleteNotification(notificationId) {
            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }

            fetch(`/api/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    loadNotificationDropdown();
                }
            })
            .catch(error => {
                console.error('Error deleting notification:', error);
            });
        }

        function markAllAsRead() {
            fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    loadNotificationDropdown();
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        }

        function clearAllNotifications() {
            if (!confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
                return;
            }

            fetch('/api/notifications/clear-all', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    loadNotificationDropdown();
                }
            })
            .catch(error => {
                console.error('Error clearing all notifications:', error);
            });
        }

        // Close notification dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const notificationBtn = document.getElementById('notificationBtn');
            const dropdown = document.getElementById('notificationDropdown');

            if (notificationBtn && dropdown &&
                !notificationBtn.contains(event.target) &&
                !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Time Log Functions (same as Developer panel)
        let activeTimerInterval = null;
        let currentActiveTimer = null;

        function loadTimeLogData() {
            loadTimeLogStatistics();
            loadTimeLogs();
            loadActiveTimer();
            loadDeadlineCards();
        }

        function loadTimeLogStatistics() {
            fetch('/api/time-logs/statistics')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const stats = data.statistics;
                        document.getElementById('stats-today').textContent = stats.today.formatted;
                        document.getElementById('stats-week').textContent = stats.week.formatted;
                        document.getElementById('stats-month').textContent = stats.month.formatted;
                        document.getElementById('stats-sessions').textContent = stats.today.sessions;
                    }
                })
                .catch(error => console.error('Error loading time log statistics:', error));
        }

        function loadTimeLogs(filter = 'all', page = 1) {
            const params = new URLSearchParams({
                per_page: 10,
                page: page
            });

            if (filter !== 'all') {
                if (filter === 'today') {
                    params.append('date_from', new Date().toISOString().split('T')[0]);
                    params.append('date_to', new Date().toISOString().split('T')[0]);
                } else if (filter === 'week') {
                    const startOfWeek = new Date();
                    startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay());
                    params.append('date_from', startOfWeek.toISOString().split('T')[0]);
                } else if (filter === 'month') {
                    const startOfMonth = new Date();
                    startOfMonth.setDate(1);
                    params.append('date_from', startOfMonth.toISOString().split('T')[0]);
                } else if (filter === 'active' || filter === 'completed') {
                    params.append('status', filter);
                }
            }

            fetch(`/api/time-logs?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateTimeLogsList(data.time_logs);
                        updateTimeLogsPagination(data.pagination);
                    }
                })
                .catch(error => {
                    console.error('Error loading time logs:', error);
                    document.getElementById('time-logs-list').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Failed to load time logs. Please try again.
                        </div>
                    `;
                });
        }

        function updateTimeLogsList(timeLogs) {
            const container = document.getElementById('time-logs-list');
            const noLogsElement = document.getElementById('no-time-logs');

            if (timeLogs.length === 0) {
                if (noLogsElement) noLogsElement.style.display = 'block';
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-clock fs-1 text-muted"></i>
                        <p class="text-muted mt-3">No time logs found</p>
                        <button class="btn btn-primary" onclick="showStartTimerModal()">
                            <i class="bi bi-play-circle"></i> Start Your First Timer
                        </button>
                    </div>
                `;
                return;
            }

            if (noLogsElement) noLogsElement.style.display = 'none';

            container.innerHTML = timeLogs.map(log => `
                <div class="time-log-item border rounded p-3 mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-1">${log.card ? log.card.card_title : 'Unknown Card'}</h6>
                            ${log.subtask ? `<small class="text-muted">Subtask: ${log.subtask.title}</small>` : ''}
                            ${log.description ? `<p class="text-muted mb-1">${log.description}</p>` : ''}
                            <small class="text-muted">
                                Started: ${new Date(log.start_time).toLocaleString()}
                                ${log.end_time ? `- Ended: ${new Date(log.end_time).toLocaleString()}` : ' (Active)'}
                            </small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="h5 mb-0 ${log.end_time ? 'text-success' : 'text-primary'}">
                                ${log.end_time ? formatMinutes(log.duration_minutes) : 'Running...'}
                            </div>
                            <small class="text-muted">${log.end_time ? 'Completed' : 'Active'}</small>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editTimeLogDescription(${log.log_id}, '${log.description || ''}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                ${!log.end_time ? '' : `
                                    <button class="btn btn-outline-danger" onclick="deleteTimeLog(${log.log_id})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                `}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function loadActiveTimer() {
            fetch('/api/time-logs/active')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.active_timer) {
                        currentActiveTimer = data.active_timer;
                        showActiveTimer(data.active_timer);
                        startTimerDisplay();
                    } else {
                        hideActiveTimer();
                    }
                })
                .catch(error => console.error('Error loading active timer:', error));
        }

        function showActiveTimer(timer) {
            const display = document.getElementById('active-timer-display');
            const startBtn = document.getElementById('start-timer-btn');
            const stopBtn = document.getElementById('stop-timer-btn');

            if (display) {
                document.getElementById('active-timer-card').textContent = timer.card_title;
                document.getElementById('active-timer-start').textContent = new Date(timer.start_time).toLocaleTimeString();

                display.style.display = 'block';
                if (startBtn) startBtn.style.display = 'none';
                if (stopBtn) stopBtn.style.display = 'inline-block';
            }
        }

        function hideActiveTimer() {
            const display = document.getElementById('active-timer-display');
            const startBtn = document.getElementById('start-timer-btn');
            const stopBtn = document.getElementById('stop-timer-btn');

            if (display) display.style.display = 'none';
            if (startBtn) startBtn.style.display = 'inline-block';
            if (stopBtn) stopBtn.style.display = 'none';

            if (activeTimerInterval) {
                clearInterval(activeTimerInterval);
                activeTimerInterval = null;
            }
            currentActiveTimer = null;
        }

        function startTimerDisplay() {
            if (activeTimerInterval) {
                clearInterval(activeTimerInterval);
            }

            activeTimerInterval = setInterval(() => {
                if (currentActiveTimer) {
                    const startTime = new Date(currentActiveTimer.start_time);
                    const now = new Date();
                    const diffInSeconds = Math.floor((now - startTime) / 1000);

                    const hours = Math.floor(diffInSeconds / 3600);
                    const minutes = Math.floor((diffInSeconds % 3600) / 60);
                    const seconds = diffInSeconds % 60;

                    const formatted = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    const durationElement = document.getElementById('active-timer-duration');
                    if (durationElement) durationElement.textContent = formatted;
                }
            }, 1000);
        }

        function formatMinutes(minutes) {
            if (!minutes) return '0m';

            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;

            if (hours > 0) {
                return `${hours}h ${mins}m`;
            }
            return `${mins}m`;
        }

        function showStartTimerModal() {
            // For Designer panel, we'll use a simple prompt for now
            const cardTitle = prompt('Enter card/task name to track time for:');
            if (cardTitle) {
                const description = prompt('What are you working on?', '');

                // Since Designer might not have cards in the system, we'll create a simple log
                fetch('/api/time-logs/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        card_id: 1, // Default card for designers
                        description: `${cardTitle}: ${description}`
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadActiveTimer();
                        loadTimeLogStatistics();
                        alert('Timer started successfully!');
                    } else {
                        alert('Failed to start timer: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error starting timer:', error);
                    alert('Failed to start timer. Please try again.');
                });
            }
        }

        function stopActiveTimer() {
            if (!confirm('Are you sure you want to stop the active timer?')) {
                return;
            }

            fetch('/api/time-logs/stop', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideActiveTimer();
                    loadTimeLogStatistics();
                    loadTimeLogs();
                    alert('Timer stopped successfully!');
                } else {
                    alert('Failed to stop timer: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error stopping timer:', error);
                alert('Failed to stop timer. Please try again.');
            });
        }

        function filterTimeLogs() {
            const filter = document.getElementById('time-log-filter').value;
            loadTimeLogs(filter, 1);
        }

        function refreshTimeLog() {
            loadTimeLogData();
            alert('Time log data refreshed!');
        }

        function editTimeLogDescription(logId, currentDescription) {
            const newDescription = prompt('Edit description:', currentDescription || '');
            if (newDescription !== null) {
                fetch(`/api/time-logs/${logId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ description: newDescription })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadTimeLogs();
                        alert('Description updated successfully!');
                    } else {
                        alert('Failed to update description: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating description:', error);
                    alert('Failed to update description. Please try again.');
                });
            }
        }

        function deleteTimeLog(logId) {
            if (!confirm('Are you sure you want to delete this time log?')) {
                return;
            }

            fetch(`/api/time-logs/${logId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadTimeLogs();
                    loadTimeLogStatistics();
                    alert('Time log deleted successfully!');
                } else {
                    alert('Failed to delete time log: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting time log:', error);
                alert('Failed to delete time log. Please try again.');
            });
        }

        function loadDeadlineCards() {
            // For Designer panel, we'll skip this for now
            const container = document.getElementById('deadline-cards-list');
            if (container) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x fs-1 text-muted"></i>
                        <p class="text-muted mt-3">Design deadline tracking coming soon</p>
                    </div>
                `;
            }
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotificationDropdown();

            // Refresh notifications every 30 seconds
            setInterval(loadNotificationDropdown, 30000);
        });
    </script>
</body>
</html>
