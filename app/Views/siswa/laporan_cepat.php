<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('siswa/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?= $period ?></li>
                    </ol>
                </div>
                <h4 class="page-title"><?= $title ?></h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Laporan Aktivitas <?= $period ?>
                    </h4>
                    <div class="card-actions">
                        <?php if ($startDate && $endDate): ?>
                            <span class="badge bg-info"><?= date('d/m/Y', strtotime($startDate)) ?> - <?= date('d/m/Y', strtotime($endDate)) ?></span>
                        <?php endif; ?>
                        <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($logs)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 4rem; color: var(--text-muted);"></i>
                            <h5 class="text-muted mt-3">Tidak ada aktivitas</h5>
                            <p class="text-muted">Belum ada log aktivitas untuk periode <?= strtolower($period) ?></p>
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Input Log Aktivitas
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Ringkasan -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1"><?= count($logs) ?></h3>
                                        <small>Total Aktivitas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1"><?= count(array_filter($logs, fn($log) => ($log['status'] ?? 'menunggu') === 'disetujui')) ?></h3>
                                        <small>Disetujui</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1"><?= count(array_filter($logs, fn($log) => ($log['status'] ?? 'menunggu') === 'revisi')) ?></h3>
                                        <small>Revisi</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body text-center">
                                        <h3 class="mb-1"><?= count(array_filter($logs, fn($log) => ($log['status'] ?? 'menunggu') === 'menunggu')) ?></h3>
                                        <small>Menunggu</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Aktivitas -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Uraian Aktivitas</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Komentar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $index => $log): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td>
                                                <strong><?= date('d/m/Y', strtotime($log['tanggal'])) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= date('l', strtotime($log['tanggal'])) ?></small>
                                            </td>
                                            <td>
                                                <?= $log['jam_mulai'] ?> - <?= $log['jam_selesai'] ?>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= $log['uraian'] ?></div>
                                                <?php if (!empty($log['output'])): ?>
                                                    <small class="text-muted">Output: <?= $log['output'] ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $start = strtotime($log['jam_mulai']);
                                                $end = strtotime($log['jam_selesai']);
                                                if ($start && $end && $end > $start) {
                                                    $duration = ($end - $start) / 3600;
                                                    echo number_format($duration, 1) . ' jam';
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status = $log['status'] ?? 'menunggu';
                                                $statusClass = $status === 'disetujui' ? 'success' : ($status === 'revisi' ? 'warning' : 'secondary');
                                                ?>
                                                <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($status) ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($log['komentar'])): ?>
                                                    <div class="text-muted">
                                                        <i class="bi bi-chat-quote me-1"></i>
                                                        <?= $log['komentar'] ?>
                                                    </div>
                                                    <?php if (!empty($log['pembimbing_nama'])): ?>
                                                        <small class="text-info">- <?= $log['pembimbing_nama'] ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="text-center mt-4">
                            <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                            </a>
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Input Log Baru
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.quick-report {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.quick-report h3 {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.quick-report-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.quick-report-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.quick-report-item:hover {
    background: #e9ecef;
    border-color: #007bff;
    transform: translateY(-2px);
    text-decoration: none;
    color: #333;
}

.quick-report-icon {
    font-size: 2rem;
    margin-bottom: 10px;
}

.quick-report-item:nth-child(1) .quick-report-icon {
    color: #007bff;
}

.quick-report-item:nth-child(2) .quick-report-icon {
    color: #28a745;
}

.quick-report-item:nth-child(3) {
    color: #6c757d;
}

.quick-report-text {
    font-weight: 500;
    text-align: center;
}

@media (max-width: 768px) {
    .quick-report-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?= $this->endSection() ?>


