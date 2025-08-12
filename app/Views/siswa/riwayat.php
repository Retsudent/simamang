<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Riwayat Aktivitas Magang</h1>
                <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Input Log Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Aktivitas</h6>
                </div>
                <div class="card-body">
                    <form method="get" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu" <?= (request()->getGet('status') == 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                                    <option value="disetujui" <?= (request()->getGet('status') == 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                                    <option value="revisi" <?= (request()->getGet('status') == 'revisi') ? 'selected' : '' ?>>Revisi</option>
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
                                    <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-secondary">
                                        <i class="fas fa-undo mr-1"></i>Reset
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
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Aktivitas</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($logs)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada aktivitas yang dicatat</h5>
                            <p class="text-muted">Mulai dengan mencatat aktivitas magang Anda hari ini</p>
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Input Log Pertama
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Durasi</th>
                                        <th>Aktivitas</th>
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
                                                <small class="text-muted"><?= date('l, j F Y', strtotime($log['tanggal'])) ?></small>
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
                                                <?php if ($log['bukti']): ?>
                                                    <small class="text-info">
                                                        <i class="fas fa-paperclip mr-1"></i>Ada bukti
                                                    </small>
                                                <?php endif; ?>
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
                                                <span class="badge <?= $statusClass ?> badge-pill"><?= $statusText ?></span>
                                            </td>
                                            <td>
                                                <?php if (isset($log['komentar']) && $log['komentar']): ?>
                                                    <div class="text-truncate" style="max-width: 200px;" title="<?= $log['komentar'] ?>">
                                                        <i class="fas fa-comment text-info mr-1"></i>
                                                        <?= $log['komentar'] ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        oleh: <?= $log['pembimbing_nama'] ?? 'Pembimbing' ?>
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('siswa/detail-log/' . $log['id']) ?>" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($log['status'] == 'menunggu'): ?>
                                                        <a href="<?= base_url('siswa/edit-log/' . $log['id']) ?>" 
                                                           class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
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
                            <div class="col-md-4">
                                <div class="card border-left-info">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                        </div>
                    <?php endif; ?>
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
