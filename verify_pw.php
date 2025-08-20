<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$user = 'dev_simamang';
$pass = 'NWyaTdmyWPZXZbsp';
$db   = 'dev_simamang';

function checkUser($mysqli, $username, $plainPassword) {
    $stmt = $mysqli->prepare('SELECT id, username, role, status, password FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $ok = password_verify($plainPassword, $row['password']);
        echo $row['username'] . ' role=' . $row['role'] . ' status=' . $row['status'] . ' verify=' . ($ok ? 'OK' : 'FAIL') . "\n";
    } else {
        echo $username . ' NOT_FOUND' . "\n";
    }
    $stmt->close();
}

try {
    $mysqli = new mysqli($host, $user, $pass, $db, 3306);
    if ($mysqli->connect_errno) {
        echo 'CONNECT_ERR:' . $mysqli->connect_errno . ' ' . $mysqli->connect_error . "\n";
        exit(1);
    }
    checkUser($mysqli, 'admin', 'admin123');
    checkUser($mysqli, 'Hendro', 'pembimbing123');
    checkUser($mysqli, 'Hapis', 'siswa123');
    checkUser($mysqli, 'supar', 'siswa123');
    $mysqli->close();
} catch (Throwable $e) {
    echo 'EXC:' . $e->getMessage() . "\n";
}
?>


