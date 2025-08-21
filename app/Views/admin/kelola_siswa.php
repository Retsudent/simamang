<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3 search-form-container">
    <h5 class="mb-0">Kelola Data Siswa</h5>
    <div class="d-flex align-items-center gap-2">
      <form method="get" class="d-flex" role="search">
        <div class="input-group">
          <input type="search" name="q" value="<?= esc($q ?? '') ?>" class="form-control" placeholder="Cari nama/username/nis/tempat..." aria-label="Cari">
          <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </div>
      </form>
      <a href="<?= base_url('admin/tambah-siswa') ?>" class="btn btn-primary add-button">Tambah Siswa</a>
    </div>
  </div>

  <?php /* flash handled in layout */ ?>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped mb-0 data-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Username</th>
              <th>NIS</th>
              <th>Tempat Magang</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($siswa)): $no = 1; foreach ($siswa as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($row['nama']) ?></td>
                <td><?= esc($row['username']) ?></td>
                <td><?= esc($row['nis']) ?></td>
                <td><?= esc($row['tempat_magang']) ?></td>
                <td>
                  <a href="<?= base_url('admin/edit-siswa/' . $row['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="<?= base_url('admin/hapus-siswa/' . $row['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Nonaktifkan siswa ini?')">Nonaktifkan</a>
                  <a href="<?= base_url('admin/hapus-siswa/' . $row['id'] . '?permanent=1') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus permanen siswa ini? Semua data terkait akan dihapus. Lanjutkan?')">Hapus Permanen</a>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="6" class="text-center">Belum ada data siswa</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


