<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-pdf mr-2"></i>Cetak Laporan Magang
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Informasi:</strong> Pilih rentang tanggal untuk menghasilkan laporan aktivitas magang Anda.
                        Laporan akan berisi semua aktivitas yang telah dicatat beserta komentar dari pembimbing.
                    </div>

                    <form action="<?= base_url('siswa/generate-laporan') ?>" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="<?= old('start_date', date('Y-m-01')) ?>" required>
                                    <small class="form-text text-muted">
                                        Pilih tanggal awal periode magang
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Akhir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="<?= old('end_date', date('Y-m-d')) ?>" required>
                                    <small class="form-text text-muted">
                                        Pilih tanggal akhir periode magang
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="jenis_laporan">Jenis Laporan</label>
                            <select class="form-control" id="jenis_laporan" name="jenis_laporan">
                                <option value="harian">Laporan Harian</option>
                                <option value="mingguan">Laporan Mingguan</option>
                                <option value="bulanan">Laporan Bulanan</option>
                                <option value="semester">Laporan Semester</option>
                            </select>
                            <small class="form-text text-muted">
                                Pilih jenis laporan yang sesuai dengan kebutuhan
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="include_komentar" name="include_komentar" checked>
                                <label class="custom-control-label" for="include_komentar">
                                    Sertakan komentar pembimbing dalam laporan
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="include_bukti" name="include_bukti">
                                <label class="custom-control-label" for="include_bukti">
                                    Sertakan daftar bukti aktivitas
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
                                    <i class="fas fa-file-pdf mr-2"></i>Generate Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Laporan Template Preview -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-eye mr-2"></i>Preview Format Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="border p-4 bg-light">
                        <div class="text-center mb-4">
                            <h5 class="font-weight-bold">LAPORAN AKTIVITAS MAGANG</h5>
                            <p class="mb-1"><strong>Nama:</strong> [Nama Siswa]</p>
                            <p class="mb-1"><strong>NIS:</strong> [Nomor Induk Siswa]</p>
                            <p class="mb-1"><strong>Tempat Magang:</strong> [Nama Perusahaan/Instansi]</p>
                            <p class="mb-3"><strong>Periode:</strong> [Tanggal Mulai] - [Tanggal Akhir]</p>
                        </div>
                        
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Durasi</th>
                                    <th>Aktivitas</th>
                                    <th>Status</th>
                                    <th>Komentar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>01/08/2024</td>
                                    <td>08:00 - 12:00</td>
                                    <td>4 jam</td>
                                    <td>Belajar database MySQL...</td>
                                    <td><span class="badge badge-success">Disetujui</span></td>
                                    <td>Bagus, lanjutkan...</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>02/08/2024</td>
                                    <td>08:00 - 12:00</td>
                                    <td>4 jam</td>
                                    <td>Membuat aplikasi web...</td>
                                    <td><span class="badge badge-warning">Revisi</span></td>
                                    <td>Perbaiki bagian...</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="mt-3">
                            <p class="mb-1"><strong>Total Jam Magang:</strong> [Total Jam]</p>
                            <p class="mb-1"><strong>Total Aktivitas:</strong> [Total Aktivitas]</p>
                            <p class="mb-0"><strong>Status:</strong> [Status Keseluruhan]</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Laporan -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-lightbulb mr-2"></i>Tips Membuat Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Sebelum Generate:</h6>
                            <ul class="text-muted">
                                <li>Pastikan semua aktivitas sudah dicatat</li>
                                <li>Pilih periode yang sesuai dengan kebutuhan</li>
                                <li>Periksa komentar pembimbing</li>
                                <li>Verifikasi data yang akan dicetak</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Setelah Generate:</h6>
                            <ul class="text-muted">
                                <li>Simpan file PDF dengan nama yang jelas</li>
                                <li>Print dalam format A4</li>
                                <li>Berikan kepada pembimbing untuk ditandatangani</li>
                                <li>Simpan sebagai arsip pribadi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set default dates
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

// Validate date range
document.getElementById('end_date').addEventListener('change', function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = this.value;
    
    if (startDate && endDate && endDate < startDate) {
        alert('Tanggal akhir tidak boleh lebih kecil dari tanggal mulai!');
        this.value = '';
    }
});
</script>
<?= $this->endSection() ?>
