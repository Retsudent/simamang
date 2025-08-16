<?php
/**
 * Test Login dan Input Log Siswa
 * Script untuk mengecek proses login dan input log
 */

echo "=== TEST LOGIN DAN INPUT LOG SISWA ===\n\n";

$baseUrl = 'http://localhost:8080';
$cookieFile = 'test_cookies.txt';

// Clean up cookie file
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}

// 1. Test akses halaman login
echo "1. Testing login page access...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Login page accessible\n";
} else {
    echo "❌ Login page not accessible (HTTP $httpCode)\n";
    exit;
}

// 2. Test login siswa
echo "\n2. Testing student login...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

// Login data untuk siswa
$loginData = [
    'username' => 'Cahyo', // Username siswa dari database
    'password' => 'siswa123' // Password default
];

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
echo "   Final URL: $finalUrl\n";

if (strpos($finalUrl, 'siswa/dashboard') !== false) {
    echo "✅ Login successful, redirected to student dashboard\n";
} else {
    echo "❌ Login failed or unexpected redirect\n";
    echo "Response: " . substr($response, 0, 500) . "...\n";
    exit;
}

// 3. Test akses halaman input log
echo "\n3. Testing input log page access...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/siswa/input-log');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Input log page accessible\n";
    
    // Cek apakah ada form input log
    if (strpos($response, 'input-log') !== false || strpos($response, 'logForm') !== false) {
        echo "✅ Input log form found\n";
    } else {
        echo "❌ Input log form not found\n";
    }
    
    // Cek apakah ada validasi JavaScript
    if (strpos($response, '15') !== false) {
        echo "✅ Found updated validation (15 characters)\n";
    } else if (strpos($response, '50') !== false) {
        echo "⚠️  Found old validation (50 characters)\n";
    } else {
        echo "⚠️  No validation found\n";
    }
    
} else {
    echo "❌ Input log page not accessible (HTTP $httpCode)\n";
    echo "Response: " . substr($response, 0, 500) . "...\n";
    exit;
}

// 4. Test submit form dengan uraian pendek (seharusnya gagal)
echo "\n4. Testing form submission with short description...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/siswa/save-log');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$shortData = [
    'tanggal' => date('Y-m-d'),
    'jam_mulai' => '08:00:00',
    'jam_selesai' => '12:00:00',
    'uraian' => 'Test singkat', // 12 karakter
    'konfirmasi' => '1'
];

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($shortData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
echo "   Final URL: $finalUrl\n";

if (strpos($response, 'minimal') !== false || strpos($response, 'karakter') !== false) {
    echo "✅ Validation error message found (expected)\n";
} else {
    echo "⚠️  No validation error message found\n";
}

// 5. Test submit form dengan uraian panjang (seharusnya berhasil)
echo "\n5. Testing form submission with long description...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/siswa/save-log');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$longData = [
    'tanggal' => date('Y-m-d'),
    'jam_mulai' => '08:00:00',
    'jam_selesai' => '12:00:00',
    'uraian' => 'Test input log aktivitas magang hari ini dengan uraian yang cukup panjang untuk memenuhi syarat minimal 15 karakter dan menjelaskan kegiatan yang dilakukan.',
    'konfirmasi' => '1'
];

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($longData));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
echo "   Final URL: $finalUrl\n";

if (strpos($finalUrl, 'dashboard') !== false && strpos($response, 'berhasil') !== false) {
    echo "✅ Form submission successful\n";
} else if (strpos($response, 'error') !== false || strpos($response, 'kesalahan') !== false) {
    echo "❌ Form submission failed with error\n";
    echo "Response: " . substr($response, 0, 500) . "...\n";
} else {
    echo "⚠️  Unexpected response\n";
    echo "Response: " . substr($response, 0, 500) . "...\n";
}

echo "\n=== TEST COMPLETED ===\n";

// Clean up
if (file_exists($cookieFile)) {
    unlink($cookieFile);
}



