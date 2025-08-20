<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Log Aktivitas Siswa</h1>
                <div>
                    <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-left-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user-graduate mr-2"></i>Informasi Siswa
                            </h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120" class="font-weight-bold">Nama</td>
                                    <td width="20">:</td>
                                    <td><strong><?= $siswa['nama'] ?></strong></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">NIS</td>
                                    <td>:</td>
                                    <td><?= $siswa['nis'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Tempat Magang</td>
                                    <td>:</td>
                                    <td><?= $siswa['tempat_magang'] ?? '-' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-chart-pie mr-2"></i>Statistik Aktivitas
                            </h5>
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="h4 mb-0 font-weight-bold text-primary"><?= count($logs) ?></div>
                                    <small class="text-muted">Total Log</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 font-weight-bold text-warning">
                                        <?= count(array_filter($logs, fn($log) => $log['status'] == 'menunggu')) ?>
                                    </div>
                                    <small class="text-muted">Menunggu</small>
                                </div>
                                <div class="col-4">
                                    <div class="h4 mb-0 font-weight-bold text-success">
                                        <?= count(array_filter($logs, fn($log) => $log['status'] == 'disetujui')) ?>
                                    </div>
                                    <small class="text-muted">Disetujui</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Log Aktivitas</h6>
                </div>
                <div class="card-body">
                    <form method="get" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu" <?= (request()->getGet('status') == 'menunggu') ? 'selected' : '' ?>>Menunggu Review</option>
                                    <option value="disetujui" <?= (request()->getGet('status') == 'disetujui') ? 'selected' : '' ?>>Sudah Disetujui</option>
                                    <option value="revisi" <?= (request()->getGet('status') == 'revisi') ? 'selected' : '' ?>>Perlu Revisi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="<?= request()->getGet('start_date') ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="end_date">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="<?= request()->getGet('end_date') ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="fas fa-search mr-1"></i>Filter
                                    </button>
                                    <a href="<?= base_url('pembimbing/log-siswa/' . $siswa['id']) ?>" class="btn btn-secondary">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Log Aktivitas</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($logs)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada log aktivitas</h5>
                            <p class="text-muted">Siswa belum mencatat aktivitas magang mereka</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered data-table" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Durasi</th>
                                        <th>Aktivitas</th>
                                        <th>Bukti</th>
                                        <th>Status</th>
                                        <th>Komentar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
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
                                                echo $hours . 'j ' . $minutes . 'm';
                                                ?>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="<?= $log['uraian'] ?>">
                                                    <?= $log['uraian'] ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (!empty($log['bukti'])): ?>
                                                    <a href="<?= base_url('uploads/bukti/' . $log['bukti']) ?>" target="_blank" class="badge bg-info text-decoration-none">
                                                        <i class="bi bi-paperclip me-1"></i> Lihat Bukti
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
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
                                                <span class="badge <?= $badgeClass ?>"><?= ucfirst($effectiveStatus) ?></span>
                                            </td>
                                            <td>
                                                <?php if (!empty($log['komentar'])): ?>
                                                    <div class="text-truncate" style="max-width: 220px;" title="<?= esc($log['komentar']) ?>">
                                                        <i class="bi bi-chat-dots text-info me-1"></i>
                                                        <?= esc($log['komentar']) ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('pembimbing/detail-log/' . $log['id']) ?>" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <?php if ($log['status'] == 'menunggu'): ?>
                                                        <a href="<?= base_url('pembimbing/detail-log/' . $log['id']) ?>" 
                                                           class="btn btn-sm btn-success" title="Review & Komentar">
                                                            <i class="bi bi-chat-left-text"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card border-left-primary">
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
                                <div class="card border-left-warning">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
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
                            <div class="col-md-3">
                                <div class="card border-left-success">
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
                                <div class="card border-left-danger">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
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
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-users mr-2"></i>Daftar Semua Siswa
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-search mr-2"></i>Cari Siswa Lain
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
</div>

<script>
// Auto-submit form when filter changes
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

// Set default dates if empty
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    if (!startDate.value) {
        const firstDay = new Date();
        firstDay.setDate(1);
        startDate.value = firstDay.toISOString().split('T')[0];
    }
    
    if (!endDate.value) {
        endDate.value = new Date().toISOString().split('T')[0];
    }
});
</script>
<?= $this->endSection() ?>
