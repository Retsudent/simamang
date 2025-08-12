<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard Siswa</h1>
                <div class="text-right">
                    <p class="mb-0 text-muted">Selamat datang,</p>
                    <h5 class="mb-0"><?= $user['nama'] ?></h5>
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
                                NIS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $user['nis'] ?? '-' ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
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
                                Tempat Magang</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800"><?= $user['tempat_magang'] ?? '-' ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                Total Log</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($recentLogs) ?></div>
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
                                Status</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Aktif</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus mr-2"></i>Input Log Baru
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-history mr-2"></i>Lihat Riwayat
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('siswa/laporan') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-file-pdf mr-2"></i>Cetak Laporan
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

    <!-- Recent Logs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Log Aktivitas Terbaru</h6>
                    <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentLogs)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Belum ada log aktivitas. Mulai dengan input log pertama Anda!</p>
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">Input Log Pertama</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentLogs as $log): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($log['tanggal'])) ?></td>
                                            <td><?= $log['jam_mulai'] ?> - <?= $log['jam_selesai'] ?></td>
                                            <td>
                                                <?= strlen($log['uraian']) > 50 ? substr($log['uraian'], 0, 50) . '...' : $log['uraian'] ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = match($log['status']) {
                                                    'disetujui' => 'badge-success',
                                                    'revisi' => 'badge-warning',
                                                    default => 'badge-secondary'
                                                };
                                                $statusText = ucfirst($log['status']);
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('siswa/detail-log/' . $log['id']) ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
