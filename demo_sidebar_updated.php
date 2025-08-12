<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Sidebar Updated - SIMAMANG</title>
    
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
            height: calc(100vh - 120px);
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

        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .highlight-box h3 {
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
            
            <!-- User Profile Section - NEW! -->
            <div class="nav-section user-section">
                <div class="nav-section-title">Akun</div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-circle"></i>
                        <span class="nav-link-text">Admin Demo</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-cog"></i>
                        <span class="nav-link-text">Profile</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="#">
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
                <h1 class="page-title">Sidebar Updated - Profile & Logout Moved!</h1>
            </div>
            
            <div class="header-right">
                <!-- User info display only - NO MORE DROPDOWN! -->
                <div class="user-info">
                    <span class="user-name">Admin Demo</span>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <div class="highlight-box">
                <h3>🎉 Update Berhasil!</h3>
                <p class="lead mb-0">Menu Profile dan Logout telah dipindahkan dari top header ke sidebar bagian bawah.</p>
            </div>

            <div class="demo-card">
                <h2>🔄 Perubahan yang Dilakukan</h2>
                <p class="lead">Berikut adalah perubahan yang telah dibuat pada sidebar SIMAMANG:</p>
                
                <div class="demo-features">
                    <div class="feature-item">
                        <h5><i class="fas fa-arrow-down"></i> Menu Dipindahkan</h5>
                        <p>Profile dan Logout sekarang berada di bagian bawah sidebar dalam section "Akun"</p>
                    </div>
                    
                    <div class="feature-item">
                        <h5><i class="fas fa-user-circle"></i> User Section Baru</h5>
                        <p>Section khusus untuk menu akun dengan styling yang berbeda dan posisi yang tepat</p>
                    </div>
                    
                    <div class="feature-item">
                        <h5><i class="fas fa-header"></i> Header Disederhanakan</h5>
                        <p>Top header sekarang hanya menampilkan nama user tanpa dropdown menu</p>
                    </div>
                    
                    <div class="feature-item">
                        <h5><i class="fas fa-palette"></i> Styling Khusus</h5>
                        <p>User section memiliki border atas dan styling yang membedakannya dari menu utama</p>
                    </div>
                </div>
            </div>

            <div class="demo-card">
                <h3>📱 Struktur Sidebar Baru</h3>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Menu Utama (Atas):</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-tachometer-alt text-primary me-2"></i>Dashboard</li>
                            <li><i class="fas fa-user-graduate text-info me-2"></i>Kelola Siswa</li>
                            <li><i class="fas fa-user-tie text-success me-2"></i>Kelola Pembimbing</li>
                            <li><i class="fas fa-file-pdf text-warning me-2"></i>Laporan Magang</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Section Akun (Bawah):</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-user-circle text-primary me-2"></i><strong>Admin Demo</strong></li>
                            <li><i class="fas fa-user-cog text-secondary me-2"></i>Profile</li>
                            <li><i class="fas fa-sign-out-alt text-danger me-2"></i>Logout</li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button class="btn btn-primary me-2" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i> Toggle Sidebar
                    </button>
                    <button class="btn btn-outline-success" onclick="showUserSection()">
                        <i class="fas fa-user"></i> Highlight User Section
                    </button>
                </div>
            </div>

            <div class="demo-card">
                <h3>✅ Keuntungan Desain Baru</h3>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Lebih Terorganisir:</strong> Menu akun terpisah dari menu utama</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Posisi Logis:</strong> Profile dan Logout di bagian bawah sidebar</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Header Bersih:</strong> Top header tidak lagi berantakan dengan dropdown</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Konsistensi:</strong> Semua menu navigasi sekarang ada di sidebar</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>User Experience:</strong> Lebih mudah menemukan menu akun</li>
                    <li><i class="fas fa-check text-success me-2"></i> <strong>Responsive:</strong> Tetap berfungsi dengan baik di mobile</li>
                </ul>
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

        function showUserSection() {
            const userSection = document.querySelector('.user-section');
            userSection.style.background = 'rgba(52, 152, 219, 0.1)';
            userSection.style.borderRadius = '0.5rem';
            
            setTimeout(() => {
                userSection.style.background = '';
                userSection.style.borderRadius = '';
            }, 2000);
        }
    </script>
</body>
</html>
