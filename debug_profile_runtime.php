<?php
/**
 * Script debugging runtime untuk mengidentifikasi error saat mengakses halaman profil
 * SIMAMANG - Sistem Monitoring Aktivitas Magang
 */

echo "🚀 Memulai debugging runtime halaman profil...\n\n";

// Test 1: Cek apakah server berjalan
echo "1. Testing Server Status...\n";
$serverUrl = 'http://localhost:8000';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Server berjalan di $serverUrl\n";
} else {
    echo "   ❌ Server tidak berjalan atau error (HTTP Code: $httpCode)\n";
    echo "   💡 Jalankan: php -S localhost:8000 -t public\n";
    exit(1);
}

// Test 2: Cek halaman utama
echo "\n2. Testing Homepage...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Homepage berhasil diakses\n";
} else {
    echo "   ❌ Homepage error (HTTP Code: $httpCode)\n";
    echo "   Response: " . substr($response, 0, 200) . "...\n";
}

// Test 3: Cek halaman profil tanpa login (seharusnya redirect ke login)
echo "\n3. Testing Profile Page (without login)...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200) {
    if (strpos($finalUrl, 'login') !== false) {
        echo "   ✅ Profile page redirect ke login (sesuai ekspektasi)\n";
    } else {
        echo "   ⚠️  Profile page berhasil diakses tanpa login (tidak sesuai ekspektasi)\n";
    }
} else {
    echo "   ❌ Profile page error (HTTP Code: $httpCode)\n";
    echo "   Final URL: $finalUrl\n";
    echo "   Response: " . substr($response, 0, 500) . "...\n";
}

// Test 4: Cek file log error
echo "\n4. Checking Error Logs...\n";
$logFiles = [
    'writable/logs/log-' . date('Y-m-d') . '.log',
    'writable/logs/log-' . date('Y-m-d', strtotime('-1 day')) . '.log'
];

foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        echo "   📄 Log file: $logFile\n";
        $logContent = file_get_contents($logFile);
        if (!empty($logContent)) {
            $lines = explode("\n", $logContent);
            $recentErrors = array_slice($lines, -10); // Ambil 10 baris terakhir
            echo "   Recent errors:\n";
            foreach ($recentErrors as $line) {
                if (!empty(trim($line))) {
                    echo "     " . trim($line) . "\n";
                }
            }
        } else {
            echo "   ✅ Log file kosong (tidak ada error)\n";
        }
    } else {
        echo "   📄 Log file tidak ada: $logFile\n";
    }
}

// Test 5: Cek session dan database connection
echo "\n5. Testing Database Connection...\n";
try {
    $host = 'localhost';
    $username = 'postgres';
    $password = 'postgres';
    $database = 'simamang';
    $port = 5432;
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "   ✅ Database connection berhasil\n";
    
    // Test query untuk profile
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM admin");
    $adminCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "   ✅ Admin table accessible (count: $adminCount)\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM pembimbing");
    $pembimbingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "   ✅ Pembimbing table accessible (count: $pembimbingCount)\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM siswa");
    $siswaCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "   ✅ Siswa table accessible (count: $siswaCount)\n";
    
} catch (PDOException $e) {
    echo "   ❌ Database Error: " . $e->getMessage() . "\n";
}

// Test 6: Cek file yang diperlukan untuk profile
echo "\n6. Testing Required Files...\n";
$requiredFiles = [
    'app/Controllers/Profile.php',
    'app/Views/profile/index.php',
    'app/Config/Routes.php',
    'app/Filters/AuthFilter.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file exists\n";
        
        // Cek syntax PHP
        $output = [];
        $returnCode = 0;
        exec("php -l $file 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ $file syntax valid\n";
        } else {
            echo "   ❌ $file syntax error:\n";
            foreach ($output as $line) {
                echo "     $line\n";
            }
        }
    } else {
        echo "   ❌ $file missing\n";
    }
}

// Test 7: Cek folder uploads
echo "\n7. Testing Upload Directory...\n";
$uploadDir = 'writable/uploads/profile/';
if (is_dir($uploadDir)) {
    echo "   ✅ Upload directory exists: $uploadDir\n";
    if (is_writable($uploadDir)) {
        echo "   ✅ Upload directory writable\n";
    } else {
        echo "   ❌ Upload directory not writable\n";
    }
} else {
    echo "   ❌ Upload directory missing: $uploadDir\n";
}

// Test 8: Cek konfigurasi CodeIgniter
echo "\n8. Testing CodeIgniter Configuration...\n";
$configFiles = [
    'app/Config/App.php',
    'app/Config/Database.php',
    'app/Config/Routes.php'
];

foreach ($configFiles as $configFile) {
    if (file_exists($configFile)) {
        echo "   ✅ $configFile exists\n";
    } else {
        echo "   ❌ $configFile missing\n";
    }
}

echo "\n🎯 Debugging runtime selesai!\n";
echo "\n💡 Jika masih ada error, coba:\n";
echo "   1. Login ke aplikasi terlebih dahulu\n";
echo "   2. Akses /profile setelah login\n";
echo "   3. Cek browser developer tools untuk error JavaScript\n";
echo "   4. Cek network tab untuk response error\n";
echo "\n💡 Untuk testing manual:\n";
echo "   1. Buka browser ke: http://localhost:8000\n";
echo "   2. Login dengan akun yang ada\n";
echo "   3. Klik 'Profil Saya' di sidebar\n";
echo "   4. Perhatikan error yang muncul\n";
