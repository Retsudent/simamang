<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-gear text-primary me-2"></i>
            Atur Siswa untuk <?= esc($pembimbing['nama']) ?>
        </h4>
        <a href="<?= base_url('admin/atur-bimbingan') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Informasi Pembimbing -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-lg bg-primary-light rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="bi bi-person text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-1"><?= esc($pembimbing['nama']) ?></h5>
                            <p class="text-muted mb-0"><?= esc($pembimbing['jabatan'] ?? 'Pembimbing') ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="mb-1"><?= count($assignedIds) ?></h4>
                        <p class="text-muted mb-0">Siswa Terbimbing</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="mb-1"><?= esc($pembimbing['username']) ?></h4>
                        <p class="text-muted mb-0">Username</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <h4 class="mb-1"><?= esc($pembimbing['instansi'] ?? '-') ?></h4>
                        <p class="text-muted mb-0">Instansi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pengaturan Siswa -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>
                Pilih Siswa untuk Dibimbing
            </h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('admin/simpan-atur-bimbingan/' . $pembimbing['id']) ?>">
                <?= csrf_field() ?>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">
                                    <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                </th>
                                <th>Nama Siswa</th>
                                <th>NIS</th>
                                <th>Tempat Magang</th>
                                <th>Status Saat Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semuaSiswa as $s): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" 
                                               class="form-check-input siswa-checkbox"
                                               <?= in_array($s['id'], $assignedIds, true) ? 'checked' : '' ?>>
                                    </td>
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
                                        <?php if (in_array($s['id'], $assignedIds, true)): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-person-check me-1"></i>
                                                Terbimbing oleh <?= esc($pembimbing['nama']) ?>
                                            </span>
                                        <?php elseif ($s['pembimbing_id']): ?>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-person-x me-1"></i>
                                                Terbimbing oleh pembimbing lain
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-person-x me-1"></i>
                                                Belum ada pembimbing
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="history.back()">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}
.avatar-lg {
    width: 80px;
    height: 80px;
}
.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
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
