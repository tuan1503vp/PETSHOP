<?php
require_once '../app/config/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Thêm danh mục sản phẩm nếu chưa có
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories WHERE type = 'product'");
    if ($stmt->fetchColumn() == 0) {
        $sql = "INSERT INTO categories (name, type, description) VALUES 
                ('Thức ăn hạt', 'product', 'Các loại hạt dinh dưỡng cao cấp'), 
                ('Phụ kiện', 'product', 'Vòng cổ, dây dắt, bát ăn...'),
                ('Sữa tắm & Vệ sinh', 'product', 'Dưỡng lông, trị rận, cát vệ sinh')";
        $pdo->exec($sql);
        echo "SUCCESS: Đã tạo danh mục sản phẩm mẫu.<br>";
    }

    // 2. Lấy danh mục vừa tạo để gán ID cho sản phẩm
    $stmt = $pdo->query("SELECT id FROM categories WHERE type = 'product'");
    $catIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($catIds) > 0) {
        // 3. Thêm sản phẩm mẫu nếu chưa có
        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
        if ($stmt->fetchColumn() == 0) {
            $cat1 = $catIds[0]; // Thức ăn
            $cat2 = $catIds[1] ?? $cat1; // Phụ kiện
            $cat3 = $catIds[2] ?? $cat1; // Vệ sinh

            $sql = "INSERT INTO products (category_id, name, description, price, stock_quantity, image) VALUES 
                    ($cat1, 'Hạt Royal Canin cho Mèo', 'Hạt dinh dưỡng cân bằng cho mèo trưởng thành trên 12 tháng tuổi.', 450000, 50, 'royal_canin.png'),
                    ($cat1, 'Hạt Pedigree cho Chó', 'Vị bò nướng thơm ngon, đầy đủ vitamin và khoáng chất.', 120000, 100, 'pedigree.png'),
                    ($cat2, 'Bát ăn Inox cao cấp', 'Chống han gỉ, dễ dàng vệ sinh, có đế cao su chống trượt.', 45000, 30, 'bowl.png'),
                    ($cat2, 'Vòng cổ chuông màu sắc', 'Làm từ vải dù bền chắc, có chuông kêu vui tai.', 25000, 200, 'collar.png'),
                    ($cat3, 'Sữa tắm SOS dưỡng lông', 'Giúp lông mềm mượt, khử mùi hôi hiệu quả.', 150000, 20, 'sos.png'),
                    ($cat3, 'Cát vệ sinh đậu nành', 'Thấm hút tốt, khóa mùi cực nhanh, có thể xả bồn cầu.', 135000, 40, 'sand.png')";
            $pdo->exec($sql);
            echo "SUCCESS: Đã chèn 6 sản phẩm mẫu.<br>";
        } else {
            echo "INFO: Đã có sản phẩm trong hệ thống.<br>";
        }
    }

    echo "DONE: Vui lòng quay lại trang Cửa hàng để xem kết quả.";

} catch(PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
