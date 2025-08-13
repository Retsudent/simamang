<?php
// Script untuk memperbaiki foreign key constraints di PostgreSQL
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=simamang', 'postgres', 'postgres');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Memperbaiki foreign key constraints...\n";
    
    // Drop existing foreign key constraints yang salah
    $pdo->exec("ALTER TABLE log_aktivitas DROP CONSTRAINT IF EXISTS log_aktivitas_siswa_id_fkey");
    $pdo->exec("ALTER TABLE komentar_pembimbing DROP CONSTRAINT IF EXISTS komentar_pembimbing_log_id_fkey");
    $pdo->exec("ALTER TABLE komentar_pembimbing DROP CONSTRAINT IF EXISTS komentar_pembimbing_pembimbing_id_fkey");
    $pdo->exec("ALTER TABLE siswa DROP CONSTRAINT IF EXISTS siswa_pembimbing_id_fkey");
    
    echo "Foreign key constraints lama berhasil dihapus\n";
    
    // Buat foreign key constraints yang benar
    $pdo->exec("ALTER TABLE log_aktivitas ADD CONSTRAINT log_aktivitas_siswa_id_fkey 
                FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE");
    
    $pdo->exec("ALTER TABLE komentar_pembimbing ADD CONSTRAINT komentar_pembimbing_log_id_fkey 
                FOREIGN KEY (log_id) REFERENCES log_aktivitas(id) ON DELETE CASCADE");
    
    $pdo->exec("ALTER TABLE komentar_pembimbing ADD CONSTRAINT komentar_pembimbing_pembimbing_id_fkey 
                FOREIGN KEY (pembimbing_id) REFERENCES pembimbing(id) ON DELETE CASCADE");
    
    $pdo->exec("ALTER TABLE siswa ADD CONSTRAINT siswa_pembimbing_id_fkey 
                FOREIGN KEY (pembimbing_id) REFERENCES pembimbing(id) ON DELETE SET NULL");
    
    echo "Foreign key constraints baru berhasil dibuat\n";
    
    // Periksa data yang ada
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM siswa");
    $siswaCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM pembimbing");
    $pembimbingCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM admin");
    $adminCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "\nData yang tersedia:\n";
    echo "Siswa: $siswaCount\n";
    echo "Pembimbing: $pembimbingCount\n";
    echo "Admin: $adminCount\n";
    
    echo "\nForeign key constraints berhasil diperbaiki!\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
