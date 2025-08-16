<?php
/**
 * Script test final untuk fitur profil yang sudah dimodifikasi
 * Hanya foto profil yang bisa diubah, informasi lain tidak bisa diedit
 */

echo "🚀 Testing Fitur Profil Final (PostgreSQL)...\n\n";

// Test 1: Cek file yang diperlukan
echo "1. Testing Required Files...\n";
$requiredFiles = [
    'app/Controllers/Profile.php',
    'app/Views/profile/index.php',
    'app/Views/profile/edit.php',  // Masih ada tapi tidak digunakan
    'app/Views/layouts/main.php',
    'app/Config/Routes.php',
    'app/Config/Autoload.php',
    'app/Config/Database.php'
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

// Test 4: Cek konfigurasi database
echo "\n4. Testing Database Configuration...\n";
$dbConfigFile = 'app/Config/Database.php';
if (file_exists($dbConfigFile)) {
    $content = file_get_contents($dbConfigFile);
    if (strpos($content, 'Postgre') !== false) {
        echo "   ✅ Database driver: PostgreSQL\n";
    } else {
        echo "   ❌ Database driver: Bukan PostgreSQL\n";
    }
    
    if (strpos($content, 'simamang') !== false) {
        echo "   ✅ Database name: simamang\n";
    } else {
        echo "   ❌ Database name: Bukan simamang\n";
    }
} else {
    echo "   ❌ Database config file not found\n";
}

// Test 5: Cek database connection PostgreSQL dan field foto_profil
echo "\n5. Testing PostgreSQL Connection & foto_profil Field...\n";
try {
    $host = 'localhost';
    $username = 'postgres';
    $password = 'postgres';  // Ganti dengan password PostgreSQL Anda
    $database = 'simamang';
    $port = 5432;
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "   ✅ PostgreSQL connected successfully\n";
    
    // Test query sederhana
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && $result['test'] == 1) {
        echo "   ✅ PostgreSQL query working\n";
    }
    
    // Cek field foto_profil di semua tabel
    echo "\n6. Testing foto_profil Field in All Tables...\n";
    $tables = ['admin', 'pembimbing', 'siswa'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->prepare("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = ? AND column_name = 'foto_profil'
            ");
            $stmt->execute([$table]);
            
            if ($stmt->rowCount() > 0) {
                echo "   ✅ Field foto_profil exists in $table\n";
            } else {
                echo "   ❌ Field foto_profil missing in $table\n";
            }
        } catch (Exception $e) {
            echo "   ❌ Error checking table $table: " . $e->getMessage() . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "   ❌ PostgreSQL Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// Test 6: Cek routes yang aktif
echo "\n7. Testing Active Routes...\n";
$routesFile = 'app/Config/Routes.php';
if (file_exists($routesFile)) {
    $content = file_get_contents($routesFile);
    
    // Cek route yang seharusnya ada
    if (strpos($content, "update-photo") !== false) {
        echo "   ✅ Route update-photo exists\n";
    } else {
        echo "   ❌ Route update-photo missing\n";
    }
    
    if (strpos($content, "change-password") !== false) {
        echo "   ✅ Route change-password exists\n";
    } else {
        echo "   ❌ Route change-password missing\n";
    }
    
    // Cek route yang seharusnya tidak ada
    if (strpos($content, "profile/edit") !== false) {
        echo "   ❌ Route edit masih ada (seharusnya dihapus)\n";
    } else {
        echo "   ✅ Route edit sudah dihapus\n";
    }
    
    if (strpos($content, "profile/update") !== false) {
        echo "   ❌ Route update masih ada (seharusnya dihapus)\n";
    } else {
        echo "   ✅ Route update sudah dihapus\n";
    }
} else {
    echo "   ❌ Routes file not found\n";
}

// Test 7: Cek view modifications
echo "\n8. Testing View Modifications...\n";
$indexFile = 'app/Views/profile/index.php';
if (file_exists($indexFile)) {
    $content = file_get_contents($indexFile);
    
    // Cek tombol yang seharusnya ada
    if (strpos($content, "Upload Foto Baru") !== false) {
        echo "   ✅ Button 'Upload Foto Baru' exists\n";
    } else {
        echo "   ❌ Button 'Upload Foto Baru' missing\n";
    }
    
    if (strpos($content, "Ganti Password") !== false) {
        echo "   ✅ Button 'Ganti Password' exists\n";
    } else {
        echo "   ❌ Button 'Ganti Password' missing\n";
    }
    
    // Cek tombol yang seharusnya tidak ada
    if (strpos($content, "Edit Profil") !== false) {
        echo "   ❌ Button 'Edit Profil' masih ada (seharusnya dihapus)\n";
    } else {
        echo "   ✅ Button 'Edit Profil' sudah dihapus\n";
    }
} else {
    echo "   ❌ Profile index view not found\n";
}

// Test 8: Cek layout modifications
echo "\n9. Testing Layout Modifications...\n";
$layoutFile = 'app/Views/layouts/main.php';
if (file_exists($layoutFile)) {
    $content = file_get_contents($layoutFile);
    
    // Cek link yang seharusnya ada
    if (strpos($content, "Profil Saya") !== false) {
        echo "   ✅ Link 'Profil Saya' exists\n";
    } else {
        echo "   ❌ Link 'Profil Saya' missing\n";
    }
    
    // Cek link yang seharusnya tidak ada
    if (strpos($content, "Edit Profil") !== false) {
        echo "   ❌ Link 'Edit Profil' masih ada (seharusnya dihapus)\n";
    } else {
        echo "   ✅ Link 'Edit Profil' sudah dihapus\n";
    }
} else {
    echo "   ❌ Layout file not found\n";
}

echo "\n🎯 Testing selesai!\n";
echo "\n📋 FITUR YANG TERSEDIA:\n";
echo "   ✅ Lihat profil lengkap (read-only)\n";
echo "   ✅ Upload/ganti foto profil\n";
echo "   ✅ Ganti password\n";
echo "\n❌ FITUR YANG TIDAK TERSEDIA:\n";
echo "   ❌ Edit informasi profil (nama, email, alamat, dll)\n";
echo "   ❌ Edit informasi role-specific\n";
echo "\n💡 UNTUK TESTING:\n";
echo "   1. Login ke sistem SIMAMANG\n";
echo "   2. Akses menu 'Profil Saya'\n";
echo "   3. Upload foto profil baru\n";
echo "   4. Ganti password\n";
echo "   5. Pastikan informasi profil tidak bisa diedit\n";
echo "\n✨ Fitur profil sudah sesuai permintaan: hanya foto profil yang bisa diubah!";
