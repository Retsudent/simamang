<?php
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

    $res = $mysqli->query("SELECT id, nama, username, password, nis, tempat_magang, status FROM siswa WHERE user_id IS NULL");
    $count = 0; $created = 0; $linked = 0;
    while ($s = $res->fetch_assoc()) {
        $count++;
        // Try find existing users by username
        $uStmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $uStmt->bind_param('s', $s['username']);
        $uStmt->execute();
        $uRes = $uStmt->get_result();
        if ($u = $uRes->fetch_assoc()) {
            // Link siswa to existing user
            $upd = $mysqli->prepare("UPDATE siswa SET user_id = ? WHERE id = ?");
            $upd->bind_param('ii', $u['id'], $s['id']);
            $upd->execute();
            $upd->close();
            $linked++;
            echo "LINKED siswa_id={$s['id']} -> users_id={$u['id']} ({$s['username']})\n";
        } else {
            // Create users row from siswa data (minimal columns to match schema)
            $ins = $mysqli->prepare("INSERT INTO users (nama, username, password, role, status) VALUES (?, ?, ?, 'siswa', ?)");
            $status = $s['status'] ?: 'aktif';
            $ins->bind_param('ssss', $s['nama'], $s['username'], $s['password'], $status);
            $ins->execute();
            $newUserId = $ins->insert_id;
            $ins->close();
            // Update siswa.user_id
            $upd = $mysqli->prepare("UPDATE siswa SET user_id = ? WHERE id = ?");
            $upd->bind_param('ii', $newUserId, $s['id']);
            $upd->execute();
            $upd->close();
            $created++;
            echo "CREATED users_id={$newUserId} and LINKED for siswa_id={$s['id']} ({$s['username']})\n";
        }
        $uStmt->close();
    }
    echo "DONE. scanned={$count} linked={$linked} created={$created}\n";
    $mysqli->close();
} catch (Throwable $e) {
    echo 'EXC:' . $e->getMessage() . "\n";
}
?>

