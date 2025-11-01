<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Lead Dashboard</title>
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
            border-radius: 8px;
            margin: 2px 0;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .teamlead-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 20px;
        }
        .permission-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
        }
        .restriction-badge {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 2px;
        }
        .task-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            transition: transform 0.2s ease;
        }
        .task-card:hover {
            transform: translateY(-2px);
        }
        .priority-critical { border-left: 4px solid #dc3545; }
        .priority-high { border-left: 4px solid #fd7e14; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .team-member {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .progress-ring {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: conic-gradient(#667eea 0deg, #764ba2 calc(var(--progress) * 3.6deg), #e9ecef calc(var(--progress) * 3.6deg));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .progress-ring::before {
            content: '';
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            position: absolute;
        }
        .progress-text {
            position: relative;
            z-index: 1;
            font-weight: bold;
            color: #333;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .blocker-alert {
            background: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
            color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .quick-action-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            margin: 5px;
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Team Lead Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4><i class="fas fa-users-cog"></i> Team Lead</h4>
                    <small>Team Leadership Panel</small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link active mb-1" href="<?= route('teamlead.dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link mb-1" href="<?= route('teamlead.tasks') ?>">
                        <i class="fas fa-tasks me-2"></i> Kelola Tugas
                    </a>
                    <a class="nav-link mb-1" href="<?= route('teamlead.tasks.create') ?>">
                        <i class="fas fa-plus-circle me-2"></i> Assign Tugas
                    </a>
                    <a class="nav-link mb-1" href="<?= route('teamlead.coordination') ?>">
                        <i class="fas fa-project-diagram me-2"></i> Koordinasi Tim
                    </a>

                    <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">

                    <a class="nav-link mb-1" href="<?= route('home') ?>">
                        <i class="fas fa-home me-2"></i> User Dashboard
                    </a>
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
                <div class="teamlead-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">
                                <i class="fas fa-users-cog me-2"></i>Selamat Datang <?= ucfirst(strtolower($userRole)) ?>, <?= htmlspecialchars($user->name) ?>!
                            </h2>
                            <p class="mb-0">Koordinasi tim, distribusi tugas, dan monitoring progress sebagai Team Lead</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="mb-2">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-users-cog me-1"></i><?= $userRole ?>
                                </span>
                            </div>
                            <button class="quick-action-btn" onclick="location.href='<?= route('teamlead.tasks.create') ?>'">
                                <i class="fas fa-plus me-2"></i>Assign Tugas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Permission & Restriction Indicators -->
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="mb-2">Hak Akses Team Lead:</h6>
                            <span class="permission-badge"><i class="fas fa-tasks me-1"></i>Assign Tugas</span>
                            <span class="permission-badge"><i class="fas fa-flag me-1"></i>Set Priority</span>
                            <span class="permission-badge"><i class="fas fa-sync me-1"></i>Update Status</span>
                            <span class="permission-badge"><i class="fas fa-chart-line me-1"></i>Lihat Progress</span>
                            <span class="permission-badge"><i class="fas fa-users me-1"></i>Koordinasi Tim</span>
                            <span class="permission-badge"><i class="fas fa-check-circle me-1"></i>Review Hasil</span>
                            <span class="permission-badge"><i class="fas fa-unlock me-1"></i>Solve Blocker</span>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-2">Batasan:</h6>
                            <span class="restriction-badge"><i class="fas fa-ban me-1"></i>Tidak Bisa Hapus Proyek</span>
                            <span class="restriction-badge"><i class="fas fa-user-minus me-1"></i>Tidak Bisa Remove Anggota</span>
                        </div>
                    </div>
                </div>

                <?php if (session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="card-header">
                                <i class="fas fa-tasks me-2"></i>Total Tasks
                            </div>
                            <div class="card-body text-center">
                                <h2 class="text-primary"><?= $stats['total_tasks'] ?></h2>
                                <small class="text-muted">Tasks yang dikelola</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="card-header">
                                <i class="fas fa-check-circle me-2"></i>Completed
                            </div>
                            <div class="card-body text-center">
                                <h2 class="text-success"><?= $stats['completed_tasks'] ?></h2>
                                <small class="text-muted">Tasks selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="card-header">
                                <i class="fas fa-spinner me-2"></i>In Progress
                            </div>
                            <div class="card-body text-center">
                                <h2 class="text-warning"><?= $stats['in_progress_tasks'] ?></h2>
                                <small class="text-muted">Tasks berjalan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="card-header">
                                <i class="fas fa-users me-2"></i>Team Members
                            </div>
                            <div class="card-body text-center">
                                <h2 class="text-info"><?= $stats['team_members'] ?></h2>
                                <small class="text-muted">Anggota tim</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blockers Alert -->
                <?php if (!empty($stats['blocked_tasks'])): ?>
                <div class="blocker-alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">⚠️ Ada <?= $stats['blocked_tasks'] ?> Task yang Blocked!</h5>
                            <p class="mb-0">Sebagai Team Lead, Anda perlu menyelesaikan blocker ini untuk menjaga progress tim.</p>
                        </div>
                        <div class="ms-auto">
                            <a href="<?= route('teamlead.coordination') ?>" class="btn btn-light">
                                <i class="fas fa-tools me-2"></i>Solve Blockers
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Team Performance Chart -->
                    <div class="col-md-8">
                        <div class="chart-container">
                            <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Team Performance</h5>
                            <canvas id="teamPerformanceChart" width="400" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Team Overview -->
                    <div class="col-md-4">
                        <div class="chart-container">
                            <h5 class="mb-3"><i class="fas fa-users me-2"></i>Team Overview</h5>
                            <?php
                            $completionRate = $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100) : 0;
                            ?>
                            <div class="text-center mb-3">
                                <div class="progress-ring mx-auto" style="--progress: <?= $completionRate ?>">
                                    <span class="progress-text"><?= $completionRate ?>%</span>
                                </div>
                                <p class="mt-2 text-muted">Completion Rate</p>
                            </div>

                            <div class="row text-center">
                                <div class="col-6">
                                    <h6 class="text-primary"><?= $stats['active_projects'] ?></h6>
                                    <small>Active Projects</small>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-success"><?= count($teamPerformance) ?></h6>
                                    <small>Team Members</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities & Pending Reviews -->
                <div class="row">
                    <!-- Recent Activities -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Activities</h5>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <?php foreach (array_slice($recentActivities, 0, 5) as $activity): ?>
                                <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                                    <div class="me-3">
                                        <i class="fas fa-circle text-<?=
                                            $activity['status'] === 'Done' ? 'success' :
                                            ($activity['status'] === 'In Progress' ? 'warning' : 'secondary') ?>"
                                           style="font-size: 0.8rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= htmlspecialchars($activity['title']) ?></h6>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($activity['assigned_to_name']) ?> •
                                            <?= htmlspecialchars($activity['project_name']) ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted"><?= date('H:i', strtotime($activity['updated_at'])) ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Reviews -->
                    <div class="col-md-6">
                        <div class="chart-container">
                            <h5 class="mb-3"><i class="fas fa-eye me-2"></i>Pending Reviews (<?= count($pendingReviews) ?>)</h5>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <?php if (empty($pendingReviews)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <p class="text-muted">Tidak ada task yang menunggu review!</p>
                                </div>
                                <?php else: ?>
                                <?php foreach ($pendingReviews as $review): ?>
                                <div class="task-card priority-<?= strtolower($review['priority']) ?>">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-1"><?= htmlspecialchars($review['title']) ?></h6>
                                            <span class="badge bg-warning">Review</span>
                                        </div>
                                        <p class="small text-muted mb-2">
                                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($review['assigned_to_name']) ?> •
                                            <i class="fas fa-project-diagram me-1"></i><?= htmlspecialchars($review['project_name']) ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i><?= date('d M Y', strtotime($review['due_date'])) ?>
                                            </small>
                                            <div>
                                                <button class="btn btn-sm btn-outline-success"
                                                        onclick="reviewTask(<?= $review['id'] ?>, 'approve')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning"
                                                        onclick="reviewTask(<?= $review['id'] ?>, 'feedback')">
                                                    <i class="fas fa-comment"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="chart-container">
                    <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <button class="quick-action-btn w-100" onclick="location.href='<?= route('teamlead.tasks.create') ?>'">
                                <i class="fas fa-plus-circle me-2"></i>Assign Task Baru
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="quick-action-btn w-100" onclick="location.href='<?= route('teamlead.tasks') ?>'">
                                <i class="fas fa-tasks me-2"></i>Kelola Semua Tasks
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="quick-action-btn w-100" onclick="location.href='<?= route('teamlead.coordination') ?>'">
                                <i class="fas fa-users-cog me-2"></i>Koordinasi Tim
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="quick-action-btn w-100" onclick="showTeamStatus()">
                                <i class="fas fa-chart-line me-2"></i>Team Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Status Modal -->
    <div class="modal fade" id="teamStatusModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-line me-2"></i>Team Status Overview
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php foreach ($teamPerformance as $member): ?>
                        <div class="col-md-6 mb-3">
                            <div class="team-member">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0"><?= htmlspecialchars($member['name']) ?></h6>
                                    <span class="badge bg-primary"><?= $member['total_tasks'] ?> tasks</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <?php
                                    $memberCompletion = $member['total_tasks'] > 0 ?
                                        round(($member['completed_tasks'] / $member['total_tasks']) * 100) : 0;
                                    ?>
                                    <div class="progress-bar" style="width: <?= $memberCompletion ?>%"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small>Completed: <?= $member['completed_tasks'] ?></small>
                                    <small>Hours: <?= $member['total_hours'] ?>h</small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Team Performance Chart
        const ctx = document.getElementById('teamPerformanceChart').getContext('2d');
        const teamPerformanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?= "'" . implode("','", array_column($teamPerformance, 'name')) . "'" ?>],
                datasets: [{
                    label: 'Completed Tasks',
                    data: [<?= implode(',', array_column($teamPerformance, 'completed_tasks')) ?>],
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1
                }, {
                    label: 'Total Tasks',
                    data: [<?= implode(',', array_column($teamPerformance, 'total_tasks')) ?>],
                    backgroundColor: 'rgba(118, 75, 162, 0.8)',
                    borderColor: 'rgba(118, 75, 162, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        function showTeamStatus() {
            const modal = new bootstrap.Modal(document.getElementById('teamStatusModal'));
            modal.show();
        }

        function reviewTask(taskId, action) {
            if (action === 'approve') {
                // Update task status to Done
                fetch(`/teamlead/tasks/${taskId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ status: 'Done' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                });
            } else if (action === 'feedback') {
                const feedback = prompt('Berikan feedback untuk task ini:');
                if (feedback) {
                    // Add comment and keep in review
                    // Implementation depends on your comment system
                    alert('Feedback berhasil ditambahkan');
                }
            }
        }

        // Auto-refresh dashboard every 30 seconds
        setInterval(function() {
            // Update dashboard data if needed
            console.log('Refreshing dashboard data...');
        }, 30000);
    </script>
</body>
</html>
