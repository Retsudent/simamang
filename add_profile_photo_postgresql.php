<?php
/**
 * Script untuk menambahkan field foto_profil ke database PostgreSQL
 * SIMAMANG - Sistem Monitoring Aktivitas Magang
 */

// Konfigurasi database PostgreSQL
$host = 'localhost';
$username = 'postgres';
$password = 'postgres';  // Ganti dengan password PostgreSQL Anda
$database = 'simamang';
$port = 5432;

try {
    echo "🚀 Memulai penambahan field foto_profil ke PostgreSQL...\n\n";
    
    // Connect ke database PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Berhasil terhubung ke database PostgreSQL: $database\n\n";
    
    // Cek apakah field foto_profil sudah ada
    $tables = ['admin', 'pembimbing', 'siswa'];
    
    foreach ($tables as $table) {
        echo "📋 Mengecek tabel: {$table}\n";
        
        // Cek apakah field foto_profil sudah ada
        $stmt = $pdo->prepare("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = ? AND column_name = 'foto_profil'
        ");
        $stmt->execute([$table]);
        
        if ($stmt->rowCount() > 0) {
            echo "   ✅ Field foto_profil sudah ada di tabel {$table}\n";
        } else {
            echo "   ➕ Menambahkan field foto_profil ke tabel {$table}...\n";
            
            // Tambahkan field foto_profil
            $sql = "ALTER TABLE $table ADD COLUMN foto_profil VARCHAR(255)";
            $pdo->exec($sql);
            
            echo "   ✅ Field foto_profil berhasil ditambahkan ke tabel {$table}\n";
        }
    }
    
    echo "\n🎉 Field foto_profil berhasil ditambahkan ke semua tabel user!\n";
    echo "🔧 Sekarang user dapat mengupload dan mengelola foto profil mereka\n\n";
    
    // Buat folder uploads/profile jika belum ada
    $uploadPath = __DIR__ . '/writable/uploads/profile/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
        echo "📁 Folder uploads/profile berhasil dibuat di: $uploadPath\n";
    } else {
        echo "📁 Folder uploads/profile sudah ada di: $uploadPath\n";
    }
    
    echo "\n✨ Fitur profil dengan foto sudah siap digunakan!\n";
    echo "🌐 Akses melalui: /profile\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "💡 Pastikan:\n";
    echo "   - PostgreSQL server berjalan\n";
    echo "   - Kredensial database benar\n";
    echo "   - Database 'simamang' sudah dibuat\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
