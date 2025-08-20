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

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: relative;
            background: transparent;
            border: none;
            color: var(--text-primary);
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 1rem;
        }

        .sidebar-toggle:hover {
            background: var(--border-light);
            color: var(--primary-color);
        }

        .sidebar-toggle i {
            font-size: 1.25rem;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle.active i {
            transform: rotate(180deg);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1002;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .top-nav {
                padding: 0.75rem 1rem;
            }
            
            .page-title {
                font-size: 1.25rem;
            }
            
            .top-nav-left {
                gap: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 75%;
                max-width: 300px;
            }
            
            .sidebar-header {
                padding: 1rem;
            }
            
            .sidebar-brand {
                font-size: 1.25rem;
                padding-right: 2.5rem;
            }
            
            .sidebar-brand i {
                font-size: 1.5rem;
            }
            
            .nav-link {
                padding: 0.75rem 0.875rem;
                margin: 0.125rem 0.75rem;
                font-size: 0.9rem;
            }
            
            .top-nav {
                padding: 0.5rem 0.75rem;
            }
            
            .page-title {
                font-size: 1.1rem;
            }
            
            .top-nav-left {
                gap: 0.5rem;
            }
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            position: relative;
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
            padding-right: 3rem;
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
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
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

        .top-nav-left {
            display: flex;
            align-items: center;
            gap: 1rem;
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
            padding: 0.75rem 1.25rem;
            background: linear-gradient(135deg, var(--background-white) 0%, var(--background-light) 100%);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .user-menu:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .user-avatar {
            width: 2.75rem;
            height: 2.75rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            border: 2px solid var(--background-white);
            box-shadow: var(--shadow-sm);
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Dark Mode Toggle Button */
        .dark-mode-toggle {
            position: relative;
            background: linear-gradient(135deg, var(--background-white) 0%, var(--background-light) 100%);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .dark-mode-toggle:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
            color: var(--primary-color);
        }

        .dark-mode-toggle:active {
            transform: translateY(0);
            box-shadow: var(--shadow-sm);
        }

        .dark-mode-toggle .dark-icon,
        .dark-mode-toggle .light-icon {
            position: absolute;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .dark-mode-toggle .dark-icon {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        .dark-mode-toggle .light-icon {
            opacity: 0;
            transform: scale(0.8) rotate(90deg);
        }

        /* Dark mode active state */
        body.dark .dark-mode-toggle {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            border-color: #6b7280;
            color: #fbbf24;
        }

        body.dark .dark-mode-toggle:hover {
            border-color: #fbbf24;
            color: #fbbf24;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);
        }

        body.dark .dark-mode-toggle .dark-icon {
            opacity: 0;
            transform: scale(0.8) rotate(-90deg);
        }

        body.dark .dark-mode-toggle .light-icon {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-greeting {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .welcome-info {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .welcome-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .welcome-item i {
            font-size: 1.1rem;
            color: var(--accent-light);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
        }

        .stat-icon.success {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        }

        .stat-icon.info {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
        }

        .stat-icon.danger {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .stat-change {
            font-size: 0.8rem;
            color: var(--accent-color);
            font-weight: 600;
        }

        /* Quick Actions */
        .quick-actions {
            background: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .quick-actions h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-actions h3 i {
            color: var(--primary-color);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: var(--background-light);
            border-radius: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .action-item:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(4px);
            text-decoration: none;
        }

        .action-item:hover .action-icon {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .action-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: white;
            transition: all 0.3s ease;
        }

        .action-text {
            font-weight: 500;
        }

        /* Recent Activity */
        .recent-activity {
            background: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
        }

        .recent-activity h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .recent-activity h3 i {
            color: var(--primary-color);
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-light);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--background-light);
            color: var(--primary-color);
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.8rem;
            color: var(--text-secondary);
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

        /* Global Page Loader */
        .page-loader-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .page-loader-overlay.active { display: flex; }

        .page-loader-box {
            background: var(--background-white);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .loader-logo {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            box-shadow: 0 6px 18px rgba(30, 58, 138, 0.25);
            animation: pulseGlow 1.4s ease-in-out infinite;
        }

        .loader-logo i { font-size: 1.2rem; }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); box-shadow: 0 6px 18px rgba(30, 58, 138, 0.25); }
            50% { transform: scale(1.06); box-shadow: 0 10px 24px rgba(16, 185, 129, 0.35); }
        }

        /* Button loading state */
        .btn.is-loading { pointer-events: none; opacity: 0.9; }

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

        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mobile-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1002;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
            
            .top-nav {
                padding: 0.75rem 1rem;
            }
            
            .page-title {
                font-size: 1.25rem;
            }
            
            .top-nav-left {
                gap: 0.75rem;
            }
        }

        /* Mobile Close Button */
        .mobile-close-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1003;
            backdrop-filter: blur(10px);
            font-size: 1rem;
        }

        .mobile-close-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.4);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .mobile-close-btn i {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .mobile-close-btn {
                display: flex;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 75%;
                max-width: 300px;
            }
            
            .top-nav {
                padding: 0.5rem 0.75rem;
            }
            
            .page-title {
                font-size: 1.1rem;
            }
            
            .top-nav-left {
                gap: 0.5rem;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Mobile Close Button -->
        <button class="mobile-close-btn" id="mobileCloseBtn" title="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
        
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
            
            <!-- Profile Link - Available for all users -->
            <a href="<?= base_url('profile') ?>" class="nav-link <?= strpos(current_url(), 'profile') !== false ? 'active' : '' ?>">
                <i class="bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>
            
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
                <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title"><?= $title ?? 'SIMAMANG' ?></h1>
            </div>
            
            <div class="top-nav-right d-flex align-items-center gap-2">
                <!-- Dark Mode Toggle Button -->
                <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                    <i class="bi bi-moon-fill dark-icon"></i>
                    <i class="bi bi-sun-fill light-icon"></i>
                </button>
                
                <div class="user-menu dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown" style="cursor: pointer;">
                                        <?php if (session()->get('foto_profil')): ?>
                    <img src="<?= base_url('photo.php?file=' . session()->get('foto_profil') . '&type=profile&v=' . time()) ?>"
                         alt="Foto Profil" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <?= strtoupper(substr(session()->get('nama') ?? 'U', 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?= session()->get('nama') ?? 'User' ?></span>
                        <span class="user-role"><?= ucfirst(session()->get('role') ?? 'Guest') ?></span>
                    </div>
                    
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('profile') ?>">
                            <i class="bi bi-person-circle me-2"></i>Profil Saya
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <?php $success = session()->getFlashdata('success'); $error = session()->getFlashdata('error'); ?>
            <!-- Flash toasts rendered via JS below to avoid duplicates and improve UX -->

            <!-- Main Content -->
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Top progress bar -->
    <div id="topProgress"></div>

    <!-- Global Page Loader -->
    <div id="pageLoader" class="page-loader-overlay" aria-hidden="true">
        <div class="page-loader-box">
            <div class="loader-logo"><i class="bi bi-graph-up-arrow"></i></div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div>
                    <div class="fw-semibold">Memuat SIMAMANG...</div>
                </div>
                <div class="text-muted small mt-1">Mohon tunggu sebentar</div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mobileCloseBtn = document.getElementById('mobileCloseBtn');
            
            // Check if sidebar state is saved in localStorage
            const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            // Initialize sidebar state
            if (window.innerWidth <= 768) {
                // Mobile: always start collapsed
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                sidebarToggle.classList.add('active');
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                // Desktop: check localStorage for initial state
                if (isSidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    sidebarToggle.classList.add('active');
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                    sidebarToggle.classList.remove('active');
                }
            }
            
            // Toggle sidebar function
            function toggleSidebar() {
                if (window.innerWidth <= 768) {
                    // Mobile behavior
                    const isOpen = sidebar.classList.contains('mobile-open');
                    if (isOpen) {
                        sidebar.classList.remove('mobile-open');
                        sidebar.classList.add('collapsed');
                        mobileOverlay.classList.remove('active');
                    } else {
                        sidebar.classList.add('mobile-open');
                        sidebar.classList.remove('collapsed');
                        mobileOverlay.classList.add('active');
                    }
                    sidebarToggle.classList.toggle('active');
                } else {
                    // Desktop behavior
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    sidebarToggle.classList.toggle('active');
                }
                
                // Save state to localStorage
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
            
            // Update toggle function
            sidebarToggle.addEventListener('click', toggleSidebar);
            
            // Close sidebar on mobile when clicking outside or overlay
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        if (sidebar.classList.contains('mobile-open')) {
                            sidebar.classList.remove('mobile-open');
                            sidebar.classList.add('collapsed');
                            mobileOverlay.classList.remove('active');
                            sidebarToggle.classList.add('active');
                            localStorage.setItem('sidebarCollapsed', 'true');
                        }
                    }
                }
            });

            // Close sidebar when clicking overlay
            mobileOverlay.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('mobile-open');
                    sidebar.classList.add('collapsed');
                    mobileOverlay.classList.remove('active');
                    sidebarToggle.classList.add('active');
                    localStorage.setItem('sidebarCollapsed', 'true');
                }
            });

            // Close sidebar when clicking close button
            mobileCloseBtn.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('mobile-open');
                    sidebar.classList.add('collapsed');
                    mobileOverlay.classList.remove('active');
                    sidebarToggle.classList.add('active');
                    localStorage.setItem('sidebarCollapsed', 'true');
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 768) {
                    // Auto-collapse on mobile
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    sidebarToggle.classList.add('active');
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    // Desktop: restore state from localStorage
                    const wasCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    if (wasCollapsed) {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('expanded');
                        sidebarToggle.classList.add('active');
                    } else {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('expanded');
                        sidebarToggle.classList.remove('active');
                    }
                }
            });
            
            // Initialize mobile state (guard if function is not defined)
            try { if (typeof handleMobileSidebar === 'function') { handleMobileSidebar(); } } catch (e) {}

            // Global loader helpers
            const pageLoader = document.getElementById('pageLoader');
            function showLoader() { pageLoader.classList.add('active'); }
            function hideLoader() { pageLoader.classList.remove('active'); }

            // Mark links with class .use-loader OR sidebar nav links to show loader on navigation
            document.querySelectorAll('a.use-loader, .sidebar a.nav-link').forEach(function(a){
                a.addEventListener('click', function(e){
                    // Skip if modifier keys or target is set (new tab/download)
                    if (e.metaKey || e.ctrlKey || a.target === '_blank' || a.hasAttribute('download')) return;
                    showLoader();
                });
            });

            // Forms that should show page loader on submit
            document.querySelectorAll('form[data-loader="page"]').forEach(function(form){
                form.addEventListener('submit', function(){ showLoader(); });
            });

            // Auto-hide loader on page show (bfcache)
            window.addEventListener('pageshow', function() { hideLoader(); });

            // Top progress bar basic behavior
            const topProgress = document.getElementById('topProgress');
            function startProgress(){ topProgress.style.width = '20%'; requestAnimationFrame(()=> topProgress.style.width = '70%'); }
            function endProgress(){ topProgress.style.width = '100%'; setTimeout(()=> topProgress.style.width = '0', 250); }
            document.querySelectorAll('a.use-loader, .sidebar a.nav-link').forEach(function(a){
                a.addEventListener('click', function(e){ if (!(e.metaKey||e.ctrlKey||a.target==='_blank')) startProgress(); });
            });
            document.querySelectorAll('form[data-loader="page"]').forEach(function(form){
                form.addEventListener('submit', function(){ startProgress(); });
            });
            window.addEventListener('pageshow', function(){ endProgress(); });

            // Dark mode toggle (persisted)
            const storedTheme = localStorage.getItem('theme');
            if (storedTheme === 'dark') document.body.classList.add('dark');
            
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function(){
                    document.body.classList.toggle('dark');
                    localStorage.setItem('theme', document.body.classList.contains('dark') ? 'dark' : 'light');
                });
            }

            // Auto-dismiss Bootstrap alerts after 3 seconds
            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(el){
                    try {
                        bootstrap.Alert.getOrCreateInstance(el).close();
                    } catch (e) {
                        el.remove();
                    }
                });
            }, 3000);

            // Toast container
            const toastContainer = document.createElement('div');
            toastContainer.className = 'position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = 2001;
            document.body.appendChild(toastContainer);

            // Flashdata toasts
            const flashSuccess = <?= json_encode($success ?? null) ?>;
            const flashError = <?= json_encode($error ?? null) ?>;
            function createToast(message, type) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = `
                  <div class="toast align-items-center text-bg-${type} border-0" role="status" aria-live="polite" aria-atomic="true">
                    <div class="d-flex">
                      <div class="toast-body">
                        ${type === 'success' ? '<i class="bi bi-check-circle-fill me-2"></i>' : '<i class="bi bi-exclamation-triangle-fill me-2"></i>'}
                        ${message}
                      </div>
                      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                  </div>`;
                const toastEl = wrapper.firstElementChild;
                toastContainer.appendChild(toastEl);
                const t = new bootstrap.Toast(toastEl, { delay: 3000 });
                t.show();
            }
            if (flashSuccess) createToast(flashSuccess, 'success');
            if (flashError) createToast(flashError, 'danger');
        });
    </script>
</body>
</html>
