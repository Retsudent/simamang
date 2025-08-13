<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Detail Log Aktivitas Siswa</h1>
                    <div>
                        <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Siswa
                        </a>
                    </div>
                </div>

            <!-- Student Info -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-person-badge"></i> Informasi Siswa
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
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
                                    <td class="font-weight-bold">Tanggal</td>
                                    <td>:</td>
                                    <td><?= date('l, j F Y', strtotime($log['tanggal'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Jam</td>
                                    <td>:</td>
                                    <td><?= $log['jam_mulai'] ?> - <?= $log['jam_selesai'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Details -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-list-task"></i> Detail Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-4 rounded">
                        <p class="mb-0" style="white-space: pre-wrap;"><?= $log['uraian'] ?></p>
                    </div>
                    
                    <?php if ($log['bukti']): ?>
                        <div class="mt-3">
                            <h6 class="text-info">
                                <i class="bi bi-paperclip"></i> Bukti Aktivitas
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-2"><strong>File:</strong> <?= $log['bukti'] ?></p>
                                <a href="<?= base_url('uploads/bukti/' . $log['bukti']) ?>" 
                                   class="btn btn-sm btn-info" target="_blank">
                                    <i class="bi bi-download"></i> Download Bukti
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Review & Comment Form -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="bi bi-chat-dots"></i> Review & Komentar
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('pembimbing/beri-komentar') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="log_id" value="<?= $log['id'] ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status Review <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="disetujui" <?= $log['status'] == 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                                        <option value="revisi" <?= $log['status'] == 'revisi' ? 'selected' : '' ?>>Perlu Revisi</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Pilih status untuk log aktivitas ini
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="komentar">Komentar <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="komentar" name="komentar" rows="4" 
                                              placeholder="Berikan komentar, feedback, atau saran untuk siswa..." required><?= $log['komentar'] ?? '' ?></textarea>
                                    <small class="form-text text-muted">
                                        Berikan komentar yang konstruktif dan membantu siswa berkembang
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="konfirmasi" required>
                                <label class="custom-control-label" for="konfirmasi">
                                    Saya telah memeriksa log aktivitas ini dengan teliti dan memberikan review yang objektif
                                </label>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-secondary btn-block">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="bi bi-check-circle"></i> Simpan Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Previous Comments -->
            <?php if (isset($log['komentar']) && $log['komentar']): ?>
                <div class="card shadow mb-4 border-left-success">
                                    <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="bi bi-clock-history"></i> Komentar Sebelumnya
                    </h6>
                </div>
                    <div class="card-body">
                        <div class="bg-light p-4 rounded">
                            <p class="mb-0" style="white-space: pre-wrap;"><?= $log['komentar'] ?></p>
                        </div>
                        <div class="text-right mt-2">
                            <small class="text-muted">
                                Diberikan pada: <?= date('d/m/Y H:i', strtotime($log['komentar_at'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="bi bi-lightning"></i> Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/log-siswa/' . $siswa['id']) ?>" class="btn btn-primary btn-block">
                                <i class="bi bi-clipboard-list"></i> Lihat Semua Log
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-info btn-block">
                                <i class="bi bi-people"></i> Daftar Siswa
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-secondary btn-block">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-block">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit form when status changes
document.getElementById('status').addEventListener('change', function() {
    if (this.value === 'revisi') {
        document.getElementById('komentar').setAttribute('placeholder', 'Berikan alasan mengapa perlu revisi dan saran perbaikan...');
    } else if (this.value === 'disetujui') {
        document.getElementById('komentar').setAttribute('placeholder', 'Berikan pujian, feedback positif, atau saran pengembangan...');
    }
});

// Validate form before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const status = document.getElementById('status').value;
    const komentar = document.getElementById('komentar').value.trim();
    
    if (!status) {
        e.preventDefault();
        alert('Pilih status review terlebih dahulu!');
        return;
    }
    
    if (!komentar) {
        e.preventDefault();
        alert('Berikan komentar untuk siswa!');
        return;
    }
    
    if (komentar.length < 10) {
        e.preventDefault();
        alert('Komentar minimal 10 karakter!');
        return;
    }
});
</script>
<?= $this->endSection() ?>
