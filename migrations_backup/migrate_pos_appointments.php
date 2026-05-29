<?php
require_once __DIR__ . '/app/config/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
    ALTER TABLE appointments 
    ADD COLUMN customer_name VARCHAR(100) NULL AFTER final_price,
    ADD COLUMN customer_phone VARCHAR(20) NULL AFTER customer_name,
    MODIFY COLUMN customer_id INT NULL;
    ";
    $pdo->exec($sql);
    echo "SUCCESS: Added customer_name and customer_phone to appointments.";
} catch(PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
