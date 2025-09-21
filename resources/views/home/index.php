<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .info-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-5px);
        }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
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
        .notification-item {
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .notification-item.unread {
            background: #f8f9ff;
            border-left-color: #ff6b6b;
        }
        .task-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            transition: all 0.3s ease;
        }
        .task-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
        .time-tracker {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            border-radius: 15px;
        }
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

                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle p-2 me-3">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0"><?= htmlspecialchars($user->name) ?></h6>
                            <small class="opacity-75">Member</small>
                        </div>
                    </div>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link active mb-2" href="<?= route('home') ?>"><i class="fas fa-home me-2"></i> Dashboard</a>
                    <a class="nav-link mb-2" href="<?= route('profile.show') ?>"><i class="fas fa-user me-2"></i> Profile</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.index') ?>"><i class="fas fa-tasks me-2"></i> Tugas Saya</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.history') ?>"><i class="fas fa-history me-2"></i> History Task</a>
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
            <div class="col-md-9 col-lg-10 main-content p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard</h2>
                    <div class="text-muted">
                        <i class="fas fa-calendar-alt me-2"></i>
                        <?= date('d F Y') ?>
                    </div>
                </div>

                <!-- Welcome Card -->
                <div class="welcome-card p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3>Selamat Datang, <?= htmlspecialchars($user->name) ?>!</h3>
                            <p class="mb-0">Kelola proyek Anda dengan efisien dan pantau progress secara real-time</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-rocket fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>

                <!-- Time Tracking -->
                <div class="time-tracker p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4><i class="fas fa-clock me-2"></i>Time Tracking</h4>
                            <div class="row">
                                <div class="col-6">
                                    <h3><?= $stats['current_week_hours'] ?> Jam</h3>
                                    <small>Minggu Ini</small>
                                </div>
                                <div class="col-6">
                                    <h3><?= $stats['current_month_hours'] ?> Jam</h3>
                                    <small>Bulan Ini</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <canvas id="timeChart" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-project-diagram fa-3x text-primary mb-3"></i>
                            <h5>Proyek Selesai</h5>
                            <h3 class="text-primary"><?= $stats['completed_projects'] ?></h3>
                            <small class="text-muted">dari <?= $stats['total_projects'] ?> total</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-tasks fa-3x text-success mb-3"></i>
                            <h5>Task Selesai</h5>
                            <h3 class="text-success"><?= $stats['completed_tasks'] ?></h3>
                            <small class="text-muted">Total pencapaian</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-hourglass-half fa-3x text-warning mb-3"></i>
                            <h5>Rata-rata Penyelesaian</h5>
                            <h3 class="text-warning"><?= $stats['avg_completion_time'] ?></h3>
                            <small class="text-muted">Hari per project</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-clock fa-3x text-info mb-3"></i>
                            <h5>Total Jam Kerja</h5>
                            <h3 class="text-info"><?= $stats['total_time_logged'] ?></h3>
                            <small class="text-muted">Jam tercatat</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Tasks -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Update New Task</h5>
                                <a href="<?= route('tasks.index') ?>" class="btn btn-light btn-sm">Lihat Semua</a>
                            </div>
                            <div class="card-body">
                                <?php foreach ($recentTasks as $task): ?>
                                <div class="task-item">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                        <span class="badge status-badge
                                            <?= $task['status'] === 'Completed' ? 'bg-success' :
                                                ($task['status'] === 'In Progress' ? 'bg-primary' : 'bg-warning') ?>">
                                            <?= $task['status'] ?>
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-2"><?= htmlspecialchars($task['project']) ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= date('d M Y', strtotime($task['due_date'])) ?>
                                        </small>
                                        <span class="badge bg-secondary"><?= $task['priority'] ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                                <div class="text-center mt-3">
                                    <a href="<?= route('tasks.create') ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-plus me-2"></i>Tambah Task Baru
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifikasi Update Task</h5>
                                <span class="badge bg-danger"><?= count(array_filter($notifications, fn($n) => !$n['read'])) ?></span>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($notifications as $notification): ?>
                                <div class="notification-item <?= !$notification['read'] ? 'unread' : '' ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= htmlspecialchars($notification['title']) ?></h6>
                                            <p class="mb-1 small"><?= htmlspecialchars($notification['message']) ?></p>
                                            <small class="text-muted">
                                                <?= $notification['created_at']->diffForHumans() ?>
                                            </small>
                                        </div>
                                        <?php if (!$notification['read']): ?>
                                        <button class="btn btn-sm btn-outline-primary mark-read"
                                                data-id="<?= $notification['id'] ?>">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Task Overview -->
                <div class="card info-card">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>History Task Overview</h5>
                        <a href="<?= route('tasks.history') ?>" class="btn btn-light btn-sm">Lihat Detail</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="taskHistoryChart" width="400" height="200"></canvas>
                            </div>
                            <div class="col-md-4">
                                <h6>Aktivitas Terakhir:</h6>
                                <div class="timeline">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-success rounded-circle p-1 me-2" style="width: 8px; height: 8px;"></div>
                                        <small>Task "Database schema" selesai</small>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-primary rounded-circle p-1 me-2" style="width: 8px; height: 8px;"></div>
                                        <small>Task "Auth system" dimulai</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning rounded-circle p-1 me-2" style="width: 8px; height: 8px;"></div>
                                        <small>Task "API docs" di-review</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Time Tracking Chart
        const timeCtx = document.getElementById('timeChart').getContext('2d');
        new Chart(timeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Jam Kerja', 'Target'],
                datasets: [{
                    data: [<?= $stats['current_week_hours'] ?>, <?= 40 - $stats['current_week_hours'] ?>],
                    backgroundColor: ['#ffffff', 'rgba(255,255,255,0.3)'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Task History Chart
        const historyCtx = document.getElementById('taskHistoryChart').getContext('2d');
        new Chart(historyCtx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Task Selesai',
                    data: [3, 5, 2, 8, 6, 4, 7],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0,123,255,0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Mark notification as read
        document.querySelectorAll('.mark-read').forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                // AJAX call to mark as read
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_token() ?>',
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    this.closest('.notification-item').classList.remove('unread');
                    this.remove();
                });
            });
        });
    </script>
</body>
</html>
