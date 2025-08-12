<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-graduation-cap fa-5x text-primary"></i>
                </div>
                <h1 class="display-4 font-weight-bold text-primary mb-3">SIMAMANG</h1>
                <h2 class="h4 text-muted mb-4">Sistem Monitoring Aktivitas Magang</h2>
                <p class="lead text-gray-600 mb-5">
                    Platform digital untuk memantau, mencatat, dan melaporkan aktivitas magang siswa 
                    dengan sistem review dan komentar dari pembimbing yang terintegrasi.
                </p>
                
                <?php if (!session()->get('isLoggedIn')): ?>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= base_url('login') ?>" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="<?= base_url('register') ?>" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-center gap-3">
                        <?php if (session()->get('role') === 'admin'): ?>
                            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                            </a>
                        <?php elseif (session()->get('role') === 'pembimbing'): ?>
                            <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Pembimbing
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-info btn-lg px-4">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Siswa
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="text-center font-weight-bold text-gray-800 mb-5">Fitur Utama</h3>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 border-left-primary">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-list fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold text-primary">Log Aktivitas Harian</h5>
                    <p class="card-text text-muted">
                        Siswa dapat mencatat aktivitas magang setiap hari dengan detail waktu, 
                        uraian kegiatan, dan upload bukti pendukung.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 border-left-success">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-comments fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title font-weight-bold text-success">Review & Komentar</h5>
                    <p class="card-text text-muted">
                        Pembimbing dapat memberikan feedback, komentar, dan validasi 
                        terhadap log aktivitas siswa secara real-time.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 border-left-info">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-file-pdf fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title font-weight-bold text-info">Laporan Otomatis</h5>
                    <p class="card-text text-muted">
                        Generate laporan magang dalam format PDF dengan data lengkap 
                        aktivitas, komentar pembimbing, dan statistik progress.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 border-left-warning">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-users-cog fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title font-weight-bold text-warning">Manajemen User</h5>
                    <p class="card-text text-muted">
                        Admin dapat mengelola data siswa dan pembimbing, 
                        memantau progress, dan mengatur hak akses sistem.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 border-left-danger">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-danger"></i>
                    </div>
                    <h5 class="card-title font-weight-bold text-danger">Monitoring Real-time</h5>
                    <p class="card-text text-muted">
                        Dashboard yang menampilkan statistik dan progress magang 
                        secara real-time untuk semua role pengguna.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100 border-left-secondary">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-secondary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold text-secondary">Responsive Design</h5>
                    <p class="card-text text-muted">
                        Interface yang responsif dan mudah digunakan di berbagai 
                        perangkat desktop, tablet, dan mobile.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h4 class="m-0 font-weight-bold text-primary text-center">
                        <i class="fas fa-cogs mr-2"></i>Bagaimana SIMAMANG Bekerja
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="mb-3">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <span class="h4 mb-0">1</span>
                                </div>
                            </div>
                            <h6 class="font-weight-bold text-primary">Siswa Input Log</h6>
                            <p class="text-muted small">
                                Siswa mencatat aktivitas harian dengan detail waktu, uraian, dan bukti
                            </p>
                        </div>
                        
                        <div class="col-md-3 text-center mb-4">
                            <div class="mb-3">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <span class="h4 mb-0">2</span>
                                </div>
                            </div>
                            <h6 class="font-weight-bold text-success">Pembimbing Review</h6>
                            <p class="text-muted small">
                                Pembimbing memeriksa log dan memberikan komentar serta validasi
                            </p>
                        </div>
                        
                        <div class="col-md-3 text-center mb-4">
                            <div class="mb-3">
                                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <span class="h4 mb-0">3</span>
                                </div>
                            </div>
                            <h6 class="font-weight-bold text-info">Monitoring Progress</h6>
                            <p class="text-muted small">
                                Admin dan pembimbing memantau progress magang secara real-time
                            </p>
                        </div>
                        
                        <div class="col-md-3 text-center mb-4">
                            <div class="mb-3">
                                <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <span class="h4 mb-0">4</span>
                                </div>
                            </div>
                            <h6 class="font-weight-bold text-warning">Generate Laporan</h6>
                            <p class="text-muted small">
                                Sistem menghasilkan laporan PDF lengkap untuk keperluan administrasi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="text-center font-weight-bold text-gray-800 mb-5">Keunggulan SIMAMANG</h3>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold text-success">Efisiensi Waktu</h6>
                    <p class="text-muted">
                        Otomatisasi proses pencatatan dan pelaporan menghemat waktu 
                        siswa, pembimbing, dan admin.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold text-success">Transparansi Data</h6>
                    <p class="text-muted">
                        Semua aktivitas dan progress magang dapat dipantau 
                        secara real-time oleh semua pihak terkait.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold text-success">Kualitas Feedback</h6>
                    <p class="text-muted">
                        Sistem komentar dan review memastikan siswa mendapat 
                        feedback berkualitas untuk pengembangan diri.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold text-success">Laporan Terstruktur</h6>
                    <p class="text-muted">
                        Laporan otomatis dengan format standar memudahkan 
                        proses administrasi dan evaluasi magang.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-left-primary">
                <div class="card-body text-center py-5">
                    <h4 class="font-weight-bold text-primary mb-3">
                        Siap Memulai Perjalanan Magang Digital?
                    </h4>
                    <p class="text-muted mb-4">
                        Bergabunglah dengan SIMAMANG dan rasakan kemudahan dalam 
                        mengelola aktivitas magang Anda.
                    </p>
                    
                    <?php if (!session()->get('isLoggedIn')): ?>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= base_url('login') ?>" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login Sekarang
                            </a>
                            <a href="<?= base_url('register') ?>" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= base_url('logout') ?>" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
.border-left-secondary { border-left: 0.25rem solid #858796 !important; }

.gap-3 { gap: 1rem; }

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.rounded-circle {
    border-radius: 50% !important;
}
</style>

<?= $this->endSection() ?>
