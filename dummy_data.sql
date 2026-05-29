USE petshop_db;

-- Chèn Danh mục
INSERT INTO `categories` (`name`, `type`, `description`) VALUES
('Thức ăn cho Chó', 'product', 'Các loại hạt, pate, thịt hộp cho chó'),
('Thức ăn cho Mèo', 'product', 'Hạt, súp thưởng, pate cho mèo'),
('Phụ kiện', 'product', 'Vòng cổ, dây dắt, balo, khay vệ sinh'),
('Thú cưng', 'product', 'Chó, mèo cảnh các loại');

-- Chèn Sản phẩm
INSERT INTO `products` (`category_id`, `name`, `description`, `price`, `stock_quantity`, `image`) VALUES
(1, 'Hạt Royal Canin Poodle Adult 1.5kg', 'Thức ăn hạt khô dành riêng cho giống chó Poodle trưởng thành trên 10 tháng tuổi.', 350000, 50, '2.jpg'),
(2, 'Pate Whiskas Vị Cá Ngừ 85g', 'Pate dinh dưỡng cho mèo, thơm ngon, dễ tiêu hóa.', 15000, 200, '1.jpg'),
(3, 'Balo Vận Chuyển Thú Cưng Phi Hành Gia', 'Balo trong suốt giúp thú cưng ngắm cảnh, thoáng khí, an toàn.', 280000, 30, '4.jpg'),
(1, 'Xương Gặm Sạch Răng Pedigree Dentastix', 'Xương gặm giúp làm sạch mảng bám, giảm hôi miệng cho chó.', 45000, 100, '2.jpg'),
(4, 'Mèo Anh Lông Ngắn (Bicolor)', 'Mèo Anh lông ngắn màu Bicolor cực kỳ đáng yêu, đã tiêm phòng mũi 1.', 4500000, 2, '3.jpg');
