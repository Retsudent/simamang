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
        
        .shadow { box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; }
        
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
<body class="bg-light">
    <!-- Navigation -->
    <?php if (session()->get('isLoggedIn')): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap mr-2"></i>
                <strong>SIMAMANG</strong>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (session()->get('role') === 'siswa'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('siswa/dashboard') ?>">
                                <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('siswa/input-log') ?>">
                                <i class="fas fa-plus mr-1"></i>Input Log
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('siswa/riwayat') ?>">
                                <i class="fas fa-history mr-1"></i>Riwayat
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('siswa/laporan') ?>">
                                <i class="fas fa-file-pdf mr-1"></i>Laporan
                            </a>
                        </li>
                    <?php elseif (session()->get('role') === 'pembimbing'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('pembimbing/dashboard') ?>">
                                <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('pembimbing/aktivitas-siswa') ?>">
                                <i class="fas fa-users mr-1"></i>Aktivitas Siswa
                            </a>
                        </li>
                    <?php elseif (session()->get('role') === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                                <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/kelola-siswa') ?>">
                                <i class="fas fa-user-graduate mr-1"></i>Kelola Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/kelola-pembimbing') ?>">
                                <i class="fas fa-user-tie mr-1"></i>Kelola Pembimbing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/laporan-magang') ?>">
                                <i class="fas fa-file-pdf mr-1"></i>Laporan
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user mr-1"></i><?= session()->get('nama') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog mr-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-4">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white py-4 mt-auto">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; SIMAMANG 2024</div>
                <div class="text-muted">
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
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
    </script>
</body>
</html>
