<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
  <h5 class="mb-3">Tambah Pembimbing</h5>

  <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
          <li><?= $error ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <form method="post" action="<?= base_url('admin/simpan-pembimbing') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <!-- Kolom opsional dihapus agar sesuai dengan skema tabel saat ini -->

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Email (opsional)</label>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">No. HP (opsional)</label>
            <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp') ?>">
          </div>
        </div>

        

        <div class="d-flex gap-2">
          <a href="<?= base_url('admin/kelola-pembimbing') ?>" class="btn btn-secondary">Kembali</a>
          <button class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


