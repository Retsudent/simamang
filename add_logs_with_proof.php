<?php
// Script untuk menambahkan sample log dengan bukti
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=simamang', 'postgres', 'postgres');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Menambahkan sample log dengan bukti...\n";
    
    // Ambil ID siswa pertama
    $stmt = $pdo->query("SELECT id FROM siswa LIMIT 1");
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$siswa) {
        die("Tidak ada data siswa. Jalankan create_sample_data_postgresql.php terlebih dahulu.\n");
    }
    
    $siswaId = $siswa['id'];
    echo "Menggunakan siswa ID: $siswaId\n";
    
    // Buat file bukti sample
    $uploadDir = __DIR__ . '/writable/uploads/bukti';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }
    
    // Buat file PDF sample
    $pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/MediaBox [0 0 612 792]\n/Contents 4 0 R\n>>\nendobj\n4 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 12 Tf\n72 720 Td\n(Bukti Aktivitas Magang) Tj\nET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000204 00000 n \ntrailer\n<<\n/Size 5\n/Root 1 0 R\n>>\nstartxref\n297\n%%EOF";
    
    $pdfFilename = 'bukti_aktivitas_' . date('Y-m-d_H-i-s') . '.pdf';
    file_put_contents($uploadDir . '/' . $pdfFilename, $pdfContent);
    
    // Buat file gambar sample
    $imageContent = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==";
    $imageFilename = 'screenshot_' . date('Y-m-d_H-i-s') . '.png';
    file_put_contents($uploadDir . '/' . $imageFilename, base64_decode($imageContent));
    
    // Sample log dengan bukti
    $sampleLogs = [
        [
            'tanggal' => '2025-08-15',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'uraian' => 'Mempelajari konsep REST API dan implementasinya menggunakan CodeIgniter 4. Membuat endpoint untuk CRUD operations dan testing menggunakan Postman. Belajar tentang HTTP methods (GET, POST, PUT, DELETE) dan response codes.',
            'status' => 'menunggu',
            'bukti' => $pdfFilename
        ],
        [
            'tanggal' => '2025-08-16',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'uraian' => 'Praktik membuat aplikasi web sederhana dengan fitur autentikasi dan otorisasi. Implementasi login, register, dan middleware untuk proteksi route. Belajar tentang session management dan password hashing.',
            'status' => 'menunggu',
            'bukti' => $imageFilename
        ]
    ];
    
    // Insert sample logs
    $sql = "INSERT INTO log_aktivitas (siswa_id, tanggal, jam_mulai, jam_selesai, uraian, bukti, status) 
            VALUES (:siswa_id, :tanggal, :jam_mulai, :jam_selesai, :uraian, :bukti, :status)
            ON CONFLICT DO NOTHING";
    
    $stmt = $pdo->prepare($sql);
    
    foreach ($sampleLogs as $log) {
        $stmt->execute([
            ':siswa_id' => $siswaId,
            ':tanggal' => $log['tanggal'],
            ':jam_mulai' => $log['jam_mulai'],
            ':jam_selesai' => $log['jam_selesai'],
            ':uraian' => $log['uraian'],
            ':bukti' => $log['bukti'],
            ':status' => $log['status']
        ]);
        echo "Log untuk tanggal {$log['tanggal']} dengan bukti {$log['bukti']} berhasil ditambahkan\n";
    }
    
    echo "\nSample log dengan bukti berhasil ditambahkan!\n";
    echo "File bukti yang dibuat:\n";
    echo "- $pdfFilename (PDF)\n";
    echo "- $imageFilename (PNG)\n";
    echo "\nSekarang pembimbing bisa review log dan download bukti aktivitas.\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
