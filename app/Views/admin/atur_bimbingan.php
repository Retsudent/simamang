<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Atur Siswa Bimbingan</h5>
    <a href="<?= base_url('admin/kelola-pembimbing') ?>" class="btn btn-secondary">Kembali</a>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-4"><strong>Pembimbing</strong><br><?= esc($pembimbing['nama']) ?></div>
        <div class="col-md-4"><strong>Username</strong><br><?= esc($pembimbing['username']) ?></div>
        <div class="col-md-4"><strong>Total Siswa</strong><br><?= count($assignedIds) ?></div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <form method="post" action="<?= base_url('admin/simpan-atur-bimbingan/' . $pembimbing['id']) ?>">
        <?= csrf_field() ?>

        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th style="width:50px;">Pilih</th>
                <th>Nama</th>
                <th>Username</th>
                <th>NIS</th>
                <th>Tempat Magang</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($semuaSiswa as $s): ?>
                <tr>
                  <td>
                    <input type="checkbox" name="siswa_ids[]" value="<?= $s['id'] ?>" 
                      <?= in_array($s['id'], $assignedIds, true) ? 'checked' : '' ?> />
                  </td>
                  <td><?= esc($s['nama']) ?></td>
                  <td><?= esc($s['username']) ?></td>
                  <td><?= esc($s['nis']) ?></td>
                  <td><?= esc($s['tempat_magang']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>


