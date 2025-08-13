<?php
// Script untuk membuat tabel-tabel di PostgreSQL untuk SIMAMANG
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=simamang', 'postgres', 'postgres');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat tabel admin
    $sql = "CREATE TABLE IF NOT EXISTS admin (
        id SERIAL PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NULL,
        no_hp VARCHAR(20) NULL,
        status VARCHAR(20) DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel admin berhasil dibuat\n";
    
    // Buat tabel pembimbing
    $sql = "CREATE TABLE IF NOT EXISTS pembimbing (
        id SERIAL PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NULL,
        no_hp VARCHAR(20) NULL,
        status VARCHAR(20) DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel pembimbing berhasil dibuat\n";
    
    // Buat tabel siswa
    $sql = "CREATE TABLE IF NOT EXISTS siswa (
        id SERIAL PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nis VARCHAR(20) NOT NULL UNIQUE,
        tempat_magang VARCHAR(100) NOT NULL,
        alamat_magang TEXT NULL,
        pembimbing_id INTEGER NULL REFERENCES pembimbing(id),
        status VARCHAR(20) DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel siswa berhasil dibuat\n";
    
    // Buat tabel log_aktivitas
    $sql = "CREATE TABLE IF NOT EXISTS log_aktivitas (
        id SERIAL PRIMARY KEY,
        siswa_id INTEGER NOT NULL REFERENCES siswa(id) ON DELETE CASCADE,
        tanggal DATE NOT NULL,
        jam_mulai TIME NOT NULL,
        jam_selesai TIME NOT NULL,
        uraian TEXT NOT NULL,
        bukti VARCHAR(255) NULL,
        status VARCHAR(20) DEFAULT 'menunggu',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel log_aktivitas berhasil dibuat\n";
    
    // Buat tabel komentar_pembimbing
    $sql = "CREATE TABLE IF NOT EXISTS komentar_pembimbing (
        id SERIAL PRIMARY KEY,
        log_id INTEGER NOT NULL REFERENCES log_aktivitas(id) ON DELETE CASCADE,
        pembimbing_id INTEGER NOT NULL REFERENCES pembimbing(id) ON DELETE CASCADE,
        komentar TEXT NULL,
        status_validasi VARCHAR(20) DEFAULT 'menunggu',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel komentar_pembimbing berhasil dibuat\n";
    
    // Buat index untuk optimasi query
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_log_siswa_id ON log_aktivitas(siswa_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_log_tanggal ON log_aktivitas(tanggal)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_komentar_log_id ON komentar_pembimbing(log_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_siswa_pembimbing_id ON siswa(pembimbing_id)");
    
    echo "Index berhasil dibuat\n";
    echo "\nSemua tabel berhasil dibuat!\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
