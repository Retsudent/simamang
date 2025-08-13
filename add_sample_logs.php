<?php
// Script untuk menambahkan sample log aktivitas
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=simamang', 'postgres', 'postgres');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Menambahkan sample log aktivitas...\n";
    
    // Ambil ID siswa pertama
    $stmt = $pdo->query("SELECT id FROM siswa LIMIT 1");
    $siswa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$siswa) {
        die("Tidak ada data siswa. Jalankan create_sample_data_postgresql.php terlebih dahulu.\n");
    }
    
    $siswaId = $siswa['id'];
    echo "Menggunakan siswa ID: $siswaId\n";
    
    // Sample log aktivitas
    $sampleLogs = [
        [
            'tanggal' => '2025-08-10',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'uraian' => 'Belajar dasar-dasar database PostgreSQL. Mempelajari struktur tabel, primary key, foreign key, dan relasi antar tabel. Praktik membuat database sederhana untuk sistem inventori.',
            'status' => 'disetujui'
        ],
        [
            'tanggal' => '2025-08-11',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'uraian' => 'Mempelajari framework CodeIgniter 4. Belajar tentang MVC pattern, routing, controller, model, dan view. Praktik membuat aplikasi CRUD sederhana.',
            'status' => 'disetujui'
        ],
        [
            'tanggal' => '2025-08-12',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'uraian' => 'Mengembangkan sistem monitoring aktivitas magang (SIMAMANG). Membuat fitur login, dashboard, dan input log aktivitas. Menggunakan Bootstrap 5 untuk frontend.',
            'status' => 'menunggu'
        ],
        [
            'tanggal' => '2025-08-13',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'uraian' => 'Melanjutkan pengembangan SIMAMANG. Menambahkan fitur riwayat aktivitas, detail log, dan sistem komentar pembimbing. Memperbaiki UI/UX aplikasi.',
            'status' => 'menunggu'
        ],
        [
            'tanggal' => '2025-08-14',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'uraian' => 'Testing dan debugging aplikasi SIMAMANG. Memperbaiki error database, foreign key constraints, dan validasi form. Memastikan semua fitur berjalan dengan baik.',
            'status' => 'menunggu'
        ]
    ];
    
    // Insert sample logs
    $sql = "INSERT INTO log_aktivitas (siswa_id, tanggal, jam_mulai, jam_selesai, uraian, status) 
            VALUES (:siswa_id, :tanggal, :jam_mulai, :jam_selesai, :uraian, :status)
            ON CONFLICT DO NOTHING";
    
    $stmt = $pdo->prepare($sql);
    
    foreach ($sampleLogs as $log) {
        $stmt->execute([
            ':siswa_id' => $siswaId,
            ':tanggal' => $log['tanggal'],
            ':jam_mulai' => $log['jam_mulai'],
            ':jam_selesai' => $log['jam_selesai'],
            ':uraian' => $log['uraian'],
            ':status' => $log['status']
        ]);
        echo "Log untuk tanggal {$log['tanggal']} berhasil ditambahkan\n";
    }
    
    // Tambah komentar pembimbing untuk log yang sudah disetujui
    $pembimbingStmt = $pdo->query("SELECT id FROM pembimbing LIMIT 1");
    $pembimbing = $pembimbingStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($pembimbing) {
        $pembimbingId = $pembimbing['id'];
        
        // Ambil log yang sudah disetujui
        $approvedLogs = $pdo->query("SELECT id FROM log_aktivitas WHERE siswa_id = $siswaId AND status = 'disetujui'")->fetchAll(PDO::FETCH_ASSOC);
        
        $commentSql = "INSERT INTO komentar_pembimbing (log_id, pembimbing_id, komentar, status_validasi) 
                       VALUES (:log_id, :pembimbing_id, :komentar, :status_validasi)
                       ON CONFLICT DO NOTHING";
        
        $commentStmt = $pdo->prepare($commentSql);
        
        foreach ($approvedLogs as $log) {
            $comments = [
                'Bagus sekali! Anda sudah memahami konsep database dengan baik. Lanjutkan pembelajaran untuk materi berikutnya.',
                'Excellent! Pemahaman tentang CodeIgniter 4 sudah sangat baik. Siap untuk melanjutkan ke tahap pengembangan aplikasi.'
            ];
            
            $comment = $comments[array_rand($comments)];
            
            $commentStmt->execute([
                ':log_id' => $log['id'],
                ':pembimbing_id' => $pembimbingId,
                ':komentar' => $comment,
                ':status_validasi' => 'disetujui'
            ]);
            
            echo "Komentar pembimbing untuk log ID {$log['id']} berhasil ditambahkan\n";
        }
    }
    
    echo "\nSample log aktivitas berhasil ditambahkan!\n";
    echo "Sekarang Anda bisa login sebagai siswa dan melihat riwayat aktivitas.\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
