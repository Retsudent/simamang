<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Detail Log Aktivitas</h1>
                <div>
                    <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Riwayat
                    </a>
                    <?php if ($log['status'] == 'menunggu'): ?>
                        <a href="<?= base_url('siswa/edit-log/' . $log['id']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Log Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-day mr-2"></i>Informasi Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120" class="font-weight-bold">Tanggal</td>
                                    <td width="20">:</td>
                                    <td><?= date('l, j F Y', strtotime($log['tanggal'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Jam Mulai</td>
                                    <td>:</td>
                                    <td><span class="badge badge-primary"><?= $log['jam_mulai'] ?></span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Jam Selesai</td>
                                    <td>:</td>
                                    <td><span class="badge badge-success"><?= $log['jam_selesai'] ?></span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Durasi</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        $start = strtotime($log['jam_mulai']);
                                        $end = strtotime($log['jam_selesai']);
                                        $duration = $end - $start;
                                        $hours = floor($duration / 3600);
                                        $minutes = floor(($duration % 3600) / 60);
                                        ?>
                                        <span class="badge badge-info"><?= $hours ?> jam <?= $minutes ?> menit</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120" class="font-weight-bold">Status</td>
                                    <td width="20">:</td>
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
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Dibuat</td>
                                    <td>:</td>
                                    <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Bukti</td>
                                    <td>:</td>
                                    <td>
                                        <?php if ($log['bukti']): ?>
                                            <span class="badge badge-info">
                                                <i class="fas fa-paperclip mr-1"></i>Ada bukti
                                            </span>
                                            <br>
                                            <small class="text-muted">File: <?= $log['bukti'] ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Tidak ada bukti</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Description -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks mr-2"></i>Uraian Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-4 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;"><?= $log['uraian'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Pembimbing Comment -->
            <?php if (isset($log['komentar']) && $log['komentar']): ?>
                <div class="card shadow mb-4 border-left-success">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-comment mr-2"></i>Komentar Pembimbing
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="bg-light p-4 rounded">
                                    <p class="mb-0" style="white-space: pre-wrap;"><?= $log['komentar'] ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-right">
                                    <p class="mb-1">
                                        <strong>Oleh:</strong><br>
                                        <span class="text-primary"><?= $log['pembimbing_nama'] ?? 'Pembimbing' ?></span>
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <small><?= date('d/m/Y H:i', strtotime($log['komentar_at'])) ?></small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-clock mr-2"></i>Status Komentar
                        </h6>
                    </div>
                    <div class="card-body text-center py-4">
                        <i class="fas fa-comments fa-3x text-gray-300 mb-3"></i>
                        <h6 class="text-gray-500">Belum ada komentar dari pembimbing</h6>
                        <p class="text-muted mb-0">
                            Pembimbing akan memberikan komentar setelah memeriksa aktivitas Anda
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="card shadow">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Riwayat
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?= base_url('siswa/input-log') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus mr-2"></i>Input Log Baru
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?= base_url('siswa/laporan') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-file-pdf mr-2"></i>Cetak Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
