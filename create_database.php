<?php
// Script untuk membuat database simamang
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database
    $sql = "CREATE DATABASE IF NOT EXISTS simamang CHARACTER SET utf8 COLLATE utf8_general_ci";
    $pdo->exec($sql);
    
    echo "Database 'simamang' berhasil dibuat!\n";
    
    // Pilih database
    $pdo->exec("USE simamang");
    
    echo "Database 'simamang' berhasil dipilih!\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
