<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Profil</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="no-loading">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('profile') ?>" class="no-loading">Profil</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <!-- Notifications will be handled by the notification system -->
    <!-- Flashdata will be automatically converted to toasts -->

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Edit Informasi Profil</div>
                <div class="card-body">
                    <form action="<?= base_url('profile/update') ?>" method="post" class="profile-form no-loading">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                <input class="form-control" id="nama" name="nama" type="text" 
                                       value="<?= old('nama', $user['nama']) ?>" required>
                                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['nama'])): ?>
                                    <div class="text-danger small"><?= session()->getFlashdata('errors')['nama'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="username">Username</label>
                                <input class="form-control" id="username" name="username" type="text" 
                                       value="<?= esc($user['username']) ?>" readonly>
                                <div class="form-text">Username tidak dapat diubah</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" 
                                       value="<?= old('email', $user['email']) ?>">
                                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['email'])): ?>
                                    <div class="text-danger small"><?= session()->getFlashdata('errors')['email'] ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="no_hp">No. HP</label>
                                <input class="form-control" id="no_hp" name="no_hp" type="tel" 
                                       value="<?= old('no_hp', $user['no_hp']) ?>">
                                <?php if (session()->getFlashdata('errors') && isset(session()->getFlashdata('errors')['no_hp'])): ?>
                                    <div class="text-danger small"><?= session()->getFlashdata('errors')['no_hp'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="small mb-1" for="alamat">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= old('alamat', $user['alamat']) ?></textarea>
                            </div>
                        </div>

                        <?php if ($role === 'siswa'): ?>
                        <hr class="my-4">
                        <h6 class="mb-3">Informasi Siswa</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="tempat_lahir">Tempat Lahir</label>
                                <input class="form-control" id="tempat_lahir" name="tempat_lahir" type="text" 
                                       value="<?= old('tempat_lahir', $user['tempat_lahir']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="tanggal_lahir">Tanggal Lahir</label>
                                <input class="form-control" id="tanggal_lahir" name="tanggal_lahir" type="date" 
                                       value="<?= old('tanggal_lahir', $user['tanggal_lahir']) ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="jenis_kelamin">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" <?= old('jenis_kelamin', $user['jenis_kelamin']) === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('jenis_kelamin', $user['jenis_kelamin']) === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="kelas">Kelas</label>
                                <input class="form-control" id="kelas" name="kelas" type="text" 
                                       value="<?= old('kelas', $user['kelas']) ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="jurusan">Jurusan</label>
                                <input class="form-control" id="jurusan" name="jurusan" type="text" 
                                       value="<?= old('jurusan', $user['jurusan']) ?>">
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($role === 'pembimbing'): ?>
                        <hr class="my-4">
                        <h6 class="mb-3">Informasi Pembimbing</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="instansi">Instansi</label>
                                <input class="form-control" id="instansi" name="instansi" type="text" 
                                       value="<?= old('instansi', $user['instansi']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="jabatan">Jabatan</label>
                                <input class="form-control" id="jabatan" name="jabatan" type="text" 
                                       value="<?= old('jabatan', $user['jabatan']) ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="small mb-1" for="bidang_keahlian">Bidang Keahlian</label>
                                <textarea class="form-control" id="bidang_keahlian" name="bidang_keahlian" rows="3"><?= old('bidang_keahlian', $user['bidang_keahlian']) ?></textarea>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Form actions-->
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                            <a href="<?= base_url('profile') ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">Informasi</div>
                <div class="card-body">
                    <div class="small text-muted">
                        <p><strong>Catatan:</strong></p>
                        <ul class="mb-0">
                            <li>Field dengan tanda <span class="text-danger">*</span> wajib diisi</li>
                            <li>Username tidak dapat diubah setelah registrasi</li>
                            <li>Data akan diperbarui secara real-time</li>
                            <li>Pastikan data yang diisi akurat dan valid</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
