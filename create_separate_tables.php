<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=simamang', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Hapus tabel lama jika ada
    $pdo->exec("DROP TABLE IF EXISTS komentar_pembimbing");
    $pdo->exec("DROP TABLE IF EXISTS log_aktivitas");
    $pdo->exec("DROP TABLE IF EXISTS pembimbing_siswa");
    $pdo->exec("DROP TABLE IF EXISTS users");
    
    echo "Tabel lama berhasil dihapus\n";
    
    // 1. Tabel admin
    $sql = "CREATE TABLE admin (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NULL,
        no_hp VARCHAR(15) NULL,
        alamat TEXT NULL,
        status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel admin berhasil dibuat\n";
    
    // 2. Tabel pembimbing
    $sql = "CREATE TABLE pembimbing (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NULL,
        no_hp VARCHAR(15) NULL,
        alamat TEXT NULL,
        instansi VARCHAR(100) NULL,
        jabatan VARCHAR(100) NULL,
        bidang_keahlian TEXT NULL,
        status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel pembimbing berhasil dibuat\n";
    
    // 3. Tabel siswa
    $sql = "CREATE TABLE siswa (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        nis VARCHAR(20) NOT NULL UNIQUE,
        nisn VARCHAR(20) NULL,
        tempat_lahir VARCHAR(100) NULL,
        tanggal_lahir DATE NULL,
        jenis_kelamin ENUM('L', 'P') NULL,
        alamat TEXT NULL,
        no_hp VARCHAR(15) NULL,
        email VARCHAR(100) NULL,
        kelas VARCHAR(10) NULL,
        jurusan VARCHAR(50) NULL,
        tempat_magang VARCHAR(100) NULL,
        alamat_magang TEXT NULL,
        periode_magang VARCHAR(50) NULL,
        status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel siswa berhasil dibuat\n";
    
    // 4. Tabel pembimbing_siswa (relasi many-to-many)
    $sql = "CREATE TABLE pembimbing_siswa (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        pembimbing_id INT(11) UNSIGNED NOT NULL,
        siswa_id INT(11) UNSIGNED NOT NULL,
        tanggal_mulai DATE NOT NULL,
        tanggal_selesai DATE NULL,
        status ENUM('aktif', 'selesai', 'batal') DEFAULT 'aktif',
        catatan TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_pembimbing_siswa (pembimbing_id, siswa_id),
        FOREIGN KEY (pembimbing_id) REFERENCES pembimbing(id) ON DELETE CASCADE,
        FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "Tabel pembimbing_siswa berhasil dibuat\n";
    
    // 5. Tabel log_aktivitas
    $sql = "CREATE TABLE log_aktivitas (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        siswa_id INT(11) UNSIGNED NOT NULL,
        tanggal DATE NOT NULL,
        jam_mulai TIME NOT NULL,
        jam_selesai TIME NOT NULL,
        uraian TEXT NOT NULL,
        kegiatan VARCHAR(255) NULL,
        output TEXT NULL,
        hambatan TEXT NULL,
        solusi TEXT NULL,
        bukti VARCHAR(255) NULL,
        status ENUM('menunggu', 'disetujui', 'revisi', 'ditolak') DEFAULT 'menunggu',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "Tabel log_aktivitas berhasil dibuat\n";
    
    // 6. Tabel komentar_pembimbing
    $sql = "CREATE TABLE komentar_pembimbing (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        log_id INT(11) UNSIGNED NOT NULL,
        pembimbing_id INT(11) UNSIGNED NOT NULL,
        komentar TEXT NOT NULL,
        rating INT(1) NULL CHECK (rating >= 1 AND rating <= 5),
        status ENUM('pending', 'dibaca') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (log_id) REFERENCES log_aktivitas(id) ON DELETE CASCADE,
        FOREIGN KEY (pembimbing_id) REFERENCES pembimbing(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "Tabel komentar_pembimbing berhasil dibuat\n";
    
    // 7. Tabel laporan_magang
    $sql = "CREATE TABLE laporan_magang (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        siswa_id INT(11) UNSIGNED NOT NULL,
        judul_laporan VARCHAR(255) NOT NULL,
        abstrak TEXT NULL,
        kata_kunci TEXT NULL,
        bab1_pendahuluan TEXT NULL,
        bab2_landasan_teori TEXT NULL,
        bab3_metodologi TEXT NULL,
        bab4_hasil_dan_pembahasan TEXT NULL,
        bab5_penutup TEXT NULL,
        daftar_pustaka TEXT NULL,
        lampiran TEXT NULL,
        file_laporan VARCHAR(255) NULL,
        status ENUM('draft', 'submitted', 'reviewed', 'approved', 'rejected') DEFAULT 'draft',
        tanggal_submit DATE NULL,
        tanggal_review DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    echo "Tabel laporan_magang berhasil dibuat\n";
    
    // 8. Tabel notifikasi
    $sql = "CREATE TABLE notifikasi (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        user_type ENUM('admin', 'pembimbing', 'siswa') NOT NULL,
        judul VARCHAR(255) NOT NULL,
        pesan TEXT NOT NULL,
        tipe ENUM('info', 'warning', 'success', 'error') DEFAULT 'info',
        is_read BOOLEAN DEFAULT FALSE,
        link VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Tabel notifikasi berhasil dibuat\n";
    
    echo "\n=== SEMUA TABEL BERHASIL DIBUAT! ===\n";
    echo "Struktur database yang dibuat:\n";
    echo "1. admin - untuk data administrator\n";
    echo "2. pembimbing - untuk data pembimbing magang\n";
    echo "3. siswa - untuk data siswa magang\n";
    echo "4. pembimbing_siswa - relasi pembimbing dan siswa\n";
    echo "5. log_aktivitas - log kegiatan harian siswa\n";
    echo "6. komentar_pembimbing - komentar dari pembimbing\n";
    echo "7. laporan_magang - laporan akhir magang\n";
    echo "8. notifikasi - sistem notifikasi\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
