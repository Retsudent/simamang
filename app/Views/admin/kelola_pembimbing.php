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
            Kelola Data Pembimbing Magang
          </h2>
          <p class="text-muted mb-0">Kelola dan atur semua data pembimbing magang dalam sistem</p>
        </div>
        <a href="<?= base_url('admin/tambah-pembimbing') ?>" class="btn btn-primary btn-lg">
          <i class="fas fa-plus me-2"></i> 
          Tambah Pembimbing
        </a>
      </div>
    </div>
  </div>

  <!-- Alert Messages -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>
      <?= session()->getFlashdata('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>
      <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Main Table Card -->
  <div class="card border-0 shadow">
    <div class="card-header bg-primary text-white py-3">
      <div class="d-flex align-items-center">
        <i class="fas fa-table me-3"></i>
        <h5 class="mb-0 fw-bold">Daftar Semua Pembimbing Magang</h5>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th class="border-0 text-center" style="width: 5%;">
                <span class="text-muted fw-semibold">#</span>
              </th>
              <th class="border-0" style="width: 30%;">
                <span class="text-muted fw-semibold">Nama</span>
              </th>
              <th class="border-0" style="width: 20%;">
                <span class="text-muted fw-semibold">Username</span>
              </th>
              <th class="border-0" style="width: 25%;">
                <span class="text-muted fw-semibold">Email</span>
              </th>
              <th class="border-0" style="width: 20%;">
                <span class="text-muted fw-semibold">Aksi</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pembimbing)): $no = 1; foreach ($pembimbing as $row): ?>
              <tr class="align-middle">
                <td class="text-center">
                  <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold">
                    <?= $no++ ?>
                  </span>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                      <i class="fas fa-user-tie text-primary"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1 text-dark"><?= esc($row['nama']) ?></h6>
                      <?php if (!empty($row['jabatan'])): ?>
                        <small class="text-muted"><?= esc($row['jabatan']) ?></small>
                      <?php endif; ?>
                    </div>
                  </div>
                </td>
                <td>
                  <code class="bg-light px-2 py-1 rounded text-primary fw-bold"><?= esc($row['username']) ?></code>
                </td>
                <td>
                  <?php if (!empty($row['email'])): ?>
                    <span class="text-dark"><?= esc($row['email']) ?></span>
                  <?php else: ?>
                    <span class="text-muted fst-italic">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="btn-group" role="group">
                    <a href="<?= base_url('admin/edit-pembimbing/' . $row['id']) ?>" 
                       class="btn btn-sm btn-warning" 
                       title="Edit Pembimbing">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="<?= base_url('admin/atur-bimbingan-pembimbing/' . $row['id']) ?>" 
                       class="btn btn-sm btn-info" 
                       title="Atur Bimbingan">
                      <i class="fas fa-users"></i> Atur Bimbingan
                    </a>
                    <button type="button" 
                            class="btn btn-sm btn-danger" 
                            title="Hapus Pembimbing"
                            onclick="confirmDelete(<?= $row['id'] ?>, '<?= esc($row['nama']) ?>')">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr>
                <td colspan="5" class="text-center py-5">
                  <div class="text-muted">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                      <i class="fas fa-inbox fa-3x text-muted"></i>
                    </div>
                    <h5 class="mb-2">Belum ada data pembimbing magang</h5>
                    <p class="mb-3">Mulai dengan menambahkan pembimbing pertama untuk sistem magang Anda</p>
                    <a href="<?= base_url('admin/tambah-pembimbing') ?>" class="btn btn-primary">
                      <i class="fas fa-plus me-2"></i>Tambah Pembimbing Pertama
                    </a>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title fw-bold">
          <i class="fas fa-exclamation-triangle me-2"></i>
          Konfirmasi Hapus
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus pembimbing <strong id="pembimbingName" class="text-danger"></strong>?</p>
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Peringatan:</strong> Tindakan ini akan menghapus data pembimbing secara permanen.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Batal
        </button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
          <i class="fas fa-trash me-2"></i>Hapus
        </a>
      </div>
    </div>
  </div>
</div>

<style>
.avatar-sm {
  width: 40px;
  height: 40px;
}

.table > :not(caption) > * > * {
  padding: 1rem 0.75rem;
}

.card {
  border-radius: 0.5rem;
  overflow: hidden;
}

.shadow {
  box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075) !important;
}
</style>

<script>
function confirmDelete(id, nama) {
  document.getElementById('pembimbingName').textContent = nama;
  document.getElementById('confirmDeleteBtn').href = '<?= base_url('admin/hapus-pembimbing/') ?>' + id;
  
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
  deleteModal.show();
}
</script>
<?= $this->endSection() ?>


