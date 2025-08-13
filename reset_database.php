<?php
// Script untuk reset dan buat ulang database
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=simamang', 'postgres', 'postgres');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Reset database SIMAMANG...\n";
    
    // Drop semua tabel yang ada
    $pdo->exec("DROP TABLE IF EXISTS komentar_pembimbing CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS log_aktivitas CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS siswa CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS pembimbing CASCADE");
    $pdo->exec("DROP TABLE IF EXISTS admin CASCADE");
    
    echo "Tabel lama berhasil dihapus\n";
    
    // Buat tabel admin
    $sql = "CREATE TABLE admin (
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
    $sql = "CREATE TABLE pembimbing (
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
    $sql = "CREATE TABLE siswa (
        id SERIAL PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nis VARCHAR(20) NOT NULL UNIQUE,
        tempat_magang VARCHAR(100) NOT NULL,
        alamat_magang TEXT NULL,
        pembimbing_id INTEGER NULL,
        status VARCHAR(20) DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel siswa berhasil dibuat\n";
    
    // Buat tabel log_aktivitas
    $sql = "CREATE TABLE log_aktivitas (
        id SERIAL PRIMARY KEY,
        siswa_id INTEGER NOT NULL,
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
    $sql = "CREATE TABLE komentar_pembimbing (
        id SERIAL PRIMARY KEY,
        log_id INTEGER NOT NULL,
        pembimbing_id INTEGER NOT NULL,
        komentar TEXT NULL,
        status_validasi VARCHAR(20) DEFAULT 'menunggu',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel komentar_pembimbing berhasil dibuat\n";
    
    // Buat foreign key constraints
    $pdo->exec("ALTER TABLE siswa ADD CONSTRAINT siswa_pembimbing_id_fkey 
                FOREIGN KEY (pembimbing_id) REFERENCES pembimbing(id) ON DELETE SET NULL");
    
    $pdo->exec("ALTER TABLE log_aktivitas ADD CONSTRAINT log_aktivitas_siswa_id_fkey 
                FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE");
    
    $pdo->exec("ALTER TABLE komentar_pembimbing ADD CONSTRAINT komentar_pembimbing_log_id_fkey 
                FOREIGN KEY (log_id) REFERENCES log_aktivitas(id) ON DELETE CASCADE");
    
    $pdo->exec("ALTER TABLE komentar_pembimbing ADD CONSTRAINT komentar_pembimbing_pembimbing_id_fkey 
                FOREIGN KEY (pembimbing_id) REFERENCES pembimbing(id) ON DELETE CASCADE");
    
    echo "Foreign key constraints berhasil dibuat\n";
    
    // Buat index untuk optimasi query
    $pdo->exec("CREATE INDEX idx_log_siswa_id ON log_aktivitas(siswa_id)");
    $pdo->exec("CREATE INDEX idx_log_tanggal ON log_aktivitas(tanggal)");
    $pdo->exec("CREATE INDEX idx_komentar_log_id ON komentar_pembimbing(log_id)");
    $pdo->exec("CREATE INDEX idx_siswa_pembimbing_id ON siswa(pembimbing_id)");
    
    echo "Index berhasil dibuat\n";
    
    echo "\nDatabase berhasil direset dan dibuat ulang!\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
