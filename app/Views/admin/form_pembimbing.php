<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h2 class="fw-bold text-primary mb-1">
            <i class="fas fa-user-tie me-3"></i>
            <?= isset($pembimbing) ? 'Edit Data Pembimbing: ' . esc($pembimbing['nama']) : 'Tambah Pembimbing Magang Baru' ?>
          </h2>
          <p class="text-muted mb-0">
            <?= isset($pembimbing) ? 'Perbarui informasi pembimbing magang yang sudah ada' : 'Daftarkan pembimbing magang baru ke dalam sistem' ?>
          </p>
        </div>
        <a href="<?= base_url('admin/kelola-pembimbing') ?>" class="btn btn-outline-secondary btn-lg">
          <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
      </div>
    </div>
  </div>

  <!-- Alert Messages -->
  <!-- Notifications will be handled by the notification system -->
  <!-- Flashdata will be automatically converted to toasts -->

  <!-- Main Form Card -->
  <div class="card border-0 shadow">
    <div class="card-header bg-primary text-white py-3">
      <div class="d-flex align-items-center">
        <i class="fas fa-edit me-3"></i>
        <h5 class="mb-0 fw-bold">
          <?= isset($pembimbing) ? 'Form Edit Data Pembimbing Magang' : 'Form Pendaftaran Pembimbing Magang' ?>
        </h5>
      </div>
    </div>
    <div class="card-body p-4">
      <form method="post" action="<?= isset($pembimbing) ? base_url('admin/update-pembimbing/' . $pembimbing['id']) : base_url('admin/simpan-pembimbing') ?>">
        <?= csrf_field() ?>

        <div class="row">
          <!-- Kolom Kiri - Informasi Pribadi -->
          <div class="col-lg-6">
            <h6 class="text-primary mb-3">Informasi Pribadi</h6>
            
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Nama Lengkap <span class="text-danger">*</span>
              </label>
              <input type="text" 
                     name="nama" 
                     class="form-control <?= session()->getFlashdata('errors.nama') ? 'is-invalid' : '' ?>" 
                     value="<?= old('nama', isset($pembimbing) ? $pembimbing['nama'] : '') ?>" 
                     placeholder="Masukkan nama lengkap pembimbing"
                     required>
              <?php if (session()->getFlashdata('errors.nama')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.nama') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Username <span class="text-danger">*</span>
              </label>
              <input type="text" 
                     name="username" 
                     class="form-control <?= session()->getFlashdata('errors.username') ? 'is-invalid' : '' ?>" 
                     value="<?= old('username', isset($pembimbing) ? $pembimbing['username'] : '') ?>" 
                     placeholder="Masukkan username unik"
                     required>
              <?php if (session()->getFlashdata('errors.username')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.username') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Password <?= isset($pembimbing) ? '(kosongkan jika tidak ingin mengubah)' : '' ?>
                <?= !isset($pembimbing) ? '<span class="text-danger">*</span>' : '' ?>
              </label>
              <input type="password" 
                     name="password" 
                     class="form-control <?= session()->getFlashdata('errors.password') ? 'is-invalid' : '' ?>" 
                     placeholder="<?= isset($pembimbing) ? 'Kosongkan jika tidak ingin mengubah' : 'Masukkan password minimal 6 karakter' ?>"
                     <?= !isset($pembimbing) ? 'required' : '' ?>>
              <?php if (session()->getFlashdata('errors.password')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.password') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Email
              </label>
              <input type="email" 
                     name="email" 
                     class="form-control <?= session()->getFlashdata('errors.email') ? 'is-invalid' : '' ?>" 
                     value="<?= old('email', isset($pembimbing) ? $pembimbing['email'] : '') ?>" 
                     placeholder="contoh@email.com">
              <?php if (session()->getFlashdata('errors.email')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.email') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                No. HP
              </label>
              <input type="text" 
                     name="no_hp" 
                     class="form-control <?= session()->getFlashdata('errors.no_hp') ? 'is-invalid' : '' ?>" 
                     value="<?= old('no_hp', isset($pembimbing) ? $pembimbing['no_hp'] : '') ?>" 
                     placeholder="08xxxxxxxxxx">
              <?php if (session()->getFlashdata('errors.no_hp')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.no_hp') ?></div>
              <?php endif; ?>
            </div>
          </div>

          <!-- Kolom Kanan - Informasi Profesional -->
          <div class="col-lg-6">
            <h6 class="text-success mb-3">Informasi Profesional</h6>
            
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Instansi
              </label>
              <input type="text" 
                     name="instansi" 
                     class="form-control <?= session()->getFlashdata('errors.instansi') ? 'is-invalid' : '' ?>" 
                     value="<?= old('instansi', isset($pembimbing) ? $pembimbing['instansi'] : '') ?>" 
                     placeholder="Nama instansi tempat bekerja">
              <?php if (session()->getFlashdata('errors.instansi')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.instansi') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Jabatan
              </label>
              <input type="text" 
                     name="jabatan" 
                     class="form-control <?= session()->getFlashdata('errors.jabatan') ? 'is-invalid' : '' ?>" 
                     value="<?= old('jabatan', isset($pembimbing) ? $pembimbing['jabatan'] : '') ?>" 
                     placeholder="Jabatan di instansi">
              <?php if (session()->getFlashdata('errors.jabatan')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.jabatan') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Bidang Keahlian
              </label>
              <textarea name="bidang_keahlian" 
                        class="form-control <?= session()->getFlashdata('errors.bidang_keahlian') ? 'is-invalid' : '' ?>" 
                        rows="3" 
                        placeholder="Bidang keahlian atau spesialisasi"><?= old('bidang_keahlian', isset($pembimbing) ? $pembimbing['bidang_keahlian'] : '') ?></textarea>
              <?php if (session()->getFlashdata('errors.bidang_keahlian')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.bidang_keahlian') ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">
                Alamat
              </label>
              <textarea name="alamat" 
                        class="form-control <?= session()->getFlashdata('errors.alamat') ? 'is-invalid' : '' ?>" 
                        rows="3" 
                        placeholder="Alamat lengkap tempat tinggal"><?= old('alamat', isset($pembimbing) ? $pembimbing['alamat'] : '') ?></textarea>
              <?php if (session()->getFlashdata('errors.alamat')): ?>
                <div class="invalid-feedback"><?= session()->getFlashdata('errors.alamat') ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <hr class="my-4">

        <!-- Action Buttons -->
        <div class="d-flex gap-3 justify-content-end">
          <a href="<?= base_url('admin/kelola-pembimbing') ?>" class="btn btn-light btn-lg px-4">
            <i class="fas fa-times me-2"></i> Batal
          </a>
          <button type="submit" class="btn btn-primary btn-lg px-4">
            <i class="fas fa-save me-2"></i> 
            <?= isset($pembimbing) ? 'Update Data Pembimbing' : 'Simpan Data Pembimbing' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.card {
  border-radius: 0.5rem;
  overflow: hidden;
}

.shadow {
  box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075) !important;
}

.btn-lg {
  border-radius: 0.5rem;
  font-weight: 600;
}
</style>
<?= $this->endSection() ?>


