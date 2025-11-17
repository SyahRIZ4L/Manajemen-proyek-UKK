<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Member Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
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

        /* Sidebar Overlay */
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

        /* Sidebar Base Styles */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Mobile Menu Toggle Button */
        #mobile-menu-toggle {
            border: none;
            background: rgba(255, 255, 255, 0.9);
            color: #495057;
            border-radius: 8px;
            padding: 10px;
            width: 44px;
            height: 44px;
            font-size: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1001;
        }

        #mobile-menu-toggle:hover,
        #mobile-menu-toggle:focus {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            outline: none;
        }

        #mobile-menu-toggle:active {
            transform: scale(0.95);
        }

        #mobile-menu-toggle i {
            transition: transform 0.3s ease;
        }

        /* Ripple animation keyframes */
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Enhanced navigation link transitions */
        .sidebar .nav-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s ease;
        }

        .sidebar .nav-link:hover {
            transform: translateX(5px);
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
                visibility: hidden;
            }

            .sidebar.show {
                transform: translateX(0);
                visibility: visible;
            }

            .col-md-9.col-lg-10 {
                margin-left: 0 !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            /* Prevent body scroll when menu is open */
            body.menu-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
            }

            /* Mobile menu specific styles */
            #mobile-menu-toggle {
                display: flex !important;
            }
        }

        @media (min-width: 769px) {
            .col-md-9.col-lg-10 {
                margin-left: 280px;
            }

            #mobile-menu-toggle {
                display: none !important;
            }
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .stats-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .stats-card.success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stats-card.warning {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .stats-card.danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .timer-display {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }

        /* Card Detail Modal Styles */
        .modal-xl {
            max-width: 90%;
        }

        /* Comments Section */
        .comment-item {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            transition: box-shadow 0.2s;
        }

        .comment-item:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .comment-content {
            line-height: 1.5;
        }

        .comment-content p {
            margin-bottom: 0;
            word-wrap: break-word;
        }

        /* Comment Form */
        #addCommentForm {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #dee2e6;
            transition: all 0.3s;
        }

        #addCommentForm:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0,123,255,0.15);
        }

        #addCommentForm textarea {
            border: 1px solid #ced4da;
            border-radius: 6px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        #addCommentForm textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }

        /* Info item styling */
        .info-item label {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* User avatar */
        .user-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3"
                 id="sidebar"
                 role="navigation"
                 aria-label="Main navigation"
                 aria-hidden="true">
                <div class="text-center mb-4">
                    <h4 class="text-white">Member Panel</h4>
                    <small class="text-light">{{ auth()->user()->full_name }}</small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}"
                       href="{{ route('member.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('member.my-cards*') ? 'active' : '' }}"
                       href="{{ route('member.my-cards') }}">
                        <i class="bi bi-kanban me-2"></i>My Cards
                    </a>
                    <a class="nav-link {{ request()->routeIs('member.time-logs*') ? 'active' : '' }}"
                       href="{{ route('member.time-logs') }}">
                        <i class="bi bi-clock-history me-2"></i>Time Logs
                    </a>
                    <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                    <a class="nav-link" href="{{ route('profile.show') }}">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </nav>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <!-- Mobile Menu Toggle -->
                        <button class="btn btn-light d-md-none me-3"
                                id="mobile-menu-toggle"
                                type="button"
                                aria-label="Toggle mobile menu"
                                aria-expanded="false"
                                aria-controls="sidebar">
                            <i class="bi bi-list"></i>
                        </button>

                        <div>
                            <h2>@yield('page-title')</h2>
                            @if(isset($breadcrumbs))
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        @foreach($breadcrumbs as $breadcrumb)
                                            @if(isset($breadcrumb['url']))
                                                <li class="breadcrumb-item">
                                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                                </li>
                                            @else
                                                <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                                            @endif
                                        @endforeach
                                    </ol>
                                </nav>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <!-- Notifications -->
                        <div class="dropdown">
                            <button class="btn btn-outline-primary position-relative" type="button"
                                    id="notificationDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                      id="notification-count" style="display: none;">
                                    0
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                <li>
                                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                        Notifications
                                        <button class="btn btn-sm btn-link text-decoration-none p-0"
                                                id="mark-all-read" style="font-size: 0.8rem;">
                                            Mark all read
                                        </button>
                                    </h6>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li id="notifications-container">
                                    <div class="text-center p-3 text-muted">
                                        <i class="bi bi-bell-slash fs-4"></i>
                                        <p class="mb-0 small">No notifications</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Active Timer Display -->
                        <div id="active-timer" class="d-none">
                            <div class="card border-warning">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-play-circle text-warning me-2"></i>
                                        <small class="me-2">Working on:</small>
                                        <strong class="me-2" id="timer-card-title"></strong>
                                        <span class="timer-display text-warning" id="timer-display">00:00:00</span>
                                        <button class="btn btn-sm btn-outline-danger ms-2" id="stop-timer-btn">
                                            <i class="bi bi-stop-circle"></i> Stop
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Active Timer Script -->
    <script>
        let timerInterval;
        let timerStartTime;
        let activeCardId;

        // Optimized polling with adaptive intervals
        let timerInterval, notificationInterval;
        let isActive = true;

        // Check for active timer and notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkActiveTimer();
            checkNotifications();

            // Adaptive polling - slower when user is inactive
            startAdaptivePolling();

            // Detect user activity
            ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, () => {
                    isActive = true;
                }, true);
            });
        });

        function startAdaptivePolling() {\n            // Timer polling - more frequent when active\n            timerInterval = setInterval(() => {\n                if (document.getElementById('active-timer') && !document.getElementById('active-timer').classList.contains('d-none')) {\n                    checkActiveTimer();\n                }\n            }, isActive ? 10000 : 30000); // 10s when active, 30s when inactive\n            \n            // Notification polling - less frequent overall\n            notificationInterval = setInterval(checkNotifications, isActive ? 45000 : 120000); // 45s when active, 2min when inactive\n            \n            // Reset activity flag periodically\n            setTimeout(() => { isActive = false; }, 30000);\n        }"

        function checkActiveTimer() {
            fetch('/member/timer/active')
                .then(response => response.json())
                .then(data => {
                    if (data.active) {
                        showActiveTimer(data);
                    } else {
                        hideActiveTimer();
                    }
                })
                .catch(error => console.error('Error checking active timer:', error));
        }

        function showActiveTimer(data) {
            document.getElementById('active-timer').classList.remove('d-none');
            document.getElementById('timer-card-title').textContent = data.card_title;

            activeCardId = data.card_id;
            timerStartTime = new Date(data.start_time);

            // Update timer display
            updateTimerDisplay();

            // Start updating every second
            if (timerInterval) clearInterval(timerInterval);
            timerInterval = setInterval(updateTimerDisplay, 1000);
        }

        function hideActiveTimer() {
            document.getElementById('active-timer').classList.add('d-none');
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }

        function updateTimerDisplay() {
            const now = new Date();
            const elapsed = Math.floor((now - timerStartTime) / 1000);

            const hours = Math.floor(elapsed / 3600);
            const minutes = Math.floor((elapsed % 3600) / 60);
            const seconds = elapsed % 60;

            const display = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('timer-display').textContent = display;
        }

        // Stop timer button
        document.getElementById('stop-timer-btn').addEventListener('click', function() {
            if (activeCardId) {
                stopTimer(activeCardId);
            }
        });

        function stopTimer(cardId) {
            fetch(`/member/card/${cardId}/timer/stop`, {
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
                    // Show success message
                    showAlert('success', 'Timer stopped successfully!');
                    // Reload page to update data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showAlert('danger', data.error || 'Failed to stop timer');
                }
            })
            .catch(error => {
                console.error('Error stopping timer:', error);
                showAlert('danger', 'An error occurred while stopping the timer');
            });
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Insert at the top of the main content
            const mainContent = document.querySelector('.col-md-9.col-lg-10 .p-4');
            mainContent.insertBefore(alertDiv, mainContent.children[1]);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Notification functions
        function checkNotifications() {
            fetch('/api/notifications/recent')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationDisplay(data.notifications, data.unread_count);
                    }
                })
                .catch(error => console.error('Error checking notifications:', error));
        }

        function updateNotificationDisplay(notifications, unreadCount) {
            const countBadge = document.getElementById('notification-count');
            const container = document.getElementById('notifications-container');

            // Update count badge
            if (unreadCount > 0) {
                countBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                countBadge.style.display = 'block';
            } else {
                countBadge.style.display = 'none';
            }

            // Update notifications list
            if (notifications.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-3 text-muted">
                        <i class="bi bi-bell-slash fs-4"></i>
                        <p class="mb-0 small">No notifications</p>
                    </div>
                `;
            } else {
                container.innerHTML = notifications.map(notification => `
                    <li>
                        <a class="dropdown-item ${!notification.is_read ? 'bg-light' : ''}"
                           href="${notification.data?.action_url || '#'}"
                           onclick="markAsRead(${notification.notification_id})">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${notification.title}</h6>
                                    <p class="mb-1 small text-muted">${notification.message}</p>
                                    <small class="text-muted">${formatTime(notification.created_at)}</small>
                                </div>
                                ${!notification.is_read ? '<div class="text-primary"><i class="bi bi-circle-fill" style="font-size: 8px;"></i></div>' : ''}
                            </div>
                        </a>
                    </li>
                `).join('');
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
                    checkNotifications(); // Refresh notifications
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;

            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
            if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
            return Math.floor(diff / 86400000) + 'd ago';
        }

        // Mark all notifications as read
        document.getElementById('mark-all-read').addEventListener('click', function() {
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
                    checkNotifications(); // Refresh notifications
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
        });

        // Mobile Menu Script - Enhanced Version
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            // Function to toggle mobile menu
            function toggleMobileMenu(e) {
                e.preventDefault();
                e.stopPropagation();

                const isOpen = sidebar.classList.contains('show');

                if (isOpen) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            }

            // Function to open mobile menu
            function openMobileMenu() {
                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.classList.add('menu-open');

                // Update toggle button icon
                const toggleIcon = mobileMenuToggle.querySelector('i');
                if (toggleIcon) {
                    toggleIcon.className = 'bi bi-x';
                }

                // Focus trap for accessibility
                sidebar.setAttribute('aria-hidden', 'false');
                mobileMenuToggle.setAttribute('aria-expanded', 'true');
            }

            // Function to close mobile menu
            function closeMobileMenu() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.classList.remove('menu-open');

                // Update toggle button icon
                const toggleIcon = mobileMenuToggle.querySelector('i');
                if (toggleIcon) {
                    toggleIcon.className = 'bi bi-list';
                }

                // Reset accessibility attributes
                sidebar.setAttribute('aria-hidden', 'true');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            }

            // Event listeners
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleMobileMenu);
                mobileMenuToggle.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                }, { passive: false });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeMobileMenu();
                });

                // Handle touch events for mobile
                sidebarOverlay.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    closeMobileMenu();
                }, { passive: false });
            }

            // Close menu when clicking on nav links (mobile only)
            if (sidebar) {
                const navLinks = sidebar.querySelectorAll('.nav-link');
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        // Only close on mobile screens
                        if (window.innerWidth <= 768) {
                            setTimeout(closeMobileMenu, 150);
                        }
                    });
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileMenu();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
                    closeMobileMenu();
                }
            });

            // Prevent body scroll when menu is open
            document.addEventListener('touchmove', function(e) {
                if (sidebar && sidebar.classList.contains('show') && !sidebar.contains(e.target)) {
                    e.preventDefault();
                }
            }, { passive: false });

            // Initialize proper state on load
            if (window.innerWidth <= 768) {
                closeMobileMenu();
            }

            // Add smooth transition for navigation links
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });

                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Add ripple effect to menu toggle button (optional enhancement)
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText =
                        'position: absolute; ' +
                        'border-radius: 50%; ' +
                        'background: rgba(102, 126, 234, 0.3); ' +
                        'transform: scale(0); ' +
                        'animation: ripple 0.6s linear; ' +
                        'left: ' + x + 'px; ' +
                        'top: ' + y + 'px; ' +
                        'width: ' + size + 'px; ' +
                        'height: ' + size + 'px;';

                    this.appendChild(ripple);

                    setTimeout(function() {
                        ripple.remove();
                    }, 600);
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
