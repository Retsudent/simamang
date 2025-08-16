<?php

/**
 * Script untuk menjalankan migration AddProfilePhoto
 * Menambahkan field foto_profil ke semua tabel user
 */

// Load CodeIgniter
require_once 'preload.php';

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\BaseConnection;

try {
    echo "🚀 Memulai migration AddProfilePhoto...\n\n";
    
    // Connect ke database
    $db = \Config\Database::connect();
    
    // Cek apakah field foto_profil sudah ada
    $tables = ['admin', 'pembimbing', 'siswa'];
    
    foreach ($tables as $table) {
        echo "📋 Mengecek tabel: {$table}\n";
        
        // Cek apakah field foto_profil sudah ada
        $fields = $db->getFieldNames($table);
        
        if (in_array('foto_profil', $fields)) {
            echo "   ✅ Field foto_profil sudah ada di tabel {$table}\n";
        } else {
            echo "   ➕ Menambahkan field foto_profil ke tabel {$table}...\n";
            
            // Tambahkan field foto_profil
            $sql = "ALTER TABLE {$table} ADD COLUMN foto_profil VARCHAR(255) NULL AFTER alamat";
            $db->query($sql);
            
            echo "   ✅ Field foto_profil berhasil ditambahkan ke tabel {$table}\n";
        }
    }
    
    echo "\n🎉 Migration AddProfilePhoto berhasil dijalankan!\n";
    echo "📝 Field foto_profil telah ditambahkan ke semua tabel user\n";
    echo "🔧 Sekarang user dapat mengupload dan mengelola foto profil mereka\n\n";
    
    // Buat folder uploads/profile jika belum ada
    $uploadPath = WRITEPATH . 'uploads/profile/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0755, true);
        echo "📁 Folder uploads/profile berhasil dibuat\n";
    } else {
        echo "📁 Folder uploads/profile sudah ada\n";
    }
    
    echo "\n✨ Fitur profil dengan foto sudah siap digunakan!\n";
    echo "🌐 Akses melalui: /profile\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "🔍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}
