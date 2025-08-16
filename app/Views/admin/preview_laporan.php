<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Preview Laporan Magang</h1>
                <div>
                    <a href="<?= base_url('admin/laporan-magang') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Laporan
                    </a>
                    <button onclick="window.print()" class="btn btn-success ml-2">
                        <i class="fas fa-print mr-2"></i>Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <?php if (!empty($siswa)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow border-left-primary">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-graduate mr-2"></i>Informasi Siswa
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h6 text-muted">Nama Siswa</div>
                                    <div class="h5 font-weight-bold text-primary"><?= esc($siswa['nama']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h6 text-muted">NIS</div>
                                    <div class="h5 font-weight-bold text-info"><?= esc($siswa['nis']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h6 text-muted">Tempat Magang</div>
                                    <div class="h5 font-weight-bold text-success"><?= esc($siswa['tempat_magang']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h6 text-muted">Periode</div>
                                    <div class="h5 font-weight-bold text-warning">
                                        <?= date('d/m/Y', strtotime($startDate)) ?> - <?= date('d/m/Y', strtotime($endDate)) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Aktivitas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($logs) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Disetujui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($logs, fn($log) => $log['status'] == 'disetujui')) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Revisi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($logs, fn($log) => $log['status'] == 'revisi')) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Menunggu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($logs, fn($log) => $log['status'] == 'menunggu')) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table mr-2"></i>Daftar Aktivitas Harian
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($logs)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Tidak ada data untuk periode ini</h5>
                            <p class="text-muted">Siswa belum mencatat aktivitas magang dalam rentang tanggal yang dipilih</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Durasi</th>
                                        <th>Aktivitas</th>
                                        <th>Status</th>
                                        <th>Komentar Pembimbing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($logs as $log): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
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
                                                <?php
                                                $start = strtotime($log['jam_mulai']);
                                                $end = strtotime($log['jam_selesai']);
                                                $duration = $end - $start;
                                                $hours = floor($duration / 3600);
                                                $minutes = floor(($duration % 3600) / 60);
                                                ?>
                                                <span class="badge bg-info"><?= $hours ?>j <?= $minutes ?>m</span>
                                            </td>
                                            <td>
                                                <div class="text-justify" style="max-width: 400px;">
                                                    <?= nl2br(esc($log['uraian'])) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $effectiveStatus = $log['status'] ?? '';
                                                if (empty($effectiveStatus) && !empty($log['status_validasi'])) {
                                                    $effectiveStatus = $log['status_validasi'];
                                                }
                                                $effectiveStatus = $effectiveStatus ?: 'menunggu';
                                                $badgeClass = match($effectiveStatus) {
                                                    'disetujui' => 'bg-success',
                                                    'revisi' => 'bg-warning',
                                                    'menunggu' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?= $badgeClass ?> rounded-pill"><?= ucfirst($effectiveStatus) ?></span>
                                            </td>
                                            <td>
                                                <?php if (isset($log['komentar']) && $log['komentar']): ?>
                                                    <div class="text-justify" style="max-width: 300px;">
                                                        <i class="fas fa-comment text-info mr-1"></i>
                                                        <?= nl2br(esc($log['komentar'])) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        oleh: <?= $log['pembimbing_nama'] ?? 'Pembimbing' ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
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

    <!-- Action Buttons -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?= base_url('admin/laporan-magang') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Laporan
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button onclick="window.print()" class="btn btn-success btn-block">
                                <i class="fas fa-print mr-2"></i>Print Laporan
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('admin/kelola-siswa') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-users mr-2"></i>Kelola Siswa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .footer {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        border: 1px solid #000 !important;
    }
    
    .table th, .table td {
        border: 1px solid #000 !important;
    }
}
</style>

<?= $this->endSection() ?>


