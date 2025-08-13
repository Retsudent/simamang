<?php
// Script untuk membuat data sample di PostgreSQL
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=simamang', 'postgres', 'postgres');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Tambah pembimbing sample
    $passwordHash = password_hash('pembimbing123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO pembimbing (nama, username, password, email, no_hp, status) 
            VALUES ('Pak Ahmad', 'pembimbing1', :password, 'ahmad@email.com', '08123456789', 'aktif')
            ON CONFLICT (username) DO NOTHING";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':password' => $passwordHash]);
    echo "Pembimbing sample berhasil ditambahkan\n";
    
    // Tambah siswa sample
    $passwordHash = password_hash('siswa123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO siswa (nama, username, password, nis, tempat_magang, alamat_magang, status) 
            VALUES ('Budi Santoso', 'siswa1', :password, '12345', 'PT Maju Jaya', 'Jl. Sudirman No. 123', 'aktif')
            ON CONFLICT (username) DO NOTHING";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':password' => $passwordHash]);
    echo "Siswa sample berhasil ditambahkan\n";
    
    // Tambah siswa sample 2
    $passwordHash = password_hash('siswa123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO siswa (nama, username, password, nis, tempat_magang, alamat_magang, status) 
            VALUES ('Siti Nurhaliza', 'siswa2', :password, '12346', 'CV Sukses Mandiri', 'Jl. Thamrin No. 456', 'aktif')
            ON CONFLICT (username) DO NOTHING";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':password' => $passwordHash]);
    echo "Siswa sample 2 berhasil ditambahkan\n";
    
    echo "\nData sample berhasil ditambahkan!\n";
    echo "Akun untuk testing:\n";
    echo "Admin: admin / admin123\n";
    echo "Pembimbing: pembimbing1 / pembimbing123\n";
    echo "Siswa: siswa1 / siswa123\n";
    echo "Siswa: siswa2 / siswa123\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
