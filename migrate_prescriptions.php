<?php
require_once __DIR__ . '/app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        "CREATE TABLE IF NOT EXISTS `health_record_prescriptions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `health_record_id` INT NOT NULL,
            `product_id` INT NOT NULL,
            `quantity` INT NOT NULL,
            `instruction` VARCHAR(255) NULL,
            FOREIGN KEY (`health_record_id`) REFERENCES `health_records`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        "ALTER TABLE `products` ADD COLUMN `expiry_date` DATE DEFAULT NULL;"
    ];

    foreach ($queries as $sql) {
        try {
            $pdo->exec($sql);
            echo "Success: " . substr($sql, 0, 80) . "...\n";
        } catch (PDOException $e) {
            echo "Skipped/Error: " . $e->getMessage() . "\n";
        }
    }
    echo "Migration completed.\n";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
