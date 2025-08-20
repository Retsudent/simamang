<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="fas fa-users-cog me-3"></i>
                        Atur Siswa untuk Pembimbing: <?= esc($pembimbing['nama']) ?>
                    </h2>
                    <p class="text-muted mb-0">Kelola dan atur siswa yang akan dibimbing oleh pembimbing ini</p>
                </div>
                <a href="<?= base_url('admin/atur-bimbingan') ?>" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Informasi Pembimbing -->
    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-tie me-3"></i>
                <h5 class="mb-0 fw-bold">Informasi Pembimbing</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center">
                        <h5 class="fw-bold mb-1"><?= esc($pembimbing['nama']) ?></h5>
                        <p class="text-muted mb-0"><?= esc($pembimbing['jabatan'] ?? 'Belum ditentukan') ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="mb-1 text-success"><?= count($assignedIds) ?></h4>
                        <p class="text-muted mb-0">Jumlah Siswa Terbimbing</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="mb-1 text-info"><?= esc($pembimbing['username']) ?></h4>
                        <p class="text-muted mb-0">Username</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="mb-1 text-warning"><?= esc($pembimbing['instansi'] ?? 'Belum diisi') ?></h4>
                        <p class="text-muted mb-0">Instansi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pengaturan Siswa -->
    <div class="card border-0 shadow">
        <div class="card-header bg-success text-white py-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-list-check me-3"></i>
                <h5 class="mb-0 fw-bold">Pilih Siswa yang Akan Dibimbing</h5>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/simpan-atur-bimbingan/' . $pembimbing['id']) ?>">
                <?= csrf_field() ?>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Petunjuk:</strong> Centang siswa yang akan dibimbing oleh pembimbing ini.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                        <label for="selectAllCheckbox" class="ms-2">Pilih Semua</label>
                                    </div>
                                </th>
                                <th>Nama Siswa</th>
                                <th>Username</th>
                                <th>NIS</th>
                                <th>Tempat Magang</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semuaSiswa as $s): ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" 
                                                   class="form-check-input siswa-checkbox"
                                                   <?= in_array($s['id'], $assignedIds, true) ? 'checked' : '' ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?= esc($s['nama']) ?></strong>
                                    </td>
                                    <td>
                                        <code><?= esc($s['username']) ?></code>
                                    </td>
                                    <td><?= esc($s['nis']) ?></td>
                                    <td><?= esc($s['tempat_magang']) ?></td>
                                    <td class="text-center">
                                        <?php if (in_array($s['id'], $assignedIds, true)): ?>
                                            <span class="badge bg-success">
                                                Sudah Terbimbing
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                Belum Ada Pembimbing
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-light btn-lg px-4" onclick="history.back()">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-check-circle me-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0.5rem;
    overflow: hidden;
}

.shadow {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075) !important;
}

.btn-lg {
    border-radius: 0.5rem;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const siswaCheckboxes = document.querySelectorAll('.siswa-checkbox');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        siswaCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Update select all checkbox when individual checkboxes change
    siswaCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCheckboxes = document.querySelectorAll('.siswa-checkbox:checked');
            selectAllCheckbox.checked = checkedCheckboxes.length === siswaCheckboxes.length;
        });
    });
});
</script>

<?= $this->endSection() ?>
