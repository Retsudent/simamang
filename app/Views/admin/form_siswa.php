<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
  <h5 class="mb-3"><?= isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa' ?></h5>

  <!-- Notifications will be handled by the notification system -->
  <!-- Flashdata will be automatically converted to toasts -->

  <div class="card">
    <div class="card-body">
      <form method="post" action="<?= isset($siswa) ? base_url('admin/update-siswa/' . $siswa['id']) : base_url('admin/simpan-siswa') ?>">
        <?= csrf_field() ?>
        


        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control" value="<?= isset($siswa) ? esc($siswa['nama']) : old('nama') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= isset($siswa) ? esc($siswa['username']) : old('username') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password <?= isset($siswa) ? '(isi jika ingin ubah)' : '' ?></label>
          <input type="password" name="password" class="form-control" <?= isset($siswa) ? '' : 'required' ?>>
        </div>

        <div class="mb-3">
          <label class="form-label">NIS</label>
          <input type="text" name="nis" class="form-control" value="<?= isset($siswa) ? esc($siswa['nis']) : old('nis') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Tempat Magang</label>
          <input type="text" name="tempat_magang" class="form-control" value="<?= isset($siswa) ? esc($siswa['tempat_magang']) : old('tempat_magang') ?>" required>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Tanggal Mulai Magang</label>
              <input type="date" name="tanggal_mulai_magang" class="form-control" value="<?= isset($siswa) ? esc($siswa['tanggal_mulai_magang'] ?? date('Y-m-d')) : (old('tanggal_mulai_magang') ?: date('Y-m-d')) ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Tanggal Selesai Magang</label>
              <input type="date" name="tanggal_selesai_magang" class="form-control" value="<?= isset($siswa) ? esc($siswa['tanggal_selesai_magang'] ?? date('Y-m-d', strtotime('+3 months'))) : (old('tanggal_selesai_magang') ?: date('Y-m-d', strtotime('+3 months'))) ?>" required>
            </div>
          </div>
        </div>

        <div class="d-flex gap-2">
          <a href="<?= base_url('admin/kelola-siswa') ?>" class="btn btn-secondary">Kembali</a>
          <button class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


