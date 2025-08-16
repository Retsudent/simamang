<?php

$host = '192.168.2.198';
$user = 'dev_simamang';
$pass = 'NWyaTdmyWPZXZbsp';
$db   = 'dev_simamang';
$port = 3306;

printf("Testing MySQL connection to %s:%d ...\n", $host, $port);

// 1) Uji koneksi menggunakan mysqli
$start = microtime(true);
$mysqli = @new mysqli($host, $user, $pass, $db, $port);
$duration = round((microtime(true) - $start) * 1000);

if ($mysqli && !$mysqli->connect_errno) {
    echo "✓ mysqli connected in {$duration} ms\n";
    $res = $mysqli->query('SELECT NOW() AS now_time');
    if ($res) {
        $row = $res->fetch_assoc();
        echo "Server time: " . $row['now_time'] . "\n";
        $res->close();
    }
    $mysqli->close();
} else {
    echo "✗ mysqli failed: " . ($mysqli ? $mysqli->connect_error : 'connection error') . " ({$duration} ms)\n";
}

// 2) Uji koneksi menggunakan PDO
try {
    $start = microtime(true);
    $dsn = "mysql:host={$host};dbname={$db};port={$port};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    $duration = round((microtime(true) - $start) * 1000);
    echo "✓ PDO connected in {$duration} ms\n";
    $stmt = $pdo->query('SELECT DATABASE() db, USER() user, NOW() now_time');
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "DB: {$info['db']} | USER: {$info['user']} | TIME: {$info['now_time']}\n";
} catch (Throwable $e) {
    echo "✗ PDO failed: " . $e->getMessage() . "\n";
}

// 3) Cek dari CodeIgniter layer (optional: try to get a connection)
try {
    require __DIR__ . '/vendor/autoload.php';
    $db = \Config\Database::connect();
    $start = microtime(true);
    $db->initialize();
    $duration = round((microtime(true) - $start) * 1000);
    echo "✓ CI Database connect OK in {$duration} ms\n";
    $query = $db->query('SELECT NOW() now_time');
    $row = $query->getRowArray();
    echo "CI server time: " . ($row['now_time'] ?? '-') . "\n";
} catch (Throwable $e) {
    echo "✗ CI connect failed: " . $e->getMessage() . "\n";
}
