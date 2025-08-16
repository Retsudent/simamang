<?php
$cfg = [
  'host' => '192.168.2.198',
  'db'   => 'dev_simamang',
  'user' => 'dev_simamang',
  'pass' => 'NWyaTdmyWPZXZbsp',
  'port' => 3306,
  'charset' => 'utf8mb4',
  'collate' => 'utf8mb4_general_ci',
];

function execStmt(PDO $pdo, string $sql, string $label) {
  try {
    $pdo->exec($sql);
    echo "✓ $label\n";
  } catch (Throwable $e) {
    echo "✗ $label -> " . $e->getMessage() . "\n";
  }
}

try {
  $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $cfg['host'], $cfg['port'], $cfg['db'], $cfg['charset']);
  $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  echo "Connected to {$cfg['host']}:{$cfg['port']} / {$cfg['db']}\n";

  // Ensure database default charset
  execStmt($pdo, "ALTER DATABASE `{$cfg['db']}` CHARACTER SET {$cfg['charset']} COLLATE {$cfg['collate']}", 'Set database charset');

  // users
  execStmt($pdo, "CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(150) NOT NULL,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin','pembimbing','siswa') NOT NULL,
    `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    `foto_profil` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET={$cfg['charset']} COLLATE={$cfg['collate']}", 'Create users');

  // admin
  execStmt($pdo, "CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(150) NOT NULL,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    `foto_profil` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET={$cfg['charset']} COLLATE={$cfg['collate']}", 'Create admin');

  // pembimbing
  execStmt($pdo, "CREATE TABLE IF NOT EXISTS `pembimbing` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(150) NOT NULL,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    `foto_profil` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET={$cfg['charset']} COLLATE={$cfg['collate']}", 'Create pembimbing');

  // siswa
  execStmt($pdo, "CREATE TABLE IF NOT EXISTS `siswa` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(150) NOT NULL,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nis` VARCHAR(50) NOT NULL,
    `tempat_magang` VARCHAR(150) NOT NULL,
    `alamat_magang` VARCHAR(255) NOT NULL,
    `pembimbing_id` INT UNSIGNED NULL,
    `tanggal_mulai_magang` DATE NULL,
    `tanggal_selesai_magang` DATE NULL,
    `status` ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    `foto_profil` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_pembimbing_id` (`pembimbing_id`),
    CONSTRAINT `fk_siswa_pembimbing` FOREIGN KEY (`pembimbing_id`) REFERENCES `pembimbing`(`id`) ON UPDATE CASCADE ON DELETE SET NULL
  ) ENGINE=InnoDB DEFAULT CHARSET={$cfg['charset']} COLLATE={$cfg['collate']}", 'Create siswa');

  // log_aktivitas
  execStmt($pdo, "CREATE TABLE IF NOT EXISTS `log_aktivitas` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `siswa_id` INT UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `jam_mulai` TIME NULL,
    `jam_selesai` TIME NULL,
    `uraian` TEXT NOT NULL,
    `status` ENUM('menunggu','disetujui','revisi','ditolak') NOT NULL DEFAULT 'menunggu',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_siswa_id` (`siswa_id`),
    CONSTRAINT `fk_log_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `siswa`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET={$cfg['charset']} COLLATE={$cfg['collate']}", 'Create log_aktivitas');

  // komentar_pembimbing
  execStmt($pdo, "CREATE TABLE IF NOT EXISTS `komentar_pembimbing` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `log_id` INT UNSIGNED NOT NULL,
    `pembimbing_id` INT UNSIGNED NOT NULL,
    `komentar` TEXT NULL,
    `status_validasi` ENUM('menunggu','disetujui','revisi') NOT NULL DEFAULT 'menunggu',
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_log_id` (`log_id`),
    KEY `idx_kpembimbing_id` (`pembimbing_id`),
    CONSTRAINT `fk_kp_log` FOREIGN KEY (`log_id`) REFERENCES `log_aktivitas`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_kp_pembimbing` FOREIGN KEY (`pembimbing_id`) REFERENCES `pembimbing`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET={$cfg['charset']} COLLATE={$cfg['collate']}", 'Create komentar_pembimbing');

  // Verify tables
  $tables = ['users','admin','pembimbing','siswa','log_aktivitas','komentar_pembimbing'];
  foreach ($tables as $t) {
    $stmt = $pdo->prepare("SELECT COUNT(*) c FROM information_schema.tables WHERE table_schema = ? AND table_name = ?");
    $stmt->execute([$cfg['db'], $t]);
    $exists = (int)$stmt->fetch()['c'] > 0 ? 'OK' : 'MISSING';
    echo "Check table $t: $exists\n";
  }

  echo "Done.\n";
} catch (Throwable $e) {
  echo 'ERROR: ' . $e->getMessage() . "\n";
}



