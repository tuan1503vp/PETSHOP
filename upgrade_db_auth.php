<?php
// Script to upgrade database schema for Auth (Email Verification)
require_once __DIR__ . '/app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>Đang cập nhật Cơ sở dữ liệu: Bảng users...</h2><pre>";

    $queries = [
        "ALTER TABLE users ADD COLUMN is_verified TINYINT(1) DEFAULT 0",
        "ALTER TABLE users ADD COLUMN otp_code VARCHAR(10) NULL",
        "ALTER TABLE users ADD COLUMN otp_expires_at DATETIME NULL"
    ];

    foreach ($queries as $query) {
        try {
            $pdo->exec($query);
            echo "Thành công: Đã chạy " . htmlspecialchars($query) . "\n";
        } catch (PDOException $e) {
            // Ignore duplicate column errors
            echo "Bỏ qua (có thể cột đã tồn tại): " . htmlspecialchars($e->getMessage()) . "\n";
        }
    }
    
    // Update existing users to be verified so they don't get locked out
    $pdo->exec("UPDATE users SET is_verified = 1 WHERE is_verified = 0");
    echo "Thành công: Đã cập nhật các tài khoản hiện tại thành 'Đã xác thực'.\n";

    echo "\n<b style='color:green;'>XONG! Cơ sở dữ liệu đã được cập nhật thành công!</b></pre>";

} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
