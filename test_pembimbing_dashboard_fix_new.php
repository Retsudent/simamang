<?php
// Test script untuk memverifikasi fix dashboard pembimbing
echo "=== TEST DASHBOARD PEMBIMBING (SETELAH FIX) ===\n\n";

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
    
    // Test 1: Verifikasi data pembimbing dan siswa
    echo "🔍 VERIFIKASI DATA PEMBIMBING DAN SISWA:\n";
    
    // Cek pembimbing aktif
    $stmt = $pdo->query("SELECT id, nama, username FROM pembimbing WHERE status = 'aktif'");
    $pembimbing = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Pembimbing aktif: " . count($pembimbing) . " orang\n";
    
    foreach ($pembimbing as $p) {
        echo "   - ID: {$p['id']}, Nama: {$p['nama']}, Username: {$p['username']}\n";
    }
    echo "\n";
    
    // Cek siswa yang dibimbing oleh setiap pembimbing
    foreach ($pembimbing as $p) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM siswa WHERE pembimbing_id = ? AND status = 'aktif'");
        $stmt->execute([$p['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "📊 Pembimbing {$p['nama']} (ID: {$p['id']}):\n";
        echo "   - Total siswa terbimbing: {$result['total']} orang\n";
        
        // Detail siswa yang dibimbing
        $stmt = $pdo->prepare("SELECT nama, nis, tempat_magang FROM siswa WHERE pembimbing_id = ? AND status = 'aktif'");
        $stmt->execute([$p['id']]);
        $siswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($siswa)) {
            foreach ($siswa as $s) {
                echo "     • {$s['nama']} (NIS: {$s['nis']}) - {$s['tempat_magang']}\n";
            }
        } else {
            echo "     • Belum ada siswa yang dibimbing\n";
        }
        echo "\n";
    }
    
    // Test 2: Verifikasi query yang digunakan di controller Pembimbing
    if (!empty($pembimbing)) {
        $pembimbingId = $pembimbing[0]['id'];
        echo "🧪 TEST QUERY CONTROLLER PEMBIMBING UNTUK ID: {$pembimbingId}\n";
        
        // Test query untuk menghitung siswa yang dibimbing
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM siswa WHERE pembimbing_id = ? AND status = 'aktif'");
        $stmt->execute([$pembimbingId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Query assignedCount: {$result['total']} siswa\n";
        
        // Test query untuk log menunggu
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as total 
            FROM log_aktivitas 
            JOIN siswa ON siswa.id = log_aktivitas.siswa_id 
            WHERE siswa.pembimbing_id = ? AND log_aktivitas.status = 'menunggu'
        ");
        $stmt->execute([$pembimbingId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Query pendingLogs: {$result['total']} log menunggu\n";
        
        // Test query untuk statistik status
        $stmt = $pdo->prepare("
            SELECT log_aktivitas.status, COUNT(*) as total 
            FROM log_aktivitas 
            JOIN siswa ON siswa.id = log_aktivitas.siswa_id 
            WHERE siswa.pembimbing_id = ? 
            GROUP BY log_aktivitas.status
        ");
        $stmt->execute([$pembimbingId]);
        $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Query statusCounts:\n";
        foreach ($statusCounts as $status) {
            echo "   - {$status['status']}: {$status['total']} log\n";
        }
        
    }
    
    echo "\n🚀 INSTRUKSI TESTING SETELAH FIX:\n";
    echo "====================================\n";
    echo "1. Buka browser: http://localhost:8080\n";
    echo "2. Login sebagai pembimbing (username: pakahmad, password: pakahmad123)\n";
    echo "3. Lihat dashboard pembimbing\n";
    echo "4. Seharusnya 'Total Siswa' menampilkan: 2 (sesuai dengan data admin)\n";
    echo "5. Statistik status juga hanya menghitung log dari siswa yang dibimbing\n";
    echo "6. Menu 'Aktivitas Siswa' hanya menampilkan siswa yang dibimbing\n\n";
    
    echo "🔧 FITUR YANG SUDAH DIPERBAIKI:\n";
    echo "- Dashboard pembimbing sekarang menampilkan jumlah siswa yang benar\n";
    echo "- Statistik status hanya menghitung log dari siswa yang dibimbing\n";
    echo "- Pending logs hanya menampilkan log dari siswa yang dibimbing\n";
    echo "- Menu aktivitas siswa hanya menampilkan siswa yang dibimbing\n\n";
    
    echo "⚠️  CATATAN PENTING:\n";
    echo "- Pastikan server berjalan: php spark serve\n";
    echo "- Pastikan tidak ada cache browser (Ctrl+F5)\n";
    echo "- Login sebagai pembimbing, bukan sebagai admin atau siswa\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
