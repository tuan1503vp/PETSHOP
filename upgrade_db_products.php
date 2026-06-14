<?php
require_once __DIR__ . '/app/config/config.php';

echo "<h2>Đang cập nhật Cơ sở dữ liệu: Thêm Sản phẩm và Danh mục mới...</h2>";
echo "<pre>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Thêm các cột còn thiếu vào bảng products trước (nếu chưa có)
    $alterQueries = [
        "ALTER TABLE products ADD COLUMN expiry_date DATE NULL",
        "ALTER TABLE products ADD COLUMN batch_number VARCHAR(50) NULL"
    ];
    foreach ($alterQueries as $q) {
        try {
            $pdo->exec($q);
            echo "Thành công: Đã thêm cột mới vào bảng products.\n";
        } catch (PDOException $e) {
            // Cột đã tồn tại, bỏ qua
        }
    }

    // Read the dump file
    $sqlFile = __DIR__ . '/dump_products.sql';
    if (!file_exists($sqlFile)) {
        die("Lỗi: Không tìm thấy file dump_products.sql");
    }

    $sqlContent = file_get_contents($sqlFile);
    
    // Split by semicolons
    $queries = explode(";\n", $sqlContent);
    $successCount = 0;

    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) continue;
        
        // Only care about INSERT INTO statements
        if (strpos($query, 'INSERT INTO') === 0) {
            // Change INSERT INTO to INSERT IGNORE INTO to avoid duplicate primary key errors
            $query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query);
            
            try {
                $pdo->exec($query);
                $successCount++;
                echo "Thành công: " . htmlspecialchars(substr($query, 0, 100)) . "...\n";
            } catch (PDOException $e) {
                echo "Lỗi khi chạy query: " . htmlspecialchars($e->getMessage()) . "\n";
            }
        }
    }

    echo "\n<b style='color:green;'>XONG! Cập nhật " . $successCount . " lệnh thêm dữ liệu thành công!</b>";
    echo "\nCác danh mục và sản phẩm mới (Thuốc, Vắc xin...) đã được đồng bộ lên máy chủ thật.";

} catch (PDOException $e) {
    die("<b style='color:red;'>Lỗi kết nối Database: " . htmlspecialchars($e->getMessage()) . "</b>");
}
echo "</pre>";
