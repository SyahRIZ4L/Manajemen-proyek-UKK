<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .notification-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .notification-item {
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .notification-item:hover {
            transform: translateX(5px);
        }
        .notification-item.unread {
            background: #f8f9ff;
            border-left-color: #ff6b6b;
        }
        .notification-item.task_assigned { border-left-color: #28a745; }
        .notification-item.task_status_changed { border-left-color: #007bff; }
        .notification-item.project_deadline { border-left-color: #ffc107; }
        .notification-item.task_comment { border-left-color: #17a2b8; }
        .notification-item.time_log_approved { border-left-color: #6f42c1; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4><i class="fas fa-project-diagram"></i> ProManage</h4>
                    <small>Sistem Manajemen Proyek</small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link mb-2" href="<?= route('home') ?>"><i class="fas fa-home me-2"></i> Dashboard</a>
                    <a class="nav-link mb-2" href="<?= route('profile.show') ?>"><i class="fas fa-user me-2"></i> Profile</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.index') ?>"><i class="fas fa-tasks me-2"></i> Tugas Saya</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.history') ?>"><i class="fas fa-history me-2"></i> History Task</a>
                    <a class="nav-link active mb-2" href="<?= route('notifications.index') ?>">
                        <i class="fas fa-bell me-2"></i> Notifikasi
                        <span class="badge bg-danger ms-2" id="notificationBadge"><?= count(array_filter($notifications, fn($n) => !$n['read'])) ?></span>
                    </a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-project-diagram me-2"></i> Proyek</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-calendar me-2"></i> Kalender</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-user-friends me-2"></i> Tim</a>
                </nav>

                <div class="mt-auto pt-4">
                    <form method="POST" action="<?= route('logout') ?>">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Notifikasi</h2>
                    <div>
                        <button class="btn btn-outline-primary me-2" onclick="markAllAsRead()">
                            <i class="fas fa-check-double me-2"></i>Tandai Semua Dibaca
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-filter me-2"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="filterNotifications('all')">Semua</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterNotifications('unread')">Belum Dibaca</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="filterNotifications('task_assigned')">Task Assigned</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterNotifications('task_status_changed')">Status Changed</a></li>
                                <li><a class="dropdown-item" href="#" onclick="filterNotifications('project_deadline')">Deadline</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card notification-card text-center p-3">
                            <i class="fas fa-bell fa-2x text-primary mb-2"></i>
                            <h5>Total Notifikasi</h5>
                            <h3 class="text-primary"><?= count($notifications) ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card notification-card text-center p-3">
                            <i class="fas fa-bell-slash fa-2x text-danger mb-2"></i>
                            <h5>Belum Dibaca</h5>
                            <h3 class="text-danger"><?= count(array_filter($notifications, fn($n) => !$n['read'])) ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card notification-card text-center p-3">
                            <i class="fas fa-tasks fa-2x text-success mb-2"></i>
                            <h5>Task Related</h5>
                            <h3 class="text-success"><?= count(array_filter($notifications, fn($n) => strpos($n['type'], 'task') !== false)) ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card notification-card text-center p-3">
                            <i class="fas fa-calendar-times fa-2x text-warning mb-2"></i>
                            <h5>Deadline Alerts</h5>
                            <h3 class="text-warning"><?= count(array_filter($notifications, fn($n) => $n['type'] === 'project_deadline')) ?></h3>
                        </div>
                    </div>
                </div>

                <!-- Notifications List -->
                <div class="card notification-card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Notifikasi</h5>
                    </div>
                    <div class="card-body" id="notificationsList">
                        <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item <?= !$notification['read'] ? 'unread' : '' ?> <?= $notification['type'] ?>"
                             data-type="<?= $notification['type'] ?>"
                             data-read="<?= $notification['read'] ? 'true' : 'false' ?>">

                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-<?=
                                            $notification['type'] === 'task_assigned' ? 'user-plus' :
                                            ($notification['type'] === 'task_status_changed' ? 'sync' :
                                            ($notification['type'] === 'project_deadline' ? 'calendar-times' :
                                            ($notification['type'] === 'task_comment' ? 'comment' : 'check-circle'))) ?>
                                           text-<?=
                                            $notification['type'] === 'task_assigned' ? 'success' :
                                            ($notification['type'] === 'task_status_changed' ? 'primary' :
                                            ($notification['type'] === 'project_deadline' ? 'warning' :
                                            ($notification['type'] === 'task_comment' ? 'info' : 'purple'))) ?> me-2"></i>
                                        <h6 class="mb-0"><?= htmlspecialchars($notification['title']) ?></h6>
                                        <?php if (!$notification['read']): ?>
                                        <span class="badge bg-danger ms-2">Baru</span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="mb-2 text-muted"><?= htmlspecialchars($notification['message']) ?></p>

                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= $notification['created_at']->diffForHumans() ?>
                                        </small>

                                        <div class="d-flex gap-2">
                                            <?php if (!$notification['read']): ?>
                                            <button class="btn btn-sm btn-outline-primary"
                                                    onclick="markAsRead(<?= $notification['id'] ?>)">
                                                <i class="fas fa-check me-1"></i>Tandai Dibaca
                                            </button>
                                            <?php endif; ?>

                                            <button class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteNotification(<?= $notification['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <?php if (isset($notification['data']['task_id'])): ?>
                                            <a href="<?= route('tasks.show', $notification['data']['task_id']) ?>"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye me-1"></i>Lihat Task
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <?php if (empty($notifications)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h4>Tidak ada notifikasi</h4>
                            <p class="text-muted">Notifikasi akan muncul di sini ketika ada update</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Load More -->
                <?php if (count($notifications) >= 20): ?>
                <div class="text-center mt-4">
                    <button class="btn btn-outline-primary" onclick="loadMoreNotifications()">
                        <i class="fas fa-chevron-down me-2"></i>Muat Lebih Banyak
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mark single notification as read
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                        notificationItem.querySelector('.badge').remove();
                        notificationItem.querySelector('.btn-outline-primary').remove();
                    }
                    updateNotificationBadge();
                }
            });
        }

        // Mark all notifications as read
        function markAllAsRead() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const badge = item.querySelector('.badge');
                        const readBtn = item.querySelector('.btn-outline-primary');
                        if (badge) badge.remove();
                        if (readBtn) readBtn.remove();
                    });
                    updateNotificationBadge();
                    location.reload();
                }
            });
        }

        // Delete notification
        function deleteNotification(notificationId) {
            if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                fetch(`/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        // Filter notifications
        function filterNotifications(type) {
            const notifications = document.querySelectorAll('.notification-item');

            notifications.forEach(item => {
                if (type === 'all') {
                    item.style.display = 'block';
                } else if (type === 'unread') {
                    item.style.display = item.dataset.read === 'false' ? 'block' : 'none';
                } else {
                    item.style.display = item.dataset.type === type ? 'block' : 'none';
                }
            });
        }

        // Update notification badge
        function updateNotificationBadge() {
            fetch('/api/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'inline' : 'none';
                    }
                });
        }

        // Load more notifications
        function loadMoreNotifications() {
            alert('Fitur load more akan segera tersedia');
        }

        // Auto refresh notification count every 30 seconds
        setInterval(updateNotificationBadge, 30000);
    </script>
</body>
</html>
