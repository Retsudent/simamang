<?php
$cfg = [
  'host' => '192.168.2.198',
  'db'   => 'dev_simamang',
  'user' => 'dev_simamang',
  'pass' => 'NWyaTdmyWPZXZbsp',
  'port' => 3306,
  'charset' => 'utf8mb4',
];

$adminUsername = 'admin';
$adminPassword = 'admin123'; // will be hashed

try {
  $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $cfg['host'], $cfg['port'], $cfg['db'], $cfg['charset']);
  $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  echo "Connected.\n";

  // 1) Try users table first
  $stmt = $pdo->prepare('SELECT id, username, status FROM users WHERE username = ? LIMIT 1');
  $stmt->execute([$adminUsername]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    echo "users.admin already exists (status={$user['status']}).\n";
  } else {
    // 2) Try admin table
    $stmt = $pdo->prepare('SELECT id, username, status FROM admin WHERE username = ? LIMIT 1');
    $stmt->execute([$adminUsername]);
    $adm = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adm) {
      echo "admin.admin already exists (status={$adm['status']}).\n";
    } else {
      $hash = password_hash($adminPassword, PASSWORD_DEFAULT);
      $ins = $pdo->prepare('INSERT INTO admin (nama, username, password, status, created_at) VALUES (?,?,?,?,NOW())');
      $ins->execute(['Administrator', $adminUsername, $hash, 'aktif']);
      echo "Created admin in table admin with username=admin / password=admin123.\n";
    }
  }

  echo "Done.\n";
} catch (Throwable $e) {
  echo 'ERROR: ' . $e->getMessage() . "\n";
}



