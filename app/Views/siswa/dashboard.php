<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Selamat Datang, <?= $siswa_info['nama'] ?>! 👋</h2>
                            <p class="text-muted mb-0">
                                <i class="bi bi-building me-2"></i>
                                Magang di: <strong><?= $siswa_info['tempat_magang'] ?></strong>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <div class="me-3">
                                    <small class="text-muted d-block">Pembimbing</small>
                                    <strong><?= $pembimbing_info['nama'] ?? 'Belum ditugaskan' ?></strong>
                                </div>
                                <div class="user-avatar">
                                    <i class="bi bi-person-workspace"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-primary-light">
                                <i class="bi bi-journal-text text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $total_log ?></h3>
                            <p class="text-muted mb-0">Total Log Aktivitas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-success-light">
                                <i class="bi bi-calendar-check text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $log_bulan_ini ?></h3>
                            <p class="text-muted mb-0">Log Bulan Ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-warning-light">
                                <i class="bi bi-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $disetujui ?></h3>
                            <p class="text-muted mb-0">Disetujui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-info-light">
                                <i class="bi bi-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $menunggu ?></h3>
                            <p class="text-muted mb-0">Menunggu Review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Activities -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-lightning"></i>
                    Aksi Cepat
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Input Log Aktivitas Baru
                        </a>
                        <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-clock-history me-2"></i>
                            Lihat Riwayat Aktivitas
                        </a>
                        <a href="<?= base_url('siswa/laporan') ?>" class="btn btn-outline-success">
                            <i class="bi bi-file-earmark-pdf me-2"></i>
                            Cetak Laporan PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-activity"></i>
                    Aktivitas Terbaru
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recent_activities)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Belum ada aktivitas yang dicatat</p>
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>
                                Mulai Input Log
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_activities as $activity): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong><?= date('d/m/Y', strtotime($activity['tanggal'])) ?></strong>
                                                    <small class="text-muted"><?= $activity['jam_mulai'] ?> - <?= $activity['jam_selesai'] ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="<?= $activity['uraian'] ?>">
                                                    <?= $activity['uraian'] ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = match($activity['status']) {
                                                    'disetujui' => 'badge-success',
                                                    'revisi' => 'badge-warning',
                                                    'ditolak' => 'badge-danger',
                                                    default => 'badge-secondary'
                                                };
                                                $statusText = ucfirst($activity['status']);
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('siswa/detail-log/' . $activity['id']) ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($recent_activities)): ?>
                    <div class="card-footer text-center">
                        <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-link">
                            Lihat Semua Aktivitas <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Student Info & Monthly Progress -->
    <div class="row">
        <!-- Student Information -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-person-badge"></i>
                    Informasi Siswa
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <p class="mb-0"><strong><?= $siswa_info['nama'] ?></strong></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">NIS</label>
                            <p class="mb-0"><strong><?= $siswa_info['nis'] ?></strong></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Tempat Magang</label>
                            <p class="mb-0"><strong><?= $siswa_info['tempat_magang'] ?></strong></p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p class="mb-0">
                                <span class="badge badge-success">Aktif</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Progress -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-graph-up"></i>
                    Statistik Bulanan
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Progress Magang</span>
                            <span class="text-muted"><?= $log_bulan_ini ?> / 20 hari</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <?php $progress = min(100, ($log_bulan_ini / 20) * 100); ?>
                            <div class="progress-bar bg-primary" style="width: <?= $progress ?>%"></div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-end">
                                <h4 class="text-success mb-1"><?= $disetujui ?></h4>
                                <small class="text-muted">Disetujui</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h4 class="text-warning mb-1"><?= $menunggu ?></h4>
                                <small class="text-muted">Menunggu</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-info mb-1"><?= $total_log ?></h4>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.bg-primary-light {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
}

.bg-success-light {
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
}

.bg-warning-light {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

.bg-info-light {
    background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
}

.badge-success {
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
    color: white;
}

.badge-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    color: white;
}

.badge-danger {
    background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
    color: white;
}

.badge-secondary {
    background: var(--secondary-color);
    color: white;
}

.progress {
    border-radius: 0.5rem;
    background-color: var(--border-light);
}

.progress-bar {
    border-radius: 0.5rem;
}

.table th {
    font-weight: 600;
    color: var(--text-primary);
    border-bottom: 2px solid var(--border-color);
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid var(--border-light);
}

.table tbody tr:hover {
    background-color: var(--background-light);
}

.btn-link {
    color: var(--primary-color);
    text-decoration: none;
}

.btn-link:hover {
    color: var(--primary-dark);
}
</style>
<?= $this->endSection() ?>
