<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=simamang', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat tabel users
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'siswa',
        nis VARCHAR(20) NULL,
        tempat_magang VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel users berhasil dibuat\n";
    
    // Buat tabel log_aktivitas
    $sql = "CREATE TABLE IF NOT EXISTS log_aktivitas (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        siswa_id INT(11) UNSIGNED NOT NULL,
        tanggal DATE NOT NULL,
        jam_mulai TIME NOT NULL,
        jam_selesai TIME NOT NULL,
        uraian TEXT NOT NULL,
        bukti VARCHAR(255) NULL,
        status VARCHAR(20) DEFAULT 'menunggu',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel log_aktivitas berhasil dibuat\n";
    
    // Buat tabel komentar_pembimbing
    $sql = "CREATE TABLE IF NOT EXISTS komentar_pembimbing (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        log_id INT(11) UNSIGNED NOT NULL,
        pembimbing_id INT(11) UNSIGNED NOT NULL,
        komentar TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel komentar_pembimbing berhasil dibuat\n";
    
    echo "\nSemua tabel berhasil dibuat!\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
