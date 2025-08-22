<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIMAMANG</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            opacity: 0.9;
            margin-bottom: 0;
        }

        .register-body {
            padding: 2rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 0.75rem;
            border: 2px solid #e2e8f0;
            padding: 1rem 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .form-floating label {
            padding: 1rem 0.75rem;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-login {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 0.75rem;
            padding: 1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 0.75rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            color: #64748b;
            font-size: 0.875rem;
        }

        .requirement-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .requirement-list li {
            padding: 0.25rem 0;
            color: #64748b;
            font-size: 0.875rem;
        }

        .requirement-list li i {
            margin-right: 0.5rem;
        }

        .requirement-list li.valid {
            color: var(--success-color);
        }

        .requirement-list li.invalid {
            color: var(--danger-color);
        }

        @media (max-width: 768px) {
            .register-container {
                padding: 1rem;
            }
            
            .register-card {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1><i class="bi bi-person-plus"></i> Daftar Siswa</h1>
                <p>Buat akun baru untuk mengakses SIMAMANG</p>
            </div>
            
            <div class="register-body">
                <!-- Notifications will be handled by the notification system -->
                <!-- Flashdata will be automatically converted to toasts -->

                <form method="post" action="<?= base_url('register') ?>" id="registerForm">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" value="<?= old('nama') ?>" required>
                                <label for="nama"><i class="bi bi-person"></i> Nama Lengkap</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?= old('username') ?>" required>
                                <label for="username"><i class="bi bi-person-badge"></i> Username</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password"><i class="bi bi-lock"></i> Password</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" required>
                                <label for="confirm_password"><i class="bi bi-lock-fill"></i> Konfirmasi Password</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nis" name="nis" placeholder="NIS" value="<?= old('nis') ?>" required>
                                <label for="nis"><i class="bi bi-card-text"></i> NIS</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="tempat_magang" name="tempat_magang" placeholder="Tempat Magang" value="<?= old('tempat_magang') ?>" required>
                                <label for="tempat_magang"><i class="bi bi-building"></i> Tempat Magang</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating">
                        <textarea class="form-control" id="alamat_magang" name="alamat_magang" placeholder="Alamat Tempat Magang" style="height: 100px"><?= old('alamat_magang') ?></textarea>
                        <label for="alamat_magang"><i class="bi bi-geo-alt"></i> Alamat Tempat Magang (Opsional)</label>
                    </div>

                    <!-- Password Requirements -->
                    <div class="mb-3">
                        <small class="text-muted">Password harus memenuhi:</small>
                        <ul class="requirement-list mt-2" id="passwordRequirements">
                            <li id="req-length"><i class="bi bi-circle"></i> Minimal 6 karakter</li>
                            <li id="req-uppercase"><i class="bi bi-circle"></i> Minimal 1 huruf besar</li>
                            <li id="req-lowercase"><i class="bi bi-circle"></i> Minimal 1 huruf kecil</li>
                            <li id="req-number"><i class="bi bi-circle"></i> Minimal 1 angka</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="agree" required>
                            <label class="form-check-label" for="agree">
                                Saya setuju dengan <a href="#" class="text-primary">syarat dan ketentuan</a> yang berlaku
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus"></i> Daftar Akun
                    </button>
                </form>

                <div class="divider">
                    <span>atau</span>
                </div>

                <a href="<?= base_url('login') ?>" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Sudah punya akun? Login
                </a>

                <div class="mt-4 text-center">
                    <small class="text-muted">
                        <i class="bi bi-shield-check"></i> Data Anda akan dijaga kerahasiaannya
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const requirements = document.getElementById('passwordRequirements');

            function checkPasswordStrength(password) {
                const checks = {
                    length: password.length >= 6,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password)
                };

                // Update requirement indicators
                document.getElementById('req-length').className = checks.length ? 'valid' : 'invalid';
                document.getElementById('req-uppercase').className = checks.uppercase ? 'valid' : 'invalid';
                document.getElementById('req-lowercase').className = checks.lowercase ? 'valid' : 'invalid';
                document.getElementById('req-number').className = checks.number ? 'valid' : 'invalid';

                // Update icons
                document.getElementById('req-length').innerHTML = `<i class="bi ${checks.length ? 'bi-check-circle' : 'bi-x-circle'}"></i> Minimal 6 karakter`;
                document.getElementById('req-uppercase').innerHTML = `<i class="bi ${checks.uppercase ? 'bi-check-circle' : 'bi-x-circle'}"></i> Minimal 1 huruf besar`;
                document.getElementById('req-lowercase').innerHTML = `<i class="bi ${checks.lowercase ? 'bi-check-circle' : 'bi-x-circle'}"></i> Minimal 1 huruf kecil`;
                document.getElementById('req-number').innerHTML = `<i class="bi ${checks.number ? 'bi-check-circle' : 'bi-x-circle'}"></i> Minimal 1 angka`;

                return Object.values(checks).every(check => check);
            }

            function checkPasswordMatch() {
                if (confirmPassword.value && password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Password tidak cocok');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }

            password.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });

            confirmPassword.addEventListener('input', checkPasswordMatch);

            // Form validation
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                if (!checkPasswordStrength(password.value)) {
                    e.preventDefault();
                    alert('Password tidak memenuhi persyaratan keamanan!');
                    return false;
                }

                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Konfirmasi password tidak cocok!');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
