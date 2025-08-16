<?php
/**
 * Script debugging untuk PostgreSQL - mengidentifikasi masalah pada halaman profil
 */

echo "🚀 Memulai debugging halaman profil (PostgreSQL)...\n\n";

// Test 1: Cek file yang diperlukan
echo "1. Testing Required Files...\n";
$requiredFiles = [
    'app/Controllers/Profile.php',
    'app/Views/profile/index.php',
    'app/Views/profile/edit.php',
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

// Test 5: Cek database connection PostgreSQL
echo "\n5. Testing PostgreSQL Connection...\n";
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
    
    // Cek tabel yang diperlukan
    echo "\n6. Testing Required Tables...\n";
    $tables = ['admin', 'pembimbing', 'siswa'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->prepare("
                SELECT table_name 
                FROM information_schema.tables 
                WHERE table_name = ?
            ");
            $stmt->execute([$table]);
            
            if ($stmt->rowCount() > 0) {
                echo "   ✅ Table $table exists\n";
                
                // Cek field foto_profil
                $columns = $pdo->prepare("
                    SELECT column_name 
                    FROM information_schema.columns 
                    WHERE table_name = ? AND column_name = 'foto_profil'
                ");
                $columns->execute([$table]);
                
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
    echo "   ❌ PostgreSQL Error: " . $e->getMessage() . "\n";
    echo "   💡 Pastikan:\n";
    echo "      - PostgreSQL server berjalan\n";
    echo "      - Kredensial database benar\n";
    echo "      - Database 'simamang' sudah dibuat\n";
    echo "      - Port 5432 tidak diblokir firewall\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎯 Debugging selesai!\n";
echo "\n💡 Jika ada field foto_profil yang missing, jalankan:\n";
echo "   - add_profile_photo_postgresql.sql (manual SQL)\n";
echo "   - atau add_profile_photo_postgresql.php (PHP script)\n";
echo "\n💡 Untuk menjalankan script PHP:\n";
echo "   php add_profile_photo_postgresql.php\n";
echo "\n💡 Untuk menjalankan SQL manual:\n";
echo "   psql -U postgres -d simamang -f add_profile_photo_postgresql.sql\n";
