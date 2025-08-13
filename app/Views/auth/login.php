<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - SIMAMANG</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --text-primary: #2d3748;
      --text-secondary: #718096;
      --bg-light: #f7fafc;
      --shadow-soft: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --shadow-medium: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --border-radius: 20px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: var(--primary-gradient);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    /* Animated Background Elements */
    .bg-shapes {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 1;
    }

    .bg-shape {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      animation: float 6s ease-in-out infinite;
    }

    .bg-shape:nth-child(1) {
      width: 80px;
      height: 80px;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }

    .bg-shape:nth-child(2) {
      width: 120px;
      height: 120px;
      top: 60%;
      right: 10%;
      animation-delay: 2s;
    }

    .bg-shape:nth-child(3) {
      width: 60px;
      height: 60px;
      bottom: 20%;
      left: 20%;
      animation-delay: 4s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }

    /* Main Container */
    .login-container {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 420px;
    }

    /* Login Card */
    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: var(--border-radius);
      box-shadow: var(--shadow-medium);
      border: 1px solid rgba(255, 255, 255, 0.2);
      overflow: hidden;
      transform: translateY(0);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    /* Card Header */
    .card-header {
      background: var(--accent-gradient);
      padding: 2rem 2rem 1.5rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .card-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      opacity: 0.3;
    }

    .logo-container {
      position: relative;
      z-index: 1;
    }

    .logo-icon {
      width: 60px;
      height: 60px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .logo-icon i {
      font-size: 1.8rem;
      color: white;
    }

    .brand-title {
      color: white;
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      letter-spacing: -0.025em;
    }

    .brand-subtitle {
      color: rgba(255, 255, 255, 0.9);
      font-size: 0.95rem;
      font-weight: 400;
      opacity: 0.9;
    }

    /* Card Body */
    .card-body {
      padding: 2rem;
    }

    .welcome-text {
      text-align: center;
      margin-bottom: 2rem;
    }

    .welcome-title {
      color: var(--text-primary);
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
      color: var(--text-secondary);
      font-size: 0.9rem;
      font-weight: 400;
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-label {
      color: var(--text-primary);
      font-weight: 500;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      display: block;
    }

    .form-control {
      width: 100%;
      padding: 0.875rem 1rem 0.875rem 3rem;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 0.95rem;
      font-weight: 400;
      color: var(--text-primary);
      background: #f8fafc;
      transition: all 0.2s ease;
      position: relative;
    }

    .form-control:focus {
      outline: none;
      border-color: #667eea;
      background: white;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-1px);
    }

    .form-control::placeholder {
      color: #a0aec0;
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
      font-size: 1rem;
      transition: color 0.2s ease;
      z-index: 2;
    }

    .form-control:focus + .input-icon {
      color: #667eea;
    }

    /* Button Styles */
    .btn {
      padding: 0.875rem 1.5rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.95rem;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-primary {
      background: var(--primary-gradient);
      color: white;
      width: 100%;
      margin-bottom: 1rem;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .btn-outline {
      background: transparent;
      color: var(--text-secondary);
      border: 2px solid #e2e8f0;
      width: 100%;
    }

    .btn-outline:hover {
      background: var(--text-secondary);
      color: white;
      border-color: var(--text-secondary);
      transform: translateY(-1px);
    }

    /* Alert Styles */
    .alert {
      border-radius: 12px;
      border: none;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
      font-weight: 500;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .alert-danger {
      background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
      color: #c53030;
      border-left: 4px solid #e53e3e;
    }

    .alert-success {
      background: linear-gradient(135deg, #c6f6d5 0%, #9ae6b4 100%);
      color: #22543d;
      border-left: 4px solid #38a169;
    }

    /* Footer */
    .card-footer {
      text-align: center;
      padding: 1.5rem 2rem 2rem;
      border-top: 1px solid #e2e8f0;
      background: rgba(248, 250, 252, 0.8);
    }

    .footer-text {
      color: var(--text-secondary);
      font-size: 0.85rem;
      font-weight: 400;
      margin: 0;
    }

    .footer-text strong {
      color: var(--text-primary);
      font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 480px) {
      .login-container {
        max-width: 100%;
        margin: 0 10px;
      }
      
      .card-header {
        padding: 1.5rem 1.5rem 1rem;
      }
      
      .card-body {
        padding: 1.5rem;
      }
      
      .card-footer {
        padding: 1rem 1.5rem 1.5rem;
      }
      
      .brand-title {
        font-size: 1.5rem;
      }
      
      .logo-icon {
        width: 50px;
        height: 50px;
      }
    }

    /* Loading Animation */
    .btn-loading {
      position: relative;
      color: transparent;
    }

    .btn-loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -10px;
      border: 2px solid transparent;
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Fade In Animation */
    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>
  <!-- Animated Background Shapes -->
  <div class="bg-shapes">
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
    <div class="bg-shape"></div>
  </div>

  <!-- Main Login Container -->
  <div class="login-container fade-in">
    <div class="login-card">
      <!-- Card Header -->
      <div class="card-header">
        <div class="logo-container">
          <div class="logo-icon">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <h1 class="brand-title">SIMAMANG</h1>
          <p class="brand-subtitle">Sistem Monitoring Aktivitas Magang</p>
        </div>
      </div>

      <!-- Card Body -->
      <div class="card-body">
        <div class="welcome-text">
          <h2 class="welcome-title">Selamat Datang Kembali</h2>
          <p class="welcome-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Flash Messages -->
        <?php if(session()->getFlashdata('error')): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
          </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
          </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="post" action="<?= base_url('/login') ?>" id="loginForm">
          <?= csrf_field() ?>
          
          <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" 
                   name="username" 
                   value="<?= old('username') ?>" 
                   class="form-control" 
                   placeholder="Masukkan username Anda"
                   required>
            <i class="fas fa-user input-icon"></i>
          </div>
          
          <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" 
                   name="password" 
                   class="form-control" 
                   placeholder="Masukkan password Anda"
                   required>
            <i class="fas fa-lock input-icon"></i>
          </div>
          
          <button type="submit" class="btn btn-primary" id="loginBtn">
            <i class="fas fa-sign-in-alt"></i>
            Masuk ke SIMAMANG
          </button>
          
          <a href="<?= base_url('register') ?>" class="btn btn-outline">
            <i class="fas fa-user-plus"></i>
            Daftar Akun Siswa
          </a>
        </form>
      </div>

      <!-- Card Footer -->
      <div class="card-footer">
        <p class="footer-text">
          <strong>SIMAMANG</strong> • Sistem Monitoring Aktivitas Magang
        </p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Form submission with loading state
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const loginBtn = document.getElementById('loginBtn');
      loginBtn.classList.add('btn-loading');
      loginBtn.disabled = true;
      
      // Re-enable button after 3 seconds (fallback)
      setTimeout(() => {
        loginBtn.classList.remove('btn-loading');
        loginBtn.disabled = false;
      }, 3000);
    });

    // Input focus effects
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.classList.remove('focused');
      });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 300);
      });
    }, 5000);
  </script>
</body>
</html>
