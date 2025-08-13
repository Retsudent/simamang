<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="bi bi-file-earmark-pdf text-primary"></i> Cetak Laporan
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-range"></i> Generate Laporan Aktivitas Magang
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('siswa/generate-laporan') ?>">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">
                                        <i class="bi bi-calendar"></i> Tanggal Mulai <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="<?= old('start_date') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">
                                        <i class="bi bi-calendar"></i> Tanggal Akhir <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="<?= old('end_date') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_comments" name="include_comments" checked>
                                <label class="form-check-label" for="include_comments">
                                    Sertakan komentar pembimbing
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="include_statistics" name="include_statistics" checked>
                                <label class="form-check-label" for="include_statistics">
                                    Sertakan statistik aktivitas
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('siswa/dashboard') ?>" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-file-earmark-pdf"></i> Generate Laporan PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Report Templates -->
            <div class="card border-0 shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Laporan Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="<?= base_url('siswa/generate-laporan-rapid?period=week') ?>" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar-week"></i> Minggu Ini
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?= base_url('siswa/generate-laporan-rapid?period=month') ?>" class="btn btn-outline-success w-100">
                                <i class="bi bi-calendar-month"></i> Bulan Ini
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?= base_url('siswa/generate-laporan-rapid?period=all') ?>" class="btn btn-outline-info w-100">
                                <i class="bi bi-calendar-all"></i> Semua Aktivitas
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Information -->
            <div class="card border-0 shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle"></i> Informasi Laporan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Yang Termasuk dalam Laporan:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-check-circle text-success"></i> Biodata siswa</li>
                                <li><i class="bi bi-check-circle text-success"></i> Informasi tempat magang</li>
                                <li><i class="bi bi-check-circle text-success"></i> Daftar aktivitas harian</li>
                                <li><i class="bi bi-check-circle text-success"></i> Komentar pembimbing</li>
                                <li><i class="bi bi-check-circle text-success"></i> Statistik aktivitas</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">Format Laporan:</h6>
                            <ul class="list-unstyled">
                                <li><i class="bi bi-file-pdf text-danger"></i> PDF (Portable Document Format)</li>
                                <li><i class="bi bi-printer text-secondary"></i> Siap untuk dicetak</li>
                                <li><i class="bi bi-download text-primary"></i> Dapat diunduh</li>
                                <li><i class="bi bi-share text-success"></i> Dapat dibagikan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates
    const today = new Date();
    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    if (!document.getElementById('start_date').value) {
        document.getElementById('start_date').value = startOfMonth.toISOString().split('T')[0];
    }
    
    if (!document.getElementById('end_date').value) {
        document.getElementById('end_date').value = today.toISOString().split('T')[0];
    }
    
    // Validate date range
    document.getElementById('end_date').addEventListener('change', function() {
        const startDate = document.getElementById('start_date').value;
        const endDate = this.value;
        
        if (startDate && endDate && startDate > endDate) {
            alert('Tanggal akhir tidak boleh lebih awal dari tanggal mulai!');
            this.value = startDate;
        }
    });
});
</script>
<?= $this->endSection() ?>
