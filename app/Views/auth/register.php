<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Registrasi Akun Siswa</h6>
                </div>
                <div class="card-body">
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
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('register') ?>">
                      <?= csrf_field() ?>
                      <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= old('nama') ?>" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" value="<?= old('username') ?>" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" name="nis" value="<?= old('nis') ?>" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Tempat Magang</label>
                        <input type="text" name="tempat_magang" value="<?= old('tempat_magang') ?>" class="form-control" required>
                      </div>
                      <div class="d-grid gap-2">
                        <button class="btn btn-primary">Daftar</button>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-secondary">Sudah punya akun? Login</a>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 </div>



<?= $this->endSection() ?>
