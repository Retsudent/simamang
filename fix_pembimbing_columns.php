<?php
$cfg = [
  'host' => '192.168.2.198',
  'db'   => 'dev_simamang',
  'user' => 'dev_simamang',
  'pass' => 'NWyaTdmyWPZXZbsp',
  'port' => 3306,
  'charset' => 'utf8mb4',
];

try {
  $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $cfg['host'], $cfg['port'], $cfg['db'], $cfg['charset']);
  $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  echo "Connected. Checking pembimbing columns...\n";

  $need = [
    'email' => "VARCHAR(150) NULL",
    'no_hp' => "VARCHAR(50) NULL",
    'alamat' => "VARCHAR(255) NULL",
    'instansi' => "VARCHAR(150) NULL",
    'jabatan' => "VARCHAR(100) NULL",
    'bidang_keahlian' => "VARCHAR(150) NULL",
  ];

  // Get existing columns
  $stmt = $pdo->prepare("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'pembimbing'");
  $stmt->execute([$cfg['db']]);
  $existing = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'COLUMN_NAME');

  foreach ($need as $col => $def) {
    if (!in_array($col, $existing, true)) {
      $sql = "ALTER TABLE `pembimbing` ADD COLUMN `$col` $def";
      try {
        $pdo->exec($sql);
        echo "✓ Added column $col\n";
      } catch (Throwable $e) {
        echo "✗ Failed add $col: " . $e->getMessage() . "\n";
      }
    } else {
      echo "- Column $col already exists\n";
    }
  }

  echo "Done.\n";
} catch (Throwable $e) {
  echo 'ERROR: ' . $e->getMessage() . "\n";
}



