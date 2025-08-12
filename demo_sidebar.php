<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Sidebar SIMAMANG</title>
    
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

        /* Demo Content */
        .demo-card {
            background: white;
            border-radius: 0.5rem;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .demo-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-item {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border-left: 4px solid var(--sidebar-active);
        }

        .feature-item h5 {
            color: var(--sidebar-active);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <i class="fas fa-graduation-cap"></i>
                <span class="sidebar-brand-text">SIMAMANG</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <!-- Demo: Admin Menu -->
            <div class="nav-section">
                <div class="nav-section-title">Menu Utama</div>
                <div class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-link-text">Dashboard</span>
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Manajemen</div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-graduate"></i>
                        <span class="nav-link-text">Kelola Siswa</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-tie"></i>
                        <span class="nav-link-text">Kelola Pembimbing</span>
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">Laporan</div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-file-pdf"></i>
                        <span class="nav-link-text">Laporan Magang</span>
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
                <h1 class="page-title">Demo Sidebar Modern</h1>
            </div>
            
            <div class="header-right">
                <div class="user-dropdown">
                    <button class="user-button" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar">A</div>
                        <span>Admin Demo</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <div class="demo-card">
                <h2>🎉 Sidebar Modern SIMAMANG</h2>
                <p class="lead">Selamat datang di demo sidebar modern yang elegan dan responsif!</p>
                
                <div class="demo-features">
                    <div class="feature-item">
                        <h5><i class="fas fa-palette"></i> Desain Modern</h5>
                        <p>Menggunakan warna-warna modern dengan gradien dan shadow yang elegan</p>
                    </div>
                    
                    <div class="feature-item">
                        <h5><i class="fas fa-mobile-alt"></i> Responsif</h5>
                        <p>Otomatis menyesuaikan dengan ukuran layar mobile dan desktop</p>
                    </div>
                    
                    <div class="feature-item">
                        <h5><i class="fas fa-magic"></i> Animasi Smooth</h5>
                        <p>Transisi dan hover effect yang halus dan menarik</p>
                    </div>
                    
                    <div class="feature-item">
                        <h5><i class="fas fa-compress-arrows-alt"></i> Collapsible</h5>
                        <p>Bisa di-collapse untuk menghemat ruang layar</p>
                    </div>
                </div>
            </div>

            <div class="demo-card">
                <h3>🚀 Fitur Sidebar</h3>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Dark Theme:</strong> Warna gelap yang nyaman di mata</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Hover Effects:</strong> Animasi slide dan highlight saat hover</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Active State:</strong> Indikator menu yang sedang aktif</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Section Grouping:</strong> Menu dikelompokkan berdasarkan kategori</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Responsive Design:</strong> Otomatis collapse di mobile</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Smooth Transitions:</strong> Animasi yang halus dan profesional</li>
                </ul>
                
                <div class="mt-4">
                    <button class="btn btn-primary me-2" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i> Toggle Sidebar
                    </button>
                    <button class="btn btn-outline-secondary" onclick="showMobileDemo()">
                        <i class="fas fa-mobile-alt"></i> Mobile Demo
                    </button>
                </div>
            </div>
        </div>
    </div>

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
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function showMobileDemo() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            // Simulate mobile view
            if (window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                
                setTimeout(() => {
                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                }, 2000);
            }
        }
    </script>
</body>
</html>
