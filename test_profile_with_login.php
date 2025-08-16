<?php
/**
 * Script test untuk mengecek akses halaman profil setelah login
 * SIMAMANG - Sistem Monitoring Aktivitas Magang
 */

echo "🧪 Testing Profile Page After Login...\n\n";

$serverUrl = 'http://localhost:8000';

// Test 1: Login sebagai admin
echo "1. Testing login as admin...\n";
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
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200) {
    if (strpos($finalUrl, 'admin/dashboard') !== false || strpos($response, 'Dashboard') !== false) {
        echo "   ✅ Login admin berhasil\n";
        echo "   📍 Redirect URL: $finalUrl\n";
    } else {
        echo "   ❌ Login admin gagal\n";
        echo "   📍 Final URL: $finalUrl\n";
        echo "   📄 Response preview: " . substr($response, 0, 200) . "...\n";
        exit(1);
    }
} else {
    echo "   ❌ Login error (HTTP Code: $httpCode)\n";
    exit(1);
}

// Test 2: Akses halaman profil setelah login
echo "\n2. Testing profile page access after login...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/profile');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200) {
    if (strpos($response, 'Profil Saya') !== false || strpos($response, 'Detail Akun') !== false) {
        echo "   ✅ Profile page berhasil diakses\n";
        echo "   📍 Final URL: $finalUrl\n";
        
        // Cek apakah ada error dalam response
        if (strpos($response, 'ErrorException') !== false || strpos($response, 'Undefined array key') !== false) {
            echo "   ❌ Error ditemukan dalam response!\n";
            echo "   📄 Error details: " . substr($response, 0, 500) . "...\n";
        } else {
            echo "   ✅ Tidak ada error dalam response\n";
            
            // Cek apakah elemen penting ada
            $checks = [
                'foto_profil' => strpos($response, 'foto_profil') !== false,
                'nama' => strpos($response, 'Nama Lengkap') !== false,
                'username' => strpos($response, 'Username') !== false,
                'role' => strpos($response, 'Role') !== false,
                'upload_button' => strpos($response, 'Upload Foto Baru') !== false,
                'password_button' => strpos($response, 'Ganti Password') !== false
            ];
            
            echo "   📋 Element checks:\n";
            foreach ($checks as $element => $exists) {
                echo "      " . ($exists ? "✅" : "❌") . " $element\n";
            }
        }
    } else {
        echo "   ❌ Profile page tidak tampil dengan benar\n";
        echo "   📍 Final URL: $finalUrl\n";
        echo "   📄 Response preview: " . substr($response, 0, 500) . "...\n";
    }
} else {
    echo "   ❌ Profile page error (HTTP Code: $httpCode)\n";
    echo "   📄 Response: " . substr($response, 0, 500) . "...\n";
}

// Test 3: Test login sebagai pembimbing
echo "\n3. Testing login as pembimbing...\n";
$loginData = [
    'username' => 'pembimbing1',
    'password' => 'pembimbing123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies_pembimbing.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies_pembimbing.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200 && (strpos($finalUrl, 'pembimbing/dashboard') !== false || strpos($response, 'Dashboard') !== false)) {
    echo "   ✅ Login pembimbing berhasil\n";
    
    // Test profile page untuk pembimbing
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serverUrl . '/profile');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies_pembimbing.txt');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && strpos($response, 'Profil Saya') !== false) {
        echo "   ✅ Profile page pembimbing berhasil diakses\n";
    } else {
        echo "   ❌ Profile page pembimbing error\n";
    }
} else {
    echo "   ❌ Login pembimbing gagal\n";
}

// Test 4: Test login sebagai siswa
echo "\n4. Testing login as siswa...\n";
$loginData = [
    'username' => 'siswa1',
    'password' => 'siswa123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $serverUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies_siswa.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies_siswa.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

if ($httpCode == 200 && (strpos($finalUrl, 'siswa/dashboard') !== false || strpos($response, 'Dashboard') !== false)) {
    echo "   ✅ Login siswa berhasil\n";
    
    // Test profile page untuk siswa
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serverUrl . '/profile');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies_siswa.txt');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && strpos($response, 'Profil Saya') !== false) {
        echo "   ✅ Profile page siswa berhasil diakses\n";
    } else {
        echo "   ❌ Profile page siswa error\n";
    }
} else {
    echo "   ❌ Login siswa gagal\n";
}

// Cleanup cookie files
if (file_exists('cookies.txt')) unlink('cookies.txt');
if (file_exists('cookies_pembimbing.txt')) unlink('cookies_pembimbing.txt');
if (file_exists('cookies_siswa.txt')) unlink('cookies_siswa.txt');

echo "\n🎯 Test selesai!\n";
echo "\n💡 Jika semua test berhasil, halaman profil sudah berfungsi dengan baik.\n";
echo "💡 Jika masih ada error, cek log file untuk detail error.\n";
