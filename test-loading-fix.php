<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Loading Fix</title>
    
    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Global Page Loader */
        .page-loader-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .page-loader-overlay.active { display: flex; }

        .page-loader-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .loader-logo {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, #1e3a8a 0%, #10b981 100%);
            box-shadow: 0 6px 18px rgba(30, 58, 138, 0.25);
            animation: pulseGlow 1.4s ease-in-out infinite;
        }

        .loader-logo i { font-size: 1.2rem; }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); box-shadow: 0 6px 18px rgba(30, 58, 138, 0.25); }
            50% { transform: scale(1.06); box-shadow: 0 10px 24px rgba(16, 185, 129, 0.35); }
        }

        /* Top Progress Bar */
        .top-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(90deg, #1e3a8a, #10b981);
            width: 0;
            transition: width 0.3s ease;
            z-index: 2001;
        }

        body {
            padding: 2rem;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .test-section {
            margin-bottom: 2rem;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Page Loader -->
    <div id="pageLoader" class="page-loader-overlay">
        <div class="page-loader-box">
            <div class="loader-logo">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="loading-text">Memuat Halaman</div>
        </div>
    </div>

    <!-- Top Progress Bar -->
    <div id="topProgress" class="top-progress"></div>

    <h1>Test Loading Fix</h1>
    
    <div class="test-section">
        <h3>Test Page Loader</h3>
        <p>Click the buttons below to test the page loader functionality:</p>
        <button class="btn btn-primary" onclick="showLoader()">Show Loader</button>
        <button class="btn btn-secondary" onclick="hideLoader()">Hide Loader</button>
        <button class="btn btn-success" onclick="testProgress()">Test Progress Bar</button>
    </div>

    <div class="test-section">
        <h3>Test Navigation</h3>
        <p>These links should trigger the loader:</p>
        <a href="#" class="btn btn-outline-primary use-loader">Test Link with Loader</a>
        <a href="#" class="btn btn-outline-secondary">Test Link without Loader</a>
    </div>

    <div class="test-section">
        <h3>Test Form</h3>
        <form data-loader="page">
            <div class="mb-3">
                <label for="testInput" class="form-label">Test Input</label>
                <input type="text" class="form-control" id="testInput" placeholder="Enter something">
            </div>
            <button type="submit" class="btn btn-primary">Submit Form</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded successfully');
            
            // Global loader helpers
            const pageLoader = document.getElementById('pageLoader');
            const topProgress = document.getElementById('topProgress');
            
            window.showLoader = function() { 
                pageLoader.classList.add('active'); 
                console.log('Loader shown');
            }
            
            window.hideLoader = function() { 
                pageLoader.classList.remove('active'); 
                console.log('Loader hidden');
            }
            
            window.testProgress = function() {
                topProgress.style.width = '20%';
                setTimeout(() => topProgress.style.width = '70%', 500);
                setTimeout(() => topProgress.style.width = '100%', 1000);
                setTimeout(() => topProgress.style.width = '0', 1500);
            }

            // Mark links with class .use-loader to show loader on navigation
            document.querySelectorAll('a.use-loader').forEach(function(a){
                a.addEventListener('click', function(e){
                    e.preventDefault();
                    console.log('Link with loader clicked');
                    showLoader();
                    setTimeout(hideLoader, 2000);
                });
            });

            // Forms that should show page loader on submit
            document.querySelectorAll('form[data-loader="page"]').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    console.log('Form with loader submitted');
                    showLoader();
                    setTimeout(hideLoader, 2000);
                });
            });

            // Auto-hide loader on page show (bfcache)
            window.addEventListener('pageshow', function() { 
                hideLoader(); 
                console.log('Page show event - loader hidden');
            });

            // Top progress bar basic behavior
            function startProgress(){ 
                topProgress.style.width = '20%'; 
                requestAnimationFrame(()=> topProgress.style.width = '70%'); 
            }
            function endProgress(){ 
                topProgress.style.width = '100%'; 
                setTimeout(()=> topProgress.style.width = '0', 250); 
            }
            
            document.querySelectorAll('a.use-loader').forEach(function(a){
                a.addEventListener('click', function(e){ 
                    if (!(e.metaKey||e.ctrlKey||a.target==='_blank')) startProgress(); 
                });
            });
            
            document.querySelectorAll('form[data-loader="page"]').forEach(function(form){
                form.addEventListener('submit', function(){ startProgress(); });
            });
            
            window.addEventListener('pageshow', function(){ endProgress(); });

            console.log('Loading test initialized');
        });
    </script>
</body>
</html>

