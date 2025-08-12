<?php
// Jalankan ini sekali saja untuk membuat akun admin baru di database

// Konfigurasi koneksi DB (samakan dengan app/Config/Database.php)
$host = "localhost";
$port = "3306"; // default MySQL
$db   = "simamang"; // ganti dengan nama DB kamu
$user = "root"; // ganti dengan username DB kamu
$pass = ""; // ganti dengan password DB kamu

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Data admin baru
    $username = "admin03";
    $passwordPlain = "admin123"; // ganti sesuai keinginan
    $passwordHash = password_hash($passwordPlain, PASSWORD_DEFAULT);
    $nama = "Administrator";
    $role = "admin";

    // Cek apakah username sudah ada
    $check = $conn->prepare("SELECT id FROM users WHERE username = :username");
    $check->execute([':username' => $username]);
    if ($check->fetch()) {
        die("Username '$username' sudah ada di database.\n");
    }

    // Masukkan ke tabel users
    $sql = "INSERT INTO users (nama, username, password, role) 
            VALUES (:nama, :username, :password, :role)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nama' => $nama,
        ':username' => $username,
        ':password' => $passwordHash,
        ':role' => $role
    ]);

    echo "Akun admin berhasil dibuat!\n";
    echo "Username: $username\n";
    echo "Password: $passwordPlain\n";

} catch (PDOException $e) {
    die("Koneksi atau query gagal: " . $e->getMessage() . "\n");
}
