<?php
// Script sederhana untuk membuat akun admin SIMAMANG

echo "=== SIMAMANG - Admin Account Creator ===\n\n";

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

    // Cek apakah tabel admin sudah ada
    echo "🔍 Memeriksa struktur database...\n";
    $checkTable = $conn->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'admin')");
    $tableExists = $checkTable->fetchColumn();
    
    if (!$tableExists) {
        echo "❌ Tabel 'admin' tidak ditemukan!\n";
        echo "💡 Jalankan script 'reset_database.php' terlebih dahulu untuk membuat struktur database.\n";
        exit(1);
    }
    echo "✅ Tabel 'admin' ditemukan!\n\n";

    // Data admin default
    $username = "admin";
    $passwordPlain = "admin123";
    $nama = "Administrator SIMAMANG";

    echo "📋 Data admin yang akan dibuat:\n";
    echo "Username: $username\n";
    echo "Password: $passwordPlain\n";
    echo "Nama: $nama\n\n";

    // Cek apakah username sudah ada
    echo "🔍 Memeriksa username...\n";
    $check = $conn->prepare("SELECT id, nama FROM admin WHERE username = :username");
    $check->execute([':username' => $username]);
    $existingAdmin = $check->fetch();
    
    if ($existingAdmin) {
        echo "⚠️  Username '$username' sudah ada!\n";
        echo "   Admin: " . $existingAdmin['nama'] . "\n";
        echo "   ID: " . $existingAdmin['id'] . "\n\n";
        
        // Tampilkan semua admin yang ada
        echo "📋 Daftar semua admin di database:\n";
        $allAdmins = $conn->query("SELECT id, nama, username, status, created_at FROM admin ORDER BY id");
        echo str_repeat("-", 80) . "\n";
        echo sprintf("%-5s %-20s %-15s %-10s %-20s\n", "ID", "Nama", "Username", "Status", "Dibuat");
        echo str_repeat("-", 80) . "\n";
        
        while ($admin = $allAdmins->fetch(PDO::FETCH_ASSOC)) {
            echo sprintf("%-5s %-20s %-15s %-10s %-20s\n", 
                $admin['id'], 
                substr($admin['nama'], 0, 18), 
                $admin['username'], 
                $admin['status'],
                date('d/m/Y H:i', strtotime($admin['created_at']))
            );
        }
        echo str_repeat("-", 80) . "\n\n";
        
        echo "🚀 Anda bisa login dengan akun admin yang sudah ada:\n";
        echo "   URL: http://localhost:8080\n";
        echo "   Username: $username\n";
        echo "   Password: $passwordPlain\n\n";
        
        exit(0);
    }

    // Hash password
    echo "🔐 Mengenkripsi password...\n";
    $passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);

    // Insert admin baru
    echo "💾 Menyimpan data admin...\n";
    $sql = "INSERT INTO admin (nama, username, password, status, created_at) 
            VALUES (:nama, :username, :password, 'aktif', CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nama' => $nama,
        ':username' => $username,
        ':password' => $passwordHash
    ]);

    $adminId = $conn->lastInsertId();
    
    echo "\n🎉 AKUN ADMIN BERHASIL DIBUAT!\n";
    echo "================================\n";
    echo "ID: $adminId\n";
    echo "Username: $username\n";
    echo "Password: $passwordPlain\n";
    echo "Nama: $nama\n";
    echo "Status: Aktif\n";
    echo "================================\n\n";

    // Tampilkan semua admin yang ada
    echo "📋 Daftar semua admin di database:\n";
    $allAdmins = $conn->query("SELECT id, nama, username, status, created_at FROM admin ORDER BY id");
    echo str_repeat("-", 80) . "\n";
    echo sprintf("%-5s %-20s %-15s %-10s %-20s\n", "ID", "Nama", "Username", "Status", "Dibuat");
    echo str_repeat("-", 80) . "\n";
    
    while ($admin = $allAdmins->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("%-5s %-20s %-15s %-10s %-20s\n", 
            $admin['id'], 
            substr($admin['nama'], 0, 18), 
            $admin['username'], 
            $admin['status'],
            date('d/m/Y H:i', strtotime($admin['created_at']))
        );
    }
    echo str_repeat("-", 80) . "\n\n";

    echo "🚀 Anda sekarang bisa login ke SIMAMANG dengan:\n";
    echo "   URL: http://localhost:8080\n";
    echo "   Username: $username\n";
    echo "   Password: $passwordPlain\n\n";

    echo "💡 Tips keamanan:\n";
    echo "   - Ganti password setelah login pertama kali\n";
    echo "   - Jangan bagikan kredensial admin\n";
    echo "   - Backup database secara berkala\n\n";

    echo "🎯 Fitur admin yang tersedia:\n";
    echo "   - Kelola data siswa\n";
    echo "   - Kelola data pembimbing\n";
    echo "   - Lihat laporan magang semua siswa\n";
    echo "   - Export data dalam format PDF\n\n";

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
