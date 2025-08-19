<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-eye text-primary"></i> Detail Log Aktivitas
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-outline-secondary no-loading">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

    <?php if (!empty($log)): ?>
        <!-- Activity Details -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-journal-text"></i> Detail Aktivitas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Tanggal:</strong></td>
                                        <td><?= date('l, d F Y', strtotime($log['tanggal'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Mulai:</strong></td>
                                        <td><span class="badge bg-primary"><?= $log['jam_mulai'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jam Selesai:</strong></td>
                                        <td><span class="badge bg-success"><?= $log['jam_selesai'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Durasi:</strong></td>
                                        <td>
                                            <?php
                                            $start = strtotime($log['jam_mulai']);
                                            $end = strtotime($log['jam_selesai']);
                                            $duration = $end - $start;
                                            $hours = floor($duration / 3600);
                                            $minutes = floor(($duration % 3600) / 60);
                                            echo "<span class='badge bg-info'>{$hours} jam {$minutes} menit</span>";
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
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
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Dibuat:</strong></td>
                                        <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Terakhir Update:</strong></td>
                                        <td><?= date('d/m/Y H:i', strtotime($log['updated_at'] ?? $log['created_at'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bukti:</strong></td>
                                        <td>
                                            <?php if ($log['bukti']): ?>
                                                <a href="<?= base_url('uploads/bukti/' . $log['bukti']) ?>" 
                                                   target="_blank" class="btn btn-sm btn-outline-primary no-loading">
                                                    <i class="bi bi-file-earmark"></i> Lihat Bukti
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak ada bukti</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label"><strong>Uraian Aktivitas:</strong></label>
                            <div class="p-3 bg-light rounded">
                                <?= nl2br(htmlspecialchars($log['uraian'])) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <?php if (isset($log['komentar']) && $log['komentar']): ?>
                    <div class="card border-0 shadow mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-chat-dots"></i> Komentar Pembimbing
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><?= $log['nama_pembimbing'] ?? 'Pembimbing' ?></h6>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="p-3 bg-light rounded">
                                        <?= nl2br(htmlspecialchars($log['komentar'])) ?>
                                    </div>
                                    <?php if (isset($log['status_validasi'])): ?>
                                        <div class="mt-2">
                                            <span class="badge bg-<?= $log['status_validasi'] == 'disetujui' ? 'success' : 'warning' ?>">
                                                <?= ucfirst($log['status_validasi']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card border-0 shadow mt-4">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-chat-dots fa-2x text-muted mb-3"></i>
                            <h6 class="text-muted">Belum ada komentar dari pembimbing</h6>
                            <p class="text-muted mb-0">Pembimbing akan memberikan komentar setelah meninjau aktivitas Anda</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card border-0 shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning"></i> Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if (in_array($log['status'], ['menunggu', 'revisi'])): ?>
                                <a href="<?= base_url('siswa/edit-log/' . $log['id']) ?>" class="btn btn-warning no-loading">
                                    <i class="bi bi-pencil"></i> Edit Log
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-outline-secondary no-loading">
                                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                            </a>
                            
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary no-loading">
                                <i class="bi bi-plus-circle"></i> Input Log Baru
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Timeline Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Log Dibuat</h6>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></small>
                                </div>
                            </div>
                            
                            <?php if ($log['status'] != 'menunggu'): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-<?= $log['status'] == 'disetujui' ? 'success' : 'warning' ?>"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Ditinjau Pembimbing</h6>
                                        <small class="text-muted">Status: <?= ucfirst($log['status']) ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="card border-0 shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="text-warning">Log tidak ditemukan</h5>
                <p class="text-muted">Log aktivitas yang Anda cari tidak ditemukan atau telah dihapus.</p>
                <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-primary no-loading">
                    <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    padding-left: 10px;
}

.avatar {
    font-size: 1.2rem;
}
</style>
<?= $this->endSection() ?>
