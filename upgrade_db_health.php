<?php
require_once __DIR__ . '/app/config/config.php';

echo "<h2>Đang cập nhật Cơ sở dữ liệu Sổ Khám Bệnh...</h2>";
echo "<pre>";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        // 1. Thêm pet_code vào bảng pets
        "ALTER TABLE `pets` ADD COLUMN `pet_code` VARCHAR(50) UNIQUE AFTER `id`",

        // 2. Tạo bảng health_records
        "CREATE TABLE IF NOT EXISTS `health_records` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `pet_id` INT NOT NULL,
            `appointment_id` INT, 
            `doctor_id` INT NOT NULL,
            `diagnosis` TEXT NOT NULL, 
            `treatment` TEXT, 
            `notes` TEXT,
            `visit_date` DATE NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE SET NULL,
            FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        // 3. Tạo bảng pet_health_logs
        "CREATE TABLE IF NOT EXISTS `pet_health_logs` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `pet_id` INT NOT NULL,
            `log_date` DATE NOT NULL,
            `weight` DECIMAL(5,2) NULL,
            `temperature` DECIMAL(4,1) NULL,
            `status` VARCHAR(100) NULL, 
            `symptoms` TEXT NULL,
            `notes` TEXT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        // 4. Tạo bảng pet_vaccinations
        "CREATE TABLE IF NOT EXISTS `pet_vaccinations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `pet_id` INT NOT NULL,
            `vaccine_name` VARCHAR(100) NOT NULL,
            `disease_prevented` VARCHAR(255),
            `vaccinated_date` DATE NOT NULL,
            `next_due_date` DATE,
            `administered_by` VARCHAR(100),
            `notes` TEXT,
            `image` VARCHAR(255),
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",

        // 5. Tạo bảng pet_milestones
        "CREATE TABLE IF NOT EXISTS `pet_milestones` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `pet_id` INT NOT NULL,
            `title` VARCHAR(150) NOT NULL,
            `description` TEXT,
            `milestone_date` DATE NOT NULL,
            `image` VARCHAR(255),
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];

    foreach ($queries as $sql) {
        try {
            $pdo->exec($sql);
            echo "Thành công: " . htmlspecialchars(substr($sql, 0, 80)) . "...\n";
        } catch (PDOException $e) {
            echo "Đã tồn tại (Bỏ qua): " . htmlspecialchars($e->getMessage()) . "\n";
        }
    }
    
    // Tạo pet_code cho các pet cũ chưa có mã
    try {
        $stmt = $pdo->query("SELECT id FROM pets WHERE pet_code IS NULL");
        $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($pets) > 0) {
            $updateStmt = $pdo->prepare("UPDATE pets SET pet_code = :code WHERE id = :id");
            foreach ($pets as $pet) {
                $code = 'PET-' . strtoupper(substr(md5(uniqid()), 0, 6));
                $updateStmt->execute([':code' => $code, ':id' => $pet['id']]);
            }
            echo "Đã tạo mã pet_code cho " . count($pets) . " thú cưng cũ!\n";
        }
    } catch (Exception $e) {
        echo "Không thể tạo mã tự động: " . $e->getMessage() . "\n";
    }

    echo "\n<b style='color:green;'>XONG! Cập nhật Database thành công!</b>";
    echo "\nBạn có thể xóa file upgrade_db_health.php này đi cho an toàn nhé.";

} catch (PDOException $e) {
    die("<b style='color:red;'>Lỗi kết nối Database: " . htmlspecialchars($e->getMessage()) . "</b>");
}
echo "</pre>";
