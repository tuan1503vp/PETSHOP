<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Add coins to users table
    $pdo->exec("ALTER TABLE users ADD COLUMN coins INT NOT NULL DEFAULT 0");
    echo "Added coins column to users table.\n";

    // 2. Create vouchers table
    $pdo->exec("CREATE TABLE IF NOT EXISTS vouchers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        discount_amount DECIMAL(10, 2) NOT NULL,
        cost_coins INT NOT NULL,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Created vouchers table.\n";

    // Insert default vouchers
    $pdo->exec("INSERT INTO vouchers (title, description, discount_amount, cost_coins) VALUES
    ('Voucher Giảm 20K', 'Sử dụng để giảm 20.000đ cho đơn hàng hoặc dịch vụ.', 20000, 20),
    ('Voucher Giảm 50K', 'Sử dụng để giảm 50.000đ cho đơn hàng hoặc dịch vụ.', 50000, 50),
    ('Voucher Giảm 100K', 'Sử dụng để giảm 100.000đ cho đơn hàng hoặc dịch vụ.', 100000, 100)");
    echo "Inserted default vouchers.\n";

    // 3. Create user_vouchers table
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_vouchers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        voucher_id INT NOT NULL,
        unique_code VARCHAR(50) NOT NULL UNIQUE,
        status ENUM('active', 'used') DEFAULT 'active',
        used_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE
    )");
    echo "Created user_vouchers table.\n";

    // 4. Create coin_history table
    $pdo->exec("CREATE TABLE IF NOT EXISTS coin_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        amount INT NOT NULL,
        reason VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "Created coin_history table.\n";

    echo "Database upgrade completed successfully.\n";
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
?>
