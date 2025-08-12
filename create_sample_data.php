<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=simamang', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MEMBUAT DATA SAMPLE ===\n\n";
    
    // 1. Insert Admin
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO admin (nama, username, password, email, no_hp, alamat) VALUES 
            ('Administrator Utama', 'admin', '$adminPassword', 'admin@simamang.com', '081234567890', 'Jl. Admin No. 1'),
            ('Admin Sistem', 'admin2', '$adminPassword', 'admin2@simamang.com', '081234567891', 'Jl. Admin No. 2')";
    $pdo->exec($sql);
    echo "✅ 2 data admin berhasil dibuat\n";
    
    // 2. Insert Pembimbing
    $pembimbingPassword = password_hash('pembimbing123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO pembimbing (nama, username, password, email, no_hp, alamat, instansi, jabatan, bidang_keahlian) VALUES 
            ('Dr. Ahmad Supriadi', 'pembimbing1', '$pembimbingPassword', 'ahmad@simamang.com', '081234567892', 'Jl. Pembimbing No. 1', 'PT. Teknologi Maju', 'Senior Developer', 'Web Development, Mobile Apps'),
            ('Ir. Siti Nurhaliza', 'pembimbing2', '$pembimbingPassword', 'siti@simamang.com', '081234567893', 'Jl. Pembimbing No. 2', 'CV. Digital Solutions', 'Project Manager', 'Database Design, System Analysis'),
            ('M.Kom. Budi Santoso', 'pembimbing3', '$pembimbingPassword', 'budi@simamang.com', '081234567894', 'Jl. Pembimbing No. 3', 'PT. Software House', 'Lead Developer', 'Backend Development, API Design')";
    $pdo->exec($sql);
    echo "✅ 3 data pembimbing berhasil dibuat\n";
    
    // 3. Insert Siswa
    $siswaPassword = password_hash('siswa123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO siswa (nama, username, password, nis, nisn, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_hp, email, kelas, jurusan, tempat_magang, alamat_magang, periode_magang) VALUES 
            ('Rizki Pratama', 'siswa1', '$siswaPassword', '2021001', '1234567890', 'Jakarta', '2005-03-15', 'L', 'Jl. Siswa No. 1', '081234567895', 'rizki@email.com', 'XI', 'Rekayasa Perangkat Lunak', 'PT. Teknologi Maju', 'Jl. Teknologi No. 1', 'Januari - Maret 2025'),
            ('Dewi Sartika', 'siswa2', '$siswaPassword', '2021002', '1234567891', 'Bandung', '2005-07-22', 'P', 'Jl. Siswa No. 2', '081234567896', 'dewi@email.com', 'XI', 'Rekayasa Perangkat Lunak', 'CV. Digital Solutions', 'Jl. Digital No. 1', 'Januari - Maret 2025'),
            ('Ahmad Fauzi', 'siswa3', '$siswaPassword', '2021003', '1234567892', 'Surabaya', '2005-01-10', 'L', 'Jl. Siswa No. 3', '081234567897', 'ahmad@email.com', 'XI', 'Rekayasa Perangkat Lunak', 'PT. Software House', 'Jl. Software No. 1', 'Januari - Maret 2025'),
            ('Siti Aisyah', 'siswa4', '$siswaPassword', '2021004', '1234567893', 'Semarang', '2005-11-05', 'P', 'Jl. Siswa No. 4', '081234567898', 'aisyah@email.com', 'XI', 'Rekayasa Perangkat Lunak', 'PT. Teknologi Maju', 'Jl. Teknologi No. 1', 'Januari - Maret 2025'),
            ('Muhammad Rizki', 'siswa5', '$siswaPassword', '2021005', '1234567894', 'Yogyakarta', '2005-05-18', 'L', 'Jl. Siswa No. 5', '081234567899', 'rizki2@email.com', 'XI', 'Rekayasa Perangkat Lunak', 'CV. Digital Solutions', 'Jl. Digital No. 1', 'Januari - Maret 2025')";
    $pdo->exec($sql);
    echo "✅ 5 data siswa berhasil dibuat\n";
    
    // 4. Insert Pembimbing-Siswa Relationship
    $sql = "INSERT INTO pembimbing_siswa (pembimbing_id, siswa_id, tanggal_mulai, status) VALUES 
            (1, 1, '2025-01-01', 'aktif'),
            (1, 4, '2025-01-01', 'aktif'),
            (2, 2, '2025-01-01', 'aktif'),
            (2, 5, '2025-01-01', 'aktif'),
            (3, 3, '2025-01-01', 'aktif')";
    $pdo->exec($sql);
    echo "✅ 5 relasi pembimbing-siswa berhasil dibuat\n";
    
    // 5. Insert Sample Log Aktivitas
    $sql = "INSERT INTO log_aktivitas (siswa_id, tanggal, jam_mulai, jam_selesai, uraian, kegiatan, output, hambatan, solusi, status) VALUES 
            (1, '2025-01-15', '08:00:00', '12:00:00', 'Belajar dasar-dasar HTML dan CSS', 'Pembelajaran Web Development', 'Membuat halaman web sederhana', 'Kesulitan dengan CSS Grid', 'Mencari tutorial dan praktik', 'disetujui'),
            (1, '2025-01-16', '08:00:00', '12:00:00', 'Mempelajari JavaScript dasar', 'Pembelajaran JavaScript', 'Membuat fungsi sederhana', 'Kesulitan dengan scope variable', 'Belajar dari dokumentasi', 'disetujui'),
            (2, '2025-01-15', '08:00:00', '12:00:00', 'Belajar database MySQL', 'Pembelajaran Database', 'Membuat database sederhana', 'Kesulitan dengan JOIN', 'Praktik dengan data sample', 'menunggu'),
            (3, '2025-01-15', '08:00:00', '12:00:00', 'Belajar PHP dasar', 'Pembelajaran PHP', 'Membuat script PHP sederhana', 'Kesulitan dengan array', 'Latihan dengan contoh', 'disetujui')";
    $pdo->exec($sql);
    echo "✅ 4 log aktivitas sample berhasil dibuat\n";
    
    // 6. Insert Sample Komentar Pembimbing
    $sql = "INSERT INTO komentar_pembimbing (log_id, pembimbing_id, komentar, rating, status) VALUES 
            (1, 1, 'Bagus sekali! Sudah memahami konsep dasar HTML dan CSS. Lanjutkan dengan JavaScript.', 5, 'dibaca'),
            (2, 1, 'JavaScript sudah bagus, tapi perlu latihan lebih untuk memahami scope dan closure.', 4, 'dibaca'),
            (3, 2, 'Database design sudah baik, untuk JOIN perlu latihan dengan kasus nyata.', 4, 'dibaca'),
            (4, 3, 'PHP dasar sudah dikuasai dengan baik. Selanjutnya bisa belajar OOP.', 5, 'dibaca')";
    $pdo->exec($sql);
    echo "✅ 4 komentar pembimbing sample berhasil dibuat\n";
    
    echo "\n=== DATA SAMPLE BERHASIL DIBUAT! ===\n";
    echo "\nInformasi Login:\n";
    echo "📱 ADMIN:\n";
    echo "   Username: admin, Password: admin123\n";
    echo "   Username: admin2, Password: admin123\n";
    echo "\n👨‍💼 PEMBIMBING:\n";
    echo "   Username: pembimbing1, Password: pembimbing123\n";
    echo "   Username: pembimbing2, Password: pembimbing123\n";
    echo "   Username: pembimbing3, Password: pembimbing123\n";
    echo "\n👨‍🎓 SISWA:\n";
    echo "   Username: siswa1, Password: siswa123\n";
    echo "   Username: siswa2, Password: siswa123\n";
    echo "   Username: siswa3, Password: siswa123\n";
    echo "   Username: siswa4, Password: siswa123\n";
    echo "   Username: siswa5, Password: siswa123\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
