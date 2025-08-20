<?php
// Script untuk menjalankan perbaikan database
echo "=== RUNNING DATABASE FIXES ===\n\n";

// Konfigurasi database (sesuaikan dengan konfigurasi Anda)
$host = '127.0.0.1';
$username = 'dev_simamang';
$password = 'NWyaTdmyWPZXZbsp';
$database = 'dev_simamang';

try {
    // Koneksi ke database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully\n\n";
    
    // 1. Tambahkan field foto_profil ke tabel pembimbing
    echo "1. Adding foto_profil field to pembimbing table...\n";
    try {
        $sql = "ALTER TABLE pembimbing ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) NULL AFTER alamat";
        $pdo->exec($sql);
        echo "   ✅ Field foto_profil added to pembimbing table\n";
    } catch (PDOException $e) {
        echo "   ⚠️  Field foto_profil might already exist: " . $e->getMessage() . "\n";
    }
    
    // 2. Tambahkan field foto_profil ke tabel siswa
    echo "\n2. Adding foto_profil field to siswa table...\n";
    try {
        $sql = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) NULL AFTER alamat";
        $pdo->exec($sql);
        echo "   ✅ Field foto_profil added to siswa table\n";
    } catch (PDOException $e) {
        echo "   ⚠️  Field foto_profil might already exist: " . $e->getMessage() . "\n";
    }
    
    // 3. Tambahkan field foto_profil ke tabel admin
    echo "\n3. Adding foto_profil field to admin table...\n";
    try {
        $sql = "ALTER TABLE admin ADD COLUMN IF NOT EXISTS foto_profil VARCHAR(255) NULL AFTER alamat";
        $pdo->exec($sql);
        echo "   ✅ Field foto_profil added to admin table\n";
    } catch (PDOException $e) {
        echo "   ⚠️  Field foto_profil might already exist: " . $e->getMessage() . "\n";
    }
    
    // 4. Periksa data pembimbing
    echo "\n4. Checking pembimbing data...\n";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pembimbing");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "   Total pembimbing: " . $count['total'] . "\n";
    
    if ($count['total'] > 0) {
        $stmt = $pdo->query("SELECT id, nama, username, email, status FROM pembimbing ORDER BY id LIMIT 3");
        $pembimbing = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "   Sample pembimbing data:\n";
        foreach ($pembimbing as $p) {
            echo "     - ID: " . $p['id'] . ", Nama: " . $p['nama'] . ", Username: " . $p['username'] . "\n";
        }
    } else {
        echo "   ⚠️  No pembimbing data found. Creating sample data...\n";
        
        // Buat data sample pembimbing
        $password_hash = password_hash('password123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO pembimbing (nama, username, password, email, no_hp, alamat, instansi, jabatan, bidang_keahlian, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'Pembimbing Test 1',
            'pembimbing1',
            $password_hash,
            'pembimbing1@test.com',
            '08123456789',
            'Jl. Test No. 1',
            'Instansi Test',
            'Jabatan Test',
            'Bidang Test',
            'aktif'
        ]);
        
        echo "   ✅ Sample pembimbing data created\n";
    }
    
    echo "\n✅ Database fixes completed successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Restart your application\n";
    echo "2. Login as pembimbing with username: pembimbing1, password: password123\n";
    echo "3. Try accessing /profile again\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ General error: " . $e->getMessage() . "\n";
}

