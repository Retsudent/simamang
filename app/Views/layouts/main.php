<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIMAMANG - Sistem Monitoring Aktivitas Magang' ?></title>
    
    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e3a8a;
            --primary-light: #3b82f6;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --accent-color: #10b981;
            --accent-light: #34d399;
            --background-light: #f8fafc;
            --background-white: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background-light);
            color: var(--text-primary);
            line-height: 1.6;
            font-weight: 400;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-lg);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .sidebar-brand i {
            font-size: 2rem;
            color: var(--accent-light);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            font-weight: 500;
            gap: 0.75rem;
            margin: 0.25rem 1rem;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
            color: white;
            box-shadow: var(--shadow-md);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
        }

        .top-nav {
            background: var(--background-white);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: var(--background-light);
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--background-light);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            border-radius: 0.75rem;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
            color: white;
        }

        /* Forms */
        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border-left: 4px solid var(--accent-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            .content-wrapper {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="<?= base_url() ?>" class="sidebar-brand">
                <i class="bi bi-graph-up-arrow"></i>
                <span>SIMAMANG</span>
            </a>
        </div>
        
        <div class="sidebar-nav">
            <?php if (session()->get('role') === 'siswa'): ?>
                <a href="<?= base_url('siswa/dashboard') ?>" class="nav-link <?= current_url() == base_url('siswa/dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('siswa/input-log') ?>" class="nav-link <?= current_url() == base_url('siswa/input-log') ? 'active' : '' ?>">
                    <i class="bi bi-plus-circle"></i>
                    <span>Input Log Aktivitas</span>
                </a>
                <a href="<?= base_url('siswa/riwayat') ?>" class="nav-link <?= current_url() == base_url('siswa/riwayat') ? 'active' : '' ?>">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat Aktivitas</span>
                </a>
                <a href="<?= base_url('siswa/laporan') ?>" class="nav-link <?= current_url() == base_url('siswa/laporan') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span>Cetak Laporan</span>
                </a>
            <?php elseif (session()->get('role') === 'pembimbing'): ?>
                <a href="<?= base_url('pembimbing/dashboard') ?>" class="nav-link <?= current_url() == base_url('pembimbing/dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="nav-link <?= current_url() == base_url('pembimbing/aktivitas-siswa') ? 'active' : '' ?>">
                    <i class="bi bi-people"></i>
                    <span>Lihat Aktivitas Siswa</span>
                </a>
                <a href="<?= base_url('pembimbing/komentar') ?>" class="nav-link <?= current_url() == base_url('pembimbing/komentar') ? 'active' : '' ?>">
                    <i class="bi bi-chat-dots"></i>
                    <span>Komentar & Validasi</span>
                </a>
            <?php elseif (session()->get('role') === 'admin'): ?>
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= current_url() == base_url('admin/dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/kelola-siswa') ?>" class="nav-link <?= current_url() == base_url('admin/kelola-siswa') ? 'active' : '' ?>">
                    <i class="bi bi-person-badge"></i>
                    <span>Kelola Siswa</span>
                </a>
                <a href="<?= base_url('admin/kelola-pembimbing') ?>" class="nav-link <?= current_url() == base_url('admin/kelola-pembimbing') ? 'active' : '' ?>">
                    <i class="bi bi-person-workspace"></i>
                    <span>Kelola Pembimbing</span>
                </a>
                <a href="<?= base_url('admin/laporan-magang') ?>" class="nav-link <?= current_url() == base_url('admin/laporan-magang') ? 'active' : '' ?>">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Magang</span>
                </a>
            <?php endif; ?>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem;">
            
            <a href="<?= base_url('logout') ?>" class="nav-link">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="top-nav-left">
                <h1 class="page-title"><?= $title ?? 'SIMAMANG' ?></h1>
            </div>
            
            <div class="top-nav-right">
                <div class="user-menu">
                    <div class="user-avatar">
                        <?= strtoupper(substr(session()->get('nama') ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?= session()->get('nama') ?? 'User' ?></span>
                        <span class="user-role"><?= ucfirst(session()->get('role') ?? 'Guest') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
