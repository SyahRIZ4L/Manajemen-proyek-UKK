<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Developer Panel - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        // Navigation system
        function simpleNavigate(section) {
            console.log('Navigating to:', section);

            try {
                // Remove active from all nav links
                const allLinks = document.querySelectorAll('.nav-link');
                allLinks.forEach(link => link.classList.remove('active'));

                // Add active to clicked link
                const clickedLink = document.querySelector(`.nav-link[data-section="${section}"]`);
                if (clickedLink) {
                    clickedLink.classList.add('active');
                }

                // Hide all content sections
                const allSections = document.querySelectorAll('.content-section');
                allSections.forEach(sect => sect.classList.remove('active'));

                // Show target section
                const targetSection = document.getElementById(section + '-content');
                if (targetSection) {
                    targetSection.classList.add('active');

                    // Update header title/subtitle
                    const contentTitle = document.getElementById('content-title');
                    const contentSubtitle = document.getElementById('content-subtitle');

                    if (clickedLink && contentTitle && contentSubtitle) {
                        const titleElement = clickedLink.querySelector('.nav-title');
                        const subtitleElement = clickedLink.querySelector('.nav-subtitle');

                        if (titleElement && subtitleElement) {
                            contentTitle.textContent = titleElement.textContent;
                            contentSubtitle.textContent = subtitleElement.textContent;
                        } else if (section === 'profile') {
                            contentTitle.textContent = 'Developer Profile';
                            contentSubtitle.textContent = 'Manage your account settings';
                        }
                    }

                    // Load section content based on section type
                    if (section === 'my-card') {
                        loadMyCards();
                    } else if (section === 'dashboard') {
                        loadDashboard();
                    } else if (typeof loadSectionContent === 'function') {
                        loadSectionContent(section);
                    }
                } else {
                    console.error('Content section not found:', section + '-content');
                }
            } catch (error) {
                console.error('Error in navigation:', error);
            }

            return false;
        }
    </script>
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
            cursor: pointer;
            z-index: 10;
            pointer-events: auto;
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

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .sidebar {
            pointer-events: auto;
            z-index: 1000;
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

        /* Subtask Styles */
        .subtask-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
            position: relative;
        }
        .subtask-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .subtask-card.priority-high {
            border-left-color: #dc3545;
        }
        .subtask-card.priority-medium {
            border-left-color: #ffc107;
        }
        .subtask-card.priority-low {
            border-left-color: #28a745;
        }
        .subtask-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 12px;
        }
        .subtask-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        .subtask-meta {
            display: flex;
            gap: 10px;
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 10px;
        }
        .subtask-description {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .subtask-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .subtask-actions .btn {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
        .priority-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
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

        /* Todo List Styles */
        .todo-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .todo-item:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }
        .todo-item.completed {
            opacity: 0.7;
            background: #f8f9fa;
        }
        .todo-item.completed .todo-text {
            text-decoration: line-through;
            color: #6c757d;
        }
        .todo-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .todo-checkbox {
            width: 20px;
            height: 20px;
            margin: 0;
        }
        .todo-text {
            flex: 1;
            font-weight: 500;
        }
        .todo-actions {
            display: flex;
            gap: 5px;
        }
        .todo-actions button {
            padding: 4px 8px;
            font-size: 0.8rem;
        }

        /* Comments Section */
        .comments-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }
        .comment-item {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 3px solid #007bff;
        }
        .comment-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 8px;
        }
        .comment-author {
            font-weight: 600;
            color: #333;
        }
        .comment-time {
            font-size: 0.8rem;
            color: #666;
        }
        .comment-text {
            color: #555;
            line-height: 1.4;
        }
        .add-comment {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .add-comment input {
            flex: 1;
        }

        /* Notification Styles */
        .notification-dropdown {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 12px;
        }
        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-item.unread {
            background-color: rgba(13, 110, 253, 0.05);
            border-left: 3px solid #007bff;
        }
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        .notification-icon.info {
            background: rgba(13, 110, 253, 0.1);
            color: #007bff;
        }
        .notification-icon.success {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }
        .notification-icon.warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        .notification-icon.danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        .notification-content {
            flex: 1;
        }
        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #333;
        }
        .notification-message {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 4px;
        }
        .notification-time {
            font-size: 0.8rem;
            color: #999;
        }

        /* Time Log Styles */
        .time-log-item {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s ease;
        }

        .time-log-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 123, 255, 0.3) !important;
        }

        .deadline-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            transition: all 0.3s ease;
        }

        .deadline-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        #active-timer-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            animation: pulse-glow 2s infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
            }
            50% {
                box-shadow: 0 0 30px rgba(102, 126, 234, 0.5);
            }
        }

        #active-timer-duration {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        /* Dark theme time log styles */
        body.dark-theme .time-log-item {
            background: rgba(45, 45, 45, 0.9);
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e0e0e0;
        }

        /* Loading Skeleton Styles - For better UX on slow networks */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .skeleton-card {
            height: 200px;
            border-radius: 8px;
        }

        .skeleton-text {
            height: 16px;
            margin-bottom: 8px;
            border-radius: 4px;
        }

        .skeleton-title {
            height: 24px;
            width: 70%;
            margin-bottom: 12px;
            border-radius: 4px;
        }

        body.dark-theme .skeleton {
            background: linear-gradient(90deg, #2a2a2a 25%, #1a1a1a 50%, #2a2a2a 75%);
            background-size: 200% 100%;
        }

        /* Optimize animations untuk performance */
        @media (prefers-reduced-motion: reduce) {
            .skeleton { animation: none; }
            @keyframes pulse-glow { 0%, 100% { box-shadow: none; } }
        }

        body.dark-theme .time-log-item:hover {
            border-color: rgba(0, 123, 255, 0.5) !important;
        }

        body.dark-theme .deadline-card {
            background: rgba(45, 45, 45, 0.9);
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #e0e0e0;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4>Developer Panel</h4>
            <p>{{ auth()->user()->full_name ?? 'Developer' }}</p>
        </div>

        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-item">
                <a href="#" class="nav-link active" data-section="dashboard" onclick="simpleNavigate('dashboard'); return false;" style="display: block !important; pointer-events: auto !important; position: relative !important; z-index: 9999 !important;">
                    <div class="nav-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Dashboard</div>
                        <div class="nav-subtitle">Development Overview</div>
                    </div>
                </a>
            </div>

            <!-- My Card -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="my-card" onclick="simpleNavigate('my-card'); return false;" style="display: block !important; pointer-events: auto !important; position: relative !important; z-index: 9999 !important;">
                    <div class="nav-icon">
                        <i class="bi bi-kanban"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">My Card</div>
                        <div class="nav-subtitle">Development Tasks</div>
                    </div>
                </a>
            </div>

            <!-- My Subtasks -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="my-subtasks" onclick="simpleNavigate('my-subtasks'); return false;" style="display: block !important; pointer-events: auto !important; position: relative !important; z-index: 9999 !important;">
                    <div class="nav-icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">My Subtasks</div>
                        <div class="nav-subtitle">Development Items</div>
                    </div>
                </a>
            </div>

            <!-- Time Log -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="time-log" onclick="simpleNavigate('time-log'); return false;" style="display: block !important; pointer-events: auto !important; position: relative !important; z-index: 9999 !important;">
                    <div class="nav-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Time Log</div>
                        <div class="nav-subtitle">Development Hours</div>
                    </div>
                </a>
            </div>

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
                    <li><a class="dropdown-item" href="#" data-section="profile" onclick="simpleNavigate('profile'); return false;"><i class="bi bi-person-gear"></i>Profile Settings</a></li>
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
                    <h2 id="content-title" class="mb-0">Developer Dashboard</h2>
                    <p class="text-muted mb-0" id="content-subtitle">Manage your development tasks and projects</p>
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
                    <h3>Welcome back, {{ auth()->user()->full_name ?? auth()->user()->username ?? 'Developer' }}!</h3>
                    <p>Ready to code? Here's your development workspace overview.</p>
                </div>

                <!-- Main Statistics -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-kanban stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary" id="my-cards-count">0</h3>
                            <p class="stat-label">My Cards</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-list-check stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning" id="active-subtasks-count">0</h3>
                            <p class="stat-label">Active Subtasks</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-check-circle stat-icon text-success"></i>
                            <h3 class="stat-number text-success" id="completed-work-count">0</h3>
                            <p class="stat-label">Completed Work</p>
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
                        <a href="#" class="quick-action-card" data-section="my-card" onclick="simpleNavigate('my-card'); return false;">
                            <div class="quick-action-icon">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h6 class="quick-action-title">View My Card</h6>
                            <p class="quick-action-desc">See assigned development cards</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="my-subtasks" onclick="simpleNavigate('my-subtasks'); return false;">
                            <div class="quick-action-icon">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <h6 class="quick-action-title">My Subtasks</h6>
                            <p class="quick-action-desc">Manage development items</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" data-section="time-log" onclick="simpleNavigate('time-log'); return false;">
                            <div class="quick-action-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h6 class="quick-action-title">Log Time</h6>
                            <p class="quick-action-desc">Track development hours</p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="quick-action-card" onclick="logNewTime()">
                            <div class="quick-action-icon">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <h6 class="quick-action-title">Quick Log</h6>
                            <p class="quick-action-desc">Add new time entry</p>
                        </a>
                    </div>
                </div>

                <!-- Recent Development Activity -->
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-4">Recent Development Activity</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="activity-item">
                                    <div class="activity-icon bg-success">
                                        <i class="bi bi-code-slash"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>Code Implementation Completed</h6>
                                        <p>Implemented user authentication API endpoints</p>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon bg-info">
                                        <i class="bi bi-check2-square"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>Subtask Completed</h6>
                                        <p>Database migration for user profiles</p>
                                        <small class="text-muted">4 hours ago</small>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon bg-warning">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>Time Logged</h6>
                                        <p>3.5 hours on frontend component development</p>
                                        <small class="text-muted">Yesterday</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Card Content -->
            <div id="my-card-content" class="content-section">
                <div class="content-header">
                    <h2>My Cards</h2>
                    <p>Manage your assigned development tasks</p>
                </div>

                <div class="content-area">
                    <!-- Status Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <select id="cardStatusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="todo">To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="review">In Review</option>
                                <option value="done">Done</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select id="cardPriorityFilter" class="form-select">
                                <option value="">All Priority</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>

                    <!-- Cards Grid -->
                    <div id="cardsContainer" class="row">
                        <!-- Cards will be loaded here dynamically -->
                        <div class="col-12 text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Loading your cards...</p>
                        </div>
                    </div>

                    <!-- Pagination Info -->
                    <div id="pagination-info" class="text-center mt-3 text-muted" style="display: none;"></div>
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

            <!-- My Subtasks Content -->
            <div id="my-subtasks-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>My Subtasks</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubtaskModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Subtask
                    </button>
                </div>

                <!-- Subtask Tabs -->
                <ul class="nav nav-tabs mb-4" id="subtaskTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="active-subtasks-tab" data-bs-toggle="tab" data-bs-target="#active-subtasks" type="button" role="tab">
                            <i class="bi bi-play-circle me-2"></i>Active <span class="badge bg-primary ms-1" id="active-count">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="completed-subtasks-tab" data-bs-toggle="tab" data-bs-target="#completed-subtasks" type="button" role="tab">
                            <i class="bi bi-check-circle me-2"></i>Completed <span class="badge bg-success ms-1" id="completed-count">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="todolist-tab" data-bs-toggle="tab" data-bs-target="#todolist" type="button" role="tab">
                            <i class="bi bi-list-check me-2"></i>Todo List <span class="badge bg-warning ms-1" id="todo-count">0</span>
                        </button>
                    </li>
                </ul>

                <!-- Subtask Tab Content -->
                <div class="tab-content" id="subtaskTabContent">
                    <!-- Active Subtasks -->
                    <div class="tab-pane fade show active" id="active-subtasks" role="tabpanel">
                        <div id="active-subtasks-list" class="row g-3">
                            <!-- Active subtasks will be loaded here -->
                        </div>
                    </div>

                    <!-- Completed Subtasks -->
                    <div class="tab-pane fade" id="completed-subtasks" role="tabpanel">
                        <div id="completed-subtasks-list" class="row g-3">
                            <!-- Completed subtasks will be loaded here -->
                        </div>
                    </div>

                    <!-- Todo List -->
                    <div class="tab-pane fade" id="todolist" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Personal Todo List</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="addTodoItem()">
                                <i class="bi bi-plus-lg me-1"></i>Add Todo
                            </button>
                        </div>
                        <div id="todo-list-container">
                            <!-- Todo items will be loaded here -->
                        </div>
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
                                            <input type="text" class="form-control" value="{{ auth()->user()->full_name ?? 'Not Set' }}" name="full_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" value="{{ auth()->user()->username ?? 'developer' }}" name="username" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ auth()->user()->email ?? 'developer@example.com' }}" name="email">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" value="Developer" readonly>
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
                                        <small class="text-muted">Ready for new tasks</small>
                                    </div>
                                </div>
                                <button class="btn btn-outline-primary btn-sm">Update Status</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Log Content -->
            <div id="time-log-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4>Time Log</h4>
                        <p class="text-muted mb-0">Track your working time and monitor deadlines</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" id="start-timer-btn" onclick="showStartTimerModal()">
                            <i class="bi bi-play-circle"></i> Start Timer
                        </button>
                        <button class="btn btn-danger" id="stop-timer-btn" onclick="stopActiveTimer()" style="display: none;">
                            <i class="bi bi-stop-circle"></i> Stop Timer
                        </button>
                        <button class="btn btn-outline-primary" onclick="refreshTimeLog()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Active Timer Display -->
                <div id="active-timer-display" class="alert alert-info" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0 me-2">Active Timer</h6>
                                <span id="auto-timer-badge" class="badge bg-success" style="display: none;">
                                    <i class="bi bi-robot"></i> AUTO
                                </span>
                            </div>
                            <div id="active-timer-details">
                                <strong id="active-timer-card">Card Name</strong>
                                <div class="text-muted">
                                    Started: <span id="active-timer-start">--:--</span>
                                    <span id="active-timer-type" class="ms-2 text-warning" style="display: none;">
                                        <i class="bi bi-magic"></i> Auto-started by status change
                                    </span>
                                </div>
                                <div id="active-timer-description" class="text-muted small" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0" id="active-timer-duration">00:00:00</div>
                            <small class="text-muted">Running time</small>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-primary mb-1" id="stats-today">0h 0m</div>
                                <small class="text-muted">Today</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-success mb-1" id="stats-week">0h 0m</div>
                                <small class="text-muted">This Week</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-info mb-1" id="stats-month">0h 0m</div>
                                <small class="text-muted">This Month</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 text-warning mb-1" id="stats-sessions">0</div>
                                <small class="text-muted">Sessions Today</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter and Search -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">Recent Time Logs</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex gap-2">
                                    <select class="form-select form-select-sm" id="time-log-filter" onchange="filterTimeLogs()">
                                        <option value="all">All Time Logs</option>
                                        <option value="today">Today</option>
                                        <option value="week">This Week</option>
                                        <option value="month">This Month</option>
                                        <option value="active">Active Only</option>
                                        <option value="completed">Completed Only</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="time-logs-list">
                            <!-- Time logs will be loaded here -->
                            <div class="text-center py-4" id="no-time-logs">
                                <i class="bi bi-clock fs-1 text-muted"></i>
                                <p class="text-muted mt-3">No time logs found</p>
                                <button class="btn btn-primary" onclick="showStartTimerModal()">
                                    <i class="bi bi-play-circle"></i> Start Your First Timer
                                </button>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Time log pagination" class="mt-4">
                            <ul class="pagination justify-content-center" id="time-log-pagination">
                                <!-- Pagination will be loaded here -->
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Cards with Deadlines -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Cards with Deadlines</h5>
                    </div>
                    <div class="card-body">
                        <div id="deadline-cards-list">
                            <!-- Cards with deadlines will be loaded here -->
                            <div class="text-center py-4" id="no-deadline-cards">
                                <i class="bi bi-calendar-x fs-1 text-muted"></i>
                                <p class="text-muted mt-3">No cards with deadlines</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Add Subtask Modal -->
    <div class="modal fade" id="addSubtaskModal" tabindex="-1" aria-labelledby="addSubtaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubtaskModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>Add New Subtask
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSubtaskForm">
                        <div class="mb-3">
                            <label for="subtaskTitle" class="form-label">Subtask Title</label>
                            <input type="text" class="form-control" id="subtaskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="subtaskDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="subtaskDescription" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="subtaskPriority" class="form-label">Priority</label>
                                <select class="form-select" id="subtaskPriority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="subtaskDueDate" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="subtaskDueDate">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSubtask()">
                        <i class="bi bi-check-lg me-1"></i>Save Subtask
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Timer Modal -->
    <div class="modal fade" id="startTimerModal" tabindex="-1" aria-labelledby="startTimerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="startTimerModalLabel">
                        <i class="bi bi-play-circle text-success me-2"></i>Start Timer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="startTimerForm">
                        <div class="mb-3">
                            <label for="timer-card-select" class="form-label">Select Card</label>
                            <select class="form-select" id="timer-card-select" required>
                                <option value="">Choose a card to track time...</option>
                                <!-- Cards will be loaded here -->
                            </select>
                            <div class="form-text">Select the card you want to track time for</div>
                        </div>

                        <div class="mb-3" id="timer-subtask-container" style="display: none;">
                            <label for="timer-subtask-select" class="form-label">Subtask (Optional)</label>
                            <select class="form-select" id="timer-subtask-select">
                                <option value="">No specific subtask</option>
                                <!-- Subtasks will be loaded here -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="timer-description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="timer-description" rows="3"
                                placeholder="What are you working on? (e.g., 'Implementing login functionality')"></textarea>
                            <div class="form-text">Brief description of what you'll be working on</div>
                        </div>

                        <!-- Card deadline info -->
                        <div id="card-deadline-info" class="alert" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Card Deadline</h6>
                                    <div id="deadline-status-text"></div>
                                </div>
                                <div class="text-end">
                                    <div id="deadline-countdown" class="h5 mb-0"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="startTimerFromModal()">
                        <i class="bi bi-play-circle me-1"></i>Start Timer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-bell-fill text-primary me-2"></i>
                <strong class="me-auto">Notification</strong>
                <small class="text-muted" id="toastTime">now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                <!-- Notification message will be inserted here -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        console.log('Script loaded - Starting initialization...');

        function initializeNavigation() {
            console.log('Initializing navigation system...');

            // Navigation functionality
            const navLinks = document.querySelectorAll('.nav-link[data-section]');
            const contentSections = document.querySelectorAll('.content-section');
            const contentTitle = document.getElementById('content-title');
            const contentSubtitle = document.getElementById('content-subtitle');

            console.log('Found nav links:', navLinks.length);
            console.log('Found content sections:', contentSections.length);
            console.log('Content title element:', contentTitle);
            console.log('Content subtitle element:', contentSubtitle);

            // Log all nav links and their sections
            navLinks.forEach((link, index) => {
                const section = link.getAttribute('data-section');
                console.log(`Nav link ${index + 1}: ${section}`);
            });

            // Log all content sections
            contentSections.forEach((section, index) => {
                console.log(`Content section ${index + 1}: ${section.id}`);
            });

            // Test dengan multiple event listener types
            navLinks.forEach((link, index) => {
                console.log(`Setting up listeners for nav link ${index + 1}`);

                // Multiple event types untuk memastikan salah satu berjalan
                // Simplified single click event
                link.addEventListener('click', function(e) {
                    console.log('Click event triggered on:', this.getAttribute('data-section'));
                    e.preventDefault();
                    e.stopPropagation();
                    const targetSection = this.getAttribute('data-section');

                    // Test alert first
                    alert(`Menu clicked: ${targetSection}`);

                    // Update active nav
                    navLinks.forEach(navLink => navLink.classList.remove('active'));
                    this.classList.add('active');

                    // Update content
                    contentSections.forEach(section => section.classList.remove('active'));
                    const targetContent = document.getElementById(targetSection + '-content');

                    if (targetContent) {
                        targetContent.classList.add('active');
                        console.log('Content section activated:', targetSection + '-content');
                    } else {
                        console.error('Content section not found:', targetSection + '-content');
                    }

                    // Update header
                    const titleElement = this.querySelector('.nav-title');
                    const subtitleElement = this.querySelector('.nav-subtitle');

                    if (titleElement && subtitleElement && contentTitle && contentSubtitle) {
                        contentTitle.textContent = titleElement.textContent;
                        contentSubtitle.textContent = subtitleElement.textContent;
                    }

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

            // Dropdown profile navigation
            document.querySelectorAll('.dropdown-item[data-section]').forEach(item => {
                console.log('Adding dropdown listener for:', item.getAttribute('data-section'));
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetSection = this.getAttribute('data-section');
                    console.log('Dropdown clicked:', targetSection);

                    // Update active nav
                    navLinks.forEach(navLink => navLink.classList.remove('active'));

                    // Update content
                    contentSections.forEach(section => section.classList.remove('active'));
                    const targetContent = document.getElementById(targetSection + '-content');

                    if (targetContent) {
                        targetContent.classList.add('active');
                        console.log('Activated content section:', targetSection + '-content');
                    } else {
                        console.error('Content section not found:', targetSection + '-content');
                    }

                    // Update header for profile
                    if (targetSection === 'profile' && contentTitle && contentSubtitle) {
                        contentTitle.textContent = 'Developer Profile';
                        contentSubtitle.textContent = 'Manage your account settings';
                    }
                });
            });

            // Quick action cards
            document.querySelectorAll('.quick-action-card[data-section]').forEach(card => {
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetSection = this.getAttribute('data-section');
                    console.log('Quick action card clicked:', targetSection);

                    // Find corresponding nav link and trigger click
                    const navLink = document.querySelector(`.nav-link[data-section="${targetSection}"]`);
                    if (navLink) {
                        navLink.click();
                        console.log('Nav link triggered from quick action:', targetSection);
                    } else {
                        console.error('Nav link not found for quick action:', targetSection);
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

            // Load notifications
            loadNotifications();

            // Refresh notifications periodically
            setInterval(loadNotifications, 60000); // Every minute
        }

        // Multiple initialization methods
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeNavigation);
        } else {
            // DOM already loaded
            initializeNavigation();
        }

        // Backup initialization
        window.addEventListener('load', function() {
            console.log('Window load event - backup initialization');
            initializeNavigation();
        });

        // Immediate test
        setTimeout(function() {
            console.log('Timeout test - trying immediate initialization');
            initializeNavigation();
        }, 1000);

        // Global function untuk test manual
        window.testNavigation = function() {
            console.log('Manual navigation test');
            const links = document.querySelectorAll('.nav-link[data-section]');
            console.log('Manual test found links:', links.length);
            if (links.length > 0) {
                console.log('Clicking first link manually...');
                links[0].click();
            }
        };

        console.log('Script setup complete. Try: testNavigation() in console');

        // Global navigation function untuk inline onclick
        window.navigateToSection = function(targetSection) {
            console.log('Navigate to section:', targetSection);

            // Get elements
            const navLinks = document.querySelectorAll('.nav-link[data-section]');
            const contentSections = document.querySelectorAll('.content-section');
            const contentTitle = document.getElementById('content-title');
            const contentSubtitle = document.getElementById('content-subtitle');

            // Update active nav
            navLinks.forEach(navLink => navLink.classList.remove('active'));
            const activeLink = document.querySelector(`.nav-link[data-section="${targetSection}"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }

            // Update content sections
            contentSections.forEach(section => section.classList.remove('active'));
            const targetContent = document.getElementById(targetSection + '-content');

            if (targetContent) {
                targetContent.classList.add('active');
                console.log('Content section activated:', targetSection + '-content');
            } else {
                console.error('Content section not found:', targetSection + '-content');
            }

            // Update header
            if (activeLink && contentTitle && contentSubtitle) {
                const titleElement = activeLink.querySelector('.nav-title');
                const subtitleElement = activeLink.querySelector('.nav-subtitle');

                if (titleElement && subtitleElement) {
                    contentTitle.textContent = titleElement.textContent;
                    contentSubtitle.textContent = subtitleElement.textContent;
                }
            }

            // Load content
            if (typeof loadSectionContent === 'function') {
                loadSectionContent(targetSection);
            }
        };

        // Load content based on section
        function loadSectionContent(section) {
            switch(section) {
                case 'dashboard':
                    loadDashboardData();
                    break;
                case 'my-card':
                    loadCards();
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
                case 'my-subtasks':
                    loadSubtasks();
                    loadTodoList();
                    break;
                case 'time-log':
                    loadTimeLogData();
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

        // Cards Management Functions - OPTIMIZED FOR SLOW NETWORKS
        let cardsCache = null;
        let cacheTimestamp = null;
        const CACHE_DURATION = 60000; // 1 minute cache

        function loadCards(useCache = true) {
            const container = document.getElementById('cardsContainer');

            // Check cache first
            if (useCache && cardsCache && cacheTimestamp && (Date.now() - cacheTimestamp < CACHE_DURATION)) {
                console.log('Using cached cards data');
                displayCards(cardsCache);
                return;
            }

            // Show loading skeleton untuk better UX
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading your cards...</p>
                </div>
            `;

            // Fetch with pagination (20 items) untuk reduce payload
            fetch('/api/developer/cards?per_page=20')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cache the data
                        cardsCache = data.data || data.cards;
                        cacheTimestamp = Date.now();

                        displayCards(cardsCache);

                        // Show pagination info if available
                        if (data.pagination) {
                            showPaginationInfo(data.pagination);
                        }
                    } else {
                        container.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Error loading cards</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<div class="col-12 text-center py-5"><p class="text-danger">Network error. Please check your connection.</p></div>';
                });
        }

        // New function for My Cards section using dedicated API
        function loadMyCards() {
            const container = document.getElementById('cardsContainer');

            // Show loading
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading your assigned cards...</p>
                </div>
            `;

            fetch('/api/developer/my-cards')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.cards && data.cards.length > 0) {
                        // Map API response to expected format
                        const mappedCards = data.cards.map(card => ({
                            card_id: card.card_id,
                            title: card.card_title, // Map card_title to title
                            description: card.description,
                            status: card.status,
                            priority: card.priority,
                            due_date: card.due_date,
                            deadline: card.deadline,
                            estimated_hours: card.estimated_hours,
                            actual_hours: card.actual_hours,
                            assigned_by: card.assigned_by,
                            board_name: card.board_name,
                            project_name: card.project_name,
                            created_at: card.created_at
                        }));

                        displayCards(mappedCards);
                    } else {
                        container.innerHTML = `
                            <div class="col-12 text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <h5 class="text-muted mt-3">No Cards Assigned</h5>
                                <p class="text-muted">You don't have any cards assigned to you yet.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading my cards:', error);
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                                Failed to load cards. Please try again.
                            </div>
                        </div>
                    `;
                });
        }

        // Load dashboard statistics
        function loadDashboard() {
            fetch('/api/developer/dashboard')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.stats) {
                        // Update dashboard stats
                        const totalCardsEl = document.getElementById('my-cards-count');
                        const activeSubtasksEl = document.getElementById('active-subtasks-count');
                        const completedWorkEl = document.getElementById('completed-work-count');

                        if (totalCardsEl) totalCardsEl.textContent = data.stats.total || 0;
                        if (activeSubtasksEl) activeSubtasksEl.textContent = data.stats.pending || 0;
                        if (completedWorkEl) completedWorkEl.textContent = data.stats.completed || 0;
                    }
                })
                .catch(error => {
                    console.error('Error loading dashboard stats:', error);
                });
        }

        function showPaginationInfo(pagination) {
            const info = document.getElementById('pagination-info');
            if (info && pagination.total > 0) {
                info.innerHTML = `Showing ${pagination.per_page} of ${pagination.total} cards`;
                info.style.display = 'block';
            }
        }

        function displayCards(cards) {
            const container = document.getElementById('cardsContainer');

            if (cards.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-kanban display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">No Cards Found</h5>
                        <p class="text-muted">You don't have any assigned cards yet.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = cards.map(card => {
                const statusClass = getStatusClass(card.status);
                const priorityClass = getPriorityClass(card.priority);

                return `
                    <div class="col-md-6 col-lg-4 mb-4 card-item" data-status="${card.status}" data-priority="${card.priority}">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="badge bg-${priorityClass} text-uppercase">${card.priority}</span>
                                <span class="badge bg-${statusClass.bg} text-${statusClass.text}">${formatStatus(card.status)}</span>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">${card.title}</h6>
                                <p class="card-text text-muted small">${card.description}</p>

                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> ${card.assigned_by}<br>
                                        <i class="bi bi-kanban"></i> ${card.board_name}<br>
                                        <i class="bi bi-calendar"></i> Due: ${formatDate(card.due_date)}
                                    </small>
                                </div>

                                ${getCardTimeInfo(card)}
                            </div>
                            <div class="card-footer bg-transparent">
                                ${getCardActionButtons(card)}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            // Add event listeners for buttons
            addCardEventListeners();
        }

        function getStatusClass(status) {
            switch(status) {
                case 'todo': return { bg: 'secondary', text: 'white' };
                case 'in_progress': return { bg: 'primary', text: 'white' };
                case 'review': return { bg: 'warning', text: 'dark' };
                case 'done': return { bg: 'success', text: 'white' };
                default: return { bg: 'light', text: 'dark' };
            }
        }

        function getPriorityClass(priority) {
            switch(priority) {
                case 'high': return 'danger';
                case 'medium': return 'warning';
                case 'low': return 'info';
                default: return 'secondary';
            }
        }

        function formatStatus(status) {
            switch(status) {
                case 'todo': return 'To Do';
                case 'in_progress': return 'In Progress';
                case 'review': return 'In Review';
                case 'done': return 'Done';
                default: return status;
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }

        function getCardActionButtons(card) {
            switch(card.status) {
                case 'todo':
                    return `<button class="btn btn-primary btn-sm w-100" onclick="startCard(${card.card_id})">
                                <i class="bi bi-play"></i> Start Task
                            </button>`;

                case 'in_progress':
                    return `<div class="d-grid gap-2">
                                <button class="btn btn-success btn-sm" onclick="submitCard(${card.card_id})">
                                    <i class="bi bi-check2"></i> Submit for Review
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="pauseCard(${card.card_id})">
                                    <i class="bi bi-pause"></i> Pause
                                </button>
                            </div>`;

                case 'review':
                    return `<div class="alert alert-warning mb-0 small">
                                <i class="bi bi-clock"></i> Waiting for TeamLead review
                            </div>`;

                case 'done':
                    return `<div class="alert alert-success mb-0 small">
                                <i class="bi bi-check-circle"></i> Task completed
                            </div>`;

                default:
                    return '';
            }
        }

        function getCardTimeInfo(card) {
            let html = '<div class="mt-2 pt-2 border-top">';

            // Estimated hours
            if (card.estimated_hours) {
                html += `<small class="text-muted d-block">
                    <i class="bi bi-hourglass-split"></i> Estimated: ${card.estimated_hours}h
                </small>`;
            }

            // Actual hours (time spent)
            if (card.actual_hours) {
                const percentage = card.estimated_hours ?
                    Math.round((card.actual_hours / card.estimated_hours) * 100) : 0;
                const barClass = percentage > 100 ? 'bg-danger' :
                               percentage > 80 ? 'bg-warning' : 'bg-success';

                html += `<small class="text-muted d-block">
                    <i class="bi bi-clock-history"></i> Spent: ${card.actual_hours}h
                </small>`;

                if (card.estimated_hours) {
                    html += `<div class="progress mt-1" style="height: 4px;">
                        <div class="progress-bar ${barClass}" role="progressbar"
                             style="width: ${Math.min(percentage, 100)}%"
                             aria-valuenow="${percentage}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>`;
                }
            }

            // Timer status
            if (card.is_timer_active) {
                html += `<small class="text-success d-block mt-1">
                    <i class="bi bi-stopwatch"></i> Timer running
                </small>`;
            }

            html += '</div>';
            return html;
        }

        function addCardEventListeners() {
            // Add event listeners for filter dengan debouncing
            const statusFilter = document.getElementById('cardStatusFilter');
            const priorityFilter = document.getElementById('cardPriorityFilter');

            if (statusFilter) statusFilter.addEventListener('change', debounce(filterCards, 300));
            if (priorityFilter) priorityFilter.addEventListener('change', debounce(filterCards, 300));
        }

        // Debounce function untuk optimize performance
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function filterCards() {
            const statusFilter = document.getElementById('cardStatusFilter')?.value;
            const priorityFilter = document.getElementById('cardPriorityFilter')?.value;
            const cards = document.querySelectorAll('.card-item');

            let visibleCount = 0;
            cards.forEach(card => {
                const cardStatus = card.dataset.status;
                const cardPriority = card.dataset.priority;

                const statusMatch = !statusFilter || cardStatus === statusFilter;
                const priorityMatch = !priorityFilter || cardPriority === priorityFilter;

                if (statusMatch && priorityMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show message jika tidak ada hasil
            updateFilterMessage(visibleCount);
        }

        function updateFilterMessage(count) {
            let messageDiv = document.getElementById('filter-message');
            if (!messageDiv) {
                messageDiv = document.createElement('div');
                messageDiv.id = 'filter-message';
                messageDiv.className = 'col-12 text-center py-3';
                document.getElementById('cardsContainer').appendChild(messageDiv);
            }

            if (count === 0) {
                messageDiv.innerHTML = '<p class="text-muted">No cards match the selected filters</p>';
                messageDiv.style.display = 'block';
            } else {
                messageDiv.style.display = 'none';
            }
        }

        // Card Action Functions - With cache invalidation
        function startCard(cardId) {
            cardsCache = null; // Invalidate cache
            updateCardStatus(cardId, 'in_progress');
        }

        function pauseCard(cardId) {
            cardsCache = null; // Invalidate cache
            updateCardStatus(cardId, 'todo');
        }

        function submitCard(cardId) {
            cardsCache = null; // Invalidate cache
            const comment = prompt('Add a comment about your work (optional):');

            fetch(`/api/developer/cards/${cardId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comment: comment || '' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Card submitted for review successfully!');
                    loadCards(); // Reload cards
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting card');
            });
        }

        function updateCardStatus(cardId, status) {
            fetch(`/api/developer/cards/${cardId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    if (status === 'in_progress') {
                        showSuccessMessage('Task started! Timer is now running.');
                    } else if (status === 'review') {
                        showSuccessMessage('Task submitted for review!');
                    }

                    loadCards(); // Reload cards to show updated status
                    loadActiveTimer(); // Refresh active timer display
                    loadStatistics(); // Update statistics
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating card status');
            });
        }

        function showSuccessMessage(message) {
            // Create toast notification
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
            toast.setAttribute('role', 'alert');
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            document.body.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        // Subtasks Management Functions
        function loadSubtasks() {
            loadActiveSubtasks();
            loadCompletedSubtasks();
        }

        function loadActiveSubtasks() {
            const container = document.getElementById('active-subtasks-list');

            // Show loading
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading active subtasks...</p>
                </div>
            `;

            // Fetch active subtasks from API
            fetch('/api/subtasks?status=active', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    const activeSubtasks = data.data;
                    container.innerHTML = activeSubtasks.map(subtask => `
                <div class="col-md-6">
                    <div class="subtask-card priority-${subtask.priority}">
                        <div class="subtask-header">
                            <h6 class="subtask-title">${subtask.title}</h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="editSubtask(${subtask.id})">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="completeSubtask(${subtask.id})">
                                        <i class="bi bi-check-lg me-2"></i>Mark Complete
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteSubtask(${subtask.id})">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="subtask-meta">
                            <span><i class="bi bi-calendar"></i> Due: ${subtask.dueDate}</span>
                            <span class="priority-badge priority-${subtask.priority}">${subtask.priority.toUpperCase()}</span>
                        </div>
                        <p class="subtask-description">${subtask.description}</p>

                        <div class="comments-section">
                            <h6><i class="bi bi-chat-dots me-2"></i>Comments (${subtask.comments.length})</h6>
                            <div id="comments-${subtask.id}">
                                ${subtask.comments.map(comment => `
                                    <div class="comment-item">
                                        <div class="comment-header">
                                            <span class="comment-author">${comment.author}</span>
                                            <span class="comment-time">${comment.time}</span>
                                        </div>
                                        <div class="comment-text">${comment.text}</div>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="add-comment">
                                <input type="text" class="form-control form-control-sm" placeholder="Add a comment..." id="comment-input-${subtask.id}">
                                <button class="btn btn-sm btn-primary" onclick="addComment(${subtask.id})">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

                    document.getElementById('active-count').textContent = activeSubtasks.length;
                } else {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-list-task display-4 text-muted"></i>
                            <p class="mt-3 text-muted">No active subtasks found.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubtaskModal">
                                <i class="bi bi-plus-circle me-2"></i>Add Your First Subtask
                            </button>
                        </div>
                    `;
                    document.getElementById('active-count').textContent = '0';
                }
            })
            .catch(error => {
                console.error('Error loading subtasks:', error);
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-exclamation-triangle display-4 text-danger"></i>
                        <p class="mt-3 text-danger">Error loading subtasks.</p>
                        <button class="btn btn-outline-primary" onclick="loadActiveSubtasks()">Try Again</button>
                    </div>
                `;
            });
        }

        function loadCompletedSubtasks() {
            const container = document.getElementById('completed-subtasks-list');

            // Show loading
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading completed subtasks...</p>
                </div>
            `;

            // Fetch completed subtasks from API
            fetch('/api/subtasks?status=completed', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    const completedSubtasks = data.data;
                    container.innerHTML = completedSubtasks.map(subtask => `
                        <div class="col-md-6">
                            <div class="subtask-card priority-${subtask.priority}" style="opacity: 0.8;">
                                <div class="subtask-header">
                                    <h6 class="subtask-title">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>${subtask.title}
                                    </h6>
                                </div>
                                <div class="subtask-meta">
                                    <span><i class="bi bi-calendar-check"></i> Completed: ${subtask.completed_at ? new Date(subtask.completed_at).toLocaleDateString() : 'N/A'}</span>
                                    <span class="priority-badge priority-${subtask.priority}">${subtask.priority.toUpperCase()}</span>
                                </div>
                                <p class="subtask-description">${subtask.description || 'No description'}</p>
                            </div>
                        </div>
                    `).join('');

                    document.getElementById('completed-count').textContent = completedSubtasks.length;
                } else {
                    container.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-check-circle display-4 text-muted"></i>
                            <p class="mt-3 text-muted">No completed subtasks yet.</p>
                        </div>
                    `;
                    document.getElementById('completed-count').textContent = '0';
                }
            })
            .catch(error => {
                console.error('Error loading completed subtasks:', error);
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-exclamation-triangle display-4 text-danger"></i>
                        <p class="mt-3 text-danger">Error loading completed subtasks.</p>
                        <button class="btn btn-outline-success" onclick="loadCompletedSubtasks()">Try Again</button>
                    </div>
                `;
            });
        }

        // Todo List Functions
        function loadTodoList() {
            const container = document.getElementById('todo-list-container');

            // Show loading
            container.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading todos...</p>
                </div>
            `;

            // Fetch todos from API
            fetch('/api/todos', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const todoItems = data.data;
                    if (todoItems.length > 0) {
                        container.innerHTML = todoItems.map(item => `
                            <div class="todo-item ${item.completed ? 'completed' : ''}">
                                <div class="todo-content">
                                    <input type="checkbox" class="todo-checkbox" ${item.completed ? 'checked' : ''}
                                           onchange="toggleTodo(${item.id})">
                                    <span class="todo-text">${item.text}</span>
                                    <div class="todo-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editTodo(${item.id}, '${item.text}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteTodo(${item.id})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `).join('');

                        const activeCount = todoItems.filter(item => !item.completed).length;
                        document.getElementById('todo-count').textContent = activeCount;
                    } else {
                        container.innerHTML = `
                            <div class="text-center py-4">
                                <i class="bi bi-list-check display-4 text-muted"></i>
                                <p class="mt-3 text-muted">No todos yet. Add one to get started!</p>
                            </div>
                        `;
                        document.getElementById('todo-count').textContent = '0';
                    }
                } else {
                    throw new Error(data.message || 'Failed to load todos');
                }
            })
            .catch(error => {
                console.error('Error loading todos:', error);
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle display-4 text-danger"></i>
                        <p class="mt-3 text-danger">Error loading todos.</p>
                        <button class="btn btn-outline-warning" onclick="loadTodoList()">Try Again</button>
                    </div>
                `;
            });
        }

        function addTodoItem() {
            const text = prompt('Enter todo item:');
            if (text && text.trim()) {
                const newItem = {
                    id: Date.now(),
                    text: text.trim(),
                    completed: false
                };
                todoItems.push(newItem);
                loadTodoList();
                showNotification('Todo item added successfully!');
            }
        }

        function toggleTodo(id) {
            const item = todoItems.find(item => item.id === id);
            if (item) {
                item.completed = !item.completed;
                loadTodoList();
                showNotification(item.completed ? 'Todo marked as completed!' : 'Todo marked as active!');
            }
        }

        function editTodo(id) {
            const item = todoItems.find(item => item.id === id);
            if (item) {
                const newText = prompt('Edit todo:', item.text);
                if (newText && newText.trim()) {
                    item.text = newText.trim();
                    loadTodoList();
                    showNotification('Todo updated successfully!');
                }
            }
        }

        function deleteTodo(id) {
            if (confirm('Are you sure you want to delete this todo item?')) {
                todoItems = todoItems.filter(item => item.id !== id);
                loadTodoList();
                showNotification('Todo item deleted!');
            }
        }

        // Subtask Action Functions
        function saveSubtask() {
            const title = document.getElementById('subtaskTitle').value;
            const description = document.getElementById('subtaskDescription').value;
            const priority = document.getElementById('subtaskPriority').value;
            const dueDate = document.getElementById('subtaskDueDate').value;

            if (!title.trim()) {
                alert('Please enter a subtask title');
                return;
            }

            // Mock save functionality
            console.log('Saving subtask:', { title, description, priority, dueDate });

            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('addSubtaskModal'));
            modal.hide();
            document.getElementById('addSubtaskForm').reset();

            // Reload subtasks and show notification
            loadSubtasks();
            showNotification('Subtask added successfully!');
        }

        function completeSubtask(id) {
            if (confirm('Mark this subtask as completed?')) {
                console.log('Completing subtask:', id);
                loadSubtasks();
                showNotification('Subtask marked as completed!');
            }
        }

        function editSubtask(id) {
            console.log('Editing subtask:', id);
            showNotification('Edit functionality will be implemented soon!');
        }

        function deleteSubtask(id) {
            if (confirm('Are you sure you want to delete this subtask?')) {
                console.log('Deleting subtask:', id);
                loadSubtasks();
                showNotification('Subtask deleted!');
            }
        }

        function addComment(subtaskId) {
            const input = document.getElementById(`comment-input-${subtaskId}`);
            const commentText = input.value.trim();

            if (commentText) {
                console.log('Adding comment to subtask:', subtaskId, commentText);
                input.value = '';
                loadSubtasks(); // Reload to show new comment
                showNotification('Comment added successfully!');
            }
        }

        // Notification Function
        function showNotification(message) {
            const toast = document.getElementById('notificationToast');
            const toastMessage = document.getElementById('toastMessage');
            const toastTime = document.getElementById('toastTime');

            toastMessage.textContent = message;
            toastTime.textContent = 'now';

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Notifications Functions
        function loadNotifications() {
            fetch('/api/notifications/recent', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationDropdown(data.data);
                    updateNotificationBadge(data.unread_count);
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
        }

        function updateNotificationDropdown(notifications) {
            const container = document.getElementById('notificationsList');

            if (notifications.length === 0) {
                container.innerHTML = `
                    <li class="px-3 py-2 text-center text-muted">
                        <i class="bi bi-bell-slash"></i>
                        <div class="mt-2">No notifications</div>
                    </li>
                `;
                return;
            }

            container.innerHTML = notifications.map(notification => `
                <li class="notification-item ${notification.read_at ? '' : 'unread'}" onclick="markAsRead(${notification.id})">
                    <div class="d-flex">
                        <div class="notification-icon ${getNotificationIconClass(notification.type)}">
                            <i class="bi ${getNotificationIcon(notification.type)}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">${notification.title}</div>
                            <div class="notification-message">${notification.message}</div>
                            <div class="notification-time">${timeAgo(notification.created_at)}</div>
                        </div>
                    </div>
                </li>
            `).join('');
        }

        function updateNotificationBadge(count) {
            const badge = document.getElementById('notificationBadge');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }

        function markAsRead(notificationId) {
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
                    loadNotifications(); // Refresh dropdown only
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        function markAllAsRead() {
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
                    loadNotifications();
                    showNotification('All notifications marked as read');
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        }

        function deleteNotification(notificationId) {
            if (confirm('Are you sure you want to delete this notification?')) {
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
                        showNotification('Notification deleted');
                    }
                })
                .catch(error => {
                    console.error('Error deleting notification:', error);
                });
            }
        }
                navLink.click();
            }
        }

        function getNotificationIcon(type) {
            switch(type) {
                case 'task_assigned': return 'bi-clipboard-check';
                case 'task_completed': return 'bi-check-circle';
                case 'project_update': return 'bi-folder-plus';
                case 'system': return 'bi-gear';
                case 'reminder': return 'bi-alarm';
                default: return 'bi-bell';
            }
        }

        function getNotificationIconClass(type) {
            switch(type) {
                case 'task_assigned': return 'info';
                case 'task_completed': return 'success';
                case 'project_update': return 'warning';
                case 'system': return 'danger';
                case 'reminder': return 'warning';
                default: return 'info';
            }
        }

        function timeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;

            return date.toLocaleDateString();
        }

        // Time Log Functions
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
                noLogsElement.style.display = 'block';
                container.innerHTML = '';
                container.appendChild(noLogsElement);
                return;
            }

            noLogsElement.style.display = 'none';

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

        function updateTimeLogsPagination(pagination) {
            const container = document.getElementById('time-log-pagination');

            if (pagination.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            if (pagination.current_page > 1) {
                paginationHTML += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadTimeLogs(document.getElementById('time-log-filter').value, ${pagination.current_page - 1}); return false;">Previous</a>
                    </li>
                `;
            }

            // Page numbers
            for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.last_page, pagination.current_page + 2); i++) {
                paginationHTML += `
                    <li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadTimeLogs(document.getElementById('time-log-filter').value, ${i}); return false;">${i}</a>
                    </li>
                `;
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                paginationHTML += `
                    <li class="page-item">
                        <a class="page-link" href="#" onclick="loadTimeLogs(document.getElementById('time-log-filter').value, ${pagination.current_page + 1}); return false;">Next</a>
                    </li>
                `;
            }

            container.innerHTML = paginationHTML;
        }

        // Optimized active timer loading dengan caching
        let activeTimerCache = null;
        let activeTimerCacheTime = null;
        const TIMER_CACHE_DURATION = 5000; // 5 detik untuk timer (lebih sering update)

        function loadActiveTimer(useCache = true) {
            // Check cache
            if (useCache && activeTimerCache && activeTimerCacheTime &&
                (Date.now() - activeTimerCacheTime < TIMER_CACHE_DURATION)) {
                console.log('Using cached active timer');
                if (activeTimerCache.active_timer) {
                    showActiveTimer(activeTimerCache.active_timer);
                } else {
                    hideActiveTimer();
                }
                return;
            }

            fetch('/api/time-logs/active')
                .then(response => response.json())
                .then(data => {
                    // Cache response
                    activeTimerCache = data;
                    activeTimerCacheTime = Date.now();
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
            const autoBadge = document.getElementById('auto-timer-badge');
            const timerType = document.getElementById('active-timer-type');
            const timerDescription = document.getElementById('active-timer-description');

            document.getElementById('active-timer-card').textContent = timer.card_title;
            document.getElementById('active-timer-start').textContent = new Date(timer.start_time).toLocaleTimeString();

            // Check if timer is auto-started using new API field
            const isAutoTimer = timer.is_auto_timer === true;

            if (isAutoTimer) {
                autoBadge.style.display = 'inline-block';
                timerType.style.display = 'inline-block';
                timerType.textContent = timer.auto_timer_type ? timer.auto_timer_type.replace('_', ' ').toUpperCase() : 'AUTO';
                timerDescription.textContent = timer.description || 'Automatic timer based on card status';
                timerDescription.style.display = 'block';

                // Change alert class for auto timer
                display.className = 'alert alert-success';
            } else {
                autoBadge.style.display = 'none';
                timerType.style.display = 'none';
                timerDescription.style.display = 'none';

                // Regular timer styling
                display.className = 'alert alert-info';
            }

            display.style.display = 'block';
            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-block';
        }

        function hideActiveTimer() {
            const display = document.getElementById('active-timer-display');
            const startBtn = document.getElementById('start-timer-btn');
            const stopBtn = document.getElementById('stop-timer-btn');

            display.style.display = 'none';
            startBtn.style.display = 'inline-block';
            stopBtn.style.display = 'none';

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
                    document.getElementById('active-timer-duration').textContent = formatted;
                }
            }, 1000);
        }

        function showStartTimerModal() {
            loadCardsForTimer();
            const modal = new bootstrap.Modal(document.getElementById('startTimerModal'));
            modal.show();
        }

        function loadCardsForTimer() {
            fetch('/api/developer/cards')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('timer-card-select');
                        select.innerHTML = '<option value="">Choose a card to track time...</option>';

                        data.cards.forEach(card => {
                            select.innerHTML += `<option value="${card.card_id}" data-deadline="${card.due_date || ''}" data-status="${card.status}">${card.card_title}</option>`;
                        });
                    }
                })
                .catch(error => console.error('Error loading cards:', error));
        }

        function onCardSelectChange() {
            const select = document.getElementById('timer-card-select');
            const selectedOption = select.options[select.selectedIndex];
            const subtaskContainer = document.getElementById('timer-subtask-container');
            const deadlineInfo = document.getElementById('card-deadline-info');

            if (select.value) {
                // Load subtasks for selected card
                loadSubtasksForTimer(select.value);

                // Show deadline info if card has deadline
                const deadline = selectedOption.dataset.deadline;
                if (deadline) {
                    showCardDeadlineInfo(deadline, selectedOption.dataset.status);
                } else {
                    deadlineInfo.style.display = 'none';
                }
            } else {
                subtaskContainer.style.display = 'none';
                deadlineInfo.style.display = 'none';
            }
        }

        function loadSubtasksForTimer(cardId) {
            // This would load subtasks for the card - simplified for now
            const subtaskContainer = document.getElementById('timer-subtask-container');
            subtaskContainer.style.display = 'block';
        }

        function showCardDeadlineInfo(deadline, status) {
            const deadlineInfo = document.getElementById('card-deadline-info');
            const deadlineDate = new Date(deadline);
            const now = new Date();
            const diffInMs = deadlineDate - now;
            const diffInDays = Math.ceil(diffInMs / (1000 * 60 * 60 * 24));

            let alertClass = 'alert-info';
            let statusText = '';
            let countdownText = '';

            if (diffInDays < 0) {
                alertClass = 'alert-danger';
                statusText = `⏰ OVERDUE by ${Math.abs(diffInDays)} days`;
                countdownText = 'PAST DUE';
            } else if (diffInDays <= 1) {
                alertClass = 'alert-warning';
                statusText = '⚠️ Due very soon!';
                countdownText = diffInDays === 0 ? 'Due Today' : '1 day left';
            } else {
                statusText = '📅 Upcoming deadline';
                countdownText = `${diffInDays} days left`;
            }

            deadlineInfo.className = `alert ${alertClass}`;
            document.getElementById('deadline-status-text').textContent = statusText;
            document.getElementById('deadline-countdown').textContent = countdownText;
            deadlineInfo.style.display = 'block';
        }

        function startTimerFromModal() {
            const cardId = document.getElementById('timer-card-select').value;
            const subtaskId = document.getElementById('timer-subtask-select').value;
            const description = document.getElementById('timer-description').value;

            if (!cardId) {
                alert('Please select a card to track time for.');
                return;
            }

            const requestData = {
                card_id: cardId,
                description: description
            };

            if (subtaskId) {
                requestData.subtask_id = subtaskId;
            }

            fetch('/api/time-logs/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('startTimerModal'));
                    modal.hide();

                    // Clear form
                    document.getElementById('startTimerForm').reset();
                    document.getElementById('timer-subtask-container').style.display = 'none';
                    document.getElementById('card-deadline-info').style.display = 'none';

                    // Refresh timer display
                    loadActiveTimer();
                    loadTimeLogStatistics();

                    showNotification('Timer started successfully!', 'success');
                } else {
                    alert('Failed to start timer: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error starting timer:', error);
                alert('Failed to start timer. Please try again.');
            });
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
                    showNotification('Timer stopped successfully!', 'success');
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
            showNotification('Time log data refreshed!', 'info');
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
                        showNotification('Description updated successfully!', 'success');
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
                    showNotification('Time log deleted successfully!', 'success');
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
            fetch('/api/developer/cards')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cardsWithDeadlines = data.cards.filter(card => card.due_date);
                        updateDeadlineCardsList(cardsWithDeadlines);
                    }
                })
                .catch(error => console.error('Error loading deadline cards:', error));
        }

        function updateDeadlineCardsList(cards) {
            const container = document.getElementById('deadline-cards-list');
            const noCardsElement = document.getElementById('no-deadline-cards');

            if (cards.length === 0) {
                noCardsElement.style.display = 'block';
                container.innerHTML = '';
                container.appendChild(noCardsElement);
                return;
            }

            noCardsElement.style.display = 'none';

            // Sort cards by deadline
            cards.sort((a, b) => new Date(a.due_date) - new Date(b.due_date));

            container.innerHTML = cards.map(card => {
                const deadline = new Date(card.due_date);
                const now = new Date();
                const diffInMs = deadline - now;
                const diffInDays = Math.ceil(diffInMs / (1000 * 60 * 60 * 24));

                let statusClass = 'text-success';
                let statusText = 'On time';
                let badgeClass = 'bg-success';

                if (diffInDays < 0) {
                    statusClass = 'text-danger';
                    statusText = `Overdue by ${Math.abs(diffInDays)} days`;
                    badgeClass = 'bg-danger';
                } else if (diffInDays <= 1) {
                    statusClass = 'text-warning';
                    statusText = diffInDays === 0 ? 'Due today' : '1 day left';
                    badgeClass = 'bg-warning';
                } else if (diffInDays <= 3) {
                    statusClass = 'text-info';
                    statusText = `${diffInDays} days left`;
                    badgeClass = 'bg-info';
                } else {
                    statusText = `${diffInDays} days left`;
                }

                return `
                    <div class="deadline-card border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${card.card_title}</h6>
                                <div class="text-muted small">Due: ${deadline.toLocaleDateString()}</div>
                                <div class="${statusClass} small fw-bold">${statusText}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge ${badgeClass}">${card.status}</span>
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="startTimerForCard(${card.card_id}, '${card.card_title}')">
                                        <i class="bi bi-play-circle"></i> Start Timer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function startTimerForCard(cardId, cardTitle) {
            const description = prompt(`Start timer for "${cardTitle}".\n\nWhat are you working on?`, '');
            if (description !== null) {
                fetch('/api/time-logs/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        card_id: cardId,
                        description: description
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadActiveTimer();
                        loadTimeLogStatistics();
                        showNotification('Timer started successfully!', 'success');
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

        // Add event listener for card select change
        document.addEventListener('DOMContentLoaded', function() {
            const cardSelect = document.getElementById('timer-card-select');
            if (cardSelect) {
                cardSelect.addEventListener('change', onCardSelectChange);
            }
        });

        function toggleTheme() {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>
</body>
</html>
