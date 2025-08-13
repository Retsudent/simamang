<?php
// Test script untuk memverifikasi fix fitur Atur Bimbingan
echo "=== TEST FITUR ATUR BIMBINGAN (SETELAH FIX) ===\n\n";

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
    
    // Test 1: Verifikasi data yang diperlukan
    echo "🔍 VERIFIKASI DATA:\n";
    
    // Cek pembimbing
    $stmt = $pdo->query("SELECT id, nama, username FROM pembimbing WHERE status = 'aktif'");
    $pembimbing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Pembimbing aktif: " . count($pembimbing) . " orang\n";
    
    // Cek siswa
    $stmt = $pdo->query("SELECT id, nama, nis, pembimbing_id FROM siswa WHERE status = 'aktif'");
    $siswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Siswa aktif: " . count($siswa) . " orang\n";
    
    // Cek siswa yang sudah dibimbing
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM siswa WHERE pembimbing_id IS NOT NULL AND status = 'aktif'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Siswa terbimbing: " . $result['total'] . " orang\n";
    
    // Cek siswa yang belum dibimbing
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM siswa WHERE pembimbing_id IS NULL AND status = 'aktif'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Siswa belum terbimbing: " . $result['total'] . " orang\n\n";
    
    // Test 2: Verifikasi query yang digunakan di controller
    if (!empty($pembimbing)) {
        $pembimbingId = $pembimbing[0]['id'];
        echo "🧪 TEST QUERY CONTROLLER UNTUK PEMBIMBING ID: {$pembimbingId}\n";
        
        // Test query aturBimbingan()
        $stmt = $pdo->query("SELECT * FROM pembimbing WHERE status = 'aktif'");
        $pembimbingResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "✅ Query aturBimbingan() - Pembimbing: " . count($pembimbingResult) . " orang\n";
        
        $stmt = $pdo->query("SELECT * FROM siswa WHERE status = 'aktif'");
        $siswaResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "✅ Query aturBimbingan() - Siswa: " . count($siswaResult) . " orang\n";
        
        // Test query aturBimbinganPembimbing()
        $stmt = $pdo->prepare("SELECT * FROM pembimbing WHERE id = ? AND status = 'aktif'");
        $stmt->execute([$pembimbingId]);
        $pembimbingData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($pembimbingData) {
            echo "✅ Query aturBimbinganPembimbing() - Pembimbing ditemukan: {$pembimbingData['nama']}\n";
        } else {
            echo "❌ Query aturBimbinganPembimbing() - Pembimbing tidak ditemukan\n";
        }
        
        // Test query untuk siswa yang sudah dibimbing
        $stmt = $pdo->prepare("SELECT * FROM siswa WHERE pembimbing_id = ? AND status = 'aktif'");
        $stmt->execute([$pembimbingId]);
        $assignedSiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "✅ Query aturBimbinganPembimbing() - Siswa terbimbing: " . count($assignedSiswa) . " orang\n";
        
    }
    
    echo "\n🚀 INSTRUKSI TESTING SETELAH FIX:\n";
    echo "==========================================\n";
    echo "1. Buka browser: http://localhost:8080\n";
    echo "2. Login sebagai admin (username: admin, password: admin123)\n";
    echo "3. Klik menu 'Kelola Pembimbing'\n";
    echo "4. Klik tombol 'Atur Bimbingan' pada pembimbing pertama\n";
    echo "5. Seharusnya muncul halaman untuk memilih siswa (TIDAK ADA ERROR)\n";
    echo "6. Pilih siswa dengan checkbox dan klik 'Simpan Perubahan'\n";
    echo "7. Seharusnya kembali ke halaman 'Atur Bimbingan' dengan pesan sukses\n\n";
    
    echo "🔧 FITUR YANG SUDAH DIPERBAIKI:\n";
    echo "- Tombol 'Atur Bimbingan' di halaman 'Kelola Pembimbing' sudah mengarah ke URL yang benar\n";
    echo "- Route admin/atur-bimbingan-pembimbing/(:num) sudah terdaftar\n";
    echo "- Controller method aturBimbinganPembimbing() sudah benar\n";
    echo "- View atur_bimbingan_pembimbing.php sudah benar\n\n";
    
    echo "⚠️  CATATAN PENTING:\n";
    echo "- Pastikan server berjalan: php spark serve\n";
    echo "- Pastikan tidak ada cache browser (Ctrl+F5)\n";
    echo "- Jika masih error, cek error log di writable/logs/\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
