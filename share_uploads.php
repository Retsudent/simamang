<?php
/**
 * Script untuk share file uploads antar tim
 * Tanpa menggunakan zip (karena ZipArchive tidak tersedia)
 */

echo "=== Share Uploads ===\n";

// Buat folder shared jika belum ada
$sharedDir = 'shared_uploads/';
if (!is_dir($sharedDir)) {
    mkdir($sharedDir, 0755, true);
    echo "✓ Membuat folder shared_uploads\n";
}

// Copy file dari uploads ke shared
$uploadsDir = 'writable/uploads/';
$copiedCount = 0;

if (is_dir($uploadsDir)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($uploadsDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $filePath = $file->getRealPath();
            $relativePath = str_replace('\\', '/', substr($filePath, strlen(realpath($uploadsDir)) + 1));
            $targetPath = $sharedDir . $relativePath;
            $targetDir = dirname($targetPath);
            
            // Buat folder target jika belum ada
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            // Copy file
            if (copy($filePath, $targetPath)) {
                $copiedCount++;
                echo "✓ Copy: $relativePath\n";
            } else {
                echo "✗ Gagal copy: $relativePath\n";
            }
        }
    }
}

if ($copiedCount > 0) {
    echo "\n✅ Share berhasil!\n";
    echo "📊 Total file di-copy: $copiedCount\n";
    echo "\n📤 Cara share ke tim:\n";
    echo "1. Upload folder 'shared_uploads/' ke Google Drive/Dropbox\n";
    echo "2. Share link ke tim\n";
    echo "3. Tim download dan jalankan get_shared_uploads.php\n";
} else {
    echo "\n⚠ Tidak ada file uploads untuk di-share\n";
    echo "Upload PP atau file lain terlebih dahulu\n";
}

echo "\n📁 Folder shared_uploads/ berisi:\n";
if (is_dir($sharedDir)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sharedDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $relativePath = str_replace('\\', '/', substr($file->getRealPath(), strlen(realpath($sharedDir)) + 1));
            $fileSize = round($file->getSize() / 1024, 2);
            echo "  📄 $relativePath ({$fileSize} KB)\n";
        }
    }
}
?>
