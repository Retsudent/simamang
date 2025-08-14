<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAMANG</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #1e3a8a;
            --primary-light: #3b82f6;
            --primary-dark: #1e40af;
            --secondary-color: #64748b;
            --accent-color: #10b981;
            --accent-light: #34d399;
            --background-light: #f8fafc;
            --background-white: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--accent-color) 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;
        }
        .login-container { width: 100%; max-width: 1200px; display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; }
        .login-hero { color: white; text-align: center; padding: 2rem; }
        .login-hero h1 { font-size: 3rem; font-weight: 700; margin-bottom: 1rem; background: linear-gradient(135deg, #ffffff 0%, var(--accent-light) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .login-hero p { font-size: 1.125rem; opacity: 0.9; margin-bottom: 2rem; line-height: 1.7; }
        .feature-list { list-style: none; text-align: left; max-width: 400px; margin: 0 auto; }
        .feature-list li { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; font-size: 1rem; opacity: 0.9; }
        .feature-list i { color: var(--accent-light); font-size: 1.25rem; width: 1.5rem; }
        .login-form-container { background: var(--background-white); border-radius: 1.5rem; box-shadow: var(--shadow-xl); padding: 3rem; position: relative; overflow: hidden; }
        .login-form-container::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%); }
        .login-header { text-align: center; margin-bottom: 2.5rem; }
        .login-header .logo { width: 4rem; height: 4rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%); border-radius: 1rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; }
        .login-header h2 { font-size: 1.75rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; }
        .login-header p { color: var(--text-secondary); font-size: 1rem; }
        .form-floating { margin-bottom: 1.5rem; }
        .form-control { border: 2px solid var(--border-color); border-radius: 0.75rem; padding: 1rem 1rem; font-size: 1rem; transition: all 0.3s ease; background: var(--background-white); }
        .form-control:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .form-floating label { color: var(--text-secondary); font-weight: 500; }
        .form-floating > .form-control:focus ~ label, .form-floating > .form-control:not(:placeholder-shown) ~ label { color: var(--primary-color); font-weight: 600; }
        .btn-login { width: 100%; background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%); border: none; border-radius: 0.75rem; padding: 1rem; font-size: 1rem; font-weight: 600; color: white; transition: all 0.3s ease; margin-bottom: 1.5rem; }
        .btn-login:hover { background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%); transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .login-footer { text-align: center; color: var(--text-secondary); font-size: 0.875rem; }
        .login-footer a { color: var(--primary-color); text-decoration: none; font-weight: 600; }
        .login-footer a:hover { color: var(--primary-dark); }
        .alert { border: none; border-radius: 0.75rem; padding: 1rem 1.5rem; margin-bottom: 1.5rem; }
        .alert-danger { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-success { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #166534; border-left: 4px solid var(--accent-color); }
        @media (max-width: 768px) { .login-container { grid-template-columns: 1fr; gap: 2rem; } .login-hero { order: 2; padding: 1rem; } .login-hero h1 { font-size: 2rem; } .login-form-container { order: 1; padding: 2rem; } }
        @media (max-width: 480px) { body { padding: 1rem; } .login-form-container { padding: 1.5rem; } .login-hero h1 { font-size: 1.75rem; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-hero">
            <h1>SIMAMANG</h1>
            <p>Sistem Monitoring Aktivitas Magang yang memudahkan siswa mencatat aktivitas harian dan pembimbing memberikan feedback secara real-time.</p>
            <ul class="feature-list">
                <li><i class="bi bi-check-circle-fill"></i><span>Input log aktivitas harian dengan mudah</span></li>
                <li><i class="bi bi-check-circle-fill"></i><span>Upload bukti aktivitas (PDF, gambar, dokumen)</span></li>
                <li><i class="bi bi-check-circle-fill"></i><span>Komentar dan validasi dari pembimbing</span></li>
                <li><i class="bi bi-check-circle-fill"></i><span>Generate laporan otomatis dalam format PDF</span></li>
                <li><i class="bi bi-check-circle-fill"></i><span>Dashboard real-time untuk monitoring</span></li>
            </ul>
        </div>

        <div class="login-form-container">
            <div class="login-header">
                <div class="logo"><i class="bi bi-graph-up-arrow"></i></div>
                <h2>Selamat Datang</h2>
                <p>Masuk ke akun SIMAMANG Anda</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username"><i class="bi bi-person me-2"></i>Username</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                </div>

                <button type="submit" class="btn btn-login"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke SIMAMANG</button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun? <a href="<?= base_url('register') ?>">Daftar sebagai Siswa</a></p>
                <p class="mt-2"><small><i class="bi bi-shield-check me-1"></i>Data Anda aman dan terenkripsi</small></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) { (new bootstrap.Alert(alert)).close(); });
        }, 5000);
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = document.querySelector('.btn-login');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
