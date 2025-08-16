<?php
/**
 * Debug Comprehensive
 * Debug komprehensif untuk mencari masalah dashboard yang belum teratasi
 */

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=dev_simamang', 'dev_simamang', 'NWyaTdmyWPZXZbsp');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DEBUG COMPREHENSIVE ===\n\n";
    
    // 1. Periksa struktur database secara detail
    echo "1. Struktur Database Detail:\n";
    
    // Cek tabel users
    $stmt = $pdo->query("DESCRIBE users");
    $usersColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📊 Tabel users columns:\n";
    foreach ($usersColumns as $col) {
        echo "      - {$col['Field']}: {$col['Type']} ({$col['Null']}) Key: {$col['Key']}\n";
    }
    
    // Cek tabel siswa
    $stmt = $pdo->query("DESCRIBE siswa");
    $siswaColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📊 Tabel siswa columns:\n";
    foreach ($siswaColumns as $col) {
        echo "      - {$col['Field']}: {$col['Type']} ({$col['Null']}) Key: {$col['Key']}\n";
    }
    
    // Cek tabel pembimbing
    $stmt = $pdo->query("DESCRIBE pembimbing");
    $pembimbingColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📊 Tabel pembimbing columns:\n";
    foreach ($pembimbingColumns as $col) {
        echo "      - {$col['Field']}: {$col['Type']} ({$col['Null']}) Key: {$col['Key']}\n";
    }
    
    echo "\n";
    
    // 2. Periksa data secara detail
    echo "2. Data Detail:\n";
    
    // Cek semua user
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📊 Semua user:\n";
    foreach ($users as $user) {
        echo "      - ID {$user['id']}: {$user['username']} ({$user['nama']}) - Role: {$user['role']} - Status: {$user['status']}\n";
    }
    
    // Cek semua siswa
    $stmt = $pdo->query("SELECT * FROM siswa ORDER BY id");
    $siswas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📊 Semua siswa:\n";
    foreach ($siswas as $siswa) {
        echo "      - ID {$siswa['id']}: {$siswa['nama']} (Username: {$siswa['username']}, Pembimbing: {$siswa['pembimbing_id']}, User ID: {$siswa['user_id']}, Status: {$siswa['status']})\n";
    }
    
    // Cek semua pembimbing
    $stmt = $pdo->query("SELECT * FROM pembimbing ORDER BY id");
    $pembimbings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📊 Semua pembimbing:\n";
    foreach ($pembimbings as $pembimbing) {
        echo "      - ID {$pembimbing['id']}: {$pembimbing['nama']} (Username: {$pembimbing['username']}, User ID: {$pembimbing['user_id']}, Status: {$pembimbing['status']})\n";
    }
    
    // Cek semua log aktivitas
    $stmt = $pdo->query("SELECT * FROM log_aktivitas ORDER BY id");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   📊 Semua log aktivitas:\n";
    foreach ($logs as $log) {
        echo "      - ID {$log['id']}: Siswa {$log['siswa_id']}, Tanggal {$log['tanggal']}, Status {$log['status']}\n";
    }
    
    echo "\n";
    
    // 3. Test query step by step
    echo "3. Test Query Step by Step:\n";
    
    // Test 1: Cari user siswa1
    echo "   🔍 Test 1: Cari user siswa1\n";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['siswa1']);
    $userSiswa1 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userSiswa1) {
        echo "     ✅ User siswa1 ditemukan:\n";
        echo "        - User ID: {$userSiswa1['id']}\n";
        echo "        - Username: {$userSiswa1['username']}\n";
        echo "        - Role: {$userSiswa1['role']}\n";
        
        // Test 2: Cari data siswa berdasarkan user_id
        echo "     🔍 Test 2: Cari data siswa berdasarkan user_id\n";
        $stmt = $pdo->prepare("SELECT * FROM siswa WHERE user_id = ?");
        $stmt->execute([$userSiswa1['id']]);
        $siswaData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($siswaData) {
            echo "        ✅ Data siswa ditemukan:\n";
            echo "           - Siswa ID: {$siswaData['id']}\n";
            echo "           - Nama: {$siswaData['nama']}\n";
            echo "           - Pembimbing ID: " . ($siswaData['pembimbing_id'] ?? 'NULL') . "\n";
            
            // Test 3: Cari log aktivitas berdasarkan siswa_id
            echo "        🔍 Test 3: Cari log aktivitas berdasarkan siswa_id\n";
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM log_aktivitas WHERE siswa_id = ?");
            $stmt->execute([$siswaData['id']]);
            $totalLog = $stmt->fetchColumn();
            echo "           ✅ Total log aktivitas: {$totalLog}\n";
            
            // Test 4: Cari log berdasarkan status
            $stmt = $pdo->prepare("SELECT status, COUNT(*) as total FROM log_aktivitas WHERE siswa_id = ? GROUP BY status");
            $stmt->execute([$siswaData['id']]);
            $statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($statusCounts as $status) {
                echo "           - Log {$status['status']}: {$status['total']}\n";
            }
            
        } else {
            echo "        ❌ Data siswa TIDAK ditemukan untuk user_id: {$userSiswa1['id']}\n";
        }
        
    } else {
        echo "     ❌ User siswa1 TIDAK ditemukan\n";
    }
    
    echo "\n";
    
    // Test pembimbing1
    echo "   🔍 Test 1: Cari user pembimbing1\n";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['pembimbing1']);
    $userPembimbing1 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($userPembimbing1) {
        echo "     ✅ User pembimbing1 ditemukan:\n";
        echo "        - User ID: {$userPembimbing1['id']}\n";
        echo "        - Username: {$userPembimbing1['username']}\n";
        echo "        - Role: {$userPembimbing1['role']}\n";
        
        // Test 2: Cari data pembimbing berdasarkan user_id
        echo "     🔍 Test 2: Cari data pembimbing berdasarkan user_id\n";
        $stmt = $pdo->prepare("SELECT * FROM pembimbing WHERE user_id = ?");
        $stmt->execute([$userPembimbing1['id']]);
        $pembimbingData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($pembimbingData) {
            echo "        ✅ Data pembimbing ditemukan:\n";
            echo "           - Pembimbing ID: {$pembimbingData['id']}\n";
            echo "           - Nama: {$pembimbingData['nama']}\n";
            
            // Test 3: Cari siswa bimbingan
            echo "        🔍 Test 3: Cari siswa bimbingan\n";
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM siswa WHERE pembimbing_id = ? AND status = 'aktif'");
            $stmt->execute([$pembimbingData['id']]);
            $totalSiswa = $stmt->fetchColumn();
            echo "           ✅ Total siswa bimbingan: {$totalSiswa}\n";
            
            // Test 4: Cari log aktivitas untuk siswa bimbingan
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM log_aktivitas l 
                JOIN siswa s ON l.siswa_id = s.id 
                WHERE s.pembimbing_id = ?
            ");
            $stmt->execute([$pembimbingData['id']]);
            $totalLog = $stmt->fetchColumn();
            echo "           ✅ Total log aktivitas: {$totalLog}\n";
            
        } else {
            echo "        ❌ Data pembimbing TIDAK ditemukan untuk user_id: {$userPembimbing1['id']}\n";
        }
        
    } else {
        echo "     ❌ User pembimbing1 TIDAK ditemukan\n";
    }
    
    echo "\n";
    
    // 4. Periksa masalah yang mungkin terjadi
    echo "4. Identifikasi Masalah:\n";
    
    // Cek apakah ada data yang tidak konsisten
    $stmt = $pdo->query("
        SELECT 'users' as table_name, COUNT(*) as total FROM users
        UNION ALL
        SELECT 'siswa' as table_name, COUNT(*) as total FROM siswa
        UNION ALL
        SELECT 'pembimbing' as table_name, COUNT(*) as total FROM pembimbing
        UNION ALL
        SELECT 'log_aktivitas' as table_name, COUNT(*) as total FROM log_aktivitas
    ");
    $tableCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Jumlah data per tabel:\n";
    foreach ($tableCounts as $count) {
        echo "      - {$count['table_name']}: {$count['total']}\n";
    }
    
    // Cek foreign key constraints
    $stmt = $pdo->query("
        SELECT 
            TABLE_NAME,
            COLUMN_NAME,
            CONSTRAINT_NAME,
            REFERENCED_TABLE_NAME,
            REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE REFERENCED_TABLE_SCHEMA = 'dev_simamang'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    $foreignKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Foreign Key Constraints:\n";
    foreach ($foreignKeys as $fk) {
        echo "      - {$fk['TABLE_NAME']}.{$fk['COLUMN_NAME']} → {$fk['REFERENCED_TABLE_NAME']}.{$fk['REFERENCED_COLUMN_NAME']}\n";
    }
    
    echo "\n";
    
    // 5. Test query yang digunakan di controller
    echo "5. Test Query Controller:\n";
    
    // Test query yang digunakan di controller siswa
    echo "   📊 Query Controller Siswa:\n";
    if ($userSiswa1 && $siswaData) {
        // Simulasi query controller
        $stmt = $pdo->prepare("
            SELECT 
                s.id as siswa_id,
                s.nama as siswa_nama,
                s.pembimbing_id,
                s.user_id as siswa_user_id
            FROM siswa s
            WHERE s.user_id = ?
        ");
        $stmt->execute([$userSiswa1['id']]);
        $controllerResult = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($controllerResult) {
            echo "     ✅ Query controller berhasil:\n";
            echo "        - Siswa ID: {$controllerResult['siswa_id']}\n";
            echo "        - Nama: {$controllerResult['siswa_nama']}\n";
            echo "        - Pembimbing ID: " . ($controllerResult['pembimbing_id'] ?? 'NULL') . "\n";
        } else {
            echo "     ❌ Query controller gagal\n";
        }
    }
    
    // Test query yang digunakan di controller pembimbing
    echo "   📊 Query Controller Pembimbing:\n";
    if ($userPembimbing1 && $pembimbingData) {
        // Simulasi query controller
        $stmt = $pdo->prepare("
            SELECT 
                p.id as pembimbing_id,
                p.nama as pembimbing_nama,
                p.user_id as pembimbing_user_id
            FROM pembimbing p
            WHERE p.user_id = ?
        ");
        $stmt->execute([$userPembimbing1['id']]);
        $controllerResult = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($controllerResult) {
            echo "     ✅ Query controller berhasil:\n";
            echo "        - Pembimbing ID: {$controllerResult['pembimbing_id']}\n";
            echo "        - Nama: {$controllerResult['pembimbing_nama']}\n";
        } else {
            echo "     ❌ Query controller gagal\n";
        }
    }
    
    echo "\n=== DEBUG SELESAI ===\n";
    echo "✅ Semua test sudah dijalankan\n";
    echo "✅ Silakan lihat hasil di atas\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>


