<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koordinasi Tim - Team Lead</title>
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
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .coordination-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .coordination-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .team-member {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .team-member:hover {
            border-color: #11998e;
            background: #f8f9fa;
        }
        .workload-bar {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
            margin-top: 5px;
            overflow: hidden;
        }
        .workload-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .workload-low { background: linear-gradient(90deg, #28a745, #20c997); }
        .workload-medium { background: linear-gradient(90deg, #ffc107, #fd7e14); }
        .workload-high { background: linear-gradient(90deg, #fd7e14, #dc3545); }
        .blocker-item {
            border-left: 4px solid #dc3545;
            background: #fff5f5;
            padding: 15px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 15px;
        }
        .review-item {
            border-left: 4px solid #ffc107;
            background: #fffbf0;
            padding: 15px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 15px;
        }
        .performance-metric {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .metric-critical { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); color: white; }
        .metric-warning { background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%); color: white; }
        .metric-good { background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%); color: white; }
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring__circle {
            stroke-dasharray: 251.2;
            stroke-dashoffset: 251.2;
            transition: stroke-dashoffset 0.35s;
        }
        .btn-resolve {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        .btn-resolve:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
            color: white;
        }
        .btn-review {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        .btn-review:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
            color: white;
        }
        .priority-critical { color: #dc3545; }
        .priority-high { color: #fd7e14; }
        .priority-medium { color: #ffc107; }
        .priority-low { color: #28a745; }
        .status-todo { background: #6c757d; }
        .status-progress { background: #007bff; }
        .status-review { background: #ffc107; }
        .status-done { background: #28a745; }
        .communication-card {
            border-left: 4px solid #17a2b8;
            background: #f0f9ff;
            padding: 15px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 15px;
        }
        .quick-action-btn {
            background: white;
            border: 2px solid #11998e;
            color: #11998e;
            padding: 10px 20px;
            border-radius: 25px;
            margin: 5px;
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            background: #11998e;
            color: white;
            transform: translateY(-2px);
        }
        .timeline-item {
            border-left: 3px solid #11998e;
            padding-left: 20px;
            margin-bottom: 20px;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 13px;
            height: 13px;
            background: #11998e;
            border-radius: 50%;
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
                    <a class="nav-link mb-1" href="<?= route('teamlead.dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link mb-1" href="<?= route('teamlead.tasks') ?>">
                        <i class="fas fa-tasks me-2"></i> Kelola Tugas
                    </a>
                    <a class="nav-link mb-1" href="<?= route('teamlead.tasks.create') ?>">
                        <i class="fas fa-plus-circle me-2"></i> Assign Tugas
                    </a>
                    <a class="nav-link active mb-1" href="<?= route('teamlead.coordination') ?>">
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
                                <i class="fas fa-project-diagram me-2"></i>Koordinasi Tim
                            </h2>
                            <p class="mb-0">Monitor dan koordinasi aktivitas tim untuk mencapai target proyek</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#teamMeetingModal">
                                <i class="fas fa-video me-2"></i>Team Meeting
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="coordination-card p-3">
                            <h6 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                            <div class="text-center">
                                <button class="quick-action-btn" onclick="checkAllProgress()">
                                    <i class="fas fa-chart-line me-1"></i>Check Progress
                                </button>
                                <button class="quick-action-btn" onclick="reviewBlockers()">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Review Blockers
                                </button>
                                <button class="quick-action-btn" onclick="sendTeamUpdate()">
                                    <i class="fas fa-bullhorn me-1"></i>Send Update
                                </button>
                                <button class="quick-action-btn" onclick="scheduleCheckIn()">
                                    <i class="fas fa-calendar-check me-1"></i>Schedule Check-in
                                </button>
                                <button class="quick-action-btn" onclick="generateReport()">
                                    <i class="fas fa-file-alt me-1"></i>Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Active Blockers -->
                        <div class="coordination-card p-4">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Active Blockers</h5>
                            </div>

                            <?php if (empty($blockers)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <h6>No Active Blockers</h6>
                                <p>Tim sedang berjalan lancar tanpa blocker!</p>
                            </div>
                            <?php else: ?>
                            <?php foreach ($blockers as $blocker): ?>
                            <div class="blocker-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="priority-<?= strtolower($blocker['priority']) ?>">
                                        <i class="fas fa-flag me-1"></i><?= htmlspecialchars($blocker['title']) ?>
                                    </h6>
                                    <span class="badge bg-danger">Blocker</span>
                                </div>
                                <p class="mb-2 text-muted"><?= htmlspecialchars($blocker['description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small><strong>Affected:</strong> <?= htmlspecialchars($blocker['affected_member']) ?></small><br>
                                        <small><strong>Since:</strong> <?= date('d M Y H:i', strtotime($blocker['created_at'])) ?></small>
                                    </div>
                                    <div>
                                        <button class="btn btn-resolve btn-sm me-2" onclick="resolveBlocker(<?= $blocker['id'] ?>)">
                                            <i class="fas fa-wrench me-1"></i>Resolve
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" onclick="escalateBlocker(<?= $blocker['id'] ?>)">
                                            <i class="fas fa-level-up-alt me-1"></i>Escalate
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Pending Reviews -->
                        <div class="coordination-card p-4 mt-4">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Pending Reviews</h5>
                            </div>

                            <?php if (empty($pendingReviews)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <h6>No Pending Reviews</h6>
                                <p>Semua task telah di-review!</p>
                            </div>
                            <?php else: ?>
                            <?php foreach ($pendingReviews as $review): ?>
                            <div class="review-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6><?= htmlspecialchars($review['title']) ?></h6>
                                    <span class="badge bg-warning">Review Required</span>
                                </div>
                                <p class="mb-2 text-muted"><?= htmlspecialchars($review['description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small><strong>Completed by:</strong> <?= htmlspecialchars($review['completed_by']) ?></small><br>
                                        <small><strong>Submitted:</strong> <?= date('d M Y H:i', strtotime($review['submitted_at'])) ?></small>
                                    </div>
                                    <div>
                                        <button class="btn btn-review btn-sm me-2" onclick="approveTask(<?= $review['id'] ?>)">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="requestRevision(<?= $review['id'] ?>)">
                                            <i class="fas fa-undo me-1"></i>Revision
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Team Communication -->
                        <div class="coordination-card p-4 mt-4">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Team Communication</h5>
                            </div>

                            <div class="communication-card">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6><i class="fas fa-bullhorn me-2 text-info"></i>Daily Standup - Today</h6>
                                    <small class="text-muted">09:00 AM</small>
                                </div>
                                <p class="mb-2">Daily standup meeting untuk sync progress dan identifikasi blocker</p>
                                <button class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-video me-1"></i>Join Meeting
                                </button>
                            </div>

                            <div class="communication-card">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6><i class="fas fa-exclamation-circle me-2 text-warning"></i>Sprint Review Reminder</h6>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                                <p class="mb-2">Sprint review dijadwalkan besok pukul 14:00. Pastikan semua task sudah ready untuk demo.</p>
                                <button class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-calendar me-1"></i>Add to Calendar
                                </button>
                            </div>

                            <!-- Add Communication Form -->
                            <div class="mt-3">
                                <form onsubmit="sendTeamMessage(event)">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Type team message..." required>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Team Performance Metrics -->
                        <div class="coordination-card p-4">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Team Performance</h5>
                            </div>

                            <div class="performance-metric metric-good">
                                <h3>87%</h3>
                                <p class="mb-0">Sprint Completion</p>
                            </div>

                            <div class="performance-metric metric-warning">
                                <h3>3</h3>
                                <p class="mb-0">Active Blockers</p>
                            </div>

                            <div class="performance-metric metric-good">
                                <h3>95%</h3>
                                <p class="mb-0">On-time Delivery</p>
                            </div>
                        </div>

                        <!-- Team Members Status -->
                        <div class="coordination-card p-4 mt-4">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Team Status</h5>
                            </div>

                            <?php foreach ($teamMembers as $member): ?>
                            <div class="team-member">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 35px; height: 35px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?= htmlspecialchars($member['name']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($member['role']) ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $member['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($member['status']) ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small>Workload:</small>
                                    <small><strong><?= $member['current_tasks'] ?? 0 ?> tasks</strong></small>
                                </div>
                                <div class="workload-bar">
                                    <?php
                                    $workload = $member['current_tasks'] ?? 0;
                                    $workloadPercentage = min(($workload / 10) * 100, 100);
                                    $workloadClass = $workload <= 3 ? 'workload-low' : ($workload <= 6 ? 'workload-medium' : 'workload-high');
                                    ?>
                                    <div class="workload-fill <?= $workloadClass ?>" style="width: <?= $workloadPercentage ?>%"></div>
                                </div>

                                <div class="mt-2">
                                    <small><strong>Current Task:</strong> <?= htmlspecialchars($member['current_task'] ?? 'No active task') ?></small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Recent Activities -->
                        <div class="coordination-card p-4 mt-4">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activities</h5>
                            </div>

                            <div class="timeline-item">
                                <small class="text-muted">2 minutes ago</small>
                                <p class="mb-0"><strong>John Doe</strong> completed task "API Integration"</p>
                            </div>

                            <div class="timeline-item">
                                <small class="text-muted">15 minutes ago</small>
                                <p class="mb-0"><strong>Jane Smith</strong> reported blocker on "Database Migration"</p>
                            </div>

                            <div class="timeline-item">
                                <small class="text-muted">1 hour ago</small>
                                <p class="mb-0"><strong>You</strong> assigned new task to Mike Wilson</p>
                            </div>

                            <div class="timeline-item">
                                <small class="text-muted">2 hours ago</small>
                                <p class="mb-0"><strong>Sarah Johnson</strong> requested review for "UI Components"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Meeting Modal -->
    <div class="modal fade" id="teamMeetingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-video me-2"></i>Schedule Team Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="teamMeetingForm">
                        <div class="mb-3">
                            <label class="form-label">Meeting Type</label>
                            <select class="form-select" required>
                                <option value="">Select type...</option>
                                <option value="standup">Daily Standup</option>
                                <option value="review">Sprint Review</option>
                                <option value="planning">Sprint Planning</option>
                                <option value="retrospective">Retrospective</option>
                                <option value="blocker">Blocker Resolution</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration (minutes)</label>
                            <select class="form-select" required>
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Agenda</label>
                            <textarea class="form-control" rows="3" placeholder="Meeting agenda..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attendees</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked disabled>
                                <label class="form-check-label">All Team Members</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="scheduleTeamMeeting()">
                        <i class="fas fa-calendar-plus me-2"></i>Schedule Meeting
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Team coordination functions
        function resolveBlocker(blockerId) {
            if (confirm('Are you sure you want to mark this blocker as resolved?')) {
                fetch(`/teamlead/blockers/${blockerId}/resolve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error resolving blocker: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error resolving blocker');
                });
            }
        }

        function escalateBlocker(blockerId) {
            if (confirm('Escalate this blocker to Project Admin?')) {
                fetch(`/teamlead/blockers/${blockerId}/escalate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Blocker escalated to Project Admin');
                        location.reload();
                    } else {
                        alert('Error escalating blocker: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error escalating blocker');
                });
            }
        }

        function approveTask(taskId) {
            if (confirm('Approve this task as completed?')) {
                fetch(`/teamlead/tasks/${taskId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error approving task: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error approving task');
                });
            }
        }

        function requestRevision(taskId) {
            const feedback = prompt('Enter feedback for revision:');
            if (feedback) {
                fetch(`/teamlead/tasks/${taskId}/revision`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ feedback: feedback })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Revision request sent');
                        location.reload();
                    } else {
                        alert('Error requesting revision: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error requesting revision');
                });
            }
        }

        function checkAllProgress() {
            window.location.href = '/teamlead/tasks?view=progress';
        }

        function reviewBlockers() {
            document.querySelector('.blocker-item').scrollIntoView({ behavior: 'smooth' });
        }

        function sendTeamUpdate() {
            const message = prompt('Enter team update message:');
            if (message) {
                fetch('/teamlead/communications/broadcast', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Team update sent successfully');
                    } else {
                        alert('Error sending update: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error sending team update');
                });
            }
        }

        function scheduleCheckIn() {
            const member = prompt('Enter team member name for check-in:');
            if (member) {
                alert(`Check-in scheduled with ${member}`);
            }
        }

        function generateReport() {
            window.open('/teamlead/reports/generate', '_blank');
        }

        function scheduleTeamMeeting() {
            alert('Team meeting scheduled successfully');
            const modal = bootstrap.Modal.getInstance(document.getElementById('teamMeetingModal'));
            modal.hide();
        }

        function sendTeamMessage(event) {
            event.preventDefault();
            const input = event.target.querySelector('input');
            const message = input.value.trim();

            if (message) {
                // Add message to communication area
                const communicationCard = document.createElement('div');
                communicationCard.className = 'communication-card';
                communicationCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6><i class="fas fa-user me-2 text-success"></i>You (Team Lead)</h6>
                        <small class="text-muted">Just now</small>
                    </div>
                    <p class="mb-0">${message}</p>
                `;

                const communicationContainer = document.querySelector('.communication-card').parentElement;
                communicationContainer.insertBefore(communicationCard, communicationContainer.children[2]);

                input.value = '';
                alert('Message sent to team');
            }
        }

        // Auto-refresh coordination data every 30 seconds
        setInterval(function() {
            // Refresh critical data without full page reload
            refreshCoordinationData();
        }, 30000);

        function refreshCoordinationData() {
            fetch('/teamlead/coordination/data')
                .then(response => response.json())
                .then(data => {
                    // Update blockers count, team status, etc.
                    updateCoordinationUI(data);
                })
                .catch(error => console.error('Error refreshing data:', error));
        }

        function updateCoordinationUI(data) {
            // Update performance metrics
            document.querySelector('.metric-good h3').textContent = data.sprintCompletion + '%';
            document.querySelector('.metric-warning h3').textContent = data.activeBlockers;

            // Update other UI elements as needed
        }
    </script>
</body>
</html>
