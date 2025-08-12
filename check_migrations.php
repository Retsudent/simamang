<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=simamang', 'root', '');
    $stmt = $pdo->query('SHOW TABLES');
    echo "Tabel yang ada di database 'simamang':\n";
    while($row = $stmt->fetch()) {
        echo "- " . $row[0] . "\n";
    }
    
    echo "\nCek tabel migrations:\n";
    $stmt = $pdo->query('SELECT * FROM migrations');
    while($row = $stmt->fetch()) {
        echo "- " . $row['version'] . " - " . $row['class'] . "\n";
    }
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
?>
