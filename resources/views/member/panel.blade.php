<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Member Panel - Project Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-color: #e0e0e0;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Mobile Menu Toggle Button */
        #mobile-menu-toggle {
            border: none;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        #mobile-menu-toggle:hover,
        #mobile-menu-toggle:focus {
            background: rgba(102, 126, 234, 0.2);
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            outline: none;
        }

        #mobile-menu-toggle:active {
            transform: scale(0.98);
        }

        /* Overlay for mobile menu */
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
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .sidebar {
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
            padding: 30px 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: rgba(255, 255, 255, 0.5);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }

        .nav-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }

        .nav-content {
            flex: 1;
        }

        .nav-title {
            font-weight: 600;
            font-size: 15px;
            display: block;
        }

        .nav-subtitle {
            font-size: 12px;
            opacity: 0.7;
            display: block;
            margin-top: 2px;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }

        .content-header {
            background: white;
            padding: 25px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px var(--shadow-color);
            margin-bottom: 30px;
            border-left: 5px solid var(--primary-color);
        }

        .content-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px var(--shadow-color);
            margin-bottom: 30px;
        }

        .section-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-header h3 {
            margin-bottom: 5px;
            color: var(--primary-color);
            font-weight: 600;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .priority-badge {
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
        }

        .card-actions {
            display: flex;
            gap: 8px;
        }

        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-content {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            border-radius: 15px 15px 0 0;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .project-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                z-index: 1001;
                transition: transform 0.3s ease;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            }

            .sidebar.show {
                transform: translateX(280px);
            }

            .main-content {
                margin-left: 0;
                padding: 10px 15px;
                width: 100%;
                transition: transform 0.3s ease;
            }

            .content-header {
                padding: 15px 20px;
                flex-wrap: wrap;
            }

            .content-header h2 {
                font-size: 1.5rem;
            }

            .content-header .d-flex {
                align-items: center;
                gap: 10px;
            }

            .content-section {
                padding: 20px;
                margin-bottom: 20px;
            }

            .section-header {
                margin-bottom: 20px;
            }

            .section-header h3 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-person-workspace mb-2" style="font-size: 2.5rem;"></i>
            <h4>Member Panel</h4>
            <p>{{ auth()->user()->full_name ?? auth()->user()->username }}</p>
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
                            <small class="nav-subtitle">Overview & Stats</small>
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
                            <small class="nav-subtitle">Assigned Tasks</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#projects" onclick="showContent('projects', this)">
                        <div class="nav-icon">
                            <i class="bi bi-kanban-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">My Projects</span>
                            <small class="nav-subtitle">Project Overview</small>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#profile" onclick="showContent('profile', this)">
                        <div class="nav-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="nav-content">
                            <span class="nav-title">Profile</span>
                            <small class="nav-subtitle">Account Settings</small>
                        </div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Logout -->
        <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-left me-2"></i>Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Mobile Menu Toggle -->
                

                <div>
                    <h2 id="content-title" class="mb-0">Member Dashboard</h2>
                    <p class="text-muted mb-0" id="content-subtitle">Welcome to your member workspace</p>
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
                <div class="section-header">
                    <h3><i class="bi bi-grid-fill me-2"></i>Dashboard Overview</h3>
                    <p class="mb-0">Monitor your tasks and progress</p>
                </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-card-checklist text-primary" style="font-size: 2rem;"></i>
                            <h4 class="mt-2" id="totalCards">0</h4>
                            <p class="text-muted mb-0">Total Cards</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-play-circle text-warning" style="font-size: 2rem;"></i>
                            <h4 class="mt-2" id="inProgressCards">0</h4>
                            <p class="text-muted mb-0">In Progress</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                            <h4 class="mt-2" id="completedCards">0</h4>
                            <p class="text-muted mb-0">Completed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                            <h4 class="mt-2" id="overdueCards">0</h4>
                            <p class="text-muted mb-0">Overdue</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Activities</h5>
                        </div>
                        <div class="card-body" id="recentActivities">
                            <div class="text-center py-4">
                                <div class="loading-spinner"></div>
                                <p class="text-muted mt-3">Loading activities...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" onclick="showContent('my-cards', document.querySelector('[href=\"#my-cards\"]'))">
                                    <i class="bi bi-card-checklist me-2"></i>View My Cards
                                </button>
                                <button class="btn btn-outline-primary" onclick="showContent('projects', document.querySelector('[href=\"#projects\"]'))">
                                    <i class="bi bi-kanban-fill me-2"></i>View Projects
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Cards Content -->
                <div id="my-cards-content" class="content-section" style="display: none;">
            <div class="section-header">
                <h3><i class="bi bi-card-checklist me-2"></i>My Cards</h3>
                <p class="mb-0">View and manage your assigned tasks</p>
            </div>

            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="cardSearchInput" placeholder="Search cards...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">In Review</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="priorityFilter">
                            <option value="">All Priority</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="cardsContainer">
                <div class="text-center py-5">
                    <div class="loading-spinner"></div>
                    <p class="text-muted mt-3">Loading cards...</p>
                </div>
            </div>
        </div>

        <!-- Projects Content -->
                <div id="projects-content" class="content-section" style="display: none;">
            <div class="section-header">
                <h3><i class="bi bi-kanban-fill me-2"></i>My Projects</h3>
                <p class="mb-0">Projects you're involved in</p>
            </div>

            <div id="projectsContainer">
                <div class="text-center py-5">
                    <div class="loading-spinner"></div>
                    <p class="text-muted mt-3">Loading projects...</p>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div id="profile-content" class="content-section" style="display: none;">
            <div class="section-header">
                <h3><i class="bi bi-person-fill me-2"></i>Profile Settings</h3>
                <p class="mb-0">Manage your account information</p>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <form id="profileForm">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" value="{{ auth()->user()->full_name }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="{{ auth()->user()->username }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->role }}" readonly>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="updateProfile()">
                                    <i class="bi bi-check2 me-2"></i>Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Member Since</small>
                                <div class="fw-bold">{{ auth()->user()->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Last Login</small>
                                <div class="fw-bold" id="lastLogin">-</div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Total Cards Completed</small>
                                <div class="fw-bold" id="totalCompleted">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Detail Modal -->
    <div class="modal fade" id="cardDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Card Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="cardDetailContent">
                    <div class="text-center py-4">
                        <div class="loading-spinner"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit For Review Modal -->
    <div class="modal fade" id="submitReviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit for Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to submit this card for review?</p>
                    <div class="mb-3">
                        <label class="form-label">Add a note (optional):</label>
                        <textarea class="form-control" id="submitNote" rows="3" placeholder="Add any notes for the reviewer..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmSubmitBtn">Submit for Review</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentCards = [];
        let cardDetailModal, submitReviewModal;
        let currentSubmitCardId = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modals with error handling
            try {
                submitReviewModal = new bootstrap.Modal(document.getElementById('submitReviewModal'));
                cardDetailModal = new bootstrap.Modal(document.getElementById('cardDetailModal'));
            } catch (modalError) {
                console.error('Error initializing modals:', modalError);
                showNotification('error', 'Error', 'Modal initialization failed. Please refresh the page.');
            }

            // Load initial content
            try {
                loadDashboard();
            } catch (loadError) {
                console.error('Error loading dashboard:', loadError);
                showNotification('error', 'Error', 'Failed to load dashboard. Please refresh the page.');
            }

            // Setup search and filter listeners
            setupFilters();
        });

        function showContent(contentType, element) {
            // Hide all content sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });

            // Show selected content
            document.getElementById(contentType + '-content').style.display = 'block';

            // Update navigation active state
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            element.classList.add('active');

            // Load content based on type
            switch(contentType) {
                case 'dashboard':
                    loadDashboard();
                    break;
                case 'my-cards':
                    loadMyCards();
                    break;
                case 'projects':
                    loadProjects();
                    break;
                case 'profile':
                    loadProfile();
                    break;
            }
        }

        function loadDashboard() {
            fetch('/api/member/dashboard')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateDashboardStats(data.stats);
                        updateRecentActivities(data.activities);
                    }
                })
                .catch(error => {
                    console.error('Error loading dashboard:', error);
                });
        }

        function updateDashboardStats(stats) {
            document.getElementById('totalCards').textContent = stats.total || 0;
            document.getElementById('inProgressCards').textContent = stats.in_progress || 0;
            document.getElementById('completedCards').textContent = stats.completed || 0;
            document.getElementById('overdueCards').textContent = stats.overdue || 0;
        }

        function updateRecentActivities(activities) {
            const container = document.getElementById('recentActivities');

            if (!activities || activities.length === 0) {
                container.innerHTML = '<p class="text-muted">No recent activities</p>';
                return;
            }

            container.innerHTML = activities.map(activity => `
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <div class="me-3">
                        <i class="bi bi-${getActivityIcon(activity.type)} text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium">${activity.title}</div>
                        <small class="text-muted">${formatDate(activity.created_at)}</small>
                    </div>
                </div>
            `).join('');
        }

        function loadMyCards() {
            const container = document.getElementById('cardsContainer');
            container.innerHTML = '<div class="text-center py-5"><div class="loading-spinner"></div><p class="text-muted mt-3">Loading cards...</p></div>';

            fetch('/api/member/my-cards')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentCards = data.cards;
                        displayCards(currentCards);
                    } else {
                        container.innerHTML = '<div class="alert alert-danger">Error loading cards: ' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading cards:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error loading cards. Please try again.</div>';
                });
        }

        function displayCards(cards) {
            const container = document.getElementById('cardsContainer');

            if (!cards || cards.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-card-checklist text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No Cards Found</h5>
                        <p class="text-muted">You don't have any assigned cards yet.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="row">
                    ${cards.map(card => createCardHTML(card)).join('')}
                </div>
            `;
        }

        function createCardHTML(card) {
            const statusColors = {
                'todo': 'secondary',
                'in_progress': 'primary',
                'review': 'warning',
                'done': 'success'
            };

            const priorityColors = {
                'high': 'danger',
                'medium': 'warning',
                'low': 'success'
            };

            const isOverdue = card.due_date && new Date(card.due_date) < new Date();

            return `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-1">
                                    ${card.card_title}
                                    ${card.estimated_hours ? `<span class="text-muted">(${card.estimated_hours} jam)</span>` : ''}
                                    ${card.priority ? `<span class="text-${priorityColors[card.priority] || 'secondary'}"> - priority: ${card.priority}</span>` : ''}
                                </h6>
                                ${isOverdue ? '<i class="bi bi-exclamation-triangle text-danger" title="Overdue"></i>' : ''}
                            </div>

                            <p class="card-text text-muted small mb-3">
                                ${card.description ? card.description.substring(0, 100) + (card.description.length > 100 ? '...' : '') : 'No description'}
                            </p>

                            <div class="mb-3">
                                <span class="status-badge bg-${statusColors[card.status] || 'secondary'} text-white">
                                    ${formatStatus(card.status)}
                                </span>
                                ${card.priority ? `<span class="priority-badge bg-${priorityColors[card.priority] || 'secondary'} text-white ms-2">${card.priority.toUpperCase()}</span>` : ''}
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-diagram-3 me-1"></i>Board: ${card.board_name || 'Unknown'}
                                </small>
                                ${card.project_name ? `<br><small class="project-badge">${card.project_name}</small>` : ''}
                                ${card.due_date ? `<br><small class="text-muted"><i class="bi bi-calendar me-1"></i>Due: ${formatDate(card.due_date)}</small>` : ''}
                            </div>

                            <div class="card-actions">
                                <button class="btn btn-outline-primary btn-sm" onclick="showCardDetail(${card.card_id})">
                                    <i class="bi bi-eye"></i> View
                                </button>

                                ${getCardActionButton(card)}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function getCardActionButton(card) {
            switch(card.status) {
                case 'todo':
                    return `<button class="btn btn-success btn-sm" onclick="startWork(${card.card_id})">
                                <i class="bi bi-play"></i> Start
                            </button>`;
                case 'in_progress':
                    return `<button class="btn btn-warning btn-sm" onclick="submitForReview(${card.card_id})">
                                <i class="bi bi-check-circle"></i> Submit
                            </button>`;
                case 'review':
                    return `<span class="badge bg-warning text-dark">Under Review</span>`;
                case 'done':
                    return `<span class="badge bg-success">Completed</span>`;
                default:
                    return '';
            }
        }

        function startWork(cardId) {
            if (confirm('Are you sure you want to start working on this card?')) {
                const btn = event.target.closest('button');
                const cardElement = btn.closest('.card');
                const originalHTML = btn.innerHTML;

                // Show loading state
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Starting...';
                btn.disabled = true;

                // Optimistic UI update
                const statusBadge = cardElement.querySelector('.status-badge');
                const originalStatusClass = statusBadge.className;
                const originalStatusText = statusBadge.textContent;

                statusBadge.className = 'status-badge bg-primary text-white';
                statusBadge.textContent = 'In Progress';

                fetch(`/api/cards/${cardId}/start`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');

                    if (!response.ok) {
                        let errorMsg = `HTTP ${response.status}: ${response.statusText}`;
                        if (contentType && contentType.includes('application/json')) {
                            try {
                                const errorData = await response.json();
                                errorMsg = errorData.message || errorMsg;
                            } catch (e) {}
                        }
                        throw new Error(errorMsg);
                    }

                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Invalid response format. Please try again.');
                    }

                    return response.json();
                })
                .then(data => {
                    if (!data) {
                        throw new Error('No response data received');
                    }

                    if (data.success === true) {
                        showNotification('success', 'Success', data.message || 'Card started successfully');

                        // Update button to submit mode
                        btn.innerHTML = '<i class="bi bi-check-circle"></i> Submit';
                        btn.className = 'btn btn-warning btn-sm';
                        btn.disabled = false;
                        btn.setAttribute('onclick', `submitForReview(${cardId})`);

                        // Update card in currentCards array if exists
                        if (typeof currentCards !== 'undefined' && Array.isArray(currentCards)) {
                            const cardIndex = currentCards.findIndex(c => c.card_id == cardId);
                            if (cardIndex !== -1) {
                                currentCards[cardIndex].status = 'in_progress';
                            }
                        }
                    } else {
                        const errorMessage = data.message || data.error || 'Failed to start card';
                        throw new Error(errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Error starting card:', error);

                    // Revert UI changes on error
                    try {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        if (statusBadge) {
                            statusBadge.className = originalStatusClass;
                            statusBadge.textContent = originalStatusText;
                        }
                    } catch (revertError) {
                        console.error('Error reverting UI:', revertError);
                    }

                    // Show user-friendly error message
                    let errorMsg = 'An error occurred while starting the card';
                    if (error && error.message) {
                        if (error.message.includes('HTTP')) {
                            errorMsg = 'Server connection error. Please try again.';
                        } else if (error.message.includes('Invalid response')) {
                            errorMsg = 'Server response error. Please refresh and try again.';
                        } else {
                            errorMsg = error.message;
                        }
                    }

                    showNotification('error', 'Error', errorMsg);
                });
            }
        }

        function submitForReview(cardId) {
            if (!cardId || isNaN(cardId)) {
                showNotification('error', 'Error', 'Invalid card ID');
                return;
            }

            if (!submitReviewModal) {
                showNotification('error', 'Error', 'Submit modal not found');
                return;
            }

            currentSubmitCardId = cardId;
            const submitNote = document.getElementById('submitNote');
            if (submitNote) {
                submitNote.value = '';
            }

            try {
                submitReviewModal.show();
            } catch (modalError) {
                console.error('Error showing modal:', modalError);
                showNotification('error', 'Error', 'Cannot open submit dialog');
            }
        }

        // Handle submit confirmation
        document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
            if (!currentSubmitCardId) {
                showNotification('error', 'Error', 'No card selected for submission');
                return;
            }

            const submitNoteElement = document.getElementById('submitNote');
            const note = submitNoteElement ? submitNoteElement.value.trim() : '';
            const btn = this;

            if (!btn) {
                showNotification('error', 'Error', 'Submit button not found');
                return;
            }

            const originalHTML = btn.innerHTML;

            try {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Submitting...';
                btn.disabled = true;
            } catch (btnError) {
                console.error('Error updating button:', btnError);
                showNotification('error', 'Error', 'Button update failed');
                return;
            }

            // Find and update UI optimistically
            const cardElements = document.querySelectorAll('.card');
            let targetCardElement = null;
            let originalStatusClass = '';
            let originalStatusText = '';
            let originalButton = null;

            cardElements.forEach(cardEl => {
                const actionButton = cardEl.querySelector(`[onclick*="submitForReview(${currentSubmitCardId})"]`);
                if (actionButton) {
                    targetCardElement = cardEl;
                    const statusBadge = cardEl.querySelector('.status-badge');
                    originalStatusClass = statusBadge.className;
                    originalStatusText = statusBadge.textContent;
                    originalButton = actionButton.outerHTML;

                    // Optimistic UI update
                    statusBadge.className = 'status-badge bg-warning text-white';
                    statusBadge.textContent = 'In Review';
                    actionButton.outerHTML = '<span class="badge bg-warning text-dark">Under Review</span>';
                }
            });

            fetch(`/api/cards/${currentSubmitCardId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    comment: note
                })
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');

                if (!response.ok) {
                    let errorMsg = `HTTP ${response.status}: ${response.statusText}`;
                    if (contentType && contentType.includes('application/json')) {
                        try {
                            const errorData = await response.json();
                            errorMsg = errorData.message || errorMsg;
                        } catch (e) {}
                    }
                    throw new Error(errorMsg);
                }

                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Invalid response format. Please try again.');
                }

                return response.json();
            })
            .then(data => {
                if (!data) {
                    throw new Error('No response data received');
                }

                if (data.success === true) {
                    showNotification('success', 'Success', data.message || 'Card submitted for review successfully');
                    submitReviewModal.hide();

                    // Update card in currentCards array if exists
                    if (typeof currentCards !== 'undefined' && Array.isArray(currentCards)) {
                        const cardIndex = currentCards.findIndex(c => c.card_id == currentSubmitCardId);
                        if (cardIndex !== -1) {
                            currentCards[cardIndex].status = 'review';
                        }
                    }
                } else {
                    const errorMessage = data.message || data.error || 'Failed to submit card';
                    throw new Error(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error submitting card:', error);

                // Revert UI changes on error
                try {
                    if (targetCardElement && originalButton) {
                        const statusBadge = targetCardElement.querySelector('.status-badge');
                        const badgeContainer = targetCardElement.querySelector('.badge.bg-warning.text-dark');

                        if (statusBadge) {
                            statusBadge.className = originalStatusClass;
                            statusBadge.textContent = originalStatusText;
                        }

                        if (badgeContainer) {
                            badgeContainer.outerHTML = originalButton;
                        }
                    }
                } catch (revertError) {
                    console.error('Error reverting UI:', revertError);
                }

                // Show user-friendly error message
                let errorMsg = 'An error occurred while submitting the card';
                if (error && error.message) {
                    if (error.message.includes('HTTP')) {
                        errorMsg = 'Server connection error. Please try again.';
                    } else if (error.message.includes('Invalid response')) {
                        errorMsg = 'Server response error. Please refresh and try again.';
                    } else {
                        errorMsg = error.message;
                    }
                }

                showNotification('error', 'Error', errorMsg);
            })
            .finally(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                currentSubmitCardId = null;
            });
        });

        function showCardDetail(cardId) {
            document.getElementById('cardDetailContent').innerHTML = '<div class="text-center py-4"><div class="loading-spinner"></div></div>';
            cardDetailModal.show();

            fetch(`/api/cards/${cardId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCardDetail(data.card);
                    } else {
                        document.getElementById('cardDetailContent').innerHTML = '<div class="alert alert-danger">Error loading card details</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading card detail:', error);
                    document.getElementById('cardDetailContent').innerHTML = '<div class="alert alert-danger">Error loading card details</div>';
                });
        }

        function displayCardDetail(card) {
            const statusColors = {
                'todo': 'secondary',
                'in_progress': 'primary',
                'review': 'warning',
                'done': 'success'
            };

            const priorityColors = {
                'high': 'danger',
                'medium': 'warning',
                'low': 'success'
            };

            document.getElementById('cardDetailContent').innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <h5>${card.card_title}</h5>
                        ${card.estimated_hours ? `<p class="text-muted">${card.estimated_hours} hours estimated</p>` : ''}

                        <div class="mb-3">
                            <span class="badge bg-${statusColors[card.status] || 'secondary'} me-2">
                                ${formatStatus(card.status)}
                            </span>
                            ${card.priority ? `<span class="badge bg-${priorityColors[card.priority] || 'secondary'}">${card.priority.toUpperCase()}</span>` : ''}
                        </div>

                        <div class="mb-4">
                            <h6>Description</h6>
                            <p class="text-muted">${card.description || 'No description provided'}</p>
                        </div>

                        <div class="mb-4">
                            <h6>Details</h6>
                            <ul class="list-unstyled">
                                <li><strong>Board:</strong> ${card.board_name || 'Unknown'}</li>
                                ${card.project_name ? `<li><strong>Project:</strong> ${card.project_name}</li>` : ''}
                                ${card.due_date ? `<li><strong>Due Date:</strong> ${formatDate(card.due_date)}</li>` : ''}
                                ${card.created_by_name ? `<li><strong>Created by:</strong> ${card.created_by_name}</li>` : ''}
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    ${getCardDetailActionButtons(card)}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function getCardDetailActionButtons(card) {
            let buttons = '';

            switch(card.status) {
                case 'todo':
                    buttons += `<button class="btn btn-success" onclick="startWork(${card.card_id})">
                                    <i class="bi bi-play"></i> Start Work
                                </button>`;
                    break;
                case 'in_progress':
                    buttons += `<button class="btn btn-warning" onclick="submitForReview(${card.card_id})">
                                    <i class="bi bi-check-circle"></i> Submit for Review
                                </button>`;
                    break;
                case 'review':
                    buttons += `<div class="alert alert-warning mb-0">
                                    <i class="bi bi-clock"></i> Under Review
                                </div>`;
                    break;
                case 'done':
                    buttons += `<div class="alert alert-success mb-0">
                                    <i class="bi bi-check-circle"></i> Completed
                                </div>`;
                    break;
            }

            return buttons;
        }

        function setupFilters() {
            const searchInput = document.getElementById('cardSearchInput');
            const statusFilter = document.getElementById('statusFilter');
            const priorityFilter = document.getElementById('priorityFilter');

            [searchInput, statusFilter, priorityFilter].forEach(element => {
                element.addEventListener('change', filterCards);
                element.addEventListener('keyup', filterCards);
            });
        }

        function filterCards() {
            const searchTerm = document.getElementById('cardSearchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;

            let filteredCards = currentCards.filter(card => {
                const matchesSearch = !searchTerm ||
                    card.card_title.toLowerCase().includes(searchTerm) ||
                    (card.description && card.description.toLowerCase().includes(searchTerm));

                const matchesStatus = !statusFilter || card.status === statusFilter;
                const matchesPriority = !priorityFilter || card.priority === priorityFilter;

                return matchesSearch && matchesStatus && matchesPriority;
            });

            displayCards(filteredCards);
        }

        function loadProjects() {
            const container = document.getElementById('projectsContainer');
            container.innerHTML = '<div class="text-center py-5"><div class="loading-spinner"></div><p class="text-muted mt-3">Loading projects...</p></div>';

            fetch('/api/member/projects')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayProjects(data.projects);
                    } else {
                        container.innerHTML = '<div class="alert alert-danger">Error loading projects</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading projects:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error loading projects</div>';
                });
        }

        function displayProjects(projects) {
            const container = document.getElementById('projectsContainer');

            if (!projects || projects.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-kanban text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No Projects</h5>
                        <p class="text-muted">You are not assigned to any projects yet.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="row">
                    ${projects.map(project => `
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">${project.project_name}</h6>
                                    <p class="card-text text-muted">${project.description || 'No description'}</p>
                                    <div class="mb-3">
                                        <small class="text-muted">Role: ${project.role}</small>
                                    </div>
                                    <div class="progress mb-3">
                                        <div class="progress-bar" style="width: ${project.progress || 0}%"></div>
                                    </div>
                                    <small class="text-muted">${project.progress || 0}% Complete</small>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function loadProfile() {
            // Profile is loaded from server-side data, no additional loading needed
        }

        // Utility functions
        function formatStatus(status) {
            const statusMap = {
                'todo': 'To Do',
                'in_progress': 'In Progress',
                'review': 'In Review',
                'done': 'Done'
            };
            return statusMap[status] || status;
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function getActivityIcon(type) {
            const iconMap = {
                'card_created': 'plus-circle',
                'card_started': 'play-circle',
                'card_completed': 'check-circle',
                'card_submitted': 'upload',
                'default': 'activity'
            };
            return iconMap[type] || iconMap.default;
        }

        function showNotification(type, title, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';

            const notification = document.createElement('div');
            notification.className = `alert ${alertClass} notification`;
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${icon} me-2"></i>
                    <div>
                        <div class="fw-bold">${title}</div>
                        <div>${message}</div>
                    </div>
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Mobile Menu Script
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            // Function to toggle mobile menu
            function toggleMobileMenu() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }

            // Function to close mobile menu
            function closeMobileMenu() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            // Event listeners
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleMobileMenu);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeMobileMenu);
            }

            // Close menu when clicking on nav links (mobile)
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Only close on mobile
                    if (window.innerWidth <= 768) {
                        setTimeout(closeMobileMenu, 100);
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileMenu();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    closeMobileMenu();
                }
            });
        });
    </script>
</body>
</html>
