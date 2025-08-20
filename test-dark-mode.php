<?php
// Simple test file for dark mode
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Dark Mode - SIMAMANG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Dark Mode CSS -->
    <link rel="stylesheet" href="assets/css/dark-mode.css">
    
    <style>
        body {
            padding: 20px;
            transition: all 0.3s ease;
        }
        .test-card {
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .test-table {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Test Dark Mode SIMAMANG</h1>
        
        <!-- Dark Mode Toggle Button -->
        <div class="d-flex justify-content-end mb-4">
            <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                <i class="bi bi-moon-fill dark-icon"></i>
                <i class="bi bi-sun-fill light-icon"></i>
            </button>
        </div>
        
        <!-- Test Cards -->
        <div class="row">
            <div class="col-md-6">
                <div class="card test-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Test Card 1</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Ini adalah card test untuk memverifikasi dark mode.</p>
                        <button class="btn btn-primary">Test Button</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card test-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Test Card 2</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Card kedua untuk testing dark mode.</p>
                        <button class="btn btn-secondary">Test Button</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Test Table -->
        <div class="card test-card">
            <div class="card-header">
                <h5 class="card-title mb-0">Test Table</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover test-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Admin</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>User</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Bob Johnson</td>
                            <td>User</td>
                            <td><span class="badge bg-danger">Inactive</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Test Form -->
        <div class="card test-card">
            <div class="card-header">
                <h5 class="card-title mb-0">Test Form</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="testName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="testName" placeholder="Masukkan nama">
                    </div>
                    <div class="mb-3">
                        <label for="testEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="testEmail" placeholder="Masukkan email">
                    </div>
                    <div class="mb-3">
                        <label for="testSelect" class="form-label">Pilihan</label>
                        <select class="form-select" id="testSelect">
                            <option>Pilih opsi</option>
                            <option value="1">Opsi 1</option>
                            <option value="2">Opsi 2</option>
                            <option value="3">Opsi 3</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Dark Mode Script -->
    <script src="assets/js/dark-mode.js"></script>
</body>
</html>

