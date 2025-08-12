<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
  <h5 class="mb-3">Laporan Magang Semua Siswa</h5>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <form method="post" action="<?= base_url('admin/generate-laporan-admin') ?>">
        <?= csrf_field() ?>
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Siswa</label>
            <select name="siswa_id" class="form-select" required>
              <option value="">Pilih Siswa</option>
              <?php foreach ($siswa as $s): ?>
                <option value="<?= $s['id'] ?>"><?= esc($s['nama']) ?> (<?= esc($s['nis']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control" required>
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary w-100">Tampilkan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


