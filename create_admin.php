<?php
// Jalankan ini sekali saja untuk membuat akun admin baru di database

// Konfigurasi koneksi DB PostgreSQL (samakan dengan app/Config/Database.php)
$host = "localhost";
$port = "5432"; // default PostgreSQL
$db   = "simamang"; // ganti dengan nama DB kamu
$user = "postgres"; // ganti dengan username DB kamu
$pass = "postgres"; // ganti dengan password DB kamu

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Data admin baru
    $username = "admin";
    $passwordPlain = "admin123"; // ganti sesuai keinginan
    $passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);
    $nama = "Administrator";

    // Cek apakah username sudah ada di tabel admin
    $check = $conn->prepare("SELECT id FROM admin WHERE username = :username");
    $check->execute([':username' => $username]);
    if ($check->fetch()) {
        die("Username '$username' sudah ada di database.\n");
    }

    // Masukkan ke tabel admin
    $sql = "INSERT INTO admin (nama, username, password, status) 
            VALUES (:nama, :username, :password, 'aktif')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nama' => $nama,
        ':username' => $username,
        ':password' => $passwordHash
    ]);

    echo "Akun admin berhasil dibuat!\n";
    echo "Username: $username\n";
    echo "Password: $passwordPlain\n";

} catch (PDOException $e) {
    die("Koneksi atau query gagal: " . $e->getMessage() . "\n");
}
