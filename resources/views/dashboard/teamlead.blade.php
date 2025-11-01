<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Lead Dashboard - Manajemen Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
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
            max-width: 1200px;
            width: 100%;
        }
        .header-section {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .stats-section {
            padding: 40px;
        }
        .stat-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: none;
            transition: transform 0.3s ease;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }
        .stat-label {
            color: #6c757d;
            margin: 0;
        }
        .logout-btn {
            position: absolute;
            top: 25px;
            right: 25px;
            z-index: 1000;
        }
        .modern-logout-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 20px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .modern-logout-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        .modern-logout-btn i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }
        .modern-logout-btn:hover i {
            transform: translateX(3px);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Modern Logout Button -->
        <div class="logout-btn">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="modern-logout-btn">
                    <i class="bi bi-power"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <div class="main-card">
            <!-- Header -->
            <div class="header-section">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <i class="bi bi-star-fill me-3" style="font-size: 3rem;"></i>
                    <div>
                        <h1 class="mb-0">Team Lead Dashboard</h1>
                        <p class="mb-0 opacity-75">Project Team Leader</p>
                    </div>
                </div>
                <h3>Selamat Datang, {{ $user->name }}!</h3>
                <p class="mb-0">Pimpin tim menuju kesuksesan proyek</p>
            </div>

            <!-- Stats Section -->
            <div class="stats-section">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-briefcase stat-icon text-primary"></i>
                            <h3 class="stat-number text-primary">3</h3>
                            <p class="stat-label">Active Projects</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-people stat-icon text-success"></i>
                            <h3 class="stat-number text-success">12</h3>
                            <p class="stat-label">Team Members</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-list-check stat-icon text-warning"></i>
                            <h3 class="stat-number text-warning">28</h3>
                            <p class="stat-label">Tasks to Review</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <i class="bi bi-trophy stat-icon text-info"></i>
                            <h3 class="stat-number text-info">92%</h3>
                            <p class="stat-label">Team Performance</p>
                        </div>
                    </div>
                </div>

                <!-- Team Lead Features -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center py-4">
                                <h5 class="mb-3">
                                    <i class="bi bi-diagram-3-fill me-2"></i>
                                    Team Leadership Center
                                </h5>
                                <p class="text-muted mb-4">
                                    Koordinasi tim, review progres, dan pastikan target tercapai
                                </p>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0"><strong>Team Management</strong></p>
                                        <small class="text-muted">Assign & coordinate tasks</small>
                                    </div>
                                    <div class="col-4">
                                        <i class="bi bi-clipboard-check text-primary" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0"><strong>Progress Review</strong></p>
                                        <small class="text-muted">Monitor & approve work</small>
                                    </div>
                                    <div class="col-4">
                                        <i class="bi bi-graph-up-arrow text-info" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0"><strong>Performance</strong></p>
                                        <small class="text-muted">Track team productivity</small>
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
</body>
</html>
