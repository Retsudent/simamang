<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SIMAMANG - Sistem Monitoring Aktivitas Magang' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --sidebar-active: #3498db;
            --sidebar-text: #ecf0f1;
            --sidebar-text-muted: #bdc3c7;
            --content-bg: #f8f9fa;
            --header-bg: #ffffff;
            --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        body {
            background-color: var(--content-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            box-shadow: var(--shadow);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-brand {
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .sidebar-brand i {
            font-size: 2rem;
            color: var(--sidebar-active);
        }

        .sidebar-brand-text {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-brand-text {
            opacity: 0;
            display: none;
        }

        .sidebar-nav {
            padding: 1rem 0;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 120px); /* Subtract header height */
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            color: var(--sidebar-text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0 1.5rem 0.5rem;
            margin-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar.collapsed .nav-section-title {
            display: none;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0;
            position: relative;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: var(--sidebar-text);
            transform: translateX(5px);
        }

        .nav-link.active {
            background-color: var(--sidebar-active);
            color: white;
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: #ffffff;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .nav-link-text {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-link-text {
            opacity: 0;
            display: none;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        /* Top Header */
        .top-header {
            background: var(--header-bg);
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-dropdown {
            position: relative;
        }

        .user-button {
            background: none;
            border: none;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-button:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--sidebar-active);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #495057;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 0.5rem;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem;
        }

        /* User Section in Sidebar */
        .user-section {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
        }

        .user-section .nav-link {
            color: var(--sidebar-text-muted);
        }

        .user-section .nav-link:hover {
            color: var(--sidebar-text);
            background-color: var(--sidebar-hover);
        }

        .user-section .nav-link:first-child {
            color: var(--sidebar-active);
            font-weight: 600;
        }

        /* User Info in Header */
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-name {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none;
            }
        }

        /* Utility Classes */
        .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
        
        .text-gray-100 { color: #f8f9fc !important; }
        .text-gray-200 { color: #eaecf4 !important; }
        .text-gray-300 { color: #dddfeb !important; }
        .text-gray-400 { color: #d1d3e2 !important; }
        .text-gray-500 { color: #b7b9cc !important; }
        .text-gray-600 { color: #858796 !important; }
        .text-gray-700 { color: #6e707e !important; }
        .text-gray-800 { color: #5a5c69 !important; }
        .text-gray-900 { color: #3a3b45 !important; }
        
        .bg-gradient-primary { background-color: #4e73df; background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%); }
        .bg-gradient-success { background-color: #1cc88a; background-image: linear-gradient(180deg, #1cc88a 10%, #13855c 100%); }
        .bg-gradient-info { background-color: #36b9cc; background-image: linear-gradient(180deg, #36b9cc 10%, #258391 100%); }
        .bg-gradient-warning { background-color: #f6c23e; background-image: linear-gradient(180deg, #f6c23e 10%, #dda20a 100%); }
        .bg-gradient-danger { background-color: #e74a3b; background-image: linear-gradient(180deg, #e74a3b 10%, #be2617 100%); }
        
        .shadow { box-shadow: var(--shadow) !important; }
        
        .card-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
        
        .btn-block { display: block; width: 100%; }
        
        .table-responsive { overflow-x: auto; }
        
        .badge-pill { border-radius: 10rem; }
        
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .alert { border-radius: 0.35rem; }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .btn-success {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }
        
        .btn-success:hover {
            background-color: #17a673;
            border-color: #169b6b;
        }
        
        .btn-info {
            background-color: #36b9cc;
            border-color: #36b9cc;
        }
        
        .btn-info:hover {
            background-color: #2c9faf;
            border-color: #2a96a5;
        }
        
        .btn-warning {
            background-color: #f6c23e;
            border-color: #f6c23e;
        }
        
        .btn-warning:hover {
            background-color: #f4b30d;
            border-color: #f4b30d;
        }
        
        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
        }
        
        .btn-danger:hover {
            background-color: #e02e1e;
            border-color: #d52a1e;
        }
        
        .btn-secondary {
            background-color: #858796;
            border-color: #858796;
        }
        
        .btn-secondary:hover {
            background-color: #717384;
            border-color: #6b6d7d;
        }
    </style>
</head>
<body>
    <?php if (session()->get('isLoggedIn')): ?>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <i class="fas fa-graduation-cap"></i>
                <span class="sidebar-brand-text">SIMAMANG</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <?php if (session()->get('role') === 'siswa'): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Menu Utama</div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('siswa/dashboard') ? 'active' : '' ?>" href="<?= base_url('siswa/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('siswa/input-log') ? 'active' : '' ?>" href="<?= base_url('siswa/input-log') ?>">
                            <i class="fas fa-plus-circle"></i>
                            <span class="nav-link-text">Input Log</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('siswa/riwayat') ? 'active' : '' ?>" href="<?= base_url('siswa/riwayat') ?>">
                            <i class="fas fa-history"></i>
                            <span class="nav-link-text">Riwayat</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('siswa/laporan') ? 'active' : '' ?>" href="<?= base_url('siswa/laporan') ?>">
                            <i class="fas fa-file-pdf"></i>
                            <span class="nav-link-text">Laporan</span>
                        </a>
                    </div>
                </div>
            <?php elseif (session()->get('role') === 'pembimbing'): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Menu Utama</div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('pembimbing/dashboard') ? 'active' : '' ?>" href="<?= base_url('pembimbing/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('pembimbing/aktivitas-siswa') ? 'active' : '' ?>" href="<?= base_url('pembimbing/aktivitas-siswa') ?>">
                            <i class="fas fa-users"></i>
                            <span class="nav-link-text">Aktivitas Siswa</span>
                        </a>
                    </div>
                </div>
            <?php elseif (session()->get('role') === 'admin'): ?>
                <div class="nav-section">
                    <div class="nav-section-title">Menu Utama</div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('admin/dashboard') ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Manajemen</div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('admin/kelola-siswa') ? 'active' : '' ?>" href="<?= base_url('admin/kelola-siswa') ?>">
                            <i class="fas fa-user-graduate"></i>
                            <span class="nav-link-text">Kelola Siswa</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('admin/kelola-pembimbing') ? 'active' : '' ?>" href="<?= base_url('admin/kelola-pembimbing') ?>">
                            <i class="fas fa-user-tie"></i>
                            <span class="nav-link-text">Kelola Pembimbing</span>
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Laporan</div>
                    <div class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('admin/laporan-magang') ? 'active' : '' ?>" href="<?= base_url('admin/laporan-magang') ?>">
                            <i class="fas fa-file-pdf"></i>
                            <span class="nav-link-text">Laporan Magang</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- User Profile Section -->
            <div class="nav-section user-section">
                <div class="nav-section-title">Akun</div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-circle"></i>
                        <span class="nav-link-text"><?= session()->get('nama') ?></span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-cog"></i>
                        <span class="nav-link-text">Profile</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="<?= base_url('logout') ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-link-text">Logout</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?= $title ?? 'Dashboard' ?></h1>
            </div>
            
            <div class="header-right">
                <!-- User info display only -->
                <div class="user-info">
                    <span class="user-name"><?= session()->get('nama') ?></span>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </div>
    </div>
    <?php else: ?>
    <!-- Login Page Content -->
    <main class="py-4">
        <?= $this->renderSection('content') ?>
    </main>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Sidebar Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                });
            }
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Enable tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Mobile sidebar toggle
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        });
    </script>
</body>
</html>
