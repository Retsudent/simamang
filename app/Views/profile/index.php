<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Profil Saya</h1>

    

    <div class="row">
        <div class="col-xl-4">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Foto Profil</div>
                <div class="card-body text-center">
                    <!-- Profile picture image-->
                    <?php 
                    // Prioritaskan session data jika ada, fallback ke database data
                    $fotoProfil = session()->get('foto_profil') ?: ($user['foto_profil'] ?? null);
                    ?>
                    <?php if ($fotoProfil): ?>
                        <img class="img-account-profile rounded-circle mb-2" 
                             src="<?= base_url('photo.php?file=' . $fotoProfil . '&type=profile&v=' . time()) ?>" 
                             alt="Foto Profil" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <img class="img-account-profile rounded-circle mb-2" 
                             src="<?= base_url('assets/img/default-avatar.png') ?>" 
                             alt="Default Avatar" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <!-- Profile picture help block-->
                    <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih dari 2 MB</div>
                    
                    <!-- Profile picture upload button-->
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                        Upload Foto Baru
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Detail Akun</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">Nama Lengkap</label>
                            <p class="form-control-plaintext"><?= esc($user['nama']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">Username</label>
                            <p class="form-control-plaintext"><?= esc($user['username']) ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">Email</label>
                            <p class="form-control-plaintext"><?= isset($user['email']) && $user['email'] ? esc($user['email']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">No. HP</label>
                            <p class="form-control-plaintext"><?= isset($user['no_hp']) && $user['no_hp'] ? esc($user['no_hp']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">Alamat</label>
                            <p class="form-control-plaintext">
                                <?php if (isset($user['alamat']) && $user['alamat']): ?>
                                    <?= esc($user['alamat']) ?>
                                <?php elseif (isset($user['alamat_magang']) && $user['alamat_magang']): ?>
                                    <?= esc($user['alamat_magang']) ?>
                                <?php else: ?>
                                    <em class="text-muted">Belum diisi</em>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">Role</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-<?= $role === 'admin' ? 'danger' : ($role === 'pembimbing' ? 'warning' : 'info') ?>">
                                    <?= ucfirst($role) ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <?php if ($role === 'siswa'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">NIS</label>
                            <p class="form-control-plaintext"><?= esc($user['nis']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">Kelas</label>
                            <p class="form-control-plaintext"><?= isset($user['kelas']) && $user['kelas'] ? esc($user['kelas']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">Jurusan</label>
                            <p class="form-control-plaintext"><?= isset($user['jurusan']) && $user['jurusan'] ? esc($user['jurusan']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">Tempat Magang</label>
                            <p class="form-control-plaintext"><?= isset($user['tempat_magang']) && $user['tempat_magang'] ? esc($user['tempat_magang']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($role === 'pembimbing'): ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">Instansi</label>
                            <p class="form-control-plaintext"><?= isset($user['instansi']) && $user['instansi'] ? esc($user['instansi']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">Jabatan</label>
                            <p class="form-control-plaintext"><?= isset($user['jabatan']) && $user['jabatan'] ? esc($user['jabatan']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="small mb-1">Bidang Keahlian</label>
                            <p class="form-control-plaintext"><?= isset($user['bidang_keahlian']) && $user['bidang_keahlian'] ? esc($user['bidang_keahlian']) : '<em class="text-muted">Belum diisi</em>' ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1">Status</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-<?= $user['status'] === 'aktif' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1">Bergabung Sejak</label>
                            <p class="form-control-plaintext"><?= date('d F Y', strtotime($user['created_at'])) ?></p>
                        </div>
                    </div>

                    <!-- Form actions-->
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                            Upload Foto Baru
                        </button>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            Ganti Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Photo Modal -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('profile/update-photo') ?>" method="post" enctype="multipart/form-data" id="uploadPhotoForm">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPhotoModalLabel">Upload Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="foto_profil" class="form-label">Pilih Foto</label>
                        <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*" required>
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="uploadPhotoBtn">
                        <span class="btn-text">Upload</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('profile/change-password') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('uploadPhotoForm');
  const btn = document.getElementById('uploadPhotoBtn');
  if (form && btn) {
    form.addEventListener('submit', function(){
      // Show loading state on button
      btn.classList.add('is-loading');
      btn.querySelector('.btn-text').classList.add('d-none');
      btn.querySelector('.spinner-border').classList.remove('d-none');
      
      // Disable button to prevent double submission
      btn.disabled = true;
    });
  }
  
  // Reset button state when modal is hidden
  const modal = document.getElementById('uploadPhotoModal');
  if (modal) {
    modal.addEventListener('hidden.bs.modal', function(){
      if (btn) {
        btn.classList.remove('is-loading');
        btn.querySelector('.btn-text').classList.remove('d-none');
        btn.querySelector('.spinner-border').classList.add('d-none');
        btn.disabled = false;
      }
    });
  }
});
</script>
