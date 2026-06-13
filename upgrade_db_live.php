<?php
require_once __DIR__ . '/app/config/config.php';

echo "<h2>Đang cập nhật Cơ sở dữ liệu (Không làm mất dữ liệu hiện tại)...</h2>";
echo "<pre>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        // 1. Membership
        "CREATE TABLE IF NOT EXISTS `membership_benefits` (
          `membership_level` varchar(20) NOT NULL,
          `min_spent` decimal(10,2) NOT NULL,
          `discount_percent` decimal(5,2) NOT NULL,
          `benefit_text` text DEFAULT NULL,
          PRIMARY KEY (`membership_level`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        
        "CREATE TABLE IF NOT EXISTS `members` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `membership_level` varchar(20) NOT NULL DEFAULT 'Bạc',
          `total_spent` decimal(10,2) NOT NULL DEFAULT 0.00,
          `points` int(11) NOT NULL DEFAULT 0,
          `joined_date` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        // 2. Orders modifications
        "ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'shipping', 'completed', 'cancelled') DEFAULT 'pending'",
        "ALTER TABLE orders ADD COLUMN receipt_image VARCHAR(255) NULL",
        "ALTER TABLE orders ADD COLUMN paid_amount DECIMAL(10,2) DEFAULT 0",
        "ALTER TABLE orders ADD COLUMN admin_note TEXT NULL",
        "ALTER TABLE orders ADD COLUMN refund_bank VARCHAR(100) NULL",
        "ALTER TABLE orders ADD COLUMN refund_account VARCHAR(50) NULL",
        "ALTER TABLE orders ADD COLUMN refund_name VARCHAR(100) NULL",
        "ALTER TABLE orders ADD COLUMN refund_status ENUM('none', 'pending', 'completed') DEFAULT 'none'",

        // 3. Health & Prescriptions
        "CREATE TABLE IF NOT EXISTS `health_record_prescriptions` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `health_record_id` INT NOT NULL,
            `product_id` INT NOT NULL,
            `quantity` INT NOT NULL,
            `instruction` VARCHAR(255) NULL,
            FOREIGN KEY (`health_record_id`) REFERENCES `health_records`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        "ALTER TABLE `products` ADD COLUMN `expiry_date` DATE DEFAULT NULL",

        // 4. Advanced Features
        "CREATE TABLE IF NOT EXISTS reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            user_id INT NOT NULL,
            rating INT NOT NULL DEFAULT 5,
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            status ENUM('pending', 'replied') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        "CREATE TABLE IF NOT EXISTS email_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            recipient_email VARCHAR(100) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            body TEXT,
            status ENUM('sent', 'failed') DEFAULT 'sent',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
        
        // 5. Insert default membership data if table is empty
        "INSERT IGNORE INTO `membership_benefits` (`membership_level`, `min_spent`, `discount_percent`, `benefit_text`) VALUES
        ('Bạc', 0.00, 0.00, 'Không giảm giá, tích điểm cơ bản'),
        ('Kim Cương', 20000000.00, 15.00, 'Giảm 15% toàn bộ hóa đơn, Miễn phí tắm spa 1 lần/tháng'),
        ('Vàng', 5000000.00, 5.00, 'Giảm 5% toàn bộ hóa đơn'),
        ('Bạch Kim', 10000000.00, 10.00, 'Giảm 10% toàn bộ hóa đơn, Ưu tiên đặt lịch');"
    ];

    foreach ($queries as $sql) {
        try {
            $pdo->exec($sql);
            echo "Thành công: " . htmlspecialchars(substr($sql, 0, 80)) . "...\n";
        } catch (PDOException $e) {
            echo "Đã tồn tại (Bỏ qua): " . htmlspecialchars($e->getMessage()) . "\n";
        }
    }
    
    echo "\n<b style='color:green;'>XONG! Cập nhật Database thành công! Dữ liệu người dùng cũ vẫn được giữ nguyên.</b>";
    echo "\nBạn có thể xóa file upgrade_db_live.php này đi cho an toàn nhé.";

} catch (PDOException $e) {
    die("<b style='color:red;'>Lỗi kết nối Database: " . htmlspecialchars($e->getMessage()) . "</b>");
}
echo "</pre>";
