<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard Pembimbing</h1>
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
                                Log Menunggu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($pendingLogs) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Total Siswa Bimbingan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= isset($assignedCount) ? (int)$assignedCount : 0 ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Prioritas</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Tinggi</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-users mr-2"></i>Lihat Semua Siswa
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-clipboard-list mr-2"></i>Review Log
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-comments mr-2"></i>Beri Komentar
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

    <!-- Pending Logs -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Log Aktivitas Menunggu Review</h6>
                    <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <?php if (empty($pendingLogs)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">Semua log sudah direview!</h5>
                            <p class="text-muted">Tidak ada log aktivitas yang menunggu review saat ini.</p>
                            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-primary">Lihat Semua Siswa</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Siswa</th>
                                        <th>NIS</th>
                                        <th>Tempat Magang</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingLogs as $log): ?>
                                        <tr>
                                            <td>
                                                <strong><?= $log['siswa_nama'] ?></strong>
                                            </td>
                                            <td><?= $log['nis'] ?? '-' ?></td>
                                            <td><?= $log['tempat_magang'] ?? '-' ?></td>
                                            <td>
                                                <strong><?= date('d/m/Y', strtotime($log['tanggal'])) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= date('l', strtotime($log['tanggal'])) ?></small>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <div class="font-weight-bold text-primary"><?= $log['jam_mulai'] ?></div>
                                                    <div class="text-muted">sampai</div>
                                                    <div class="font-weight-bold text-success"><?= $log['jam_selesai'] ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="<?= $log['uraian'] ?>">
                                                    <?= $log['uraian'] ?>
                                                </div>
                                                <?php if ($log['bukti']): ?>
                                                    <small class="text-info">
                                                        <i class="fas fa-paperclip mr-1"></i>Ada bukti
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary badge-pill">Menunggu</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('pembimbing/detail-log/' . $log['id']) ?>" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('pembimbing/detail-log/' . $log['id']) ?>" 
                                                       class="btn btn-sm btn-success" title="Review & Komentar">
                                                        <i class="fas fa-comment"></i>
                                                    </a>
                                                </div>
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

    <!-- Recent Activity Summary -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-pie mr-2"></i>Statistik Review
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 mb-0 font-weight-bold text-warning"><?= isset($statusCounts) ? (int)$statusCounts['menunggu'] : count($pendingLogs) ?></div>
                            <small class="text-muted">Menunggu</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 font-weight-bold text-success"><?= isset($statusCounts) ? (int)$statusCounts['disetujui'] : 0 ?></div>
                            <small class="text-muted">Disetujui</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-0 font-weight-bold text-danger"><?= isset($statusCounts) ? (int)$statusCounts['revisi'] : 0 ?></div>
                            <small class="text-muted">Revisi</small>
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
                    <?php if (empty($pendingLogs)): ?>
                        <p class="text-muted mb-0">Tidak ada tugas prioritas hari ini.</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach (array_slice($pendingLogs, 0, 3) as $log): ?>
                                <li class="mb-2">
                                    <i class="fas fa-circle text-warning mr-2" style="font-size: 8px;"></i>
                                    <strong><?= $log['siswa_nama'] ?></strong> - 
                                    <?= date('d/m/Y', strtotime($log['tanggal'])) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
