<?php
/**
 * Script untuk export file uploads ke zip
 * Agar bisa di-share ke tim lain
 */

echo "=== Export Uploads ===\n";

// Buat folder exports jika belum ada
if (!is_dir('exports')) {
    mkdir('exports', 0755, true);
    echo "✓ Membuat folder exports\n";
}

// Nama file zip dengan timestamp
$timestamp = date('Y-m-d_H-i-s');
$zipName = "exports/uploads_backup_{$timestamp}.zip";
$zipPath = $zipName;

// Buat zip file
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
    echo "✗ Gagal membuat file zip\n";
    exit;
}

// Tambahkan file dari folder uploads
$uploadsDir = 'writable/uploads/';
if (is_dir($uploadsDir)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($uploadsDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    $fileCount = 0;
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $filePath = $file->getRealPath();
            $relativePath = str_replace('\\', '/', substr($filePath, strlen(realpath($uploadsDir)) + 1));
            
            $zip->addFile($filePath, $relativePath);
            $fileCount++;
            echo "✓ Menambahkan: $relativePath\n";
        }
    }
    echo "✓ Total file ditambahkan: $fileCount\n";
} else {
    echo "⚠ Folder uploads tidak ditemukan\n";
}

$zip->close();

if (file_exists($zipPath)) {
    $fileSize = round(filesize($zipPath) / 1024, 2);
    echo "\n✅ Export berhasil!\n";
    echo "📁 File: $zipName\n";
    echo "📊 Ukuran: {$fileSize} KB\n";
    echo "\n📤 Cara share ke tim:\n";
    echo "1. Upload file zip ke Google Drive/Dropbox\n";
    echo "2. Share link ke tim\n";
    echo "3. Tim download dan jalankan import_uploads.php\n";
} else {
    echo "✗ Gagal membuat file zip\n";
}
?>
