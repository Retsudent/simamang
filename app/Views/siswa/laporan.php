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
                    <?php /* moved flash to layout to avoid duplicates */ ?>

                    <form method="post" action="<?= base_url('siswa/generate-laporan') ?>" id="generateReportForm" data-loader="page">
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
                            <button type="submit" class="btn btn-primary" id="generateReportBtn">
                                <span class="btn-text"><i class="bi bi-file-earmark-pdf"></i> Generate Laporan PDF</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Report Buttons -->
            <div class="card border-0 shadow mt-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Laporan Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="report-buttons-container">
                        <a href="<?= base_url('siswa/generate-laporan-rapid?period=week') ?>" class="report-button week-btn use-loader">
                            <div class="button-icon">
                                <i class="bi bi-calendar-week"></i>
                            </div>
                            <div class="button-content">
                                <div class="button-title">Minggu Ini</div>
                                <div class="button-subtitle">Senin - Minggu</div>
                            </div>
                        </a>
                        
                        <a href="<?= base_url('siswa/generate-laporan-rapid?period=month') ?>" class="report-button month-btn use-loader">
                            <div class="button-icon">
                                <i class="bi bi-calendar-month"></i>
                            </div>
                            <div class="button-content">
                                <div class="button-title">Bulan Ini</div>
                                <div class="button-subtitle">1 - Akhir Bulan</div>
                            </div>
                        </a>
                        
                        <a href="<?= base_url('siswa/generate-laporan-rapid?period=all') ?>" class="report-button all-btn use-loader">
                            <div class="button-icon">
                                <i class="bi bi-list-ul"></i>
                            </div>
                            <div class="button-content">
                                <div class="button-title">Semua Aktivitas</div>
                                <div class="button-subtitle">Dari Awal Magang</div>
                            </div>
                        </a>
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

<style>
/* Quick Report Styling - Section Laporan Cepat */
.report-buttons-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.report-button {
    display: flex;
    align-items: center;
    padding: 20px 25px;
    background: #ffffff;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    text-decoration: none;
    color: #2c3e50;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.report-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
    transition: left 0.6s ease;
}

.report-button:hover::before {
    left: 100%;
}

.report-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-decoration: none;
}

/* Button Variants */
.week-btn {
    border-left: 4px solid #3498db;
}

.week-btn:hover {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-color: #3498db;
    color: #1976d2;
}

.month-btn {
    border-left: 4px solid #27ae60;
}

.month-btn:hover {
    background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
    border-color: #27ae60;
    color: #2e7d32;
}

.all-btn {
    border-left: 4px solid #9b59b6;
}

.all-btn:hover {
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    border-color: #9b59b6;
    color: #7b1fa2;
}

.button-icon {
    font-size: 2.5rem;
    margin-right: 20px;
    transition: all 0.3s ease;
    min-width: 50px;
    text-align: center;
}

.week-btn .button-icon {
    color: #3498db;
}

.month-btn .button-icon {
    color: #27ae60;
}

.all-btn .button-icon {
    color: #9b59b6;
}

.report-button:hover .button-icon {
    transform: scale(1.1);
}

.button-content {
    flex: 1;
}

.button-title {
    font-weight: 700;
    font-size: 1.3rem;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.button-subtitle {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
}

.report-button:hover .button-subtitle {
    color: inherit;
}

/* Card Header Styling */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

/* Enhanced Card Styling */
.card {
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 25px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.card-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

.card-header h5 {
    font-weight: 600;
    font-size: 1.1rem;
}

.card-header i {
    margin-right: 8px;
    font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .report-buttons-container {
        gap: 12px;
    }
    
    .report-button {
        padding: 18px 20px;
    }
    
    .button-icon {
        font-size: 2.2rem;
        margin-right: 15px;
    }
    
    .button-title {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .report-button {
        padding: 15px 18px;
    }
    
    .button-icon {
        font-size: 2rem;
        margin-right: 12px;
    }
    
    .button-title {
        font-size: 1.1rem;
    }
    
    .button-subtitle {
        font-size: 0.8rem;
    }
}

/* Animation for page load */
.report-buttons-container {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Stagger animation for button items */
.report-button:nth-child(1) { animation-delay: 0.1s; }
.report-button:nth-child(2) { animation-delay: 0.2s; }
.report-button:nth-child(3) { animation-delay: 0.3s; }

/* Enhanced Button Styling */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Form Styling */
.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-label {
    font-weight: 600;
    color: #2c3e50;
}

.form-label i {
    margin-right: 8px;
    color: #007bff;
}
</style>

<?= $this->endSection() ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('generateReportForm');
  const btn = document.getElementById('generateReportBtn');
  if (form && btn) {
    form.addEventListener('submit', function(){
      btn.classList.add('is-loading');
      btn.querySelector('.btn-text').classList.add('d-none');
      btn.querySelector('.spinner-border').classList.remove('d-none');
    });
  }
});
</script>
