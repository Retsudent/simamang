<?php
/**
 * Script untuk sinkronisasi file uploads antar tim
 * 
 * Cara pakai:
 * 1. Setelah pull dari git, jalankan: php sync_uploads.php
 * 2. Script akan membuat folder uploads yang diperlukan
 * 3. Jika ada file yang hilang, akan menggunakan default avatar
 */

echo "=== Sinkronisasi Uploads Folder ===\n";

// Buat folder uploads yang diperlukan
$uploadDirs = [
    'writable/uploads/',
    'writable/uploads/profile/',
    'writable/uploads/logs/'
];

foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Membuat folder: $dir\n";
        } else {
            echo "✗ Gagal membuat folder: $dir\n";
        }
    } else {
        echo "✓ Folder sudah ada: $dir\n";
    }
}

// Buat file index.html di setiap folder untuk keamanan
foreach ($uploadDirs as $dir) {
    $indexFile = $dir . 'index.html';
    if (!file_exists($indexFile)) {
        file_put_contents($indexFile, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>403 Forbidden</h1><p>Access denied.</p></body></html>');
        echo "✓ Membuat index.html di: $dir\n";
    }
}

// Cek apakah default avatar ada
$defaultAvatar = 'public/assets/img/default-avatar.png';
if (!file_exists($defaultAvatar)) {
    echo "⚠ Warning: Default avatar tidak ditemukan di: $defaultAvatar\n";
    echo "   Pastikan file default-avatar.png ada di folder public/assets/img/\n";
} else {
    echo "✓ Default avatar tersedia: $defaultAvatar\n";
}

echo "\n=== Selesai ===\n";
echo "Tips:\n";
echo "- File uploads tidak di-track oleh Git untuk keamanan\n";
echo "- Jika ada foto yang tidak muncul, akan menggunakan default avatar\n";
echo "- Untuk berbagi foto, bisa upload ulang atau share file secara manual\n";
?>
