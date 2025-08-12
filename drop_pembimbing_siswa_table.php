<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=simamang', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MENGHAPUS TABEL PEMBIMBING_SISWA ===\n\n";
    
    // Hapus tabel pembimbing_siswa
    $sql = "DROP TABLE IF EXISTS pembimbing_siswa";
    $pdo->exec($sql);
    echo "✅ Tabel pembimbing_siswa berhasil dihapus\n";
    
    // Tampilkan tabel yang tersisa
    echo "\n=== TABEL YANG TERSISA ===\n";
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    echo "\n=== SELESAI ===\n";
    echo "Tabel pembimbing_siswa telah dihapus dari database simamang\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
