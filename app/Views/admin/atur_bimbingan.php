<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">
            <i class="bi bi-people-fill text-primary me-2"></i>
            Atur Bimbingan Siswa
        </h4>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Notifications will be handled by the notification system -->
    <!-- Flashdata will be automatically converted to toasts -->

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-person-workspace" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1"><?= count($pembimbing) ?></h4>
                            <p class="mb-0">Total Pembimbing</p>
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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-person-check-fill" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1"><?= count(array_filter($siswa, function($s) { return $s['pembimbing_id'] !== null; })) ?></h4>
                            <p class="mb-0">Siswa Terbimbing</p>
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
                            <i class="bi bi-person-x-fill" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1"><?= count(array_filter($siswa, function($s) { return $s['pembimbing_id'] === null; })) ?></h4>
                            <p class="mb-0">Siswa Belum Terbimbing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pembimbing -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-person-workspace me-2"></i>
                Daftar Pembimbing
            </h5>
        </div>
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end mb-3">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label mb-1">Cari Pembimbing</label>
                    <div class="d-flex">
                        <input type="search" name="qp" value="<?= esc($qp ?? '') ?>" class="form-control" placeholder="Nama/Username">
                        <button class="btn btn-outline-primary ms-2" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </form>
            <?php if (empty($pembimbing)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Belum ada data pembimbing</p>
                    <a href="<?= base_url('admin/kelola-pembimbing') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Pembimbing
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Pembimbing</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Jumlah Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pembimbing as $index => $p): ?>
                                <?php 
                                $jumlahSiswa = count(array_filter($siswa, function($s) use ($p) { 
                                    return $s['pembimbing_id'] == $p['id']; 
                                }));
                                ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <strong><?= esc($p['nama']) ?></strong>
                                                <br><small class="text-muted"><?= esc($p['jabatan'] ?? '-') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><code><?= esc($p['username']) ?></code></td>
                                    <td><?= esc($p['email'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $jumlahSiswa > 0 ? 'success' : 'secondary' ?>">
                                            <?= $jumlahSiswa ?> siswa
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/atur-bimbingan-pembimbing/' . $p['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-gear me-1"></i>
                                            Atur Siswa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-mortarboard-fill me-2"></i>
                Daftar Siswa
            </h5>
        </div>
        <div class="card-body">
            <form method="get" class="row g-2 align-items-end mb-3">
                <input type="hidden" name="qp" value="<?= esc($qp ?? '') ?>">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label mb-1">Cari Siswa</label>
                    <div class="input-group">
                        <input type="search" name="qs" value="<?= esc($qs ?? '') ?>" class="form-control" placeholder="Nama/Username/NIS/Tempat magang">
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </form>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($siswa as $index => $s): ?>
                                <?php 
                                $pembimbingSiswa = null;
                                if ($s['pembimbing_id']) {
                                    $pembimbingSiswa = array_filter($pembimbing, function($p) use ($s) { 
                                        return $p['id'] == $s['pembimbing_id']; 
                                    });
                                    $pembimbingSiswa = reset($pembimbingSiswa);
                                }
                                ?>
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
                                        <?php if ($pembimbingSiswa): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-person-check me-1"></i>
                                                <?= esc($pembimbingSiswa['nama']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="bi bi-person-x me-1"></i>
                                                Belum ada pembimbing
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $s['pembimbing_id'] ? 'success' : 'warning' ?>">
                                            <?= $s['pembimbing_id'] ? 'Terbimbing' : 'Belum Terbimbing' ?>
                                        </span>
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
.bg-primary-light {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-success-light {
    background-color: rgba(25, 135, 84, 0.1);
}
</style>

<?= $this->endSection() ?>


