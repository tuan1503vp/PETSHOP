CREATE DATABASE IF NOT EXISTS petshop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE petshop_db;

-- Bảng Người dùng (Quản trị viên, Nhân viên/Bác sĩ, Khách hàng)
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20),
  `address` TEXT,
  `role` ENUM('admin', 'staff', 'doctor', 'customer') DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng Thú cưng (Của khách hàng)
CREATE TABLE `pets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pet_code` VARCHAR(50) UNIQUE,
  `customer_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `species` VARCHAR(50) NOT NULL, -- Chó, Mèo, Chim...
  `breed` VARCHAR(100), -- Giống (VD: Corgi, Poodle...)
  `age` INT, -- Tuổi (Số tháng tuổi)
  `gender` ENUM('male', 'female', 'unknown') DEFAULT 'unknown',
  `color` VARCHAR(100), -- Màu sắc
  `weight` DECIMAL(5,2), -- Cân nặng (kg)
  `image` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- Bảng Danh mục sản phẩm/dịch vụ
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `type` ENUM('product', 'service') NOT NULL,
  `description` TEXT
);

-- Bảng Sản phẩm (Hàng hóa, thức ăn, phụ kiện)
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_quantity` INT DEFAULT 0,
  `image` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
);

-- Bảng Dịch vụ
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `duration_minutes` INT DEFAULT 30, -- Thời gian ước tính (phút)
  `image` VARCHAR(255),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
);

-- Bảng Đơn hàng (POS & Online)
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT, -- Có thể NULL nếu khách vãng lai mua tại POS
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
  `payment_method` ENUM('cash', 'card', 'transfer', 'cod', 'vnpay') DEFAULT 'cash',
  `order_type` ENUM('online', 'pos') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
);

-- Bảng Chi tiết Đơn hàng
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

-- Bảng Ảnh phụ của Sản phẩm (Nhiều ảnh cho 1 sản phẩm)
CREATE TABLE `product_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
);

-- Bảng Lịch hẹn Dịch vụ
CREATE TABLE `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `pet_id` INT, -- Đặt cho thú cưng nào
  `pet_info` VARCHAR(255), -- Thông tin mô tả thú cưng tự nhập
  `service_id` INT NOT NULL,
  `doctor_id` INT, -- Bác sĩ/Nhân viên phụ trách
  `final_price` DECIMAL(10,2), -- Số tiền chốt sau khi hoàn thành
  `appointment_date` DATE NOT NULL,
  `appointment_time` TIME NOT NULL,
  `status` ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
);

-- Bảng Hồ sơ Sức khỏe (Y bạ)
CREATE TABLE `health_records` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pet_id` INT NOT NULL,
  `appointment_id` INT, -- Có thể liên kết với một lịch khám cụ thể
  `doctor_id` INT NOT NULL,
  `diagnosis` TEXT NOT NULL, -- Chẩn đoán
  `treatment` TEXT, -- Điều trị/Kê đơn
  `notes` TEXT,
  `visit_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- Bảng Nhật ký sức khỏe (Chủ nuôi theo dõi hàng ngày)
CREATE TABLE `pet_health_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `pet_id` INT NOT NULL,
  `log_date` DATE NOT NULL,
  `weight` DECIMAL(5,2) NULL,
  `temperature` DECIMAL(4,1) NULL,
  `status` VARCHAR(100) NULL, -- Rất tốt, Bình thường, Mệt mỏi, Ốm yếu...
  `symptoms` TEXT NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE CASCADE
);

-- Bảng Lịch sử Phân tích AI
CREATE TABLE `ai_analyses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT,
  `pet_id` INT,
  `input_text` TEXT, -- Triệu chứng nhập vào
  `input_image` VARCHAR(255), -- Đường dẫn ảnh nếu có
  `ai_response` TEXT, -- Phản hồi từ AI
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`pet_id`) REFERENCES `pets`(`id`) ON DELETE CASCADE
);

-- Chèn một số dữ liệu mẫu ban đầu
INSERT INTO `users` (`fullname`, `email`, `password`, `role`) VALUES
('Admin', 'admin@petshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'), -- password: password
('Bác sĩ Tuấn', 'doctor@petshop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor');
