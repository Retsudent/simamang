<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Kelola Data Pembimbing</h5>
    <a href="<?= base_url('admin/tambah-pembimbing') ?>" class="btn btn-primary">Tambah Pembimbing</a>
  </div>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

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
                  <a href="<?= base_url('admin/hapus-pembimbing/' . $row['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pembimbing ini? Data yang terkait akan dihapus juga.')">Hapus</a>
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


