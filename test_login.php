<?php
// Script untuk test login dengan struktur database baru
echo "=== TEST LOGIN SIMAMANG ===\n\n";

// Test data yang tersedia
$testUsers = [
    ['username' => 'admin', 'password' => 'admin123', 'expected_role' => 'admin'],
    ['username' => 'pembimbing1', 'password' => 'pembimbing123', 'expected_role' => 'pembimbing'],
    ['username' => 'siswa1', 'password' => 'siswa123', 'expected_role' => 'siswa']
];

foreach ($testUsers as $user) {
    echo "Testing login: {$user['username']}\n";
    
    // Simulasi login process
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=simamang', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Cek di semua tabel
        $found = false;
        $userData = null;
        
        // Cek admin
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? AND status = 'aktif'");
        $stmt->execute([$user['username']]);
        if ($admin = $stmt->fetch()) {
            $userData = $admin;
            $userData['role'] = 'admin';
            $userData['table'] = 'admin';
            $found = true;
        }
        
        // Cek pembimbing
        if (!$found) {
            $stmt = $pdo->prepare("SELECT * FROM pembimbing WHERE username = ? AND status = 'aktif'");
            $stmt->execute([$user['username']]);
            if ($pembimbing = $stmt->fetch()) {
                $userData = $pembimbing;
                $userData['role'] = 'pembimbing';
                $userData['table'] = 'pembimbing';
                $found = true;
            }
        }
        
        // Cek siswa
        if (!$found) {
            $stmt = $pdo->prepare("SELECT * FROM siswa WHERE username = ? AND status = 'aktif'");
            $stmt->execute([$user['username']]);
            if ($siswa = $stmt->fetch()) {
                $userData = $siswa;
                $userData['role'] = 'siswa';
                $userData['table'] = 'siswa';
                $found = true;
            }
        }
        
        if ($found && password_verify($user['password'], $userData['password'])) {
            echo "✅ Login berhasil!\n";
            echo "   Role: {$userData['role']}\n";
            echo "   Nama: {$userData['nama']}\n";
            echo "   Tabel: {$userData['table']}\n";
            
            if ($userData['role'] === $user['expected_role']) {
                echo "   ✅ Role sesuai ekspektasi\n";
            } else {
                echo "   ❌ Role tidak sesuai ekspektasi\n";
            }
        } else {
            echo "❌ Login gagal!\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

echo "=== TEST SELESAI ===\n";
?>
