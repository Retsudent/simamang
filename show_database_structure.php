<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=simamang', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== STRUKTUR DATABASE SIMAMANG ===\n\n";
    
    // Tampilkan semua tabel
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        echo "📋 TABEL: $table\n";
        echo str_repeat("-", 50) . "\n";
        
        // Tampilkan struktur tabel
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $column) {
            $null = $column['Null'] === 'YES' ? 'NULL' : 'NOT NULL';
            $default = $column['Default'] ? "DEFAULT {$column['Default']}" : '';
            echo "  {$column['Field']} - {$column['Type']} - $null $default\n";
        }
        
        // Tampilkan jumlah data
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  📊 Total data: {$count['total']}\n";
        
        // Tampilkan sample data (maksimal 3 baris)
        if ($count['total'] > 0) {
            $stmt = $pdo->query("SELECT * FROM $table LIMIT 3");
            $sampleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "  📝 Sample data:\n";
            foreach ($sampleData as $index => $row) {
                echo "    Row " . ($index + 1) . ": ";
                $displayData = [];
                foreach ($row as $key => $value) {
                    if (in_array($key, ['id', 'nama', 'username', 'status', 'created_at'])) {
                        $displayData[] = "$key: $value";
                    }
                }
                echo implode(', ', $displayData) . "\n";
            }
        }
        
        echo "\n";
    }
    
    echo "=== RELASI ANTAR TABEL ===\n";
    echo "1. admin ←→ notifikasi (user_id, user_type='admin')\n";
    echo "2. pembimbing ←→ pembimbing_siswa ←→ siswa\n";
    echo "3. pembimbing ←→ komentar_pembimbing ←→ log_aktivitas ←→ siswa\n";
    echo "4. siswa ←→ laporan_magang\n";
    echo "5. pembimbing ←→ notifikasi (user_id, user_type='pembimbing')\n";
    echo "6. siswa ←→ notifikasi (user_id, user_type='siswa')\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
