<?php
require_once __DIR__ . '/app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        "ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'shipping', 'completed', 'cancelled') DEFAULT 'pending'",
        "ALTER TABLE orders ADD COLUMN receipt_image VARCHAR(255) NULL",
        "ALTER TABLE orders ADD COLUMN paid_amount DECIMAL(10,2) DEFAULT 0",
        "ALTER TABLE orders ADD COLUMN admin_note TEXT NULL",
        "ALTER TABLE orders ADD COLUMN refund_bank VARCHAR(100) NULL",
        "ALTER TABLE orders ADD COLUMN refund_account VARCHAR(50) NULL",
        "ALTER TABLE orders ADD COLUMN refund_name VARCHAR(100) NULL",
        "ALTER TABLE orders ADD COLUMN refund_status ENUM('none', 'pending', 'completed') DEFAULT 'none'"
    ];

    foreach ($queries as $sql) {
        try {
            $pdo->exec($sql);
            echo "Success: $sql\n";
        } catch (PDOException $e) {
            echo "Skipped/Error (already exists?): " . $e->getMessage() . "\n";
        }
    }
    echo "Migration completed.\n";

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
