<?php
require_once 'app/config/config.php';

echo "<h2>PETSHOP - Database Migration Tool</h2>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. users: add coins
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN coins INT NOT NULL DEFAULT 0");
        echo "✅ Added 'coins' column to 'users' table.<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') === false) throw $e;
        echo "ℹ️ Column 'coins' already exists in 'users' table.<br>";
    }

    // 2. members
    $pdo->exec("CREATE TABLE IF NOT EXISTS `members` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `phone` varchar(20) DEFAULT NULL,
      `address` text DEFAULT NULL,
      `membership_level` varchar(50) DEFAULT 'Đồng',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'members' table.<br>";

    // 3. membership_benefits
    $pdo->exec("CREATE TABLE IF NOT EXISTS `membership_benefits` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `membership_level` varchar(50) NOT NULL,
      `benefit_text` text NOT NULL,
      `discount_percent` int(11) DEFAULT 0,
      `free_service` tinyint(4) DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'membership_benefits' table.<br>";

    // Insert default benefits if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM membership_benefits");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO `membership_benefits` (`membership_level`, `benefit_text`, `discount_percent`, `free_service`) VALUES
        ('Đồng', 'Tích điểm mỗi lần mua hàng', 0, 0),
        ('Bạc', 'Giảm 5% cho mọi đơn hàng', 5, 0),
        ('Vàng', 'Giảm 10% cho mọi đơn hàng', 10, 0),
        ('Bạch kim', 'Giảm 15% cho mọi đơn hàng', 15, 1),
        ('VIP', 'Giảm 20% cho mọi đơn hàng', 20, 1)");
        echo "✅ Inserted default membership benefits.<br>";
    }

    // 4. vouchers
    $pdo->exec("CREATE TABLE IF NOT EXISTS `vouchers` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `code` varchar(50) DEFAULT NULL,
      `description` text DEFAULT NULL,
      `start_date` datetime DEFAULT NULL,
      `end_date` datetime DEFAULT NULL,
      `discount_type` enum('fixed','percent') NOT NULL DEFAULT 'fixed',
      `discount_amount` decimal(10,2) NOT NULL,
      `max_discount` decimal(10,2) DEFAULT NULL,
      `min_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
      `category_id` int(11) DEFAULT NULL,
      `usage_limit` int(11) DEFAULT NULL,
      `usage_per_user` int(11) DEFAULT 1,
      `used_count` int(11) NOT NULL DEFAULT 0,
      `cost_coins` int(11) NOT NULL,
      `is_active` tinyint(1) DEFAULT 1,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `is_combinable` tinyint(1) DEFAULT 0,
      PRIMARY KEY (`id`),
      UNIQUE KEY `code` (`code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'vouchers' table.<br>";

    // Alter vouchers just in case it was created without the new fields
    $voucherColumns = [
        "code VARCHAR(50) DEFAULT NULL UNIQUE",
        "start_date DATETIME DEFAULT NULL",
        "end_date DATETIME DEFAULT NULL",
        "discount_type ENUM('fixed','percent') NOT NULL DEFAULT 'fixed'",
        "max_discount DECIMAL(10,2) DEFAULT NULL",
        "min_order_value DECIMAL(10,2) NOT NULL DEFAULT 0.00",
        "category_id INT(11) DEFAULT NULL",
        "usage_limit INT(11) DEFAULT NULL",
        "usage_per_user INT(11) DEFAULT 1",
        "used_count INT(11) NOT NULL DEFAULT 0",
        "is_combinable TINYINT(1) DEFAULT 0"
    ];
    foreach ($voucherColumns as $colDef) {
        $colName = explode(" ", trim($colDef))[0];
        try {
            $pdo->exec("ALTER TABLE vouchers ADD COLUMN $colDef");
            echo "✅ Added '$colName' column to 'vouchers' table.<br>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') === false) throw $e;
        }
    }

    // 5. user_vouchers
    $pdo->exec("CREATE TABLE IF NOT EXISTS `user_vouchers` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `voucher_id` int(11) NOT NULL,
      `unique_code` varchar(50) NOT NULL,
      `status` enum('active','used') DEFAULT 'active',
      `used_at` timestamp NULL DEFAULT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `unique_code` (`unique_code`),
      KEY `user_id` (`user_id`),
      KEY `voucher_id` (`voucher_id`),
      CONSTRAINT `user_vouchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      CONSTRAINT `user_vouchers_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'user_vouchers' table.<br>";

    // 6. coin_history
    $pdo->exec("CREATE TABLE IF NOT EXISTS `coin_history` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `amount` int(11) NOT NULL,
      `reason` varchar(255) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      CONSTRAINT `coin_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'coin_history' table.<br>";

    echo "<br><b style='color:green;'>🎉 Database upgrade completed successfully!</b>";
} catch (PDOException $e) {
    echo "<br><b style='color:red;'>❌ Database Error:</b> " . $e->getMessage() . "<br>";
}
?>
