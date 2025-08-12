<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
                <div class="text-right">
                    <p class="mb-0 text-muted">Selamat datang,</p>
                    <h5 class="mb-0"><?= session()->get('nama') ?></h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Siswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalSiswa ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pembimbing</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalPembimbing ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Log Aktivitas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalLog ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Log Menunggu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $logPending ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('admin/kelola-siswa') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-user-graduate mr-2"></i>Kelola Siswa
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('admin/kelola-pembimbing') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-user-tie mr-2"></i>Kelola Pembimbing
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('admin/laporan-magang') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-file-pdf mr-2"></i>Laporan Magang
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-block">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-2"></i>Statistik Sistem
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 mb-0 font-weight-bold text-primary"><?= $totalSiswa ?></div>
                            <small class="text-muted">Siswa</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 font-weight-bold text-success"><?= $totalPembimbing ?></div>
                            <small class="text-muted">Pembimbing</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 font-weight-bold text-info"><?= $totalLog ?></div>
                            <small class="text-muted">Log</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h5 mb-0 font-weight-bold text-success"><?= $totalLog > 0 ? round((($totalLog - $logPending) / $totalLog) * 100, 1) : 0 ?>%</div>
                            <small class="text-muted">Progress</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0 font-weight-bold text-warning"><?= $logPending ?></div>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-tasks mr-2"></i>Prioritas Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    <?php if ($logPending > 0): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Perhatian:</strong> Ada <?= $logPending ?> log aktivitas yang menunggu review dari pembimbing.
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>
                                Review log aktivitas siswa
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>
                                Pastikan pembimbing aktif
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>
                                Monitor progress magang
                            </li>
                        </ul>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-success mb-0">Semua sistem berjalan dengan baik!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                        <h6 class="text-gray-500">Fitur Aktivitas Terbaru</h6>
                        <p class="text-muted">Akan menampilkan aktivitas terbaru dari sistem</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Reports -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Laporan Harian</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Generate laporan harian siswa</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('admin/laporan-magang') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-pdf mr-1"></i>Buat Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Kelola User</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Tambah/edit siswa & pembimbing</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('admin/kelola-siswa') ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-user-plus mr-1"></i>Kelola User
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Monitoring</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Monitor progress magang siswa</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="<?= base_url('admin/laporan-magang') ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar mr-1"></i>Lihat Progress
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
