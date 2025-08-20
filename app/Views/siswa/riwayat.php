<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-clock-history text-primary"></i> Riwayat Aktivitas
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Input Log Baru
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                                         <select class="form-select" id="status" name="status">
                         <option value="">Semua Status</option>
                         <option value="menunggu" <?= request()->getGet('status') == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                         <option value="disetujui" <?= request()->getGet('status') == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                         <option value="revisi" <?= request()->getGet('status') == 'revisi' ? 'selected' : '' ?>>Revisi</option>
                     </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                         <input type="date" class="form-control" id="start_date" name="start_date" value="<?= request()->getGet('start_date') ?>">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                                         <input type="date" class="form-control" id="end_date" name="end_date" value="<?= request()->getGet('end_date') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity List -->
    <div class="card border-0 shadow">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-ul"></i> Daftar Aktivitas
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($logs)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Aktivitas</th>
                                <th>Status</th>
                                <th>Bukti</th>
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
                                        <small class="text-muted"><?= date('l, d F Y', strtotime($log['tanggal'])) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= $log['jam_mulai'] ?></span>
                                        <i class="bi bi-arrow-right"></i>
                                        <span class="badge bg-info"><?= $log['jam_selesai'] ?></span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;" title="<?= $log['uraian'] ?>">
                                            <?= $log['uraian'] ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch ($log['status']) {
                                            case 'disetujui':
                                                $statusClass = 'success';
                                                $statusText = 'Disetujui';
                                                break;
                                            case 'revisi':
                                                $statusClass = 'warning';
                                                $statusText = 'Revisi';
                                                break;
                                            default:
                                                $statusClass = 'secondary';
                                                $statusText = 'Menunggu';
                                        }
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                    <td>
                                        <?php if ($log['bukti']): ?>
                                            <a href="<?= base_url('uploads/bukti/' . $log['bukti']) ?>" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark"></i> Lihat
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($log['komentar']) && $log['komentar']): ?>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="tooltip" data-bs-placement="top" 
                                                    title="<?= $log['komentar'] ?>">
                                                <i class="bi bi-chat-dots"></i> Ada
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('siswa/detail-log/' . $log['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($log['status'] == 'menunggu' || $log['status'] == 'revisi'): ?>
                                                <a href="<?= base_url('siswa/edit-log/' . $log['id']) ?>" 
                                                   class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (isset($pager)): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-x fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada aktivitas</h5>
                    <p class="text-muted">Mulai input log aktivitas magang Anda hari ini!</p>
                    <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Input Log Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history fa-2x text-primary mb-2"></i>
                    <h4 class="text-primary"><?= count($logs) ?></h4>
                    <p class="text-muted mb-0">Total Aktivitas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fa-2x text-success mb-2"></i>
                    <h4 class="text-success"><?= count(array_filter($logs, function($log) { return $log['status'] == 'disetujui'; })) ?></h4>
                    <p class="text-muted mb-0">Disetujui</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle fa-2x text-warning mb-2"></i>
                    <h4 class="text-warning"><?= count(array_filter($logs, function($log) { return $log['status'] == 'revisi'; })) ?></h4>
                    <p class="text-muted mb-0">Perlu Revisi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?= $this->endSection() ?>
