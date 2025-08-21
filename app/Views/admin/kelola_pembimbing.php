<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3 search-form-container">
    <h5 class="mb-0">Kelola Data Pembimbing</h5>
    <div class="d-flex align-items-center gap-2">
      <form method="get" class="d-flex" role="search">
        <div class="input-group">
          <input type="search" name="q" value="<?= esc($q ?? '') ?>" class="form-control" placeholder="Cari nama/username/email..." aria-label="Cari">
          <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
      </form>
      <a href="<?= base_url('admin/tambah-pembimbing') ?>" class="btn btn-primary add-button">Tambah Pembimbing</a>
    </div>
  </div>

  <?php /* flash handled in layout */ ?>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Username</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pembimbing)): $no = 1; foreach ($pembimbing as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($row['nama']) ?></td>
                <td><?= esc($row['username']) ?></td>
                <td>
                  <a href="<?= base_url('admin/atur-bimbingan-pembimbing/' . $row['id']) ?>" class="btn btn-sm btn-info">Atur Bimbingan</a>
                  <a href="<?= base_url('admin/hapus-pembimbing/' . $row['id']) ?>" class="btn btn-sm btn-warning" onclick="return confirm('Nonaktifkan pembimbing ini?')">Nonaktifkan</a>
                  <a href="<?= base_url('admin/hapus-pembimbing/' . $row['id'] . '?permanent=1') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus permanen pembimbing ini beserta akun users-nya?')">Hapus Permanen</a>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="4" class="text-center">Belum ada data pembimbing</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


