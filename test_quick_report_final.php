<?php

echo "=== TEST QUICK REPORT FINAL ===\n";

// Test 1: Cek server berjalan
echo "1. Test server:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 302) {
    echo "   ✓ Server berjalan (HTTP Code: $httpCode)\n";
} else {
    echo "   ✗ Server tidak berjalan (HTTP Code: $httpCode)\n";
    exit;
}

// Test 2: Test route tanpa login (harusnya 302)
echo "2. Test route tanpa login (harusnya 302):\n";

$routes = [
    'laporan-minggu-ini' => 'Laporan Minggu Ini',
    'laporan-bulan-ini' => 'Laporan Bulan Ini',
    'laporan-semua-aktivitas' => 'Laporan Semua Aktivitas'
];

foreach ($routes as $route => $name) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/siswa/$route");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   $name: HTTP $httpCode";
    if ($httpCode == 302) {
        echo " ✓ (Route ditemukan, redirect ke login)\n";
    } elseif ($httpCode == 404) {
        echo " ✗ (404 - Route tidak ditemukan)\n";
    } else {
        echo " ? (HTTP Code tidak sesuai: $httpCode)\n";
    }
}

// Test 3: Test login dan akses menu
echo "3. Test login dan akses menu:\n";

// Ambil halaman login untuk CSRF token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');
$loginPage = curl_exec($ch);
curl_close($ch);

// Extract CSRF token
preg_match('/<input type="hidden" name="csrf_test_name" value="([^"]+)"/', $loginPage, $matches);
$csrfToken = $matches[1] ?? '';

if (!empty($csrfToken)) {
    echo "   ✓ CSRF token ditemukan\n";
    
    // Login sebagai siswa
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/login');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'username' => 'siswa1',
        'password' => 'siswa123',
        'csrf_test_name' => $csrfToken
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');
    $loginResponse = curl_exec($ch);
    $loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($loginHttpCode == 200) {
        echo "   ✓ Login berhasil\n";
        
        // Test akses menu laporan cepat setelah login
        echo "   Test akses menu setelah login:\n";
        
        foreach ($routes as $route => $name) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://localhost:8080/siswa/$route");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "     $name: HTTP $httpCode";
            if ($httpCode == 200) {
                echo " ✓ (Berhasil diakses)\n";
            } else {
                echo " ✗ (Gagal diakses)\n";
            }
        }
        
    } else {
        echo "   ✗ Login gagal (HTTP Code: $loginHttpCode)\n";
    }
    
} else {
    echo "   ✗ CSRF token tidak ditemukan\n";
}

// Bersihkan file
if (file_exists('test_cookies.txt')) {
    unlink('test_cookies.txt');
}

echo "\n=== SELESAI ===\n";
echo "Jika semua route menunjukkan HTTP 302 tanpa login, berarti route sudah benar.\n";
echo "Jika setelah login semua route menunjukkan HTTP 200, berarti menu berfungsi.\n";


