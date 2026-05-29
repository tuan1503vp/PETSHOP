<?php
require_once '../app/config/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra xem đã có danh mục dịch vụ chưa
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories WHERE type = 'service'");
    if ($stmt->fetchColumn() == 0) {
        $sql = "INSERT INTO categories (name, type, description) VALUES 
                ('Khám chữa bệnh', 'service', 'Khám tổng quát, tiêm phòng, cấp cứu'), 
                ('Chăm sóc - Grooming', 'service', 'Tắm rửa, cắt tỉa lông, spa thú cưng'),
                ('Khách sạn Thú cưng (Pet Hotel)', 'service', 'Lưu trú, chăm sóc qua đêm')";
        $pdo->exec($sql);
        echo "SUCCESS: Đã chèn 3 danh mục dịch vụ mặc định.";
    } else {
        echo "INFO: Danh mục dịch vụ đã tồn tại trong CSDL.";
    }
} catch(PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
