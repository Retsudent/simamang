<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-file-earmark-text text-primary me-2"></i>
            Laporan Magang Semua Siswa
        </h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <?php /* flash handled in layout */ ?>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-mortarboard-fill" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1"><?= count($siswa) ?></h4>
                            <p class="mb-0">Total Siswa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-journal-text" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1"><?= $totalLog ?? 0 ?></h4>
                            <p class="mb-0">Total Log Aktivitas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1"><?= $logDisetujui ?? 0 ?></h4>
                            <p class="mb-0">Log Disetujui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-clock" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1"><?= $logMenunggu ?? 0 ?></h4>
                            <p class="mb-0">Log Menunggu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Generate Laporan -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-search me-2"></i>
                Generate Laporan Magang
            </h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/generate-laporan-admin') ?>" id="reportForm" class="no-loading">
                <?= csrf_field() ?>
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-person me-1"></i>
                            Pilih Siswa
                        </label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach ($siswa as $s): ?>
                                <option value="<?= $s['id'] ?>">
                                    <?= esc($s['nama']) ?> (<?= esc($s['nis']) ?>) - <?= esc($s['tempat_magang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-start me-1"></i>
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="bi bi-calendar-end me-1"></i>
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>
                            Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list me-2"></i>
                Daftar Siswa
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($siswa)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Belum ada data siswa</p>
                    <a href="<?= base_url('admin/kelola-siswa') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Siswa
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>NIS</th>
                                <th>Tempat Magang</th>
                                <th>Pembimbing</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa as $index => $s): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="bi bi-mortarboard text-success"></i>
                                            </div>
                                            <div>
                                                <strong><?= esc($s['nama']) ?></strong>
                                                <br><small class="text-muted"><?= esc($s['username']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code><?= esc($s['nis']) ?></code></td>
                                    <td><?= esc($s['tempat_magang']) ?></td>
                                    <td>
                                        <?php if ($s['pembimbing_id']): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-person-check me-1"></i>
                                                Ada Pembimbing
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-person-x me-1"></i>
                                                Belum ada pembimbing
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $s['status'] === 'aktif' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($s['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="setSiswaForReport(<?= $s['id'] ?>)">
                                            <i class="bi bi-file-earmark-text me-1"></i>
                                            Lihat Laporan
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}
.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
}
</style>

<script>
function setSiswaForReport(siswaId) {
    // Set nilai siswa di form
    const form = document.getElementById('reportForm');
    form.querySelector('select[name="siswa_id"]').value = siswaId;
    
    // Set tanggal default (bulan ini)
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    form.querySelector('input[name="start_date"]').value = firstDay.toISOString().split('T')[0];
    form.querySelector('input[name="end_date"]').value = lastDay.toISOString().split('T')[0];
    
    // Scroll ke form
    form.closest('.card').scrollIntoView({ behavior: 'smooth' });
    
    // Submit form otomatis untuk menampilkan laporan
    form.submit();
}
</script>

<?= $this->endSection() ?>


