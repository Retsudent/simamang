<?php
// Script untuk membuat akun admin custom SIMAMANG

echo "=== SIMAMANG - Custom Admin Creator ===\n\n";

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

    // Data admin custom
    $username = "superadmin";
    $passwordPlain = "superadmin2024";
    $nama = "Super Administrator";

    echo "📋 Data admin custom yang akan dibuat:\n";
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
    
    echo "\n🎉 AKUN ADMIN CUSTOM BERHASIL DIBUAT!\n";
    echo "=====================================\n";
    echo "ID: $adminId\n";
    echo "Username: $username\n";
    echo "Password: $passwordPlain\n";
    echo "Nama: $nama\n";
    echo "Status: Aktif\n";
    echo "=====================================\n\n";

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

    echo "💡 Atau gunakan akun admin default:\n";
    echo "   Username: admin\n";
    echo "   Password: admin123\n\n";

} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
