<?php
// Simple DB connectivity test
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$user = 'dev_simamang';
$pass = 'NWyaTdmyWPZXZbsp';
$db   = 'dev_simamang';

try {
    $mysqli = new mysqli($host, $user, $pass, $db, 3306);
    if ($mysqli->connect_errno) {
        echo 'CONNECT_ERR:' . $mysqli->connect_errno . ' ' . $mysqli->connect_error . "\n";
        exit(1);
    }
    $res = $mysqli->query("SELECT id, username, role, status FROM users LIMIT 3");
    if (!$res) {
        echo 'QUERY_ERR:' . $mysqli->error . "\n";
    } else {
        while ($row = $res->fetch_assoc()) {
            echo $row['id'] . ' ' . $row['username'] . ' ' . $row['role'] . ' ' . $row['status'] . "\n";
        }
        if ($res->num_rows === 0) {
            echo "EMPTY_USERS\n";
        }
    }
    $mysqli->close();
} catch (Throwable $e) {
    echo 'EXC:' . $e->getMessage() . "\n";
}
?>

