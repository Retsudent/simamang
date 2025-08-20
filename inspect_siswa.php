<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = '127.0.0.1';
$user = 'dev_simamang';
$pass = 'NWyaTdmyWPZXZbsp';
$db   = 'dev_simamang';

$q = $argv[1] ?? '';

try {
    $mysqli = new mysqli($host, $user, $pass, $db, 3306);
    if ($mysqli->connect_errno) {
        echo 'CONNECT_ERR:' . $mysqli->connect_errno . ' ' . $mysqli->connect_error . "\n";
        exit(1);
    }

    $sql = "SELECT id, nama, username, nis, user_id, status FROM siswa WHERE nama LIKE ? OR username LIKE ? ORDER BY id LIMIT 50";
    $stmt = $mysqli->prepare($sql);
    $like = '%' . $q . '%';
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();
    echo "SISWA MATCHES (query='{$q}')\n";
    while ($row = $res->fetch_assoc()) {
        echo 'siswa_id=' . $row['id'] . ' nama=' . $row['nama'] . ' username=' . $row['username'] . ' nis=' . $row['nis'] . ' user_id=' . ($row['user_id'] ?? 'NULL') . ' status=' . $row['status'] . "\n";
        if (!empty($row['user_id'])) {
            $uStmt = $mysqli->prepare('SELECT id, username, nama, role, status FROM users WHERE id = ?');
            $uStmt->bind_param('i', $row['user_id']);
            $uStmt->execute();
            $uRes = $uStmt->get_result();
            if ($u = $uRes->fetch_assoc()) {
                echo '  -> users: id=' . $u['id'] . ' username=' . $u['username'] . ' nama=' . $u['nama'] . ' role=' . $u['role'] . ' status=' . $u['status'] . "\n";
            } else {
                echo "  -> users: NOT FOUND for user_id\n";
            }
            $uStmt->close();
        }
    }
    $stmt->close();
    $mysqli->close();
} catch (Throwable $e) {
    echo 'EXC:' . $e->getMessage() . "\n";
}
?>


