<?php
// Test script untuk memverifikasi fix nama file upload
echo "=== TEST FIX NAMA FILE UPLOAD ===\n\n";

try {
    // Test database connection
    $host = 'localhost';
    $port = '5432';
    $dbname = 'simamang';
    $username = 'postgres';
    $password = 'postgres';
    
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database terhubung\n\n";
    
    // Test 1: Verifikasi data log aktivitas dengan bukti file
    echo "🔍 VERIFIKASI LOG AKTIVITAS DENGAN BUKTI FILE:\n";
    
    $stmt = $pdo->query("
        SELECT 
            la.id,
            la.tanggal,
            la.bukti,
            s.nama as nama_siswa,
            s.nis,
            p.nama as nama_pembimbing
        FROM log_aktivitas la
        JOIN siswa s ON s.id = la.siswa_id
        LEFT JOIN pembimbing p ON p.id = s.pembimbing_id
        WHERE la.bukti IS NOT NULL
        ORDER BY la.created_at DESC
        LIMIT 10
    ");
    
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($logs)) {
        echo "✅ Ditemukan " . count($logs) . " log dengan bukti file:\n\n";
        
        foreach ($logs as $log) {
            echo "📋 Log ID: {$log['id']}\n";
            echo "   Tanggal: {$log['tanggal']}\n";
            echo "   Siswa: {$log['nama_siswa']} (NIS: {$log['nis']})\n";
            echo "   Pembimbing: " . ($log['nama_pembimbing'] ?? 'Belum ada') . "\n";
            echo "   Nama File: {$log['bukti']}\n";
            
            // Analisis nama file
            if (preg_match('/^(\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2})_(.+)$/', $log['bukti'], $matches)) {
                echo "   ✅ Format file sudah benar (timestamp_nama_asli)\n";
                echo "   Timestamp: {$matches[1]}\n";
                echo "   Nama Asli: {$matches[2]}\n";
            } else {
                echo "   ⚠️  Format file masih lama (random name)\n";
            }
            echo "\n";
        }
    } else {
        echo "❌ Tidak ada log dengan bukti file\n";
    }
    
    // Test 2: Verifikasi direktori upload
    echo "📁 VERIFIKASI DIREKTORI UPLOAD:\n";
    
    $uploadDir = __DIR__ . '/writable/uploads/bukti';
    if (is_dir($uploadDir)) {
        echo "✅ Direktori upload ada: {$uploadDir}\n";
        
        $files = scandir($uploadDir);
        $fileCount = count($files) - 2; // Kurangi . dan ..
        
        echo "✅ Jumlah file di direktori: {$fileCount}\n";
        
        if ($fileCount > 0) {
            echo "📋 Daftar file:\n";
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $uploadDir . '/' . $file;
                    $fileSize = filesize($filePath);
                    $fileSizeKB = round($fileSize / 1024, 2);
                    
                    echo "   • {$file} ({$fileSizeKB} KB)\n";
                }
            }
        }
    } else {
        echo "❌ Direktori upload tidak ditemukan: {$uploadDir}\n";
    }
    
    echo "\n🚀 INSTRUKSI TESTING SETELAH FIX:\n";
    echo "====================================\n";
    echo "1. Buka browser: http://localhost:8080\n";
    echo "2. Login sebagai siswa (username: budi, password: budi123)\n";
    echo "3. Buat log aktivitas baru dengan upload file\n";
    echo "4. Upload file dengan nama: '11-Siti-tugas1.pdf'\n";
    echo "5. Simpan log aktivitas\n";
    echo "6. Login sebagai pembimbing (username: pakahmad, password: pakahmad123)\n";
    echo "7. Lihat detail log siswa\n";
    echo "8. Seharusnya nama file tampil: '2025-08-13_XX-XX-XX_11-Siti-tugas1.pdf'\n\n";
    
    echo "🔧 FITUR YANG SUDAH DIPERBAIKI:\n";
    echo "- Nama file upload sekarang menggunakan format: timestamp_nama_asli\n";
    echo "- Timestamp format: YYYY-MM-DD_HH-MM-SS\n";
    echo "- Nama asli file tetap terjaga\n";
    echo "- Karakter berbahaya di nama file dibersihkan\n\n";
    
    echo "⚠️  CATATAN PENTING:\n";
    echo "- File lama masih menggunakan nama random\n";
    echo "- File baru akan menggunakan format timestamp_nama_asli\n";
    echo "- Pastikan server berjalan: php spark serve\n";
    echo "- Test dengan upload file baru untuk melihat perubahan\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
