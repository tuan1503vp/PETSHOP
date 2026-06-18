<?php
require_once 'app/config/config.php';

echo "<h2>PETSHOP - Database Migration Tool</h2>";

function addColumnSafe($pdo, $table, $column, $definition) {
    try {
        $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
        echo "✅ Added column '$column' to '$table' table.<br>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "ℹ️ Column '$column' already exists in '$table' table.<br>";
        } else {
            throw $e;
        }
    }
}

function modifyColumnSafe($pdo, $table, $column, $definition) {
    try {
        $pdo->exec("ALTER TABLE `$table` MODIFY COLUMN `$column` $definition");
        echo "✅ Modified column '$column' in '$table' table.<br>";
    } catch (PDOException $e) {
        echo "❌ Error modifying column '$column' in '$table': " . $e->getMessage() . "<br>";
    }
}

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. users: add coins
    addColumnSafe($pdo, 'users', 'coins', 'INT NOT NULL DEFAULT 0');

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
    addColumnSafe($pdo, 'vouchers', 'code', "VARCHAR(50) DEFAULT NULL UNIQUE");
    addColumnSafe($pdo, 'vouchers', 'start_date', "DATETIME DEFAULT NULL");
    addColumnSafe($pdo, 'vouchers', 'end_date', "DATETIME DEFAULT NULL");
    addColumnSafe($pdo, 'vouchers', 'discount_type', "ENUM('fixed','percent') NOT NULL DEFAULT 'fixed'");
    addColumnSafe($pdo, 'vouchers', 'max_discount', "DECIMAL(10,2) DEFAULT NULL");
    addColumnSafe($pdo, 'vouchers', 'min_order_value', "DECIMAL(10,2) NOT NULL DEFAULT 0.00");
    addColumnSafe($pdo, 'vouchers', 'category_id', "INT(11) DEFAULT NULL");
    addColumnSafe($pdo, 'vouchers', 'usage_limit', "INT(11) DEFAULT NULL");
    addColumnSafe($pdo, 'vouchers', 'usage_per_user', "INT(11) DEFAULT 1");
    addColumnSafe($pdo, 'vouchers', 'used_count', "INT(11) NOT NULL DEFAULT 0");
    addColumnSafe($pdo, 'vouchers', 'is_combinable', "TINYINT(1) DEFAULT 0");

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

    // 7. appointments table upgrades
    modifyColumnSafe($pdo, 'appointments', 'customer_id', 'INT NULL');
    addColumnSafe($pdo, 'appointments', 'pet_info', 'VARCHAR(255) NULL');
    addColumnSafe($pdo, 'appointments', 'final_price', 'DECIMAL(10,2) NULL');
    addColumnSafe($pdo, 'appointments', 'customer_name', 'VARCHAR(100) NULL');
    addColumnSafe($pdo, 'appointments', 'customer_phone', 'VARCHAR(20) NULL');
    addColumnSafe($pdo, 'appointments', 'duration_value', 'INT DEFAULT 1');
    addColumnSafe($pdo, 'appointments', 'duration_unit', "ENUM('hour', 'day', 'month', 'none') DEFAULT 'none'");
    addColumnSafe($pdo, 'appointments', 'customer_notified', 'TINYINT(1) DEFAULT 0');
    addColumnSafe($pdo, 'appointments', 'selected_test', 'VARCHAR(255) NULL');

    // 8. orders table upgrades
    modifyColumnSafe($pdo, 'orders', 'status', "ENUM('pending', 'shipping', 'completed', 'cancelled') DEFAULT 'pending'");
    addColumnSafe($pdo, 'orders', 'receipt_image', 'VARCHAR(255) NULL');
    addColumnSafe($pdo, 'orders', 'paid_amount', 'DECIMAL(10,2) DEFAULT 0');
    addColumnSafe($pdo, 'orders', 'admin_note', 'TEXT NULL');
    addColumnSafe($pdo, 'orders', 'refund_bank', 'VARCHAR(100) NULL');
    addColumnSafe($pdo, 'orders', 'refund_account', 'VARCHAR(50) NULL');
    addColumnSafe($pdo, 'orders', 'refund_name', 'VARCHAR(100) NULL');
    addColumnSafe($pdo, 'orders', 'refund_status', "ENUM('none', 'pending', 'completed') DEFAULT 'none'");
    addColumnSafe($pdo, 'orders', 'customer_name', 'VARCHAR(255) DEFAULT NULL');
    addColumnSafe($pdo, 'orders', 'customer_phone', 'VARCHAR(20) DEFAULT NULL');
    addColumnSafe($pdo, 'orders', 'customer_notified', 'TINYINT(1) DEFAULT 0');

    // 9. products table upgrades
    addColumnSafe($pdo, 'products', 'expiry_date', 'DATE DEFAULT NULL');

    // 10. health_record_prescriptions table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `health_record_prescriptions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `health_record_id` INT NOT NULL,
        `product_id` INT NOT NULL,
        `quantity` INT NOT NULL,
        `instruction` VARCHAR(255) NULL,
        FOREIGN KEY (`health_record_id`) REFERENCES `health_records`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✅ Checked/Created 'health_record_prescriptions' table.<br>";

    // 11. reviews, contacts, email_logs
    $pdo->exec("CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        user_id INT NOT NULL,
        rating INT NOT NULL DEFAULT 5,
        comment TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'reviews' table.<br>";

    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('pending', 'replied') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'contacts' table.<br>";

    $pdo->exec("CREATE TABLE IF NOT EXISTS email_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        recipient_email VARCHAR(100) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        body TEXT,
        status ENUM('sent', 'failed') DEFAULT 'sent',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'email_logs' table.<br>";

    // 12. product_images table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `product_images` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `product_id` INT NOT NULL,
      `image` VARCHAR(255) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'product_images' table.<br>";

    // 13. appointment_reviews table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `appointment_reviews` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `appointment_id` INT NOT NULL UNIQUE,
      `user_id` INT NOT NULL,
      `service_id` INT NOT NULL,
      `doctor_id` INT DEFAULT NULL,
      `rating` INT NOT NULL DEFAULT 5,
      `comment` TEXT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    echo "✅ Checked/Created 'appointment_reviews' table.<br>";

    echo "<br><b style='color:green;'>🎉 Database upgrade completed successfully!</b>";
} catch (PDOException $e) {
    echo "<br><b style='color:red;'>❌ Database Error:</b> " . $e->getMessage() . "<br>";
}
?>
