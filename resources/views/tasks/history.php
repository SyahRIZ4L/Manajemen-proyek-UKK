<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Task - Manajemen Proyek</title>
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
        .history-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .timeline-item {
            border-left: 3px solid #dee2e6;
            padding-left: 20px;
            margin-bottom: 20px;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #007bff;
        }
        .timeline-item.completed::before { background: #28a745; }
        .timeline-item.in-progress::before { background: #007bff; }
        .timeline-item.review::before { background: #ffc107; }
        .timeline-item.created::before { background: #6c757d; }
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
                    <a class="nav-link active mb-2" href="<?= route('tasks.history') ?>"><i class="fas fa-history me-2"></i> History Task</a>
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
                    <h2>History Task</h2>
                    <div>
                        <button class="btn btn-outline-primary me-2" onclick="exportHistory()">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <a href="<?= route('tasks.index') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Tasks
                        </a>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card history-card p-3 mb-4" style="background: #f8f9fa;">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <select class="form-select" id="actionFilter">
                                <option value="">Semua Aktivitas</option>
                                <option value="created">Task Dibuat</option>
                                <option value="status_changed">Status Diubah</option>
                                <option value="completed">Task Selesai</option>
                                <option value="time_logged">Waktu Dicatat</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="dateFrom" placeholder="Dari tanggal">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="dateTo" placeholder="Sampai tanggal">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchHistory" placeholder="Cari dalam history...">
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card history-card text-center p-3">
                            <i class="fas fa-tasks fa-2x text-primary mb-2"></i>
                            <h5>Total Aktivitas</h5>
                            <h3 class="text-primary"><?= count($history) ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card history-card text-center p-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h5>Task Selesai</h5>
                            <h3 class="text-success"><?= count(array_filter($history, fn($h) => $h['action'] === 'completed')) ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card history-card text-center p-3">
                            <i class="fas fa-clock fa-2x text-info mb-2"></i>
                            <h5>Waktu Dicatat</h5>
                            <h3 class="text-info"><?= count(array_filter($history, fn($h) => $h['action'] === 'time_logged')) ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card history-card text-center p-3">
                            <i class="fas fa-sync fa-2x text-warning mb-2"></i>
                            <h5>Status Diubah</h5>
                            <h3 class="text-warning"><?= count(array_filter($history, fn($h) => $h['action'] === 'status_changed')) ?></h3>
                        </div>
                    </div>
                </div>

                <!-- History Timeline -->
                <div class="card history-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Timeline Aktivitas</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline" id="historyTimeline">
                            <?php foreach ($history as $item): ?>
                            <div class="timeline-item <?= str_replace('_', '-', $item['action']) ?>"
                                 data-action="<?= $item['action'] ?>"
                                 data-date="<?= $item['created_at']->format('Y-m-d') ?>"
                                 data-content="<?= strtolower($item['task_title'] . ' ' . $item['description']) ?>">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= htmlspecialchars($item['task_title']) ?></h6>
                                        <p class="mb-1 text-muted"><?= htmlspecialchars($item['description']) ?></p>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted me-3">
                                                <i class="fas fa-clock me-1"></i>
                                                <?= $item['created_at']->format('d M Y H:i') ?>
                                            </small>
                                            <span class="badge bg-<?=
                                                $item['action'] === 'completed' ? 'success' :
                                                ($item['action'] === 'status_changed' ? 'primary' :
                                                ($item['action'] === 'time_logged' ? 'info' : 'secondary')) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $item['action'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="viewDetails(<?= $item['id'] ?>)">
                                                <i class="fas fa-eye me-2"></i>Lihat Detail
                                            </a></li>
                                            <?php if ($item['action'] === 'time_logged'): ?>
                                            <li><a class="dropdown-item" href="#" onclick="editTimeLog(<?= $item['id'] ?>)">
                                                <i class="fas fa-edit me-2"></i>Edit Log
                                            </a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (empty($history)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h4>Belum ada history</h4>
                            <p class="text-muted">History aktivitas task akan muncul di sini</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Load More Button -->
                <?php if (count($history) >= 20): ?>
                <div class="text-center mt-4">
                    <button class="btn btn-outline-primary" onclick="loadMoreHistory()">
                        <i class="fas fa-chevron-down me-2"></i>Muat Lebih Banyak
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Aktivitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functions
        function filterHistory() {
            const actionFilter = document.getElementById('actionFilter').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            const searchTerm = document.getElementById('searchHistory').value.toLowerCase();

            const timelineItems = document.querySelectorAll('.timeline-item');

            timelineItems.forEach(item => {
                const action = item.dataset.action;
                const date = item.dataset.date;
                const content = item.dataset.content;

                const actionMatch = !actionFilter || action === actionFilter;
                const dateFromMatch = !dateFrom || date >= dateFrom;
                const dateToMatch = !dateTo || date <= dateTo;
                const searchMatch = !searchTerm || content.includes(searchTerm);

                if (actionMatch && dateFromMatch && dateToMatch && searchMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Event listeners for filters
        document.getElementById('actionFilter').addEventListener('change', filterHistory);
        document.getElementById('dateFrom').addEventListener('change', filterHistory);
        document.getElementById('dateTo').addEventListener('change', filterHistory);
        document.getElementById('searchHistory').addEventListener('input', filterHistory);

        // View details function
        function viewDetails(historyId) {
            // Mock detail data - replace with actual AJAX call
            const detailHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Aktivitas</h6>
                        <table class="table table-sm">
                            <tr><td><strong>ID:</strong></td><td>${historyId}</td></tr>
                            <tr><td><strong>Tipe:</strong></td><td>Status Changed</td></tr>
                            <tr><td><strong>Waktu:</strong></td><td>21 Sep 2025 14:30</td></tr>
                            <tr><td><strong>User:</strong></td><td>John Doe</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Detail Perubahan</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Task:</strong></td><td>Develop Authentication</td></tr>
                            <tr><td><strong>Status Lama:</strong></td><td>To Do</td></tr>
                            <tr><td><strong>Status Baru:</strong></td><td>In Progress</td></tr>
                            <tr><td><strong>Komentar:</strong></td><td>Mulai mengerjakan fitur authentication</td></tr>
                        </table>
                    </div>
                </div>
            `;

            document.getElementById('detailContent').innerHTML = detailHTML;
            new bootstrap.Modal(document.getElementById('detailModal')).show();
        }

        // Edit time log function
        function editTimeLog(historyId) {
            alert('Fitur edit time log akan segera tersedia');
        }

        // Export history function
        function exportHistory() {
            // Create CSV data
            const csvData = [
                ['Tanggal', 'Task', 'Aktivitas', 'Deskripsi'],
                <?php foreach ($history as $item): ?>
                ['<?= $item['created_at']->format('d/m/Y H:i') ?>', '<?= htmlspecialchars($item['task_title']) ?>', '<?= ucfirst(str_replace('_', ' ', $item['action'])) ?>', '<?= htmlspecialchars($item['description']) ?>'],
                <?php endforeach; ?>
            ];

            const csvContent = csvData.map(row => row.join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = 'task_history_<?= date('Y-m-d') ?>.csv';
            a.click();

            window.URL.revokeObjectURL(url);
        }

        // Load more history function
        function loadMoreHistory() {
            alert('Fitur load more akan segera tersedia');
        }

        // Set default date filters (last 30 days)
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);

            document.getElementById('dateTo').value = today.toISOString().split('T')[0];
            document.getElementById('dateFrom').value = thirtyDaysAgo.toISOString().split('T')[0];
        });
    </script>
</body>
</html>
