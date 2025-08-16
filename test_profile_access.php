<?php
/**
 * Script test sederhana untuk mengecek akses halaman profil
 * SIMAMANG - Sistem Monitoring Aktivitas Magang
 */

echo "🧪 Testing Profile Page Access...\n\n";

// Test 1: Cek apakah server berjalan
echo "1. Checking server status...\n";
$serverUrl = 'http://localhost:8000';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Server berjalan dan dapat diakses\n";
    echo "   📍 Final URL: $finalUrl\n";
} else {
    echo "   ❌ Server error (HTTP Code: $httpCode)\n";
    echo "   💡 Pastikan server berjalan dengan: php -S localhost:8000 -t public\n";
    exit(1);
}

// Test 2: Cek halaman profil (seharusnya redirect ke login)
echo "\n2. Testing profile page access...\n";
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
        echo "   📍 Redirect URL: $finalUrl\n";
    } else {
        echo "   ⚠️  Profile page berhasil diakses tanpa login\n";
        echo "   📍 Final URL: $finalUrl\n";
        
        // Cek apakah ada error dalam response
        if (strpos($response, 'ErrorException') !== false || strpos($response, 'Undefined array key') !== false) {
            echo "   ❌ Error ditemukan dalam response!\n";
            echo "   📄 Response preview: " . substr($response, 0, 200) . "...\n";
        } else {
            echo "   ✅ Tidak ada error dalam response\n";
        }
    }
} else {
    echo "   ❌ Profile page error (HTTP Code: $httpCode)\n";
    echo "   📄 Response: " . substr($response, 0, 500) . "...\n";
}

// Test 3: Cek file log terbaru
echo "\n3. Checking recent error logs...\n";
$logFile = 'writable/logs/log-' . date('Y-m-d') . '.log';

if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -5); // Ambil 5 baris terakhir
    
    $hasRecentErrors = false;
    foreach ($recentLines as $line) {
        if (strpos($line, 'CRITICAL') !== false || strpos($line, 'ERROR') !== false) {
            echo "   ⚠️  Recent error: " . trim($line) . "\n";
            $hasRecentErrors = true;
        }
    }
    
    if (!$hasRecentErrors) {
        echo "   ✅ Tidak ada error terbaru dalam log\n";
    }
} else {
    echo "   📄 Log file tidak ditemukan: $logFile\n";
}

echo "\n🎯 Test selesai!\n";
echo "\n💡 Untuk testing manual:\n";
echo "   1. Buka browser ke: http://localhost:8000\n";
echo "   2. Login dengan akun yang ada\n";
echo "   3. Klik 'Profil Saya' di sidebar\n";
echo "   4. Perhatikan apakah halaman profil tampil dengan benar\n";
