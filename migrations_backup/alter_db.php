<?php
require_once __DIR__ . '/app/config/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "ALTER TABLE appointments ADD COLUMN final_price DECIMAL(10,2) NULL AFTER doctor_id";
    $pdo->exec($sql);
    echo "SUCCESS: Added final_price column to appointments.";
} catch(PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
