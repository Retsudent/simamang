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
    <!-- Dark Mode CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dark-mode.css') ?>">
    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/sidebar.css') ?>">
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

        /* Dark Theme */
        .theme-dark {
            --background-light: #0b1220;
            --background-white: #0f172a;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: #1e293b;
            --border-light: #0b1220;
        }

        .theme-dark .top-nav,
        .theme-dark .card,
        .theme-dark .quick-actions,
        .theme-dark .recent-activity,
        .theme-dark .content-wrapper {
            background: var(--background-white);
            color: var(--text-primary);
        }

        .theme-dark .nav-link { color: rgba(255,255,255,0.85); }
        .theme-dark .nav-link:hover { background-color: rgba(255,255,255,0.08); }
        .theme-dark .nav-link.active { box-shadow: none; }
        .theme-dark .user-menu { background: linear-gradient(135deg, var(--background-white) 0%, #0b1220 100%); border-color: var(--border-color); }
        .theme-dark .page-title { color: var(--text-primary); }

        /* Improve contrast for muted/secondary texts and UI in dark mode */
        .theme-dark .text-muted,
        .theme-dark small,
        .theme-dark .form-text { color: #a8b3c7 !important; }
        .theme-dark .text-secondary { color: var(--text-secondary) !important; }

        /* Profile page and plaintext fields */
        .theme-dark .form-control-plaintext { color: var(--text-primary) !important; }
        .theme-dark .card .form-label { color: var(--text-secondary); }
        .theme-dark h1, .theme-dark h5, .theme-dark h6 { color: var(--text-primary); }

        /* Breadcrumbs */
        .theme-dark .breadcrumb { background: transparent; }
        .theme-dark .breadcrumb .breadcrumb-item,
        .theme-dark .breadcrumb a { color: #b6c3d8; }
        .theme-dark .breadcrumb .breadcrumb-item.active { color: var(--text-secondary); }

        .theme-dark .dropdown-menu { background-color: #0f172a; border-color: #1e293b; color: var(--text-primary); z-index: 1100; }
        .theme-dark .dropdown-item { color: var(--text-primary); }
        .theme-dark .dropdown-item:hover { background-color: #1e293b; color: var(--text-primary); }

        .theme-dark .table { color: var(--text-primary); }
        .theme-dark .table thead th { border-color: #1e293b; color: #cbd5e1; }
        .theme-dark .table tbody td { border-color: #1e293b; }
        .theme-dark .table-hover tbody tr:hover { background-color: rgba(148,163,184,0.08); }

        .theme-dark .badge.bg-secondary { background-color: #334155 !important; color: #e2e8f0 !important; }
        .theme-dark .badge.bg-warning { color: #111827 !important; }

        .theme-dark .form-control,
        .theme-dark .form-select { background-color: #0b1220; color: var(--text-primary); border-color: #334155; }
        .theme-dark .form-control::placeholder { color: #94a3b8; opacity: 1; }

        /* Auto-hide notification animations */
        .alert {
            transition: all 0.3s ease-in-out;
        }
        
        .alert.fade {
            opacity: 0;
            transform: translateY(-10px);
        }
        
        .alert.fade-out {
            opacity: 0;
            transform: translateY(-20px);
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Progress bar for notifications */
        .alert {
            position: relative;
            overflow: hidden;
        }

        .alert::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: currentColor;
            animation: notification-progress 3s linear forwards;
        }

        @keyframes notification-progress {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* Hover pause animation */
        .alert:hover::after {
            animation-play-state: paused;
        }
        .theme-dark .form-select option { background-color: #0f172a; color: var(--text-primary); }

        /* Card and separators */
        .theme-dark .card { border-color: #1e293b; }
        .theme-dark hr { border-color: #1e293b; opacity: 1; }

        .theme-dark .btn-outline-secondary { color: #cbd5e1; border-color: #334155; }
        .theme-dark .btn-outline-secondary:hover { background-color: #334155; color: #e2e8f0; }

        .theme-dark .data-table-filter input { background-color: #0b1220; color: var(--text-primary); border-color: #334155; }

        /* Sidebar styles are now in sidebar.css */
            
            .top-nav {
            background: var(--background-white);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 1030;
        }

        .top-nav-left {
            display: flex;
            align-items: center;
                gap: 1rem;
            }
            
        .top-nav-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
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

        /* Data Table - lightweight enhancements */
        .data-table thead th { cursor: pointer; user-select: none; }
        .data-table thead th.sort-asc::after { content: ' \2191'; }
        .data-table thead th.sort-desc::after { content: ' \2193'; }
        .data-table-filter { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.75rem; }

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

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            max-width: 300px;
            width: 90%;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--border-light);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        .loading-text {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .loading-subtext {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Pulse animation for loading dots */
        .loading-dots {
            display: inline-flex;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .loading-dot {
            width: 8px;
            height: 8px;
            background: var(--primary-color);
            border-radius: 50%;
            animation: pulse 1.4s ease-in-out infinite both;
        }

        .loading-dot:nth-child(1) { animation-delay: -0.32s; }
        .loading-dot:nth-child(2) { animation-delay: -0.16s; }
        .loading-dot:nth-child(3) { animation-delay: 0s; }

        @keyframes pulse {
            0%, 80%, 100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
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

            .loading-container {
                padding: 1.5rem;
                max-width: 280px;
            }

            .loading-spinner {
                width: 50px;
                height: 50px;
            }

            .loading-text {
                font-size: 0.9rem;
            }

            .loading-subtext {
                font-size: 0.8rem;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Memuat Halaman</div>
            <div class="loading-subtext">Mohon tunggu sebentar</div>
            <div class="loading-dots">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <!-- Toast Container -->
    <div id="toastContainer" class="position-fixed p-3" style="z-index: 11000; right: 0; top: 0; max-width: 380px;"></div>

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
    
    <!-- Dark Mode Script -->
    <script src="<?= base_url('assets/js/dark-mode.js') ?>"></script>
    
    <!-- Sidebar Toggle Script -->
    <script src="<?= base_url('assets/js/sidebar-toggle.js') ?>"></script>
    
    <!-- Main Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Toast helper
            const toastContainer = document.getElementById('toastContainer');
            window.showToast = function(message, type = 'success') {
                const id = 't' + Date.now();
                const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'x-circle');
                const bg = type === 'success' ? 'bg-success' : (type === 'warning' ? 'bg-warning' : 'bg-danger');
                const text = type === 'warning' ? 'text-dark' : 'text-white';
                const el = document.createElement('div');
                el.className = `toast align-items-center ${bg} ${text} border-0 show mb-2`;
                el.id = id;
                el.setAttribute('role', 'alert');
                el.innerHTML = `<div class="d-flex">
                    <div class="toast-body"><i class="bi bi-${icon} me-2"></i>${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>`;
                toastContainer.appendChild(el);
                setTimeout(() => { el.remove(); }, 4000);
            }
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Inline validation helper (basic)
            document.body.addEventListener('input', function(e) {
                const input = e.target.closest('input, textarea, select');
                if (!input) return;
                if (input.hasAttribute('required')) {
                    if (input.value && input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                    }
                }
            });

            // Auto-hide notifications after 3 seconds
            function autoHideNotifications() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        if (alert && alert.parentNode) {
                            alert.classList.add('fade');
                            setTimeout(() => {
                                if (alert && alert.parentNode) {
                                    alert.classList.add('fade-out');
                                    setTimeout(() => {
                                        if (alert && alert.parentNode) {
                                            alert.remove();
                                        }
                                    }, 300);
                                }
                            }, 300);
                        }
                    }, 3000);
                });
            }

            // Call auto-hide function when page loads
            autoHideNotifications();

            // Auto-refresh profile photos every 10 seconds
            function refreshProfilePhotos() {
                const profileImages = document.querySelectorAll('img[src*="photo.php"], img[src*="default-avatar"]');
                profileImages.forEach(img => {
                    if (img.src.includes('photo.php')) {
                        // Add timestamp to force refresh
                        const separator = img.src.includes('?') ? '&' : '?';
                        img.src = img.src + separator + 'v=' + Date.now();
                    }
                });
            }

            // Refresh profile photos every 10 seconds
            setInterval(refreshProfilePhotos, 10000);

            // Also refresh when page becomes visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    refreshProfilePhotos();
                }
            });

            // Function to show notification with auto-hide
            window.showNotification = function(message, type = 'success', duration = 3000) {
                const alertContainer = document.querySelector('.content-wrapper');
                if (!alertContainer) return;

                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.setAttribute('role', 'alert');
                
                const icon = type === 'success' ? 'check-circle-fill' : 
                            type === 'error' ? 'exclamation-triangle-fill' : 
                            type === 'warning' ? 'exclamation-triangle-fill' : 'info-circle-fill';
                
                alertDiv.innerHTML = `
                    <i class="bi bi-${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                // Insert at the top of content wrapper
                alertContainer.insertBefore(alertDiv, alertContainer.firstChild);

                // Auto-hide after specified duration
                setTimeout(() => {
                    if (alertDiv && alertDiv.parentNode) {
                        alertDiv.classList.add('fade');
                        setTimeout(() => {
                            if (alertDiv && alertDiv.parentNode) {
                                alertDiv.classList.add('fade-out');
                                setTimeout(() => {
                                    if (alertDiv && alertDiv.parentNode) {
                                        alertDiv.remove();
                                    }
                                }, 300);
                            }
                        }, 300);
                    }
                }, duration);
            };

            // Loading functions
            function showLoading(message = 'Memuat Halaman') {
                const loadingText = loadingOverlay.querySelector('.loading-text');
                loadingText.textContent = message;
                loadingOverlay.classList.add('active');
            }

            function hideLoading() {
                loadingOverlay.classList.remove('active');
            }

            // Show loading on page load
            showLoading('Memuat Halaman');
            
            // Hide loading when page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(hideLoading, 500); // Small delay for smooth transition
            });

            // Show loading on navigation (but exclude specific forms)
            document.addEventListener('click', function(e) {
                const target = e.target.closest('a');
                if (target && target.href && !target.href.includes('javascript:') && !target.href.includes('#')) {
                    // Don't show loading for logout or specific actions
                    if (!target.href.includes('logout') && 
                        !target.href.includes('photo.php') && 
                        !target.href.includes('generate-pdf') &&
                        !target.href.includes('save-log') &&
                        !target.href.includes('update-photo') &&
                        !target.href.includes('change-password')) {
                        showLoading('Memuat Halaman');
                    }
                }
            });

            // Don't show loading for form submissions (to avoid interfering with file uploads, log input, etc.)
            document.addEventListener('submit', function(e) {
                const form = e.target;
                // Don't show loading for specific forms that need to work without interruption
                if (form.id === 'photoForm' || 
                    form.id === 'logForm' || 
                    form.id === 'laporanForm' ||
                    form.id === 'reportForm' ||
                    form.id === 'passwordForm' ||
                    form.classList.contains('no-loading')) {
                    return;
                }
                showLoading('Memproses Data');
            });

            // Show loading for quick report buttons (but not for form submissions)
            document.addEventListener('click', function(e) {
                const target = e.target.closest('a');
                if (target && target.href) {
                    // Show loading for quick report generation
                    if (target.href.includes('generate-laporan-rapid') || 
                        target.href.includes('generate-pdf')) {
                        showLoading('Membuat Laporan PDF');
                    }
                }

                // Don't show loading for modal triggers
                if (target && target.getAttribute('data-bs-toggle') === 'modal') {
                    return;
                }
            });

            // Data Table: basic sort + filter + export CSV
            function initDataTables() {
                document.querySelectorAll('table.data-table').forEach(function(table) {
                    // Inject filter bar
                    const wrapper = table.closest('.table-responsive') || table.parentElement;
                    const bar = document.createElement('div');
                    bar.className = 'data-table-filter';
                    bar.innerHTML = '<input type="text" class="form-control form-control-sm" placeholder="Cari..." style="max-width:220px;">\n<button class="btn btn-sm btn-outline-secondary export-csv"><i class="bi bi-download"></i> CSV</button>';
                    wrapper.parentNode.insertBefore(bar, wrapper);

                    // Search filter
                    const input = bar.querySelector('input');
                    input.addEventListener('input', function() {
                        const q = this.value.toLowerCase();
                        Array.from(table.tBodies[0].rows).forEach(function(row) {
                            const text = row.innerText.toLowerCase();
                            row.style.display = text.includes(q) ? '' : 'none';
                        });
                    });

                    // Sorting
                    Array.from(table.tHead.rows[0].cells).forEach(function(th, idx) {
                        th.addEventListener('click', function() {
                            const rows = Array.from(table.tBodies[0].rows);
                            const current = th.classList.contains('sort-asc') ? 'asc' : th.classList.contains('sort-desc') ? 'desc' : null;
                            Array.from(table.tHead.rows[0].cells).forEach(c => c.classList.remove('sort-asc','sort-desc'));
                            const newDir = current === 'asc' ? 'desc' : 'asc';
                            th.classList.add(newDir === 'asc' ? 'sort-asc' : 'sort-desc');
                            rows.sort(function(a,b){
                                const ta = (a.cells[idx]?.innerText || '').trim().toLowerCase();
                                const tb = (b.cells[idx]?.innerText || '').trim().toLowerCase();
                                if (!isNaN(ta) && !isNaN(tb)) { return (newDir==='asc'?1:-1) * (parseFloat(ta)-parseFloat(tb)); }
                                return (newDir==='asc'?1:-1) * ta.localeCompare(tb);
                            });
                            rows.forEach(r => table.tBodies[0].appendChild(r));
                        });
                    });

                    // Export CSV
                    bar.querySelector('.export-csv').addEventListener('click', function() {
                        const rows = [];
                        const trs = table.querySelectorAll('tr');
                        trs.forEach(tr => {
                            const cells = Array.from(tr.querySelectorAll('th,td')).map(td => '"' + (td.innerText||'').replace(/"/g,'""') + '"');
                            rows.push(cells.join(','));
                        });
                        const blob = new Blob([rows.join('\n')], {type: 'text/csv;charset=utf-8;'});
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url; a.download = 'data.csv'; a.click();
                        URL.revokeObjectURL(url);
                        showToast && showToast('Berhasil ekspor CSV','success');
                    });
                });
            }

            initDataTables();
            
            // Sidebar toggle is now handled by sidebar-toggle.js

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

            // Dark mode is now handled by dark-mode.js

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
