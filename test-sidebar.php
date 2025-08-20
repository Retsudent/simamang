<?php
// Test file for sidebar toggle functionality
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Sidebar Toggle - SIMAMANG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/dark-mode.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    
    <style>
        body {
            padding: 0;
            margin: 0;
            transition: all 0.3s ease;
        }
        .test-content {
            padding: 2rem;
        }
        .test-card {
            margin: 1rem 0;
            transition: all 0.3s ease;
        }
        .device-info {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <!-- Device Info -->
    <div class="device-info">
        <div>Width: <span id="deviceWidth">-</span>px</div>
        <div>Device: <span id="deviceType">-</span></div>
        <div>Sidebar: <span id="sidebarState">-</span></div>
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
            <a href="#" class="sidebar-brand">
                <i class="bi bi-graph-up-arrow"></i>
                <span>SIMAMANG</span>
            </a>
        </div>
        
        <div class="sidebar-nav">
            <a href="#" class="nav-link active">
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-people"></i>
                <span>Kelola Siswa</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-person-badge"></i>
                <span>Kelola Pembimbing</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-journal-text"></i>
                <span>Laporan Magang</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-gear"></i>
                <span>Pengaturan</span>
            </a>
            <a href="#" class="nav-link">
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
                <h1 class="page-title">Test Sidebar Toggle</h1>
            </div>
            
            <div class="top-nav-right d-flex align-items-center gap-2">
                <!-- Dark Mode Toggle Button -->
                <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                    <i class="bi bi-moon-fill dark-icon"></i>
                    <i class="bi bi-sun-fill light-icon"></i>
                </button>
                
                <div class="user-menu dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                            T
                        </div>
                    </div>
                    <div class="user-info">
                        <span class="user-name">Test User</span>
                        <span class="user-role">Admin</span>
                    </div>
                    
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-person-circle me-2"></i>Profil Saya
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="test-content">
                <h2>Test Sidebar Toggle Functionality</h2>
                <p>Ini adalah halaman test untuk memverifikasi sidebar toggle berfungsi dengan baik di semua device.</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card test-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Test Card 1</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Test card untuk memverifikasi sidebar toggle.</p>
                                <button class="btn btn-primary">Test Button</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card test-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Test Card 2</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">Card kedua untuk testing.</p>
                                <button class="btn btn-secondary">Test Button</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card test-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Test Table</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>Admin</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td>User</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <strong>Petunjuk Test:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Klik tombol hamburger menu (☰) untuk toggle sidebar</li>
                        <li>Test di berbagai ukuran layar (resize browser)</li>
                        <li>Test di mobile device atau mobile view</li>
                        <li>Pastikan sidebar terbuka/tertutup dengan smooth</li>
                        <li>Test dark mode toggle juga</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script src="assets/js/dark-mode.js"></script>
    <script src="assets/js/sidebar-toggle.js"></script>
    
    <!-- Test Script -->
    <script>
        // Update device info
        function updateDeviceInfo() {
            const width = window.innerWidth;
            const deviceType = width <= 576 ? 'Mobile' : width <= 768 ? 'Tablet' : 'Desktop';
            const sidebarState = document.getElementById('sidebar').classList.contains('collapsed') ? 'Collapsed' : 'Open';
            
            document.getElementById('deviceWidth').textContent = width;
            document.getElementById('deviceType').textContent = deviceType;
            document.getElementById('sidebarState').textContent = sidebarState;
        }
        
        // Update on load and resize
        window.addEventListener('load', updateDeviceInfo);
        window.addEventListener('resize', updateDeviceInfo);
        
        // Update every second for sidebar state
        setInterval(updateDeviceInfo, 1000);
        
        // Toast helper for testing
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
            document.getElementById('toastContainer').appendChild(el);
            setTimeout(() => { el.remove(); }, 4000);
        };
    </script>
</body>
</html>

