<?php
/**
 * Script untuk debug session
 * SIMAMANG - Sistem Monitoring Aktivitas Magang
 */

echo "🔍 Debugging Session Issues...\n\n";

$serverUrl = 'http://localhost:8000';

// Test 1: Login dan cek session
echo "1. Testing login and session storage...\n";
$loginData = [
    'username' => 'admin',
    'password' => 'admin123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "   HTTP Code: $httpCode\n";
echo "   Final URL: $finalUrl\n";

// Cek apakah ada cookie session
if (file_exists('test_cookies.txt')) {
    $cookieContent = file_get_contents('test_cookies.txt');
    echo "   📄 Cookie file content:\n";
    echo "   " . str_replace("\n", "\n   ", $cookieContent) . "\n";
    
    if (strpos($cookieContent, 'ci_session') !== false) {
        echo "   ✅ Session cookie found\n";
    } else {
        echo "   ❌ Session cookie not found\n";
    }
} else {
    echo "   ❌ Cookie file not created\n";
}

// Test 2: Cek session files
echo "\n2. Checking session files...\n";
$sessionDir = 'writable/session/';
if (is_dir($sessionDir)) {
    $sessionFiles = glob($sessionDir . 'sess_*');
    echo "   📄 Session files count: " . count($sessionFiles) . "\n";
    
    if (count($sessionFiles) > 0) {
        echo "   📄 Session files:\n";
        foreach ($sessionFiles as $file) {
            $content = file_get_contents($file);
            echo "   " . basename($file) . " (size: " . strlen($content) . " bytes)\n";
            echo "   Content preview: " . substr($content, 0, 100) . "...\n";
        }
    } else {
        echo "   ❌ No session files found\n";
    }
} else {
    echo "   ❌ Session directory not found\n";
}

// Test 3: Cek apakah session tersimpan dengan benar
echo "\n3. Testing session retrieval...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/admin/dashboard');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "   Dashboard HTTP Code: $httpCode\n";
echo "   Dashboard Final URL: $finalUrl\n";

if ($httpCode == 200 && strpos($response, 'Dashboard') !== false) {
    echo "   ✅ Dashboard accessible with session\n";
} else {
    echo "   ❌ Dashboard not accessible with session\n";
    echo "   Response preview: " . substr($response, 0, 200) . "...\n";
}

// Test 4: Cek profile page dengan session yang sama
echo "\n4. Testing profile page with session...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'test_cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "   Profile HTTP Code: $httpCode\n";
echo "   Profile Final URL: $finalUrl\n";

if ($httpCode == 200 && strpos($response, 'Profil Saya') !== false) {
    echo "   ✅ Profile page accessible with session\n";
} else {
    echo "   ❌ Profile page not accessible with session\n";
    echo "   Response preview: " . substr($response, 0, 200) . "...\n";
}

// Cleanup
if (file_exists('test_cookies.txt')) {
    unlink('test_cookies.txt');
}

echo "\n🎯 Session debugging selesai!\n";
echo "\n💡 Jika session tidak tersimpan:\n";
echo "   1. Cek permission folder writable/session/\n";
echo "   2. Cek konfigurasi session di app/Config/Session.php\n";
echo "   3. Cek apakah ada error di log file\n";
echo "   4. Restart server PHP\n";
