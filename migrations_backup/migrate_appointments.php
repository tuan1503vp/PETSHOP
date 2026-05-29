<?php
$host = 'localhost';
$db   = 'petshop_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
     $pdo = new PDO($dsn, $user, $pass);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $pdo->exec("ALTER TABLE appointments ADD COLUMN duration_value INT DEFAULT 1");
     $pdo->exec("ALTER TABLE appointments ADD COLUMN duration_unit ENUM('hour', 'day', 'none') DEFAULT 'none'");
     echo "Success";
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}
