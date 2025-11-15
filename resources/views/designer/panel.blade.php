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
                        <i class="bi bi-palette"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Dashboard</div>
                        <div class="nav-subtitle">Overview & Projects</div>
                    </div>
                </a>
            </div>

            <!-- Design Assets -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="assets">
                    <div class="nav-icon">
                        <i class="bi bi-images"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Design Assets</div>
                        <div class="nav-subtitle">My design files</div>
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
                        <div class="nav-subtitle">Active design work</div>
                    </div>
                </a>
            </div>

            <!-- Design Gallery -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="gallery">
                    <div class="nav-icon">
                        <i class="bi bi-grid-3x3"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Gallery</div>
                        <div class="nav-subtitle">Portfolio showcase</div>
                    </div>
                </a>
            </div>

            <!-- Design Tools -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="tools">
                    <div class="nav-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Design Tools</div>
                        <div class="nav-subtitle">Resources & utilities</div>
                    </div>
                </a>
            </div>

            <!-- Feedback -->
            <div class="nav-item">
                <a href="#" class="nav-link" data-section="feedback">
                    <div class="nav-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                    <div class="nav-content">
                        <div class="nav-title">Feedback</div>
                        <div class="nav-subtitle">Client reviews</div>
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
                        <div class="nav-subtitle">Portfolio & settings</div>
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
                    <h2 id="content-title" class="mb-0">Designer Dashboard</h2>
                    <p class="text-muted mb-0" id="content-subtitle">Create and manage your design projects</p>
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

            <!-- Design Assets Content -->
            <div id="assets-content" class="content-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Design Assets</h4>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="assetFilter" id="all-assets" checked>
                        <label class="btn btn-outline-primary" for="all-assets">All</label>

                        <input type="radio" class="btn-check" name="assetFilter" id="ui-assets">
                        <label class="btn btn-outline-primary" for="ui-assets">UI/UX</label>

                        <input type="radio" class="btn-check" name="assetFilter" id="brand-assets">
                        <label class="btn btn-outline-primary" for="brand-assets">Branding</label>

                        <input type="radio" class="btn-check" name="assetFilter" id="web-assets">
                        <label class="btn btn-outline-primary" for="web-assets">Web</label>
                    </div>
                </div>
                <div id="assets-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-images display-4"></i>
                        <p class="mt-3">Loading design assets...</p>
                    </div>
                </div>
            </div>

            <!-- Projects Content -->
            <div id="projects-content" class="content-section">
                <h4 class="mb-4">Design Projects</h4>
                <div id="projects-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-folder display-4"></i>
                        <p class="mt-3">Loading projects...</p>
                    </div>
                </div>
            </div>

            <!-- Gallery Content -->
            <div id="gallery-content" class="content-section">
                <h4 class="mb-4">Design Gallery</h4>
                <div class="design-gallery" id="design-gallery">
                    <div class="text-center py-5 text-muted col-12">
                        <i class="bi bi-grid-3x3 display-4"></i>
                        <p class="mt-3">Loading gallery...</p>
                    </div>
                </div>
            </div>

            <!-- Design Tools Content -->
            <div id="tools-content" class="content-section">
                <h4 class="mb-4">Design Tools & Resources</h4>

                <!-- Color Palettes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Color Palettes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6>Primary Palette</h6>
                                <div class="color-palette">
                                    <div class="color-swatch" style="background-color: #ff6b6b;" title="#ff6b6b"></div>
                                    <div class="color-swatch" style="background-color: #ff8e53;" title="#ff8e53"></div>
                                    <div class="color-swatch" style="background-color: #ff9f43;" title="#ff9f43"></div>
                                    <div class="color-swatch" style="background-color: #feca57;" title="#feca57"></div>
                                    <div class="color-swatch" style="background-color: #ff9ff3;" title="#ff9ff3"></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>Secondary Palette</h6>
                                <div class="color-palette">
                                    <div class="color-swatch" style="background-color: #54a0ff;" title="#54a0ff"></div>
                                    <div class="color-swatch" style="background-color: #5f27cd;" title="#5f27cd"></div>
                                    <div class="color-swatch" style="background-color: #00d2d3;" title="#00d2d3"></div>
                                    <div class="color-swatch" style="background-color: #ff9ff3;" title="#ff9ff3"></div>
                                    <div class="color-swatch" style="background-color: #9c88ff;" title="#9c88ff"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Design Tools Grid -->
                <div class="tools-grid">
                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="bi bi-brush"></i>
                        </div>
                        <h6 class="tool-name">Adobe Photoshop</h6>
                        <p class="tool-desc">Photo editing & manipulation</p>
                    </div>
                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="bi bi-vector-pen"></i>
                        </div>
                        <h6 class="tool-name">Adobe Illustrator</h6>
                        <p class="tool-desc">Vector graphics design</p>
                    </div>
                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h6 class="tool-name">Figma</h6>
                        <p class="tool-desc">UI/UX design & prototyping</p>
                    </div>
                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="bi bi-layout-text-window"></i>
                        </div>
                        <h6 class="tool-name">Adobe XD</h6>
                        <p class="tool-desc">User experience design</p>
                    </div>
                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="bi bi-camera"></i>
                        </div>
                        <h6 class="tool-name">Canva</h6>
                        <p class="tool-desc">Quick design templates</p>
                    </div>
                    <div class="tool-card">
                        <div class="tool-icon">
                            <i class="bi bi-palette"></i>
                        </div>
                        <h6 class="tool-name">Color Hunt</h6>
                        <p class="tool-desc">Color palette inspiration</p>
                    </div>
                </div>
            </div>

            <!-- Feedback Content -->
            <div id="feedback-content" class="content-section">
                <h4 class="mb-4">Client Feedback</h4>
                <div id="feedback-list">
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-chat-left-text display-4"></i>
                        <p class="mt-3">Loading feedback...</p>
                    </div>
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
    </script>
</body>
</html>
