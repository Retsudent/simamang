<?php
/**
 * Script untuk memperbaiki data pembimbing_siswa
 * Memindahkan data dari field pembimbing_id di tabel siswa ke tabel pembimbing_siswa
 */

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
require_once $pathsPath;

$app = require realpath(FCPATH . '../app/Config/Boot/production.php');
$app->run();

// Connect to database
$db = \Config\Database::connect();

echo "=== Script Perbaikan Data Pembimbing Siswa ===\n";
echo "Memindahkan data dari siswa.pembimbing_id ke tabel pembimbing_siswa\n\n";

try {
    // 1. Ambil semua siswa yang memiliki pembimbing_id
    $siswaWithPembimbing = $db->table('siswa')
                              ->where('pembimbing_id IS NOT NULL')
                              ->where('pembimbing_id !=', 0)
                              ->get()
                              ->getResultArray();
    
    echo "Ditemukan " . count($siswaWithPembimbing) . " siswa dengan pembimbing_id\n";
    
    if (empty($siswaWithPembimbing)) {
        echo "Tidak ada data yang perlu diperbaiki.\n";
        exit;
    }
    
    // 2. Masukkan data ke tabel pembimbing_siswa
    $insertedCount = 0;
    foreach ($siswaWithPembimbing as $siswa) {
        // Cek apakah data sudah ada di pembimbing_siswa
        $exists = $db->table('pembimbing_siswa')
                     ->where('pembimbing_id', $siswa['pembimbing_id'])
                     ->where('siswa_id', $siswa['id'])
                     ->countAllResults();
        
        if ($exists == 0) {
            $data = [
                'pembimbing_id' => $siswa['pembimbing_id'],
                'siswa_id' => $siswa['id']
            ];
            
            $db->table('pembimbing_siswa')->insert($data);
            $insertedCount++;
            
            echo "✓ Siswa ID {$siswa['id']} ({$siswa['nama']}) -> Pembimbing ID {$siswa['pembimbing_id']}\n";
        } else {
            echo "- Siswa ID {$siswa['id']} ({$siswa['nama']}) sudah ada di pembimbing_siswa\n";
        }
    }
    
    echo "\n=== Hasil ===\n";
    echo "Total data yang dimasukkan: {$insertedCount}\n";
    
    // 3. Verifikasi data
    echo "\n=== Verifikasi Data ===\n";
    
    // Hitung total relasi di pembimbing_siswa
    $totalRelations = $db->table('pembimbing_siswa')->countAllResults();
    echo "Total relasi di tabel pembimbing_siswa: {$totalRelations}\n";
    
    // Hitung siswa yang masih memiliki pembimbing_id
    $siswaWithOldPembimbingId = $db->table('siswa')
                                   ->where('pembimbing_id IS NOT NULL')
                                   ->where('pembimbing_id !=', 0)
                                   ->countAllResults();
    echo "Siswa yang masih memiliki pembimbing_id: {$siswaWithOldPembimbingId}\n";
    
    // 4. Opsional: Hapus field pembimbing_id dari tabel siswa
    echo "\n=== Opsional: Hapus Field pembimbing_id ===\n";
    echo "Apakah Anda ingin menghapus field pembimbing_id dari tabel siswa? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    
    if (trim($line) === 'y' || trim($line) === 'Y') {
        // Hapus field pembimbing_id dari tabel siswa
        $db->query("ALTER TABLE siswa DROP COLUMN pembimbing_id");
        echo "✓ Field pembimbing_id berhasil dihapus dari tabel siswa\n";
    } else {
        echo "Field pembimbing_id tidak dihapus.\n";
    }
    
    echo "\n=== Selesai ===\n";
    echo "Data pembimbing_siswa berhasil diperbaiki!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
