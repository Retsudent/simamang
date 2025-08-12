<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - SIMAMANG</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-md-5">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="card-title mb-3">SIMAMANG - Login</h4>

            <?php if(session()->getFlashdata('error')): ?>
              <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('/login') ?>">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" value="<?= old('username') ?>" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="d-grid gap-2">
                <button class="btn btn-primary">Login</button>
                <a href="<?= base_url('register') ?>" class="btn btn-outline-secondary">Daftar Akun Siswa</a>
              </div>
            </form>

          </div>
        </div>
        <p class="text-center text-muted mt-2">SIMAMANG • Sistem Monitoring Aktivitas Magang</p>
      </div>
    </div>
  </div>
</body>
</html>
