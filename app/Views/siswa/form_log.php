<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-plus mr-2"></i>Input Log Aktivitas Harian
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Notifications will be handled by the notification system -->
                    <!-- Flashdata will be automatically converted to toasts -->

                    <form action="<?= base_url('siswa/save-log') ?>" method="post" enctype="multipart/form-data" id="logForm" class="no-loading">
                        <?= csrf_field() ?>
                        
                        <!-- Date and Time Section -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal" class="form-label">
                                        <i class="fas fa-calendar mr-1"></i>Tanggal <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                                    <small class="form-text text-muted">
                                        Pilih tanggal aktivitas magang
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_mulai" class="form-label">
                                        <i class="fas fa-clock mr-1"></i>Jam Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" 
                                           value="<?= old('jam_mulai') ?>" required>
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
                                        <i class="fas fa-clock mr-1"></i>Jam Selesai <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" 
                                           value="<?= old('jam_selesai') ?>" required>
                                    <small class="form-text text-muted">
                                        Jam selesai aktivitas
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bukti" class="form-label">
                                        <i class="fas fa-paperclip mr-1"></i>Bukti Aktivitas (Opsional)
                                    </label>
                                    <input type="file" class="form-control" id="bukti" name="bukti" 
                                           accept="image/*,.pdf,.doc,.docx">
                                    <small class="form-text text-muted">
                                        Format: JPG, PNG, PDF, DOC, DOCX. Maksimal 2MB.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Description -->
                        <div class="form-group mb-3">
                            <label for="uraian" class="form-label">
                                <i class="fas fa-tasks mr-1"></i>Uraian Aktivitas <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="uraian" name="uraian" rows="8" 
                                      placeholder="Jelaskan detail aktivitas yang Anda lakukan hari ini..." 
                                      required><?= old('uraian') ?></textarea>
                            <small class="form-text text-muted">
                                <strong>Minimal 10 karakter.</strong> Jelaskan dengan detail apa yang Anda lakukan, 
                                apa yang Anda pelajari, dan bagaimana Anda mengaplikasikan pengetahuan tersebut.
                            </small>
                        </div>

                        <!-- Confirmation -->
                        <div class="form-group mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="konfirmasi" required>
                                <label class="custom-control-label" for="konfirmasi">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Saya menyatakan bahwa informasi yang saya berikan adalah benar dan akurat
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save mr-2"></i>Simpan Log Aktivitas
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Section -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb mr-2"></i>Tips Menulis Log Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">
                                <i class="fas fa-check mr-2"></i>Yang Harus Ditulis:
                            </h6>
                            <ul class="text-muted">
                                <li>Deskripsi tugas yang dikerjakan</li>
                                <li>Alat atau software yang digunakan</li>
                                <li>Masalah yang dihadapi dan solusinya</li>
                                <li>Pelajaran yang didapat</li>
                                <li>Kemajuan yang dicapai</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">
                                <i class="fas fa-star mr-2"></i>Contoh Format:
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted">
                                    "Hari ini saya belajar membuat database menggunakan MySQL. 
                                    Saya membuat tabel users dengan field id, nama, email, dan password. 
                                    Saya juga belajar tentang primary key dan foreign key. 
                                    Masalah yang saya hadapi adalah syntax error saat membuat foreign key, 
                                    tapi akhirnya bisa diselesaikan dengan bantuan dokumentasi."
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guidelines Section -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Panduan Pengisian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-warning">Yang Perlu Diperhatikan:</h6>
                            <ul class="text-muted">
                                <li>Jam selesai harus lebih besar dari jam mulai</li>
                                <li>Tanggal tidak boleh lebih dari hari ini</li>
                                <li>Uraian aktivitas minimal 10 karakter</li>
                                <li>File bukti maksimal 2MB</li>
                                <li>Pastikan data yang diisi akurat</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-info">Setelah Submit:</h6>
                            <ul class="text-muted">
                                <li>Log akan ditinjau oleh pembimbing</li>
                                <li>Status awal: "Menunggu"</li>
                                <li>Pembimbing akan memberikan komentar</li>
                                <li>Status bisa berubah menjadi "Disetujui" atau "Revisi"</li>
                                <li>Anda bisa lihat komentar di menu Riwayat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set default tanggal ke hari ini
document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('tanggal').value) {
        document.getElementById('tanggal').value = new Date().toISOString().split('T')[0];
    }
});

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
        alert('Anda harus menyetujui bahwa informasi yang diberikan benar dan akurat!');
        return;
    }
});
</script>

<?= $this->endSection() ?>
