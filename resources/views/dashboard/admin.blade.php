<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .content-section {
            padding: 50px 40px;
            text-align: center;
        }
        .start-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 18px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.2rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 15px;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        .start-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .start-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s;
        }
        .start-btn:hover::before {
            left: 100%;
        }
        .start-btn i {
            font-size: 1.4rem;
            transition: transform 0.3s ease;
        }
        .start-btn:hover i {
            transform: rotate(15deg) scale(1.1);
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        .feature-item {
            text-align: center;
            padding: 20px;
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #667eea;
        }
        .feature-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        .feature-desc {
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="main-card">
            <!-- Header -->
            <div class="header-section">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <i class="bi bi-shield-check me-3" style="font-size: 3.5rem;"></i>
                    <div>
                        <h1 class="mb-0">Admin Dashboard</h1>
                        <p class="mb-0 opacity-75">Project Administrator</p>
                    </div>
                </div>
                <h3>Selamat Datang, {{ $user->name }}!</h3>
                <p class="mb-0">Akses penuh ke sistem manajemen proyek</p>
            </div>

            <!-- Content Section -->
            <div class="content-section">
                <h4 class="mb-4">Administrator Control Center</h4>
                <p class="text-muted mb-4">
                    Sebagai administrator, Anda memiliki akses penuh untuk mengelola<br>
                    proyek, tim, dan seluruh sistem manajemen
                </p>

                <!-- Start Button -->
                <div class="mb-5">
                    <a href="{{ route('admin.panel') }}" class="start-btn">
                        <i class="bi bi-rocket-takeoff"></i>
                        <span>Launch Admin Panel</span>
                    </a>
                </div>

                <!-- Features -->
                <div class="features-grid">
                    <div class="feature-item">
                        <i class="bi bi-kanban-fill feature-icon"></i>
                        <h6 class="feature-title">Project Management</h6>
                        <p class="feature-desc">Kelola semua proyek dan tugas</p>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-people-fill feature-icon"></i>
                        <h6 class="feature-title">User Management</h6>
                        <p class="feature-desc">Atur pengguna dan permissions</p>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-bar-chart-fill feature-icon"></i>
                        <h6 class="feature-title">Reports & Analytics</h6>
                        <p class="feature-desc">Lihat laporan dan analisis data</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



