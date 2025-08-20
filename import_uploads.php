<?php
/**
 * Script untuk import file uploads dari zip
 * Yang di-share oleh tim lain
 */

echo "=== Import Uploads ===\n";

// Cek apakah ada file zip di folder exports
$exportsDir = 'exports/';
$zipFiles = glob($exportsDir . '*.zip');

if (empty($zipFiles)) {
    echo "❌ Tidak ada file zip di folder exports/\n";
    echo "📥 Cara pakai:\n";
    echo "1. Download file zip dari tim\n";
    echo "2. Letakkan di folder exports/\n";
    echo "3. Jalankan script ini lagi\n";
    exit;
}

// Tampilkan file yang tersedia
echo "📁 File zip yang tersedia:\n";
foreach ($zipFiles as $index => $file) {
    $fileName = basename($file);
    $fileSize = round(filesize($file) / 1024, 2);
    echo ($index + 1) . ". $fileName ({$fileSize} KB)\n";
}

// Pilih file (ambil yang terbaru)
$latestZip = end($zipFiles);
$fileName = basename($latestZip);

echo "\n🔄 Importing: $fileName\n";

// Extract zip file
$zip = new ZipArchive();
if ($zip->open($latestZip) !== TRUE) {
    echo "✗ Gagal membuka file zip\n";
    exit;
}

// Buat folder uploads jika belum ada
$uploadsDir = 'writable/uploads/';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
    echo "✓ Membuat folder uploads\n";
}

// Extract semua file
$extractedCount = 0;
for ($i = 0; $i < $zip->numFiles; $i++) {
    $fileInfo = $zip->statIndex($i);
    $fileName = $fileInfo['name'];
    
    // Skip folder
    if (substr($fileName, -1) === '/') {
        continue;
    }
    
    // Extract file
    $targetPath = $uploadsDir . $fileName;
    $targetDir = dirname($targetPath);
    
    // Buat folder jika belum ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Extract file
    $zip->extractTo($uploadsDir, $fileName);
    $extractedCount++;
    echo "✓ Extract: $fileName\n";
}

$zip->close();

echo "\n✅ Import berhasil!\n";
echo "📊 Total file di-extract: $extractedCount\n";
echo "🎉 Sekarang PP dan file uploads lain sudah tersedia!\n";

// Hapus file zip yang sudah di-import (opsional)
echo "\n🗑️ Hapus file zip yang sudah di-import? (y/n): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) === 'y' || trim($line) === 'Y') {
    unlink($latestZip);
    echo "✓ File zip dihapus\n";
}
fclose($handle);
?>
