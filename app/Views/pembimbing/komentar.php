<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Komentar & Validasi</h3>
        <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            Log Menunggu Review
        </div>
        <div class="card-body p-0">
            <?php if (empty($logs)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Tidak ada log yang menunggu review</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Tanggal</th>
                                <th>Aktivitas</th>
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong><?= esc($log['siswa_nama']) ?></strong>
                                        <small class="text-muted">NIS: <?= esc($log['nis']) ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong><?= date('d/m/Y', strtotime($log['tanggal'])) ?></strong>
                                        <small class="text-muted"><?= $log['jam_mulai'] ?> - <?= $log['jam_selesai'] ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 320px;" title="<?= esc($log['uraian']) ?>">
                                        <?= esc($log['uraian']) ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($log['bukti'])): ?>
                                        <a href="<?= base_url('uploads/bukti/' . $log['bukti']) ?>" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="bi bi-paperclip me-1"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('pembimbing/detail-log/' . $log['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-chat-left-text me-1"></i> Review
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
<?= $this->endSection() ?>


