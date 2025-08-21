<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Daftar Siswa Magang</h1>
                <a href="<?= base_url('pembimbing/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
                </div>
                <div class="card-body">
                    <form method="get" class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Cari Siswa</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Nama, NIS, atau tempat magang..." 
                                           value="<?= request()->getGet('search') ?>">
                                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status Log</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu" <?= (request()->getGet('status') == 'menunggu') ? 'selected' : '' ?>>Menunggu Review</option>
                                    <option value="disetujui" <?= (request()->getGet('status') == 'disetujui') ? 'selected' : '' ?>>Sudah Disetujui</option>
                                    <option value="revisi" <?= (request()->getGet('status') == 'revisi') ? 'selected' : '' ?>>Perlu Revisi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tempat_magang">Tempat Magang</label>
                                <select class="form-control" id="tempat_magang" name="tempat_magang">
                                    <option value="">Semua Tempat</option>
                                    <?php 
                                    $tempatMagang = array_unique(array_filter(array_column($siswa, 'tempat_magang')));
                                    foreach ($tempatMagang as $tempat): 
                                        if ($tempat):
                                    ?>
                                        <option value="<?= $tempat ?>" <?= (request()->getGet('tempat_magang') == $tempat) ? 'selected' : '' ?>>
                                            <?= $tempat ?>
                                        </option>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center">
                                        <i class="bi bi-search me-1"></i>
                                        Filter
                                    </button>
                                    <a href="<?= base_url('pembimbing/aktivitas-siswa') ?>" class="btn btn-secondary d-inline-flex align-items-center" title="Reset filter">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Siswa List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Siswa</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($siswa)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada siswa yang terdaftar</h5>
                            <p class="text-muted">Siswa akan muncul di sini setelah mendaftar dan mulai magang</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($siswa as $s): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card border-left-primary shadow h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Siswa Magang
                                                    </div>
                                                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                        <?= $s['nama'] ?>
                                                    </div>
                                                    <div class="text-muted mb-2">
                                                        <i class="fas fa-id-card mr-1"></i><?= $s['nis'] ?? 'NIS tidak tersedia' ?>
                                                    </div>
                                                    <div class="text-muted mb-3">
                                                        <i class="fas fa-building mr-1"></i><?= $s['tempat_magang'] ?? 'Tempat tidak tersedia' ?>
                                                    </div>
                                                    
                                                    <!-- Quick Stats -->
                                                    <div class="row text-center">
                                                        <div class="col-3">
                                                            <div class="h6 mb-0 font-weight-bold text-primary"><?= (int)($s['total_log'] ?? 0) ?></div>
                                                            <small class="text-muted">Total Log</small>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="h6 mb-0 font-weight-bold text-warning"><?= (int)($s['menunggu_count'] ?? 0) ?></div>
                                                            <small class="text-muted">Menunggu</small>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="h6 mb-0 font-weight-bold text-success"><?= (int)($s['disetujui_count'] ?? 0) ?></div>
                                                            <small class="text-muted">Disetujui</small>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="h6 mb-0 font-weight-bold text-danger"><?= (int)($s['revisi_count'] ?? 0) ?></div>
                                                            <small class="text-muted">Direvisi</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="h1 mb-0 font-weight-bold text-gray-300">
                                                        <i class="fas fa-user-graduate"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Buttons -->
                                            <div class="mt-3">
                                                <div class="btn-group btn-block" role="group">
                                                    <a href="<?= base_url('pembimbing/log-siswa/' . $s['id']) ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-clipboard-list mr-1"></i>Lihat Log
                                                    </a>
                                                    <a href="<?= base_url('pembimbing/log-siswa/' . $s['id']) ?>" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-comment mr-1"></i>Review
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Siswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($siswa) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tempat Magang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_unique(array_filter(array_column($siswa, 'tempat_magang')))) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($siswa) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Prioritas</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Tinggi</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit form when filter changes
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('tempat_magang').addEventListener('change', function() {
    this.form.submit();
});
</script>
<?= $this->endSection() ?>
