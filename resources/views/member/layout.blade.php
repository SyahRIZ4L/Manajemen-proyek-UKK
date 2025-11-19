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
            transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: inline-flex;
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
                height: 100%;
            }

            /* Mobile menu specific styles */
            #mobile-menu-toggle {
                display: flex !important;
                min-width: 44px;
                min-height: 44px;
                /* Enhanced touch properties */
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
                /* Prevent text selection and callouts */
                -webkit-user-drag: none;
                -khtml-user-drag: none;
                -moz-user-drag: none;
                -o-user-drag: none;
                user-drag: none;
            }

            /* Touch-friendly immediate feedback */
            #mobile-menu-toggle:active {
                transform: scale(0.95);
                background: rgba(255, 255, 255, 1);
                transition: all 0.1s ease;
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
                        <i class="bi bi-kanban me-2"></i>My Tasks
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
                        <button class="d-md-none me-3"
                                id="mobile-menu-toggle"
                                type="button"
                                aria-label="Toggle mobile menu"
                                aria-expanded="false"
                                aria-controls="sidebar"
                                style="border: none; background: rgba(255, 255, 255, 0.9); color: #495057; border-radius: 8px; padding: 10px; width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">
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

    <!-- Notifications Script -->
    <script>
        let notificationInterval;
        let isActive = true;

        // Main initialization function
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing all components...');

            // Initialize notifications with error handling
            try {
                checkNotifications();
                startAdaptivePolling();
            } catch (error) {
                console.error('Error initializing notifications:', error);
            }

            // Attach event listeners for notifications
            try {
                attachMarkAllReadListener();
            } catch (error) {
                console.error('Error attaching event listeners:', error);
            }

            // Initialize mobile menu with delay to ensure DOM is fully ready
            setTimeout(function() {
                try {
                    console.log('Initializing mobile menu...');
                    initializeMobileMenu();
                } catch (error) {
                    console.error('Error initializing mobile menu:', error);
                }
            }, 100);

            // Detect user activity
            ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, () => {
                    isActive = true;
                }, true);
            });

            console.log('All components initialized');
        });

        function startAdaptivePolling() {
            // Notification polling - less frequent overall
            notificationInterval = setInterval(checkNotifications, isActive ? 45000 : 120000); // 45s when active, 2min when inactive

            // Reset activity flag periodically
            setTimeout(() => { isActive = false; }, 30000);
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
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        updateNotificationDisplay(data.notifications || [], data.unread_count || 0);
                    } else {
                        // Handle case where API returns success: false or malformed data
                        updateNotificationDisplay([], 0);
                    }
                })
                .catch(error => {
                    console.error('Error checking notifications:', error);
                    // Show default empty state on error
                    updateNotificationDisplay([], 0);
                });
        }

        function updateNotificationDisplay(notifications, unreadCount) {
            const countBadge = document.getElementById('notification-count');
            const container = document.getElementById('notifications-container');

            // Check if DOM elements exist
            if (!countBadge || !container) {
                console.warn('Notification DOM elements not found');
                return;
            }

            // Validate parameters
            if (!Array.isArray(notifications)) {
                console.warn('Invalid notifications data, using empty array');
                notifications = [];
            }

            if (typeof unreadCount !== 'number') {
                unreadCount = 0;
            }

            // Update count badge
            if (unreadCount > 0) {
                countBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                countBadge.style.display = 'block';
            } else {
                countBadge.style.display = 'none';
            }

            // Update notifications list
            if (!notifications || notifications.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-3 text-muted">
                        <i class="bi bi-bell-slash fs-4"></i>
                        <p class="mb-0 small">No notifications</p>
                    </div>
                `;
            } else {
                container.innerHTML = notifications.map(notification => {
                    // Validate notification object
                    if (!notification) return '';

                    return `
                        <li>
                            <a class="dropdown-item ${!notification.is_read ? 'bg-light' : ''}"
                               href="${notification.data?.action_url || '#'}"
                               onclick="markAsRead(${notification.notification_id || 0})">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">${notification.title || 'No title'}</h6>
                                        <p class="mb-1 small text-muted">${notification.message || 'No message'}</p>
                                        <small class="text-muted">${formatTime(notification.created_at)}</small>
                                    </div>
                                    ${!notification.is_read ? '<div class="text-primary"><i class="bi bi-circle-fill" style="font-size: 8px;"></i></div>' : ''}
                                </div>
                            </a>
                        </li>
                    `;
                }).filter(html => html !== '').join('');
            }
        }

        function markAsRead(notificationId) {
            // Validate notification ID
            if (!notificationId || notificationId === 0) {
                console.warn('Invalid notification ID');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }

            fetch(`/api/notifications/${notificationId}/read`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    checkNotifications(); // Refresh notifications
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        function formatTime(timestamp) {
            try {
                if (!timestamp) return 'Unknown time';

                const date = new Date(timestamp);

                // Check if date is valid
                if (isNaN(date.getTime())) {
                    return 'Invalid date';
                }

                const now = new Date();
                const diff = now - date;

                if (diff < 0) return 'Future date';
                if (diff < 60000) return 'Just now';
                if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
                if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
                if (diff < 2592000000) return Math.floor(diff / 86400000) + 'd ago'; // 30 days

                // For older dates, show actual date
                return date.toLocaleDateString();
            } catch (error) {
                console.error('Error formatting time:', error);
                return 'Unknown time';
            }
        }

        // Mark all notifications as read - moved to DOM ready
        function attachMarkAllReadListener() {
            const markAllReadBtn = document.getElementById('mark-all-read');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }

            fetch('/api/notifications/read-all', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    checkNotifications(); // Refresh notifications
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
                });
            } else {
                console.warn('Mark all read button not found');
            }
        }

        // Initialize Mobile Menu
        function initializeMobileMenu() {
            console.log('=== MOBILE MENU INITIALIZATION START ===');

            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            console.log('Finding elements:', {
                toggle: !!mobileMenuToggle,
                sidebar: !!sidebar,
                overlay: !!sidebarOverlay,
                toggleDisplay: mobileMenuToggle ? window.getComputedStyle(mobileMenuToggle).display : 'not found',
                screenWidth: window.innerWidth
            });

            // Check if all required elements exist
            if (!mobileMenuToggle || !sidebar || !sidebarOverlay) {
                console.error('Mobile menu elements not found:', {
                    mobileMenuToggle: !!mobileMenuToggle,
                    sidebar: !!sidebar,
                    sidebarOverlay: !!sidebarOverlay
                });
                return;
            }

            // Function to toggle mobile menu
            function toggleMobileMenu(e) {
                console.log('=== TOGGLE MOBILE MENU FUNCTION CALLED ===');
                console.log('Event received:', e);

                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                const isOpen = sidebar.classList.contains('show');
                console.log('Current menu state:', isOpen ? 'OPEN' : 'CLOSED');
                console.log('Sidebar classes:', sidebar.className);

                if (isOpen) {
                    console.log('Calling closeMobileMenu...');
                    closeMobileMenu();
                } else {
                    console.log('Calling openMobileMenu...');
                    openMobileMenu();
                }
            }

            // Function to open mobile menu
            function openMobileMenu() {
                console.log('=== OPENING MOBILE MENU ===');

                // Force a reflow to ensure transitions work
                sidebar.style.visibility = 'visible';
                void sidebar.offsetHeight; // Force reflow

                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.classList.add('menu-open');

                console.log('Classes after opening:', {
                    sidebar: sidebar.className,
                    overlay: sidebarOverlay.className,
                    body: document.body.className
                });

                // Update toggle button icon with small delay
                setTimeout(function() {
                    const currentToggle = document.getElementById('mobile-menu-toggle');
                    const toggleIcon = currentToggle ? currentToggle.querySelector('i') : null;
                    if (toggleIcon) {
                        toggleIcon.className = 'bi bi-x';
                        console.log('Toggle icon changed to X');
                    } else {
                        console.warn('Toggle icon not found');
                    }
                }, 50);

                // Focus trap for accessibility
                sidebar.setAttribute('aria-hidden', 'false');
                const currentToggle = document.getElementById('mobile-menu-toggle');
                if (currentToggle) {
                    currentToggle.setAttribute('aria-expanded', 'true');
                }

                // Ensure menu is fully visible
                setTimeout(function() {
                    console.log('Final sidebar visibility check:', {
                        display: window.getComputedStyle(sidebar).display,
                        visibility: window.getComputedStyle(sidebar).visibility,
                        transform: window.getComputedStyle(sidebar).transform,
                        opacity: window.getComputedStyle(sidebar).opacity
                    });
                }, 100);

                console.log('Mobile menu opened successfully');
            }

            // Function to close mobile menu
            function closeMobileMenu() {
                console.log('=== CLOSING MOBILE MENU ===');

                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.classList.remove('menu-open');

                console.log('Classes after closing:', {
                    sidebar: sidebar.className,
                    overlay: sidebarOverlay.className,
                    body: document.body.className
                });

                // Update toggle button icon
                const currentToggle = document.getElementById('mobile-menu-toggle');
                const toggleIcon = currentToggle ? currentToggle.querySelector('i') : null;
                if (toggleIcon) {
                    toggleIcon.className = 'bi bi-list';
                    console.log('Toggle icon changed to hamburger');
                } else {
                    console.warn('Toggle icon not found');
                }

                // Reset accessibility attributes
                sidebar.setAttribute('aria-hidden', 'true');
                if (currentToggle) {
                    currentToggle.setAttribute('aria-expanded', 'false');
                }

                console.log('Mobile menu closed successfully');
            }

            // Event listeners with detailed logging
            console.log('Adding click event listener to mobile toggle...');

            // Remove any existing listeners by cloning the element
            const newToggle = mobileMenuToggle.cloneNode(true);
            mobileMenuToggle.parentNode.replaceChild(newToggle, mobileMenuToggle);
            const refreshedToggle = document.getElementById('mobile-menu-toggle');

            if (refreshedToggle) {
                console.log('Adding fresh event listeners...');

                let touchStarted = false;
                let clickHandled = false;

                // Single touch handler for immediate response
                refreshedToggle.addEventListener('touchstart', function(e) {
                    console.log('Touch start - immediate action');
                    touchStarted = true;
                    clickHandled = true;

                    e.preventDefault();
                    e.stopPropagation();

                    // Immediate visual feedback
                    this.style.transform = 'scale(0.95)';
                    this.style.opacity = '0.8';

                    // Immediate menu toggle
                    toggleMobileMenu(e);

                    // Reset visual state after a brief moment
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                        this.style.opacity = '1';
                    }, 150);

                }, { passive: false });

                // Click handler for non-touch devices (with conflict prevention)
                refreshedToggle.addEventListener('click', function(e) {
                    console.log('Click event detected');

                    // Prevent double handling if touch already handled it
                    if (clickHandled) {
                        console.log('Click blocked - already handled by touch');
                        clickHandled = false; // Reset for next interaction
                        return;
                    }

                    console.log('Processing click event');
                    e.preventDefault();
                    e.stopPropagation();
                    toggleMobileMenu(e);
                });

                // Reset touch state on touch end
                refreshedToggle.addEventListener('touchend', function(e) {
                    console.log('Touch end');
                    touchStarted = false;

                    // Reset click handled after a short delay to prevent ghost clicks
                    setTimeout(() => {
                        clickHandled = false;
                    }, 300);
                }, { passive: false });

                // Prevent touch move to avoid accidental scrolling
                refreshedToggle.addEventListener('touchmove', function(e) {
                    if (touchStarted) {
                        e.preventDefault();
                    }
                }, { passive: false });

                console.log('Event listeners attached successfully');
            } else {
                console.error('Could not find refreshed toggle element');
            }

            // Overlay event listeners
            sidebarOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                closeMobileMenu();
            });

            // Handle touch events for mobile
            sidebarOverlay.addEventListener('touchstart', function(e) {
                e.preventDefault();
                closeMobileMenu();
            }, { passive: false });

            // Close menu when clicking on nav links (mobile only)
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    // Only close on mobile screens
                    if (window.innerWidth <= 768) {
                        setTimeout(closeMobileMenu, 150);
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

            // Prevent body scroll when menu is open
            document.addEventListener('touchmove', function(e) {
                if (sidebar.classList.contains('show') && !sidebar.contains(e.target)) {
                    e.preventDefault();
                }
            }, { passive: false });

            // Initialize proper state on load
            if (window.innerWidth <= 768) {
                closeMobileMenu();
            }

            // Add smooth transition for navigation links
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(5px)';
                });

                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Add manual test function to window for debugging
            window.testMobileMenu = function() {
                console.log('=== MANUAL MOBILE MENU TEST ===');
                toggleMobileMenu();
            };

            window.debugMobileMenu = function() {
                console.log('=== MOBILE MENU DEBUG INFO ===');
                const toggle = document.getElementById('mobile-menu-toggle');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');

                console.log('Elements found:', {
                    toggle: !!toggle,
                    sidebar: !!sidebar,
                    overlay: !!overlay
                });

                if (toggle) {
                    console.log('Toggle button info:', {
                        display: window.getComputedStyle(toggle).display,
                        visibility: window.getComputedStyle(toggle).visibility,
                        pointerEvents: window.getComputedStyle(toggle).pointerEvents,
                        zIndex: window.getComputedStyle(toggle).zIndex,
                        position: window.getComputedStyle(toggle).position
                    });
                }

                if (sidebar) {
                    console.log('Sidebar info:', {
                        classes: sidebar.className,
                        display: window.getComputedStyle(sidebar).display,
                        visibility: window.getComputedStyle(sidebar).visibility,
                        transform: window.getComputedStyle(sidebar).transform,
                        opacity: window.getComputedStyle(sidebar).opacity
                    });
                }

                console.log('Screen info:', {
                    width: window.innerWidth,
                    height: window.innerHeight,
                    devicePixelRatio: window.devicePixelRatio
                });

                console.log('Menu state:', {
                    isOpen: sidebar ? sidebar.classList.contains('show') : false,
                    bodyHasMenuOpen: document.body.classList.contains('menu-open')
                });
            };

            window.forceMobileMenuOpen = function() {
                console.log('=== FORCE MOBILE MENU OPEN ===');
                openMobileMenu();
            };

            window.forceMobileMenuClose = function() {
                console.log('=== FORCE MOBILE MENU CLOSE ===');
                closeMobileMenu();
            };

            // Quick touch test function
            window.testTouch = function() {
                console.log('=== SIMULATING TOUCH EVENT ===');
                const toggle = document.getElementById('mobile-menu-toggle');
                if (toggle) {
                    const touchEvent = new TouchEvent('touchstart', {
                        bubbles: true,
                        cancelable: true,
                        touches: [{
                            clientX: 0,
                            clientY: 0,
                            target: toggle
                        }]
                    });
                    toggle.dispatchEvent(touchEvent);
                }
            };

            console.log('=== MOBILE MENU INITIALIZATION COMPLETE ===');
            console.log('Test functions available: testMobileMenu(), debugMobileMenu(), testTouch()');
        }


    </script>

    @stack('scripts')
</body>
</html>
