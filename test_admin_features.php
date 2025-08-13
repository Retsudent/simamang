<?php
// Script untuk menguji fitur admin SIMAMANG

echo "=== SIMAMANG - Admin Features Test ===\n\n";

// Konfigurasi koneksi DB PostgreSQL
$host = "localhost";
$port = "5432";
$db   = "simamang";
$user = "postgres";
$pass = "postgres";

try {
    echo "🔗 Menghubungkan ke database PostgreSQL...\n";
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Koneksi database berhasil!\n\n";

    // Cek data yang tersedia
    echo "📊 DATA YANG TERSEDIA:\n";
    echo str_repeat("-", 50) . "\n";
    
    // Cek admin
    $adminCount = $conn->query("SELECT COUNT(*) FROM admin WHERE status = 'aktif'")->fetchColumn();
    echo "Admin aktif: $adminCount\n";
    
    // Cek pembimbing
    $pembimbingCount = $conn->query("SELECT COUNT(*) FROM pembimbing WHERE status = 'aktif'")->fetchColumn();
    echo "Pembimbing aktif: $pembimbingCount\n";
    
    // Cek siswa
    $siswaCount = $conn->query("SELECT COUNT(*) FROM siswa WHERE status = 'aktif'")->fetchColumn();
    echo "Siswa aktif: $siswaCount\n";
    
    // Cek log aktivitas
    $logCount = $conn->query("SELECT COUNT(*) FROM log_aktivitas")->fetchColumn();
    echo "Log aktivitas: $logCount\n";
    
    // Cek komentar pembimbing
    $komentarCount = $conn->query("SELECT COUNT(*) FROM komentar_pembimbing")->fetchColumn();
    echo "Komentar pembimbing: $komentarCount\n";
    
    echo str_repeat("-", 50) . "\n\n";

    // Cek pembimbing yang sudah ada siswa
    echo "📋 PEMBIMBING DAN SISWA:\n";
    echo str_repeat("-", 50) . "\n";
    
    $pembimbingSiswa = $conn->query("
        SELECT 
            p.nama as pembimbing_nama,
            COUNT(s.id) as jumlah_siswa,
            STRING_AGG(s.nama, ', ') as nama_siswa
        FROM pembimbing p
        LEFT JOIN siswa s ON p.id = s.pembimbing_id AND s.status = 'aktif'
        WHERE p.status = 'aktif'
        GROUP BY p.id, p.nama
        ORDER BY p.nama
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($pembimbingSiswa as $ps) {
        echo "Pembimbing: " . $ps['pembimbing_nama'] . "\n";
        echo "  Jumlah siswa: " . $ps['jumlah_siswa'] . "\n";
        if ($ps['nama_siswa']) {
            echo "  Siswa: " . $ps['nama_siswa'] . "\n";
        } else {
            echo "  Siswa: Belum ada siswa\n";
        }
        echo "\n";
    }
    
    echo str_repeat("-", 50) . "\n\n";

    // Cek siswa yang belum ada pembimbing
    echo "⚠️  SISWA YANG BELUM ADA PEMBIMBING:\n";
    echo str_repeat("-", 50) . "\n";
    
    $siswaTanpaPembimbing = $conn->query("
        SELECT nama, nis, tempat_magang
        FROM siswa 
        WHERE status = 'aktif' AND pembimbing_id IS NULL
        ORDER BY nama
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($siswaTanpaPembimbing)) {
        echo "✅ Semua siswa sudah memiliki pembimbing!\n";
    } else {
        foreach ($siswaTanpaPembimbing as $s) {
            echo "- " . $s['nama'] . " (NIS: " . $s['nis'] . ") - " . $s['tempat_magang'] . "\n";
        }
    }
    
    echo str_repeat("-", 50) . "\n\n";

    echo "🚀 INSTRUKSI TESTING FITUR ADMIN:\n";
    echo str_repeat("=", 60) . "\n\n";
    
    echo "1. LOGIN SEBAGAI ADMIN:\n";
    echo "   URL: http://localhost:8080\n";
    echo "   Username: admin\n";
    echo "   Password: admin123\n\n";
    
    echo "2. TEST FITUR ATUR BIMBINGAN:\n";
    echo "   - Klik menu 'Atur Bimbingan'\n";
    echo "   - Lihat daftar pembimbing dan siswa\n";
    echo "   - Klik 'Atur Siswa' pada pembimbing tertentu\n";
    echo "   - Pilih siswa yang akan dibimbing\n";
    echo "   - Klik 'Simpan Perubahan'\n\n";
    
    echo "3. TEST FITUR LAPORAN MAGANG:\n";
    echo "   - Klik menu 'Laporan Magang'\n";
    echo "   - Pilih siswa dari dropdown\n";
    echo "   - Pilih rentang tanggal\n";
    echo "   - Klik 'Tampilkan'\n";
    echo "   - Lihat preview laporan\n\n";
    
    echo "4. TEST FITUR KELOLA DATA:\n";
    echo "   - Kelola Siswa: Tambah, edit, hapus siswa\n";
    echo "   - Kelola Pembimbing: Tambah, edit, hapus pembimbing\n\n";
    
    echo "5. TEST DASHBOARD ADMIN:\n";
    echo "   - Lihat statistik di dashboard\n";
    echo "   - Cek quick actions\n";
    echo "   - Lihat daftar siswa dan pembimbing\n\n";
    
    echo "🔧 TROUBLESHOOTING:\n";
    echo str_repeat("-", 30) . "\n";
    echo "Jika ada error:\n";
    echo "1. Pastikan server berjalan: php spark serve\n";
    echo "2. Pastikan database terhubung\n";
    echo "3. Cek error log di writable/logs/\n";
    echo "4. Pastikan semua route terdaftar: php spark routes\n\n";
    
    echo "📞 SUPPORT:\n";
    echo str_repeat("-", 20) . "\n";
    echo "Jika masih ada masalah, periksa:\n";
    echo "- File log di writable/logs/\n";
    echo "- Konfigurasi database di app/Config/Database.php\n";
    echo "- Routes di app/Config/Routes.php\n";
    echo "- Controller Admin di app/Controllers/Admin.php\n\n";

} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n🔧 Troubleshooting:\n";
    echo "1. Pastikan PostgreSQL berjalan\n";
    echo "2. Pastikan database 'simamang' sudah dibuat\n";
    echo "3. Pastikan username/password PostgreSQL benar\n";
    echo "4. Jalankan 'reset_database.php' jika tabel belum ada\n";
    exit(1);
}
?>
