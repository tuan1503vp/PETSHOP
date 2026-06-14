<?php
require_once __DIR__ . '/app/config/config.php';

echo "<h2>Đang vá lỗi Database: Thêm các cột còn thiếu cho bảng Pets...</h2>";
echo "<pre>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        "ALTER TABLE `pets` ADD COLUMN `pet_code` VARCHAR(50) UNIQUE AFTER `id`",
        "ALTER TABLE `pets` ADD COLUMN `breed` VARCHAR(100) AFTER `species`",
        "ALTER TABLE `pets` ADD COLUMN `age` INT AFTER `breed`",
        "ALTER TABLE `pets` ADD COLUMN `gender` ENUM('male', 'female', 'unknown') DEFAULT 'unknown' AFTER `age`",
        "ALTER TABLE `pets` ADD COLUMN `color` VARCHAR(100) AFTER `gender`",
        "ALTER TABLE `pets` ADD COLUMN `weight` DECIMAL(5,2) AFTER `color`",
        "ALTER TABLE `pets` ADD COLUMN `image` VARCHAR(255) AFTER `weight`"
    ];

    foreach ($queries as $sql) {
        try {
            $pdo->exec($sql);
            echo "Thành công: " . htmlspecialchars($sql) . "\n";
        } catch (PDOException $e) {
            echo "Bỏ qua (có thể cột đã tồn tại): " . htmlspecialchars($e->getMessage()) . "\n";
        }
    }

    echo "\n<b style='color:green;'>XONG! Vá lỗi Database thành công!</b>";
    echo "\nBạn có thể tải lại trang Thêm thú cưng để sử dụng bình thường.";

} catch (PDOException $e) {
    die("<b style='color:red;'>Lỗi kết nối Database: " . htmlspecialchars($e->getMessage()) . "</b>");
}
echo "</pre>";
