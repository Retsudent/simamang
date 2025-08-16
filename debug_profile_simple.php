<?php
/**
 * Script debugging sederhana untuk mengidentifikasi masalah pada halaman profil
 */

echo "🚀 Memulai debugging halaman profil...\n\n";

// Test 1: Cek file yang diperlukan
echo "1. Testing Required Files...\n";
$requiredFiles = [
    'app/Controllers/Profile.php',
    'app/Views/profile/index.php',
    'app/Views/profile/edit.php',
    'app/Views/layouts/main.php',
    'app/Config/Routes.php',
    'app/Config/Autoload.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file exists\n";
    } else {
        echo "   ❌ $file missing\n";
    }
}

// Test 2: Cek folder uploads
echo "\n2. Testing Upload Directory...\n";
$uploadPath = __DIR__ . '/writable/uploads/profile/';
if (is_dir($uploadPath)) {
    echo "   ✅ Upload directory exists: $uploadPath\n";
    if (is_writable($uploadPath)) {
        echo "   ✅ Upload directory is writable\n";
    } else {
        echo "   ❌ Upload directory is not writable\n";
    }
} else {
    echo "   ❌ Upload directory not found: $uploadPath\n";
    echo "   Attempting to create...\n";
    if (mkdir($uploadPath, 0755, true)) {
        echo "   ✅ Upload directory created successfully\n";
    } else {
        echo "   ❌ Failed to create upload directory\n";
    }
}

// Test 3: Cek sintaks PHP files
echo "\n3. Testing PHP Syntax...\n";
$phpFiles = [
    'app/Controllers/Profile.php',
    'app/Views/profile/index.php',
    'app/Views/profile/edit.php'
];

foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = [];
        $returnCode = 0;
        exec("php -l $file 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ $file - Syntax OK\n";
        } else {
            echo "   ❌ $file - Syntax Error:\n";
            foreach ($output as $line) {
                echo "      $line\n";
            }
        }
    }
}

// Test 4: Cek database connection sederhana
echo "\n4. Testing Database Connection...\n";
try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'simamang';
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "   ✅ Database connected successfully\n";
    
    // Test query sederhana
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && $result['test'] == 1) {
        echo "   ✅ Database query working\n";
    }
    
    // Cek tabel yang diperlukan
    echo "\n5. Testing Required Tables...\n";
    $tables = ['admin', 'pembimbing', 'siswa'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "   ✅ Table $table exists\n";
                
                // Cek field foto_profil
                $columns = $pdo->query("SHOW COLUMNS FROM $table LIKE 'foto_profil'");
                if ($columns->rowCount() > 0) {
                    echo "   ✅ Field foto_profil exists in $table\n";
                } else {
                    echo "   ❌ Field foto_profil missing in $table\n";
                }
            } else {
                echo "   ❌ Table $table not found\n";
            }
        } catch (Exception $e) {
            echo "   ❌ Error checking table $table: " . $e->getMessage() . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "   ❌ Database Error: " . $e->getMessage() . "\n";
    echo "   💡 Pastikan MySQL server berjalan dan kredensial benar\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Debugging selesai!\n";
echo "\n💡 Jika ada field foto_profil yang missing, jalankan:\n";
echo "   - add_profile_photo.sql (manual SQL)\n";
echo "   - atau add_profile_photo_simple.php (PHP script)\n";
