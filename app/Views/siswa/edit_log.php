<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Edit Log Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Notifications will be handled by the notification system -->
                    <!-- Flashdata will be automatically converted to toasts -->

                    <!-- Current Log Info -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Info:</strong> Anda sedang mengedit log aktivitas tanggal 
                        <strong><?= date('d/m/Y', strtotime($log['tanggal'])) ?></strong> 
                        dengan status <strong><?= ucfirst($log['status']) ?></strong>.
                        <?php if ($log['status'] !== 'menunggu'): ?>
                            <br><small class="text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Log ini sudah direview pembimbing. Perubahan mungkin memerlukan review ulang.
                            </small>
                        <?php endif; ?>
                    </div>

                    <form action="<?= base_url('siswa/update-log/' . $log['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <!-- Date and Time Section -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal" class="form-label">
                                        <i class="bi bi-calendar me-1"></i>Tanggal <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= old('tanggal', $log['tanggal']) ?>" required>
                                    <small class="form-text text-muted">
                                        Pilih tanggal aktivitas magang
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_mulai" class="form-label">
                                        <i class="bi bi-clock me-1"></i>Jam Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" 
                                           value="<?= old('jam_mulai', $log['jam_mulai']) ?>" required>
                                    <small class="form-text text-muted">
                                        Jam mulai aktivitas
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_selesai" class="form-label">
                                        <i class="bi bi-clock-fill me-1"></i>Jam Selesai <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" 
                                           value="<?= old('jam_selesai', $log['jam_selesai']) ?>" required>
                                    <small class="form-text text-muted">
                                        Jam selesai aktivitas
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bukti" class="form-label">
                                        <i class="bi bi-paperclip me-1"></i>Bukti Aktivitas
                                    </label>
                                    <input type="file" class="form-control" id="bukti" name="bukti" 
                                           accept="image/*,.pdf,.doc,.docx">
                                    <small class="form-text text-muted">
                                        Format: JPG, PNG, PDF, DOC, DOCX. Maksimal 2MB.
                                    </small>
                                    <?php if ($log['bukti']): ?>
                                        <div class="mt-2">
                                            <small class="text-info">
                                                <i class="fas fa-file mr-1"></i>File saat ini: <?= $log['bukti'] ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Description -->
                        <div class="form-group mb-3">
                            <label for="uraian" class="form-label">
                                <i class="bi bi-text-paragraph me-1"></i>Uraian Aktivitas <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="uraian" name="uraian" rows="8" 
                                      placeholder="Jelaskan detail aktivitas yang Anda lakukan hari ini..." 
                                      required><?= old('uraian', $log['uraian']) ?></textarea>
                            <small class="form-text text-muted">
                                <strong>Minimal 10 karakter.</strong> Jelaskan dengan detail apa yang Anda lakukan, 
                                apa yang Anda pelajari, dan bagaimana Anda mengaplikasikan pengetahuan tersebut.
                            </small>
                        </div>

                        <!-- Current Comment (if any) -->
                        <?php if (isset($log['komentar']) && $log['komentar']): ?>
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="bi bi-chat-dots me-2"></i>Komentar Pembimbing Saat Ini:
                                </h6>
                                <p class="mb-2"><?= nl2br(esc($log['komentar'])) ?></p>
                                <small class="text-muted">
                                    oleh: <?= $log['pembimbing_nama'] ?? 'Pembimbing' ?> 
                                    pada <?= date('d/m/Y H:i', strtotime($log['komentar_at'])) ?>
                                </small>
                            </div>
                        <?php endif; ?>

                        <!-- Confirmation -->
                        <div class="form-group mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="konfirmasi" required>
                                <label class="custom-control-label" for="konfirmasi">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Saya menyatakan bahwa perubahan yang saya buat adalah benar dan akurat
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-4">
                                <a href="<?= base_url('siswa/riwayat') ?>" class="btn btn-secondary btn-block">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Riwayat
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('siswa/detail-log/' . $log['id']) ?>" class="btn btn-info btn-block">
                                    <i class="bi bi-eye me-2"></i>Lihat Detail
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="bi bi-save me-2"></i>Update Log
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Guidelines Section -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Panduan Edit Log
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-warning">Yang Bisa Diedit:</h6>
                            <ul class="text-muted">
                                <li>Tanggal aktivitas</li>
                                <li>Jam mulai dan selesai</li>
                                <li>Uraian aktivitas</li>
                                <li>File bukti (ganti dengan yang baru)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">Yang Perlu Diperhatikan:</h6>
                            <ul class="text-muted">
                                <li>Jam selesai harus lebih besar dari jam mulai</li>
                                <li>Tanggal tidak boleh lebih dari hari ini</li>
                                <li>Uraian aktivitas minimal 10 karakter</li>
                                <li>File bukti maksimal 2MB</li>
                                <li>Jika sudah direview, mungkin perlu review ulang</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-lightbulb mr-2"></i>Tips Edit Log
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">Saat Edit:</h6>
                            <ul class="text-muted">
                                <li>Periksa kembali semua data</li>
                                <li>Pastikan format waktu benar</li>
                                <li>Jelaskan perubahan yang dibuat</li>
                                <li>Upload bukti yang lebih relevan</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Setelah Update:</h6>
                            <ul class="text-muted">
                                <li>Log akan ditinjau ulang oleh pembimbing</li>
                                <li>Status mungkin berubah</li>
                                <li>Pembimbing akan memberikan komentar baru</li>
                                <li>Periksa status di menu Riwayat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validasi jam selesai harus lebih besar dari jam mulai
document.getElementById('jam_selesai').addEventListener('change', function() {
    const jamMulai = document.getElementById('jam_mulai').value;
    const jamSelesai = this.value;
    
    if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
        alert('Jam selesai harus lebih besar dari jam mulai!');
        this.value = '';
    }
});

// Validasi tanggal tidak boleh lebih dari hari ini
document.getElementById('tanggal').addEventListener('change', function() {
    const today = new Date().toISOString().split('T')[0];
    const selectedDate = this.value;
    
    if (selectedDate > today) {
        alert('Tanggal tidak boleh lebih dari hari ini!');
        this.value = today;
    }
});

// Auto-resize textarea
document.getElementById('uraian').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const uraian = document.getElementById('uraian').value.trim();
    
    if (uraian.length < 10) {
        e.preventDefault();
        alert('Uraian aktivitas minimal 10 karakter!');
        document.getElementById('uraian').focus();
        return;
    }
    
    if (!document.getElementById('konfirmasi').checked) {
        e.preventDefault();
        alert('Anda harus menyetujui bahwa perubahan yang dibuat benar dan akurat!');
        return;
    }
    
    // Confirm before submit if log has been reviewed
    <?php if ($log['status'] !== 'menunggu'): ?>
    if (!confirm('Log ini sudah direview pembimbing. Apakah Anda yakin ingin mengubahnya? Perubahan mungkin memerlukan review ulang.')) {
        e.preventDefault();
        return;
    }
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>
