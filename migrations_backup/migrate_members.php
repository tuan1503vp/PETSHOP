<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();

try {
    // 1. Tạo bảng members
    $db->query("CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        phone VARCHAR(20),
        address TEXT,
        membership_level VARCHAR(50) DEFAULT 'Member',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    $db->execute();
    echo "Table 'members' created.\n";

    // 2. Chuyển dữ liệu cũ (nếu có)
    $db->query("INSERT INTO members (user_id, phone, address)
                SELECT id, phone, address FROM users 
                WHERE (phone IS NOT NULL OR address IS NOT NULL)
                AND id NOT IN (SELECT user_id FROM members)");
    $db->execute();
    echo "Existing data migrated to 'members'.\n";

    // 3. Xóa cột phone và address ở bảng users
    // Kiểm tra xem cột có tồn tại không trước khi xóa
    $db->query("SHOW COLUMNS FROM users LIKE 'phone'");
    if ($db->single()) {
        $db->query("ALTER TABLE users DROP COLUMN phone");
        $db->execute();
        echo "Column 'phone' dropped from 'users'.\n";
    }

    $db->query("SHOW COLUMNS FROM users LIKE 'address'");
    if ($db->single()) {
        $db->query("ALTER TABLE users DROP COLUMN address");
        $db->execute();
        echo "Column 'address' dropped from 'users'.\n";
    }

    echo "Migration successful!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
