<?php
/**
 * Script untuk mendapatkan file uploads yang di-share tim
 * Copy dari folder shared_uploads/ ke writable/uploads/
 */

echo "=== Get Shared Uploads ===\n";

// Cek apakah folder shared_uploads ada
$sharedDir = 'shared_uploads/';
if (!is_dir($sharedDir)) {
    echo "❌ Folder shared_uploads/ tidak ditemukan\n";
    echo "📥 Cara pakai:\n";
    echo "1. Download folder shared_uploads dari tim\n";
    echo "2. Letakkan di root project\n";
    echo "3. Jalankan script ini lagi\n";
    exit;
}

// Copy file dari shared ke uploads
$uploadsDir = 'writable/uploads/';
$copiedCount = 0;

// Buat folder uploads jika belum ada
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
    echo "✓ Membuat folder uploads\n";
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sharedDir, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $filePath = $file->getRealPath();
        $relativePath = str_replace('\\', '/', substr($filePath, strlen(realpath($sharedDir)) + 1));
        $targetPath = $uploadsDir . $relativePath;
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

if ($copiedCount > 0) {
    echo "\n✅ Import berhasil!\n";
    echo "📊 Total file di-copy: $copiedCount\n";
    echo "🎉 Sekarang PP dan file uploads lain sudah tersedia!\n";
    
    // Hapus folder shared_uploads (opsional)
    echo "\n🗑️ Hapus folder shared_uploads? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) === 'y' || trim($line) === 'Y') {
        // Hapus folder dan isinya
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sharedDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($sharedDir);
        echo "✓ Folder shared_uploads dihapus\n";
    }
    fclose($handle);
} else {
    echo "\n⚠ Tidak ada file di folder shared_uploads/\n";
    echo "Pastikan folder berisi file yang di-share tim\n";
}
?>
