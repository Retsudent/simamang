<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Input Log Aktivitas Harian</h6>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('siswa/save-log') ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                           value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" 
                                           value="<?= old('jam_mulai') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" 
                                           value="<?= old('jam_selesai') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bukti">Bukti Aktivitas (Opsional)</label>
                                    <input type="file" class="form-control-file" id="bukti" name="bukti" 
                                           accept="image/*,.pdf,.doc,.docx">
                                    <small class="form-text text-muted">
                                        Format yang didukung: JPG, PNG, PDF, DOC, DOCX. Maksimal 2MB.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="uraian">Uraian Aktivitas <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="uraian" name="uraian" rows="6" 
                                      placeholder="Jelaskan detail aktivitas yang Anda lakukan hari ini..." 
                                      required><?= old('uraian') ?></textarea>
                            <small class="form-text text-muted">
                                Minimal 10 karakter. Jelaskan dengan detail apa yang Anda lakukan, 
                                apa yang Anda pelajari, dan bagaimana Anda mengaplikasikan pengetahuan tersebut.
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="konfirmasi" required>
                                <label class="custom-control-label" for="konfirmasi">
                                    Saya menyatakan bahwa informasi yang saya berikan adalah benar dan akurat
                                </label>
                            </div>
                        </div>

                        <div class="row mt-4">
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
                            <h6 class="text-primary">Yang Harus Ditulis:</h6>
                            <ul class="text-muted">
                                <li>Deskripsi tugas yang dikerjakan</li>
                                <li>Alat atau software yang digunakan</li>
                                <li>Masalah yang dihadapi dan solusinya</li>
                                <li>Pelajaran yang didapat</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Contoh Format:</h6>
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
</script>
<?= $this->endSection() ?>
