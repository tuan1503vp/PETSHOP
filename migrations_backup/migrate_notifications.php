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
     $pdo->exec("ALTER TABLE appointments ADD COLUMN customer_notified TINYINT(1) DEFAULT 0");
     $pdo->exec("ALTER TABLE orders ADD COLUMN customer_notified TINYINT(1) DEFAULT 0");
     echo "Success";
} catch (\PDOException $e) {
     echo "Error: " . $e->getMessage();
}
