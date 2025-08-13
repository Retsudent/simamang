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
                            <h2 class="mb-2">Selamat Datang, <?= session()->get('nama') ?>! 👨‍🏫</h2>
                            <p class="text-muted mb-0">
                                <i class="bi bi-shield-check me-2"></i>
                                Anda bertugas sebagai <strong>Pembimbing Magang</strong> untuk memantau dan memberikan feedback kepada siswa
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <div class="me-3">
                                    <small class="text-muted d-block">Status</small>
                                    <strong class="text-success">Aktif</strong>
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
                            <div class="stat-icon bg-warning-light">
                                <i class="bi bi-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $statusCounts['menunggu'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Menunggu Review</p>
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
                                <i class="bi bi-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $statusCounts['disetujui'] ?? 0 ?></h3>
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
                                <i class="bi bi-arrow-clockwise text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $statusCounts['revisi'] ?? 0 ?></h3>
                            <p class="text-muted mb-0">Perlu Revisi</p>
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
                            <div class="stat-icon bg-primary-light">
                                <i class="bi bi-people text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1"><?= $assignedCount ?? 0 ?></h3>
                            <p class="text-muted mb-0">Total Siswa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Pending Logs -->
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
                        <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-primary">
                            <i class="bi bi-people me-2"></i>
                            Lihat Aktivitas Siswa
                        </a>
                        <a href="<?= base_url('pembimbing/komentar') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-chat-dots me-2"></i>
                            Berikan Komentar
                        </a>
                        <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-outline-success">
                            <i class="bi bi-graph-up me-2"></i>
                            Lihat Statistik
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Logs -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-clock-history"></i>
                    Log Menunggu Review
                </div>
                <div class="card-body p-0">
                    <?php if (empty($pendingLogs)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">Tidak ada log yang menunggu review</p>
                            <p class="text-muted small">Semua log aktivitas siswa telah direview</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Siswa</th>
                                        <th>Tanggal</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingLogs as $log): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong><?= $log['siswa_nama'] ?></strong>
                                                    <small class="text-muted"><?= $log['nis'] ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <strong><?= date('d/m/Y', strtotime($log['tanggal'])) ?></strong>
                                                    <small class="text-muted"><?= $log['jam_mulai'] ?> - <?= $log['jam_selesai'] ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 250px;" title="<?= $log['uraian'] ?>">
                                                    <?= $log['uraian'] ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">Menunggu</span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('pembimbing/detail-log/' . $log['id']) ?>" 
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
                <?php if (!empty($pendingLogs)): ?>
                    <div class="card-footer text-center">
                        <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-link">
                            Lihat Semua Aktivitas <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Statistics -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-activity"></i>
                    Aktivitas Terbaru
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php 
                        $recentActivities = array_slice($pendingLogs, 0, 5);
                        if (empty($recentActivities)): 
                        ?>
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">Belum ada aktivitas terbaru</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recentActivities as $activity): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?= $activity['siswa_nama'] ?></h6>
                                                <p class="text-muted mb-1"><?= $activity['uraian'] ?></p>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?= date('d/m/Y H:i', strtotime($activity['tanggal'] . ' ' . $activity['jam_mulai'])) ?>
                                                </small>
                                            </div>
                                            <span class="badge badge-warning">Menunggu</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-graph-up"></i>
                    Ringkasan Statistik
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Status Review</h6>
                        <div class="progress mb-2" style="height: 8px;">
                            <?php 
                            $total = array_sum($statusCounts);
                            $pendingPercent = $total > 0 ? ($statusCounts['menunggu'] / $total) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-warning" style="width: <?= $pendingPercent ?>%"></div>
                        </div>
                        <small class="text-muted"><?= $statusCounts['menunggu'] ?? 0 ?> dari <?= $total ?> log menunggu review</small>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success mb-1"><?= $statusCounts['disetujui'] ?? 0 ?></h4>
                                <small class="text-muted">Disetujui</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-1"><?= $statusCounts['revisi'] ?? 0 ?></h4>
                            <small class="text-muted">Revisi</small>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <h6 class="text-muted mb-2">Efisiensi Review</h6>
                        <?php 
                        $efficiency = $total > 0 ? (($statusCounts['disetujui'] + $statusCounts['revisi']) / $total) * 100 : 0;
                        ?>
                        <div class="display-6 text-primary mb-2"><?= number_format($efficiency, 1) ?>%</div>
                        <small class="text-muted">Log yang telah direview</small>
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

.badge-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
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

/* Timeline */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
}

.timeline-content {
    background: var(--background-light);
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 3px solid var(--primary-color);
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -1.625rem;
    top: 1rem;
    width: 2px;
    height: calc(100% - 0.5rem);
    background: var(--border-color);
}
</style>
<?= $this->endSection() ?>
