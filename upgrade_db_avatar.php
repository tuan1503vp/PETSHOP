<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

try {
    // Thêm cột avatar
    $db->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) NULL AFTER password");
    $db->execute();
    echo "Đã thêm cột avatar vào bảng users thành công.<br>";
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "<br>";
}

echo "Hoàn tất nâng cấp CSDL!";
