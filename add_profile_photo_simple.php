<?php

/**
 * Script sederhana untuk menambahkan field foto_profil
 * Menambahkan field foto_profil ke semua tabel user
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'simamang';

try {
    echo "🚀 Memulai penambahan field foto_profil...\n\n";
    
    // Connect ke database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Berhasil terhubung ke database: $database\n\n";
    
    // Cek apakah field foto_profil sudah ada
    $tables = ['admin', 'pembimbing', 'siswa'];
    
    foreach ($tables as $table) {
        echo "📋 Mengecek tabel: {$table}\n";
        
        // Cek apakah field foto_profil sudah ada
        $stmt = $pdo->query("DESCRIBE $table");
        $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (in_array('foto_profil', $fields)) {
            echo "   ✅ Field foto_profil sudah ada di tabel {$table}\n";
        } else {
            echo "   ➕ Menambahkan field foto_profil ke tabel {$table}...\n";
            
            // Tambahkan field foto_profil
            $sql = "ALTER TABLE $table ADD COLUMN foto_profil VARCHAR(255) NULL AFTER alamat";
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
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
