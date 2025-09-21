
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
                            <h6 class="mb-0"><?= htmlspecialchars(auth()->user()->name ?? 'User') ?></h6>
                            <small class="opacity-75">Member</small>
                        </div>
                    </div>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link active mb-2" href="<?= route('home') ?>"><i class="fas fa-home me-2"></i> Dashboard</a>
                    <a class="nav-link mb-2" href="<?= route('profile.show') ?>"><i class="fas fa-user me-2"></i> Profile</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.index') ?>"><i class="fas fa-tasks me-2"></i> Tugas Saya</a>
                    <a class="nav-link mb-2" href="<?= route('tasks.history') ?>"><i class="fas fa-history me-2"></i> History Task</a>
                    <a class="nav-link mb-2" href="<?= route('notifications.index') ?>"><i class="fas fa-bell me-2"></i> Notifikasi</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-project-diagram me-2"></i> Proyek</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-calendar me-2"></i> Kalender</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-user-friends me-2"></i> Tim</a>
                    <a class="nav-link mb-2" href="#"><i class="fas fa-cog me-2"></i> Pengaturan</a>
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
                            <h3>Selamat Datang, <?= htmlspecialchars(auth()->user()->name ?? 'User') ?>!</h3>
                            <p class="mb-0">Mulai kelola proyek Anda dengan efisien dan terorganisir</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-rocket fa-3x opacity-75"></i>
                        </div>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-project-diagram fa-3x text-primary mb-3"></i>
                            <h5>Proyek Aktif</h5>
                            <h3 class="text-primary">5</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-tasks fa-3x text-success mb-3"></i>
                            <h5>Tugas Selesai</h5>
                            <h3 class="text-success">23</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                            <h5>Tugas Pending</h5>
                            <h3 class="text-warning">7</h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card info-card text-center p-3">
                            <i class="fas fa-user-friends fa-3x text-info mb-3"></i>
                            <h5>Anggota Tim</h5>
                            <h3 class="text-info">15</h3>
                        </div>
                    </div>
                </div>

                <!-- Company Info Section -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Tentang Perusahaan</h5>
                            </div>
                            <div class="card-body">
                                <h6>PT. Teknologi Maju Bersama</h6>
                                <p class="text-muted mb-3">Perusahaan teknologi yang fokus pada pengembangan solusi digital untuk berbagai industri.</p>

                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Didirikan:</small>
                                        <div>2020</div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Karyawan:</small>
                                        <div>50+ Orang</div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Lokasi:</small>
                                        <div>Jakarta, Indonesia</div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Industri:</small>
                                        <div>Teknologi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-bullseye me-2"></i>Visi & Misi</h5>
                            </div>
                            <div class="card-body">
                                <h6>Visi</h6>
                                <p class="text-muted mb-3">Menjadi perusahaan teknologi terdepan dalam menghadirkan solusi digital inovatif untuk kemajuan bisnis di Indonesia.</p>

                                <h6>Misi</h6>
                                <ul class="text-muted">
                                    <li>Mengembangkan teknologi berkualitas tinggi</li>
                                    <li>Memberikan pelayanan terbaik kepada klien</li>
                                    <li>Menciptakan lingkungan kerja yang inovatif</li>
                                    <li>Berkontribusi pada kemajuan teknologi nasional</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card info-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle p-2 me-3">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                            <div>
                                <div>Selamat datang di sistem manajemen proyek!</div>
                                <small class="text-muted">Baru saja</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle p-2 me-3">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <div>Akun Anda telah berhasil diaktifkan</div>
                                <small class="text-muted">5 menit yang lalu</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle p-2 me-3">
                                <i class="fas fa-book text-white"></i>
                            </div>
                            <div>
                                <div>Silakan jelajahi fitur-fitur yang tersedia</div>
                                <small class="text-muted">10 menit yang lalu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
