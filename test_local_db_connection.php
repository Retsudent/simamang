<?php
/**
 * Test Local Database Connection
 * Script untuk mengecek koneksi database lokal PostgreSQL
 */

echo "=== TEST LOCAL DATABASE CONNECTION ===\n\n";

// Konfigurasi database lokal PostgreSQL
$config = [
    'hostname' => 'localhost',
    'username' => 'postgres',
    'password' => 'postgres',
    'database' => 'simamang',
    'port' => 5432,
    'charset' => 'utf8'
];

echo "1. Testing PostgreSQL connection...\n";
echo "   Host: {$config['hostname']}\n";
echo "   Database: {$config['database']}\n";
echo "   Username: {$config['username']}\n";
echo "   Port: {$config['port']}\n\n";

try {
    // Test koneksi PostgreSQL
    $dsn = "pgsql:host={$config['hostname']};port={$config['port']};dbname={$config['database']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ PostgreSQL connection successful!\n\n";
    
    // Test query sederhana
    echo "2. Testing simple query...\n";
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "✅ Query test successful: " . $result['test'] . "\n\n";
    
    // Cek tabel yang ada
    echo "3. Checking available tables...\n";
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "❌ No tables found in database\n";
    } else {
        echo "✅ Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "   - $table\n";
        }
    }
    
    // Cek tabel log_aktivitas
    echo "\n4. Checking log_aktivitas table...\n";
    if (in_array('log_aktivitas', $tables)) {
        echo "✅ Table log_aktivitas exists\n";
        
        // Cek struktur tabel
        $stmt = $pdo->query("SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_name = 'log_aktivitas' ORDER BY ordinal_position");
        $columns = $stmt->fetchAll();
        echo "   Columns:\n";
        foreach ($columns as $column) {
            $nullable = $column['is_nullable'] === 'YES' ? 'NULL' : 'NOT NULL';
            echo "   - {$column['column_name']} ({$column['data_type']}) $nullable\n";
        }
        
        // Cek jumlah data
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM log_aktivitas");
        $count = $stmt->fetch();
        echo "   Total records: {$count['total']}\n";
        
    } else {
        echo "❌ Table log_aktivitas not found\n";
    }
    
    // Cek tabel siswa
    echo "\n5. Checking siswa table...\n";
    if (in_array('siswa', $tables)) {
        echo "✅ Table siswa exists\n";
        
        // Cek jumlah siswa
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM siswa");
        $count = $stmt->fetch();
        echo "   Total siswa: {$count['total']}\n";
        
        // Cek sample data
        $stmt = $pdo->query("SELECT id, nama, username FROM siswa LIMIT 3");
        $siswa = $stmt->fetchAll();
        if (!empty($siswa)) {
            echo "   Sample siswa:\n";
            foreach ($siswa as $s) {
                echo "   - ID: {$s['id']}, Nama: {$s['nama']}, Username: {$s['username']}\n";
            }
        }
        
    } else {
        echo "❌ Table siswa not found\n";
    }
    
} catch (PDOException $e) {
    echo "❌ PostgreSQL connection failed: " . $e->getMessage() . "\n";
    echo "   Error code: " . $e->getCode() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";



