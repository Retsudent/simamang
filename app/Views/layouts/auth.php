<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login - SIMAMANG' ?></title>
    
    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts - Inter -->
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
            --border-color: #e2e8f0;
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
    <?= $this->renderSection('content') ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

