<?php
/**
 * Script test login tanpa CSRF
 * SIMAMANG - Sistem Monitoring Aktivitas Magang
 */

echo "🧪 Testing Login Without CSRF...\n\n";

$serverUrl = 'http://localhost:8000';

// Test 1: Cek halaman login dan ambil CSRF token
echo "1. Getting CSRF token from login page...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'csrf_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'csrf_cookies.txt');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "   ✅ Login page loaded\n";
    
    // Cari CSRF token
    if (preg_match('/name="csrf_test_name" value="([^"]+)"/', $response, $matches)) {
        $csrfToken = $matches[1];
        echo "   ✅ CSRF token found: " . substr($csrfToken, 0, 10) . "...\n";
    } else {
        echo "   ⚠️  CSRF token not found in form\n";
        $csrfToken = '';
    }
} else {
    echo "   ❌ Login page error (HTTP Code: $httpCode)\n";
    exit(1);
}

// Test 2: Test login dengan CSRF token
echo "\n2. Testing login with CSRF token...\n";
$loginData = [
    'username' => 'admin',
    'password' => 'admin123'
];

if ($csrfToken) {
    $loginData['csrf_test_name'] = $csrfToken;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'csrf_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $headerSize);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";

// Cek redirect
if (strpos($headers, 'Location:') !== false) {
    preg_match('/Location:\s*(.+)/', $headers, $matches);
    $redirectUrl = trim($matches[1]);
    echo "   📍 Redirect to: $redirectUrl\n";
    
    if (strpos($redirectUrl, 'admin/dashboard') !== false) {
        echo "   ✅ Login successful!\n";
    } else {
        echo "   ❌ Login failed\n";
    }
} else {
    echo "   ❌ No redirect found\n";
}

// Test 3: Cek session cookie
echo "\n3. Testing session cookie...\n";
if (strpos($headers, 'Set-Cookie: ci_session=') !== false) {
    echo "   ✅ Session cookie set\n";
} else {
    echo "   ❌ Session cookie not set\n";
}

// Test 4: Cek apakah ada error di response body
echo "\n4. Checking for error messages...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'csrf_cookies.txt');
$response = curl_exec($ch);
curl_close($ch);

if (strpos($response, 'error') !== false) {
    echo "   ⚠️  Error message found in response\n";
    // Extract error message
    if (preg_match('/<div[^>]*class="[^"]*alert[^"]*danger[^"]*"[^>]*>(.*?)<\/div>/s', $response, $matches)) {
        echo "   📄 Error: " . strip_tags($matches[1]) . "\n";
    }
} else {
    echo "   ✅ No error message found\n";
}

// Cleanup
if (file_exists('csrf_cookies.txt')) {
    unlink('csrf_cookies.txt');
}

echo "\n🎯 CSRF test selesai!\n";
echo "\n💡 Jika masih ada masalah:\n";
echo "   1. Cek apakah user admin ada di database\n";
echo "   2. Cek apakah password benar\n";
echo "   3. Cek konfigurasi CSRF di app/Config/App.php\n";
echo "   4. Cek log file untuk error detail\n";
