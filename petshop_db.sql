-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 02, 2026 lúc 08:11 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `petshop_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `username`, `role`, `action`, `details`, `created_at`) VALUES
(1, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00036 cho Khách lẻ.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 4,998,000đ.\nChi tiết mục mua: [Sản phẩm] Mèo Ba Tư (Persian Cat) (x1)', '2026-05-18 12:36:01'),
(2, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00037 cho Khách lẻ.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 130,000đ.\nChi tiết mục mua: [Sản phẩm] Royal Canin Kitten (x1)', '2026-05-18 12:38:37'),
(3, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00038 cho Khách lẻ.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 130,000đ.\nChi tiết mục mua: [Sản phẩm] Royal Canin Kitten (x1)', '2026-05-18 12:38:45'),
(4, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00039 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 93,500đ.\nChi tiết mục mua: [Sản phẩm] Whiskas Pate Cho Mèo Con (x1)', '2026-05-18 12:38:59'),
(5, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00040 cho Khách lẻ.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 110,000đ.\nChi tiết mục mua: [Sản phẩm] Whiskas Pate Cho Mèo Con (x1)', '2026-05-18 12:39:31'),
(6, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00041 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 505,750đ.\nChi tiết mục mua: [Sản phẩm] SmartHeart Adult Beef Flavor 20kg (x1)', '2026-05-18 12:40:16'),
(7, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-21 07:39:53'),
(8, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-21 07:54:57'),
(9, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-21 08:57:11'),
(10, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-05-21 09:04:42'),
(11, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-21 09:04:52'),
(12, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-29 08:12:13'),
(13, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-29 08:24:26'),
(14, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-05-29 08:25:42'),
(15, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-29 08:26:07'),
(16, 1, NULL, 'admin', 'Xử lý liên hệ', 'Đã đánh dấu liên hệ ID 1 là: replied', '2026-05-29 08:28:17'),
(17, 1, NULL, 'admin', 'Xử lý liên hệ', 'Đã đánh dấu liên hệ ID 2 là: replied', '2026-05-29 08:30:20'),
(18, 1, NULL, 'admin', 'Xử lý liên hệ', 'Đã đánh dấu liên hệ ID 4 là: replied', '2026-05-29 08:33:33'),
(19, 1, NULL, 'admin', 'Xử lý liên hệ', 'Đã đánh dấu liên hệ ID 5 là: replied', '2026-05-29 08:36:42'),
(20, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-29 13:20:24'),
(21, 1, 'Admin', 'admin', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-05-29 13:27:28'),
(22, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-29 13:27:45'),
(23, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-05-29 13:30:40'),
(24, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-29 13:30:54'),
(25, 1, 'Admin', 'admin', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-05-29 13:34:47'),
(26, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-05-29 13:34:53'),
(27, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-02 04:55:11'),
(28, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-02 05:00:22'),
(29, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-02 05:18:07'),
(30, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-02 05:27:00'),
(31, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-02 05:37:32'),
(32, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-02 06:30:35'),
(33, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 04:10:16'),
(34, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 05:16:04'),
(35, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 07:05:08'),
(36, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 07:05:16'),
(37, 9, NULL, 'manager', 'Xử lý liên hệ', 'Đã đánh dấu liên hệ ID 6 là: replied', '2026-06-10 07:06:55'),
(38, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 07:11:42'),
(39, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 12:25:58'),
(40, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:24:06'),
(41, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 14:41:03'),
(42, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:41:11'),
(43, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 14:42:29'),
(44, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:42:36'),
(45, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 14:45:40'),
(46, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:45:45'),
(47, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 14:49:07'),
(48, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:49:15'),
(49, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 14:50:36'),
(50, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:50:42'),
(51, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 14:53:54'),
(52, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:54:01'),
(53, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 14:54:57'),
(54, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 14:55:02'),
(55, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 15:10:50'),
(56, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 15:10:56'),
(57, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 15:12:10'),
(58, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 15:12:20'),
(59, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 15:31:26'),
(60, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 15:32:28'),
(61, 12, 'Nguyễn Văn A', 'customer', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-10 15:33:41'),
(62, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-10 15:33:49'),
(63, 13, 'NGUYEN VAN A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-11 03:20:44'),
(64, 5, 'Heo men', 'doctor', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-11 03:24:04'),
(65, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-11 03:24:39'),
(66, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-11 03:24:56'),
(67, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-11 03:27:50'),
(68, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #43', '2026-06-11 03:28:11'),
(69, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #44', '2026-06-11 03:29:32'),
(70, 1, NULL, 'admin', 'Phân công bác sĩ', 'Đã xếp lịch hẹn #45 cho bác sĩ/nhân viên ID: 5', '2026-06-11 03:30:52'),
(71, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #43: 100000đ', '2026-06-11 03:31:09'),
(72, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #44: 100000đ', '2026-06-11 03:31:16'),
(73, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #45: 100000đ', '2026-06-11 03:33:04'),
(74, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00062 cho NGUYEN VAN A.\nHình thức: Tiền mặt.\nTổng thanh toán: 200,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: NGUYEN VAN A) (x1), [Dịch vụ] Dịch vụ: Tiêm phòng & xét nghiệm (KH: NGUYEN VAN A) (x1)', '2026-06-11 03:33:54'),
(75, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-12 08:29:19'),
(76, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-12 08:29:50'),
(77, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00063 cho NGUYEN VAN A.\nHình thức: Tiền mặt.\nTổng thanh toán: 100,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: NGUYEN VAN A) (x1)', '2026-06-12 08:30:01'),
(78, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-12 08:30:46'),
(79, 5, 'Heo men', 'doctor', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-12 08:51:37'),
(80, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #46', '2026-06-12 08:51:50'),
(81, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #47', '2026-06-12 08:52:42'),
(82, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #46: 100000đ', '2026-06-12 08:53:04'),
(83, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00064 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 85,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-12 08:53:39'),
(84, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #47: 200000đ', '2026-06-12 08:55:53'),
(85, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00065 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 170,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-12 08:56:09'),
(86, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #48', '2026-06-12 08:56:23'),
(87, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #48: 100000đ', '2026-06-12 08:56:32'),
(88, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00066 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 85,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Điều trị bệnh (KH: Nguyễn Văn A) (x1)', '2026-06-12 08:56:44'),
(89, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #49', '2026-06-12 08:57:52'),
(90, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #49: 100000đ', '2026-06-12 09:00:13'),
(91, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00067 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 85,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-12 09:00:23'),
(92, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #50', '2026-06-12 09:01:08'),
(93, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #50: 100000đ', '2026-06-12 09:01:23'),
(94, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00068 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 85,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Điều trị bệnh (KH: Nguyễn Văn A) (x1)', '2026-06-12 09:01:32'),
(95, 9, NULL, 'manager', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 24: Mèo Anh lông ngắn (British Shorthair)', '2026-06-12 09:09:26'),
(96, 9, NULL, 'manager', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 23: Mèo Tuxedo', '2026-06-12 09:13:55'),
(97, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-12 09:32:38'),
(98, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #51', '2026-06-12 09:36:54'),
(99, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #51: 100000đ', '2026-06-12 09:37:01'),
(100, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00069 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 85,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-12 09:37:19'),
(101, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #52', '2026-06-12 09:41:30'),
(102, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #52: 0đ', '2026-06-12 09:41:35'),
(103, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00070 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 0đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-12 09:43:37'),
(104, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #53', '2026-06-12 09:44:22'),
(105, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #53: 1000000đ', '2026-06-12 09:44:29'),
(106, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00071 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 850,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-12 09:44:36'),
(107, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #54', '2026-06-12 09:46:58'),
(108, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ đã báo giá ca khám #54: 100000đ', '2026-06-12 09:47:06'),
(109, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00072 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 85,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Điều trị bệnh (KH: Nguyễn Văn A) (x1)', '2026-06-12 09:47:13'),
(110, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #55', '2026-06-12 09:52:54'),
(111, 5, NULL, 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #55: 100000đ', '2026-06-12 09:53:52'),
(112, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00073 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 85,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-12 09:54:25'),
(113, 1, NULL, 'admin', 'Thêm sản phẩm', 'Đã thêm sản phẩm mới: Frontline Plus (Merial) (Giá: 150000đ)', '2026-06-12 09:58:46'),
(114, 1, NULL, 'admin', 'Thêm sản phẩm', 'Đã thêm sản phẩm mới: Advocate (Bayer) (Giá: 200000đ)', '2026-06-12 10:00:58'),
(115, 1, NULL, 'admin', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 26: Advocate (Bayer)', '2026-06-12 10:01:34'),
(116, 5, NULL, 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #56', '2026-06-12 10:11:28'),
(117, 5, 'heomen', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #56: 0.00đ', '2026-06-12 10:22:41'),
(118, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00074 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 0đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Điều trị bệnh (KH: Nguyễn Văn A) (x1)', '2026-06-12 10:23:37'),
(119, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #57', '2026-06-12 10:24:02'),
(120, 5, 'Heo men', 'doctor', 'Hủy ca nhận', 'Bác sĩ/NV đã trả lại ca #57 về trạng thái chờ nhận', '2026-06-12 10:37:06'),
(121, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #57', '2026-06-12 10:37:10'),
(122, 5, 'Heo men', 'doctor', 'Hủy ca nhận', 'Bác sĩ/NV đã trả lại ca #57 về trạng thái chờ nhận', '2026-06-12 10:37:14'),
(123, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #58', '2026-06-12 10:37:45'),
(124, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #58: 100000đ', '2026-06-12 10:48:48'),
(125, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00075 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 255,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1), [Sản phẩm] Advocate (Bayer) (x1)', '2026-06-12 10:49:09'),
(126, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #57', '2026-06-12 10:49:50'),
(127, 5, 'Heo men', 'doctor', 'Hủy ca nhận', 'Bác sĩ/NV đã trả lại ca #57 về trạng thái chờ nhận', '2026-06-12 10:50:22'),
(128, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #57', '2026-06-12 10:54:53'),
(129, 5, 'Heo men', 'doctor', 'Hủy ca nhận', 'Bác sĩ/NV đã trả lại ca #57 về trạng thái chờ nhận', '2026-06-12 10:56:40'),
(130, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #57', '2026-06-12 10:56:44'),
(131, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #57: 9998đ', '2026-06-12 10:57:44'),
(132, 5, 'Heo men', 'doctor', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-13 04:35:00'),
(133, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-13 04:35:24'),
(134, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00076 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 8,498đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Điều trị bệnh (KH: Nguyễn Văn A) (x1)', '2026-06-13 04:35:40'),
(135, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-13 04:36:00'),
(136, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #59', '2026-06-13 04:39:02'),
(137, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #59: 0100000đ', '2026-06-13 04:39:21'),
(138, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00077 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 255,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1), [Sản phẩm] Advocate (Bayer) (x1)', '2026-06-13 04:39:47'),
(139, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #60', '2026-06-13 04:50:47'),
(140, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #60: 100000đ', '2026-06-13 04:51:12'),
(141, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00078 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 255,000đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1), [Sản phẩm] Advocate (Bayer) (x1)', '2026-06-13 04:51:57'),
(142, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #61', '2026-06-13 05:47:24'),
(143, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #61: 0đ', '2026-06-13 05:49:54'),
(144, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00079 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 0đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Tiêm phòng & xét nghiệm (KH: Nguyễn Văn A) (x1)', '2026-06-13 05:50:00'),
(145, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #62', '2026-06-13 05:53:21'),
(146, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #62: 0đ', '2026-06-13 05:54:05'),
(147, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00080 cho Nguyễn Văn A.\nHình thức: Tiền mặt.\nTổng thanh toán: 0đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Tiêm phòng & xét nghiệm (KH: Nguyễn Văn A) (x1)', '2026-06-13 05:54:15'),
(148, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #63', '2026-06-13 05:55:42'),
(149, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #63: 01000đ', '2026-06-13 05:56:00'),
(150, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00081 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 850đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Tiêm phòng & xét nghiệm (KH: Nguyễn Văn A) (x1)', '2026-06-13 05:56:07'),
(151, 5, 'Heo men', 'doctor', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-13 15:05:15'),
(152, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-13 15:05:53'),
(153, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-13 15:06:15'),
(154, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-13 15:07:28'),
(155, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #64', '2026-06-13 15:10:32'),
(156, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #64: 99997đ', '2026-06-13 15:12:02'),
(157, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00082 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 84,997đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Tiêm phòng & xét nghiệm (KH: Nguyễn Văn A) (x1)', '2026-06-13 15:12:19'),
(158, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #65', '2026-06-13 15:20:54'),
(159, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #65: 99997đ', '2026-06-13 15:21:02'),
(160, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00083 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 84,997đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-13 15:21:29'),
(161, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #66', '2026-06-13 15:36:59'),
(162, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #66: 99998đ', '2026-06-13 15:37:06'),
(163, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00000 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 84,998đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Khám bệnh cho thú cưng (KH: Nguyễn Văn A) (x1)', '2026-06-13 15:37:23'),
(164, 9, 'Nguyễn Minh Tuấn', 'manager', 'Thêm danh mục', 'Đã thêm danh mục mới qua AJAX: Vắc - Xin (Loại: product)', '2026-06-13 15:44:20'),
(165, 5, 'Heo men', 'doctor', 'Nhận ca khám', 'Bác sĩ đã tự nhận ca khám/lịch hẹn #67', '2026-06-13 16:06:22'),
(166, 5, 'Heo men', 'doctor', 'Báo giá ca khám', 'Bác sĩ/Nhân viên đã báo giá ca khám #67: 99999đ', '2026-06-13 16:06:50'),
(167, 7, 'Chu Thúy Huệ', 'cashier', 'Thanh toán POS', 'Thanh toán đơn POS #00000 cho Nguyễn Văn A.\nHình thức: Chuyển khoản VietQR.\nTổng thanh toán: 84,999đ.\nChi tiết mục mua: [Dịch vụ] Dịch vụ: Tiêm phòng & xét nghiệm (KH: Nguyễn Văn A) (x1)', '2026-06-13 16:06:58'),
(168, 9, 'Nguyễn Minh Tuấn', 'manager', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 27: Nobivac DHPPi', '2026-06-13 16:18:12'),
(169, 9, 'Nguyễn Minh Tuấn', 'manager', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 27: Nobivac DHPPi', '2026-06-13 16:19:49'),
(170, 9, 'Nguyễn Minh Tuấn', 'manager', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 28: Nobivac Rabies', '2026-06-13 16:22:26'),
(171, 9, 'Nguyễn Minh Tuấn', 'manager', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 28: Nobivac Rabies', '2026-06-13 16:22:49'),
(172, 9, 'Nguyễn Minh Tuấn', 'manager', 'Sửa sản phẩm', 'Đã sửa sản phẩm ID 29: Vanguard Plus', '2026-06-13 16:24:33'),
(173, 9, 'Nguyễn Minh Tuấn', 'manager', 'Thêm sản phẩm', 'Đã thêm sản phẩm mới: Vanguard HTLP 5/CV-L (V8) (Giá: 300000đ)', '2026-06-13 16:27:25'),
(174, 9, 'Nguyễn Minh Tuấn', 'manager', 'Thêm sản phẩm', 'Đã thêm sản phẩm mới: Vanguard Plus 5 L4 CV (V10) (Giá: 350000đ)', '2026-06-13 16:29:14'),
(175, 9, 'Nguyễn Minh Tuấn', 'manager', 'Thêm sản phẩm', 'Đã thêm sản phẩm mới: Nobivac KC (Giá: 250000đ)', '2026-06-13 16:30:52'),
(176, 9, 'Nguyễn Minh Tuấn', 'manager', 'Thêm sản phẩm', 'Đã thêm sản phẩm mới: Purevax RCP (Giá: 300000đ)', '2026-06-13 16:32:10'),
(177, 9, 'Nguyễn Minh Tuấn', 'manager', 'Thêm sản phẩm', 'Đã thêm sản phẩm mới: Purevax RCP-FeLV (Giá: 450000đ)', '2026-06-13 16:33:30'),
(178, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-14 13:16:04'),
(179, 1, 'Admin', 'admin', 'Đăng xuất', 'Đăng xuất khỏi hệ thống', '2026-06-14 13:35:07'),
(180, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-14 13:35:18'),
(181, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-14 14:41:11'),
(182, 1, 'Admin', 'admin', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-14 14:46:51'),
(183, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-14 15:44:02'),
(184, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-14 16:00:46'),
(185, 12, 'Nguyễn Văn A', 'customer', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-15 05:20:03'),
(186, 5, 'Heo men', 'doctor', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-15 05:21:26'),
(187, 7, 'Chu Thúy Huệ', 'cashier', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-15 05:21:41'),
(188, 9, 'Nguyễn Minh Tuấn', 'manager', 'Đăng nhập', 'Đăng nhập vào hệ thống thành công', '2026-06-15 05:22:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ai_analyses`
--

CREATE TABLE `ai_analyses` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `input_text` text DEFAULT NULL,
  `input_image` varchar(255) DEFAULT NULL,
  `ai_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ai_analyses`
--

INSERT INTO `ai_analyses` (`id`, `customer_id`, `pet_id`, `input_text`, `input_image`, `ai_response`, `created_at`) VALUES
(1, 12, NULL, 'Chó Poodle 2 tuổi, bỏ ăn 2 ngày nay, bị nôn mửa dịch vàng, mệt mỏi.', NULL, '**1. Phân tích triệu chứng:**  \n- **Bỏ ăn 2 ngày:** Hàng ngày, chó cần 2–3 bữa ăn. Bỏ ăn quá 24 giờ là dấu hiệu cảnh báo.  \n- **Nôn mửa dịch vàng:** Dịch vàng (cần mật) thường xuất phát từ vùng dạ dày hoặc ống mật.  \n- **Mệt mỏi:** Thiếu năng lượng do không ăn uống, dẫn đến mất sinh khí.  \n\n**2. Nguyên nhân có thể:**  \n- **Bệnh đường tiêu hóa:** Đổ nôn, viêm tụy, viêm phổi hoặc viêm tràng trộm.  \n- **Bệnh gan/tụy:** Tăng bilirubin gây dịch vàng, kèm theo nôn, mệt mỏi.  \n- **Bệnh hô hấp:** Phổi tắc nghẽn (bệnh do ký sinh trùng, vi khuẩn) gây nôn, khó thở.  \n- **Bệnh thần kinh:** Cứu trĩ, viêm não bẹ gây mất thận, nôn mửa.  \n- **Bệnh lý gan/trang trải:** Tủy sinh tố tách ra, nôn, mệt mỏi.  \n- **Bệnh lý hô hấp:** Phổi tắc nghẽn, viêm phổi do vi khuẩn.  \n- **Bệnh lý thần kinh:** Cứu trĩ, viêm não bẹ gây mất thận, nôn mửa.  \n\n**3. Mức độ khẩn cấp:**  \n**Rất cao!** Các triệu chứng này có thể liên quan đến bệnh lý gan, tụy, hô hấp hoặc thần kinh. Chó mệt mỏi và không ăn uống là dấu hiệu nguy hiểm, cần **khám ngay trong 24 giờ**.  \n\n**4. Lời khuyên chăm sóc tại nhà:**  \n- **Cân nặng & điện tích:** Đo mỗi ngày để theo dõi tình trạng.  \n- **Chăm sóc hỗ trợ:**  \n  - Dùng nước ấm để nuốt (nếu chó còn tỉnh táo).  \n  - Cân bằng điện tích bằng chất béo (nếu chó không ăn).  \n  - Giảm căng thẳng (không để chó chạy túc, không dùng đồ chơi mạnh).  \n- **Không tự dùng thuốc:** Dùng thuốc gây ngủ hoặc thuốc kháng nôn có thể gây biến chứng.  \n\n**Lưu ý:** Đây chỉ là tư vấn tham khảo. **Khám chó ngay tại bác sĩ thú y để chẩn đoán chính xác và điều trị kịp thời.**\n', '2026-06-02 06:39:33'),
(2, 12, NULL, 'Mèo Anh lông ngắn bị rụng lông nhiều thành mảng, da mẩn đỏ và gãi liên tục.', NULL, '\nChào bạn! Tôi là bác sĩ thú y và sẽ phân tích các triệu chứng mà bạn quan tâm cho đến mèo Anh lông ngắn của bạn.\n\n## 1. Phân tích triệu chứng\n- **Rụng lông nhiều thành mảng**: Không đều, có thể do yếu tố dị ứng hoặc vấn đề da\n- **Da mẩn đỏ**: Cho thấy có viêm hoặc phản ứng dị ứng\n- **Gãi liên tục**: Là triệu chứng chính khiến da bị tổn thương thêm\n\n## 2. Nguyên nhân có thể\n- **Ký sinh trùng**: Rệp, mite lông (mite lông thường gây rụng lông ở mèo)\n- **Dị ứng thức ăn hoặc môi trường**: Thực phẩm mới, khói máy tính, không khí ô nhiễm\n- **Bệnh da ngứa khoang**: Bao gồm lupus, psoriasis tuyến của mèo\n- **Quá nhiệt/giải nhiệt dư thừa**: Môi trường nóng ẩm kích hoạt dị ứng da\n- **Tổn thương do gãi quá mạnh**: Tạo chuỗi reo điện giữa các triệu chứng\n\n## 3. Mức độ khẩn cấp\n- **Trung bình đến cao**: Nếu da nhiễm đỏ rộng, có nếp nhăn da, hoặc mèo trở nên buồn ngủ/kích động giảm thì nên đi khám trong 24-48 giờ\n- **Khẩn cấp ngay**: Nếu xuất hiện biểu hiện sốt, mèo không ăn uống, hay phát ban nghiêm trọng\n\n## 4. Lời khuyên chăm sóc tại nhà\n- **Giữ môi trường mát mẻ**: Tắm nguội cho mèo, giữ nhà ẩn khô\n- **Tránh gãi**: Dùng khăn hoặc băng dính để vỡ bọc, ngăn mèo tự giã\n- **Kiểm tra ký sinh trùng**: Dùng dịch kiểm tra rệp/mite từ phòng thí nghiệm\n- **Theo dõi ăn uống**: Đảm bảo mèo ăn ngon và hydrate đủ\n- **Trứng cá hồi hoặc cà chua**: Phục vụ cho da lành mạnh (trong giới hạn 1-2 ngày)\n\n⚠️ **Lưu ý quan trọng**: Đây chỉ là tư vấn tham khảo. Nếu triệu chứng kéo dài hơn 3-5 ngày hoặc đ worsening, vui lòng đưa đến bác sĩ thú y để được chẩn đoán chính xác và điều trị phù hợp nhất. Chữa vật lý có thể kết hợp cortiốt nội tiết và xạ trị nếu cần thiết.\n', '2026-06-02 06:43:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `pet_info` varchar(255) DEFAULT NULL,
  `service_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `duration_value` int(11) DEFAULT 1,
  `duration_unit` enum('hour','day','month','none') DEFAULT 'none',
  `customer_notified` tinyint(1) DEFAULT 0,
  `selected_test` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `appointments`
--

INSERT INTO `appointments` (`id`, `customer_id`, `pet_id`, `pet_info`, `service_id`, `doctor_id`, `final_price`, `customer_name`, `customer_phone`, `appointment_date`, `appointment_time`, `status`, `notes`, `created_at`, `duration_value`, `duration_unit`, `customer_notified`, `selected_test`) VALUES
(40, NULL, NULL, NULL, 12, 5, 4000000.00, 'Nguyễn V ăn A', '11111111', '2026-05-18', '09:00:00', 'completed', 'Đặt trực tiếp tại POS', '2026-05-17 08:14:47', 1, 'none', 0, NULL),
(41, 12, NULL, NULL, 8, 5, 99999999.99, 'Nguyễn Văn A', '1111111111', '2026-05-18', '09:00:00', 'completed', 'Đặt trực tiếp tại POS', '2026-05-17 08:26:39', 1, 'none', 0, NULL),
(42, 12, NULL, NULL, 8, 5, 8500000.00, NULL, NULL, '2026-05-18', '11:11:00', 'completed', '[Thú cưng: Mèo  rừng châu phi] ', '2026-05-18 11:53:58', 1, 'none', 0, NULL),
(43, 13, NULL, NULL, 10, 5, 100000.00, NULL, NULL, '2026-06-11', '10:16:00', 'completed', '[Thú cưng: Mèo  rừng châu phi] ', '2026-06-11 03:25:57', 1, 'none', 0, NULL),
(44, 13, NULL, NULL, 8, 5, 100000.00, NULL, NULL, '2026-06-11', '10:31:00', 'completed', '[Thú cưng: Mèo  rừng châu phi] ', '2026-06-11 03:29:15', 1, 'none', 0, NULL),
(45, 13, NULL, NULL, 8, 5, 100000.00, NULL, NULL, '2026-06-11', '10:32:00', 'completed', '[Thú cưng: Mèo  rừng châu phi] ', '2026-06-11 03:30:03', 1, 'none', 0, NULL),
(46, 12, NULL, NULL, 8, 5, 85000.00, NULL, NULL, '2026-06-12', '16:20:00', 'completed', '[Thú cưng: Mèo  rừng châu phi] ', '2026-06-12 08:48:42', 1, 'none', 0, NULL),
(47, 12, NULL, NULL, 8, 5, 170000.00, NULL, NULL, '2026-06-12', '16:25:00', 'completed', '[Thú cưng: Chó poodle] ', '2026-06-12 08:52:27', 1, 'none', 0, NULL),
(48, 12, NULL, NULL, 9, 5, 85000.00, NULL, NULL, '2026-06-12', '16:26:00', 'completed', '[Thú cưng: Chó poodle] ', '2026-06-12 08:54:16', 1, 'none', 0, NULL),
(49, 12, NULL, NULL, 8, 5, 85000.00, NULL, NULL, '2026-06-12', '16:31:00', 'completed', '[Thú cưng: Chó poodle] ', '2026-06-12 08:57:48', 1, 'none', 0, NULL),
(50, 12, NULL, 'Mèo  rừng châu phi', 9, 5, 85000.00, NULL, NULL, '2026-06-12', '16:31:00', 'completed', '', '2026-06-12 09:00:46', 1, 'none', 0, NULL),
(51, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 85000.00, NULL, NULL, '2026-06-12', '17:07:00', 'completed', '', '2026-06-12 09:36:49', 1, 'none', 0, NULL),
(52, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 0.00, NULL, NULL, '2026-06-12', '17:12:00', 'completed', '', '2026-06-12 09:41:25', 1, 'none', 0, NULL),
(53, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 850000.00, NULL, NULL, '2026-06-12', '17:15:00', 'completed', '', '2026-06-12 09:44:18', 1, 'none', 0, NULL),
(54, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 9, 5, 85000.00, NULL, NULL, '2026-06-12', '17:18:00', 'completed', '', '2026-06-12 09:46:53', 1, 'none', 0, NULL),
(55, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 85000.00, NULL, NULL, '2026-06-12', '17:24:00', 'completed', '', '2026-06-12 09:52:49', 1, 'none', 0, NULL),
(56, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 9, 5, 0.00, NULL, NULL, '2026-06-12', '17:45:00', 'completed', '', '2026-06-12 10:11:23', 1, 'none', 0, NULL),
(57, 12, NULL, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 9, 5, 8498.00, NULL, NULL, '2026-06-12', '17:55:00', 'completed', '', '2026-06-12 10:23:56', 1, 'none', 0, NULL),
(58, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 85000.00, NULL, NULL, '2026-06-12', '18:08:00', 'completed', '', '2026-06-12 10:37:36', 1, 'none', 0, NULL),
(59, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 85000.00, NULL, NULL, '2026-06-13', '12:09:00', 'completed', '', '2026-06-13 04:38:57', 1, 'none', 0, NULL),
(60, 12, NULL, 'Chó poodle', 8, 5, 85000.00, NULL, NULL, '2026-06-13', '12:21:00', 'completed', '', '2026-06-13 04:50:43', 1, 'none', 0, NULL),
(61, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 10, 5, 0.00, NULL, NULL, '2026-06-13', '13:18:00', 'completed', '', '2026-06-13 05:47:18', 1, 'none', 0, NULL),
(62, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 10, 5, 0.00, NULL, NULL, '2026-06-13', '13:24:00', 'completed', '', '2026-06-13 05:53:17', 1, 'none', 0, 'Không xét nghiệm (chỉ tiêm phòng)'),
(63, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 10, 5, 850.00, NULL, NULL, '2026-06-13', '13:26:00', 'completed', '', '2026-06-13 05:55:37', 1, 'none', 0, 'Không xét nghiệm (chỉ tiêm phòng)'),
(64, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 10, 5, 84997.00, NULL, NULL, '2026-06-13', '22:41:00', 'completed', '', '2026-06-13 15:10:28', 1, 'none', 0, 'Không xét nghiệm (chỉ tiêm phòng)'),
(65, 12, NULL, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 84997.00, NULL, NULL, '2026-06-13', '22:51:00', 'completed', '', '2026-06-13 15:20:44', 1, 'none', 0, 'Không xét nghiệm (chỉ tiêm phòng)'),
(66, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 8, 5, 84998.00, NULL, NULL, '2026-06-13', '23:07:00', 'completed', '', '2026-06-13 15:36:52', 1, 'none', 0, 'Không xét nghiệm (chỉ tiêm phòng)'),
(67, 12, 1, 'MiMI (Mèo Mèo anh lông ngắn, 12 tháng tuổi, 3.5kg)', 10, 5, 84999.00, NULL, NULL, '2026-06-13', '23:37:00', 'completed', '', '2026-06-13 16:06:18', 1, 'none', 0, 'Không xét nghiệm (chỉ tiêm phòng)');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointment_reviews`
--

CREATE TABLE `appointment_reviews` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late','on_leave') DEFAULT 'present',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `date`, `status`, `notes`) VALUES
(4, 7, '2026-05-14', 'present', ''),
(5, 5, '2026-05-14', 'present', ''),
(6, 9, '2026-05-14', 'present', ''),
(7, 4, '2026-05-14', 'present', ''),
(8, 11, '2026-05-18', 'present', ''),
(9, 7, '2026-05-18', 'present', ''),
(10, 5, '2026-05-18', 'present', ''),
(11, 9, '2026-05-18', 'present', ''),
(12, 4, '2026-05-18', 'present', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('product','service') NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`, `description`) VALUES
(1, 'Thức ăn cho Chó', 'product', 'Các loại hạt, pate, thịt hộp cho chó'),
(2, 'Thức ăn cho Mèo', 'product', 'Hạt, súp thưởng, pate cho mèo'),
(3, 'Phụ kiện', 'product', 'Vòng cổ, dây dắt, balo, khay vệ sinh'),
(5, 'Khám, chữa bệnh', 'service', ''),
(6, 'Chăm sóc', 'service', ''),
(7, 'Huấn luyện', 'service', ''),
(8, 'Chụp ảnh', 'service', ''),
(9, 'Trông giữ', 'service', ''),
(10, 'Chó', 'product', ''),
(11, 'Mèo', 'product', ''),
(12, 'Thuốc', 'product', ''),
(13, 'Vắc - Xin', 'product', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coin_history`
--

CREATE TABLE `coin_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `coin_history`
--

INSERT INTO `coin_history` (`id`, `user_id`, `amount`, `reason`, `created_at`) VALUES
(1, 12, 7, 'Hoàn xu đơn hàng Online #ORD-00086', '2026-06-14 14:44:47'),
(2, 12, 999, 'Hoàn xu đơn hàng Online #ORD-00087', '2026-06-14 14:55:49'),
(3, 12, -20, 'Đổi Voucher Giảm 20K', '2026-06-14 14:58:34'),
(4, 12, -20, 'Đổi Voucher Giảm 20K', '2026-06-14 14:58:49'),
(5, 12, -20, 'Đổi Voucher Giảm 20K', '2026-06-14 14:59:22'),
(6, 12, -100, 'Đổi Voucher Giảm 100K', '2026-06-14 14:59:31'),
(7, 12, -50, 'Đổi Voucher Giảm 50K', '2026-06-14 15:00:26'),
(8, 12, 3, 'Hoàn xu đơn hàng Online #ORD-00088', '2026-06-14 15:02:21'),
(9, 12, 3, 'Hoàn xu đơn hàng Online #ORD-00089', '2026-06-14 15:02:22'),
(10, 12, 4, 'Hoàn xu đơn hàng Online #ORD-00090', '2026-06-14 15:11:50'),
(11, 12, -100, 'Đổi Voucher Giảm 20%', '2026-06-14 15:28:00'),
(12, 12, 3, 'Hoàn xu đơn hàng Online #ORD-00091', '2026-06-14 15:39:57'),
(13, 12, -25, 'Đổi Voucher Giảm 50K', '2026-06-14 15:43:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','replied') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `message`, `status`, `created_at`) VALUES
(1, 'Nguyễn Văn A', 'khachhang1@gmail.com', 'sdadasdasdasdasdad', 'replied', '2026-05-29 08:23:17'),
(2, 'Nguyễn Văn A', 'khachhang1@gmail.com', 'fasdasasasasasasasas', 'replied', '2026-05-29 08:24:07'),
(3, 'Nguyễn Văn A', 'khachhang1@gmail.com', 'njflasdhjfhjfaklfafjfjkafjlasjfsjfasf', 'replied', '2026-05-29 08:30:31'),
(4, 'Nguyễn Văn A', 'khachhang1@gmail.com', 'sfaaaaaaaaaa', 'replied', '2026-05-29 08:33:15'),
(5, 'Nguyễn Văn A', 'khachhang1@gmail.com', 'dâsdasdada', 'replied', '2026-05-29 08:36:19'),
(6, 'Nguyễn Văn A', 'khachhang1@gmail.com', 'akshdjkah', 'replied', '2026-06-10 04:17:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL,
  `recipient_email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text DEFAULT NULL,
  `status` enum('sent','failed') DEFAULT 'sent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `email_logs`
--

INSERT INTO `email_logs` (`id`, `recipient_email`, `subject`, `body`, `status`, `created_at`) VALUES
(1, 'khachhang1@gmail.com', 'Phản hồi từ PETSHOP - Cảm ơn bạn đã liên hệ', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            \n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã liên hệ với PETSHOP. Chúng tôi xin gửi phản hồi cho yêu cầu của bạn như sau:</p>\n            \n            <div style=\'background-color: #f8fafc; padding: 15px; border-left: 4px solid #4f46e5; margin: 20px 0;\'>\n                <p style=\'margin: 0; color: #334155; line-height: 1.6; white-space: pre-wrap;\'>sadasad</p>\n            </div>\n            \n            <p style=\'color: #334155; line-height: 1.6;\'>Nếu bạn có bất kỳ câu hỏi nào khác, vui lòng trả lời trực tiếp email này hoặc liên hệ hotline của chúng tôi.</p>\n            \n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            \n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Bộ phận Chăm sóc khách hàng - PETSHOP</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>\n        ', 'sent', '2026-05-29 08:33:31'),
(2, 'khachhang1@gmail.com', 'Phản hồi từ PETSHOP - Cảm ơn bạn đã liên hệ', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            \n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã liên hệ với PETSHOP. Chúng tôi xin gửi phản hồi cho yêu cầu của bạn như sau:</p>\n            \n            <div style=\'background-color: #f8fafc; padding: 15px; border-left: 4px solid #4f46e5; margin: 20px 0;\'>\n                <p style=\'margin: 0; color: #334155; line-height: 1.6; white-space: pre-wrap;\'>adadd</p>\n            </div>\n            \n            <p style=\'color: #334155; line-height: 1.6;\'>Nếu bạn có bất kỳ câu hỏi nào khác, vui lòng trả lời trực tiếp email này hoặc liên hệ hotline của chúng tôi.</p>\n            \n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            \n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Bộ phận Chăm sóc khách hàng - PETSHOP</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>\n        ', 'sent', '2026-05-29 08:35:57'),
(3, 'khachhang1@gmail.com', 'Phản hồi từ PETSHOP - Cảm ơn bạn đã liên hệ', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            \n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã liên hệ với PETSHOP. Chúng tôi xin gửi phản hồi cho yêu cầu của bạn như sau:</p>\n            \n            <div style=\'background-color: #f8fafc; padding: 15px; border-left: 4px solid #4f46e5; margin: 20px 0;\'>\n                <p style=\'margin: 0; color: #334155; line-height: 1.6; white-space: pre-wrap;\'>à</p>\n            </div>\n            \n            <p style=\'color: #334155; line-height: 1.6;\'>Nếu bạn có bất kỳ câu hỏi nào khác, vui lòng trả lời trực tiếp email này hoặc liên hệ hotline của chúng tôi.</p>\n            \n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            \n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Bộ phận Chăm sóc khách hàng - PETSHOP</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>\n        ', 'sent', '2026-05-29 08:36:40'),
(4, 'nmtvp11223311@gmail.com', 'Yêu cầu liên hệ mới từ khách hàng: Nguyễn Văn A', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            \n            <h2 style=\'color: #0f172a;\'>Có yêu cầu liên hệ mới từ khách hàng!</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Dưới đây là chi tiết yêu cầu:</p>\n            \n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0; color: #334155;\'>\n                <p><strong>Họ và tên:</strong> Nguyễn Văn A</p>\n                <p><strong>Email khách hàng:</strong> khachhang1@gmail.com</p>\n                <p><strong>Nội dung:</strong></p>\n                <p style=\'white-space: pre-wrap; background-color: #ffffff; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;\'>akshdjkah</p>\n            </div>\n            \n            <p style=\'color: #334155; line-height: 1.6;\'>Bạn có thể đăng nhập vào hệ thống Admin của PETSHOP để phản hồi khách hàng.</p>\n            \n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            \n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>\n        ', 'sent', '2026-06-10 04:17:07'),
(5, 'khachhang1@gmail.com', 'Phản hồi từ PETSHOP - Cảm ơn bạn đã liên hệ', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã liên hệ với PETSHOP. Chúng tôi xin gửi phản hồi cho yêu cầu của bạn như sau:</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-left: 4px solid #4f46e5; margin: 20px 0;\'>\n                <p style=\'margin: 0; color: #334155; line-height: 1.6; white-space: pre-wrap;\'>da</p>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Nếu bạn có bất kỳ câu hỏi nào khác, vui lòng liên hệ lại với chúng tôi.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Bộ phận Chăm sóc khách hàng - PETSHOP</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 07:06:52'),
(6, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00054 từ PETSHOP', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;\'>\n                <h3 style=\'margin-top: 0; color: #0f172a;\'>Chi tiết đơn hàng</h3>\n                <ul style=\'list-style: none; padding: 0; margin: 0; color: #334155;\'>\n                    <li style=\'margin-bottom: 10px;\'><strong>Mã đơn hàng:</strong> #ORD-00054</li>\n                    <li style=\'margin-bottom: 10px;\'><strong>Tổng thanh toán:</strong> <span style=\'color: #e11d48; font-weight: bold;\'>130.000đ</span></li>\n                    <li><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</li>\n                </ul>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Đây là email tự động, vui lòng không trả lời email này.</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 14:40:26'),
(7, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00055 từ PETSHOP', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;\'>\n                <h3 style=\'margin-top: 0; color: #0f172a;\'>Chi tiết đơn hàng</h3>\n                <ul style=\'list-style: none; padding: 0; margin: 0; color: #334155;\'>\n                    <li style=\'margin-bottom: 10px;\'><strong>Mã đơn hàng:</strong> #ORD-00055</li>\n                    <li style=\'margin-bottom: 10px;\'><strong>Tổng thanh toán:</strong> <span style=\'color: #e11d48; font-weight: bold;\'>130.000đ</span></li>\n                    <li><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</li>\n                </ul>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Đây là email tự động, vui lòng không trả lời email này.</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 14:45:12'),
(8, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00056 từ PETSHOP', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;\'>\n                <h3 style=\'margin-top: 0; color: #0f172a;\'>Chi tiết đơn hàng</h3>\n                <ul style=\'list-style: none; padding: 0; margin: 0; color: #334155;\'>\n                    <li style=\'margin-bottom: 10px;\'><strong>Mã đơn hàng:</strong> #ORD-00056</li>\n                    <li style=\'margin-bottom: 10px;\'><strong>Tổng thanh toán:</strong> <span style=\'color: #e11d48; font-weight: bold;\'>130.000đ</span></li>\n                    <li><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</li>\n                </ul>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Đây là email tự động, vui lòng không trả lời email này.</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 14:50:05'),
(9, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00057 từ PETSHOP', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;\'>\n                <h3 style=\'margin-top: 0; color: #0f172a;\'>Chi tiết đơn hàng</h3>\n                <ul style=\'list-style: none; padding: 0; margin: 0; color: #334155;\'>\n                    <li style=\'margin-bottom: 10px;\'><strong>Mã đơn hàng:</strong> #ORD-00057</li>\n                    <li style=\'margin-bottom: 10px;\'><strong>Tổng thanh toán:</strong> <span style=\'color: #e11d48; font-weight: bold;\'>130.000đ</span></li>\n                    <li><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</li>\n                </ul>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Đây là email tự động, vui lòng không trả lời email này.</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 14:54:44'),
(10, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00058 từ PETSHOP', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;\'>\n                <h3 style=\'margin-top: 0; color: #0f172a;\'>Chi tiết đơn hàng</h3>\n                <ul style=\'list-style: none; padding: 0; margin: 0; color: #334155;\'>\n                    <li style=\'margin-bottom: 10px;\'><strong>Mã đơn hàng:</strong> #ORD-00058</li>\n                    <li style=\'margin-bottom: 10px;\'><strong>Tổng thanh toán:</strong> <span style=\'color: #e11d48; font-weight: bold;\'>596.000đ</span></li>\n                    <li><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</li>\n                </ul>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Đây là email tự động, vui lòng không trả lời email này.</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 15:11:09'),
(11, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00059 từ PETSHOP', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;\'>\n                <h3 style=\'margin-top: 0; color: #0f172a;\'>Chi tiết đơn hàng</h3>\n                <ul style=\'list-style: none; padding: 0; margin: 0; color: #334155;\'>\n                    <li style=\'margin-bottom: 10px;\'><strong>Mã đơn hàng:</strong> #ORD-00059</li>\n                    <li style=\'margin-bottom: 10px;\'><strong>Tổng thanh toán:</strong> <span style=\'color: #e11d48; font-weight: bold;\'>596.000đ</span></li>\n                    <li><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</li>\n                </ul>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Đây là email tự động, vui lòng không trả lời email này.</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 15:11:59'),
(12, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00060 từ PETSHOP', '\n        <div style=\'font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;\'>\n            <div style=\'text-align: center; margin-bottom: 20px;\'>\n                <h1 style=\'color: #4f46e5; margin: 0;\'>PETSHOP</h1>\n                <p style=\'color: #64748b; margin: 5px 0 0 0;\'>Nơi yêu thương bắt đầu</p>\n            </div>\n            <h2 style=\'color: #0f172a;\'>Xin chào Nguyễn Văn A,</h2>\n            <p style=\'color: #334155; line-height: 1.6;\'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>\n            <div style=\'background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;\'>\n                <h3 style=\'margin-top: 0; color: #0f172a;\'>Chi tiết đơn hàng</h3>\n                <ul style=\'list-style: none; padding: 0; margin: 0; color: #334155;\'>\n                    <li style=\'margin-bottom: 10px;\'><strong>Mã đơn hàng:</strong> #ORD-00060</li>\n                    <li style=\'margin-bottom: 10px;\'><strong>Tổng thanh toán:</strong> <span style=\'color: #e11d48; font-weight: bold;\'>596.000đ</span></li>\n                    <li><strong>Phương thức:</strong> Thanh toán khi nhận hàng (COD)</li>\n                </ul>\n            </div>\n            <p style=\'color: #334155; line-height: 1.6;\'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>\n            <hr style=\'border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;\'>\n            <div style=\'text-align: center; color: #94a3b8; font-size: 12px;\'>\n                <p>Đây là email tự động, vui lòng không trả lời email này.</p>\n                <p>&copy; 2026 PETSHOP. All rights reserved.</p>\n            </div>\n        </div>', 'sent', '2026-06-10 15:33:30'),
(13, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00084 từ PETSHOP', '\n        <div style=\'background-color: #f8fafc; padding: 40px 0;\'>\n            <div style=\'font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);\'>\n                \n                <!-- Header -->\n                <div style=\'text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;\'>\n                    <h1 style=\'color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;\'>PETSHOP</h1>\n                    <p style=\'color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;\'>Nơi yêu thương bắt đầu</p>\n                </div>\n                \n                <!-- Greeting -->\n                <h2 style=\'color: #0f172a; font-size: 20px; margin-top: 0;\'>Xin chào Nguyễn Văn A,</h2>\n                <p style=\'color: #475569; line-height: 1.6; font-size: 16px;\'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>\n                \n                <!-- Order Details Box -->\n                <div style=\'background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;\'>\n                    <div style=\'display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;\'>\n                        <div>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Mã đơn hàng</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 18px;\'>#ORD-00084</h3>\n                        </div>\n                        <div style=\'text-align: right;\'>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Ngày đặt</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;\'>14/06/2026</h3>\n                        </div>\n                    </div>\n                    \n                    <!-- Items -->\n                    <table style=\'width: 100%; border-collapse: collapse; margin-top: 15px;\'>\n                        <thead>\n                            <tr style=\'border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;\'>\n                                <th style=\'padding: 10px 5px;\'>Sản phẩm</th>\n                                <th style=\'padding: 10px 5px; text-align: center;\'>SL</th>\n                                <th style=\'padding: 10px 5px; text-align: right;\'>Thành tiền</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Purevax RCP-FeLV</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr></tbody></table>\n                    \n                    <!-- Summary -->\n                    <div style=\'margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;\'>\n                        <table style=\'width: 100%; border-collapse: collapse;\'>\n                            <tr>\n                                <td style=\'padding: 5px 0; color: #64748b;\'>Phương thức thanh toán:</td>\n                                <td style=\'padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;\'>Thanh toán khi nhận hàng (COD)</td>\n                            </tr>\n                            <tr>\n                                <td style=\'padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;\'>Tổng thanh toán:</td>\n                                <td style=\'padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;\'>450.000đ</td>\n                            </tr>\n                        </table>\n                    </div>\n                </div>\n                \n                <!-- Call to Action -->\n                <div style=\'text-align: center; margin: 40px 0;\'>\n                    <a href=\'http://localhost/PETSHOP\' style=\'background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;\'>Tiếp tục mua sắm</a>\n                </div>\n                \n                <p style=\'color: #475569; line-height: 1.6; font-size: 15px; text-align: center;\'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>\n                \n                <!-- Footer -->\n                <div style=\'text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;\'>\n                    <p style=\'color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;\'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>\n                    <p style=\'color: #64748b; font-size: 13px; margin: 0;\'><strong>&copy; 2026 PETSHOP.</strong> All rights reserved.</p>\n                </div>\n            </div>\n        </div>', 'sent', '2026-06-14 14:42:07'),
(14, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00085 từ PETSHOP', '\n        <div style=\'background-color: #f8fafc; padding: 40px 0;\'>\n            <div style=\'font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);\'>\n                \n                <!-- Header -->\n                <div style=\'text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;\'>\n                    <h1 style=\'color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;\'>PETSHOP</h1>\n                    <p style=\'color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;\'>Nơi yêu thương bắt đầu</p>\n                </div>\n                \n                <!-- Greeting -->\n                <h2 style=\'color: #0f172a; font-size: 20px; margin-top: 0;\'>Xin chào Nguyễn Văn A,</h2>\n                <p style=\'color: #475569; line-height: 1.6; font-size: 16px;\'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>\n                \n                <!-- Order Details Box -->\n                <div style=\'background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;\'>\n                    <div style=\'display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;\'>\n                        <div>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Mã đơn hàng</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 18px;\'>#ORD-00085</h3>\n                        </div>\n                        <div style=\'text-align: right;\'>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Ngày đặt</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;\'>14/06/2026</h3>\n                        </div>\n                    </div>\n                    \n                    <!-- Items -->\n                    <table style=\'width: 100%; border-collapse: collapse; margin-top: 15px;\'>\n                        <thead>\n                            <tr style=\'border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;\'>\n                                <th style=\'padding: 10px 5px;\'>Sản phẩm</th>\n                                <th style=\'padding: 10px 5px; text-align: center;\'>SL</th>\n                                <th style=\'padding: 10px 5px; text-align: right;\'>Thành tiền</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Mèo Anh lông ngắn (British Shorthair)</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr></tbody></table>\n                    \n                    <!-- Summary -->\n                    <div style=\'margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;\'>\n                        <table style=\'width: 100%; border-collapse: collapse;\'>\n                            <tr>\n                                <td style=\'padding: 5px 0; color: #64748b;\'>Phương thức thanh toán:</td>\n                                <td style=\'padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;\'>Thanh toán khi nhận hàng (COD)</td>\n                            </tr>\n                            <tr>\n                                <td style=\'padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;\'>Tổng thanh toán:</td>\n                                <td style=\'padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;\'>2.997.000đ</td>\n                            </tr>\n                        </table>\n                    </div>\n                </div>\n                \n                <!-- Call to Action -->\n                <div style=\'text-align: center; margin: 40px 0;\'>\n                    <a href=\'http://localhost/PETSHOP\' style=\'background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;\'>Tiếp tục mua sắm</a>\n                </div>\n                \n                <p style=\'color: #475569; line-height: 1.6; font-size: 15px; text-align: center;\'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>\n                \n                <!-- Footer -->\n                <div style=\'text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;\'>\n                    <p style=\'color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;\'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>\n                    <p style=\'color: #64748b; font-size: 13px; margin: 0;\'><strong>&copy; 2026 PETSHOP.</strong> All rights reserved.</p>\n                </div>\n            </div>\n        </div>', 'sent', '2026-06-14 14:42:58'),
(15, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00086 từ PETSHOP', '\n        <div style=\'background-color: #f8fafc; padding: 40px 0;\'>\n            <div style=\'font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);\'>\n                \n                <!-- Header -->\n                <div style=\'text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;\'>\n                    <h1 style=\'color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;\'>PETSHOP</h1>\n                    <p style=\'color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;\'>Nơi yêu thương bắt đầu</p>\n                </div>\n                \n                <!-- Greeting -->\n                <h2 style=\'color: #0f172a; font-size: 20px; margin-top: 0;\'>Xin chào Nguyễn Văn A,</h2>\n                <p style=\'color: #475569; line-height: 1.6; font-size: 16px;\'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>\n                \n                <!-- Order Details Box -->\n                <div style=\'background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;\'>\n                    <div style=\'display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;\'>\n                        <div>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Mã đơn hàng</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 18px;\'>#ORD-00086</h3>\n                        </div>\n                        <div style=\'text-align: right;\'>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Ngày đặt</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;\'>14/06/2026</h3>\n                        </div>\n                    </div>\n                    \n                    <!-- Items -->\n                    <table style=\'width: 100%; border-collapse: collapse; margin-top: 15px;\'>\n                        <thead>\n                            <tr style=\'border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;\'>\n                                <th style=\'padding: 10px 5px;\'>Sản phẩm</th>\n                                <th style=\'padding: 10px 5px; text-align: center;\'>SL</th>\n                                <th style=\'padding: 10px 5px; text-align: right;\'>Thành tiền</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Purevax RCP-FeLV</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Purevax RCP</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr></tbody></table>\n                    \n                    <!-- Summary -->\n                    <div style=\'margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;\'>\n                        <table style=\'width: 100%; border-collapse: collapse;\'>\n                            <tr>\n                                <td style=\'padding: 5px 0; color: #64748b;\'>Phương thức thanh toán:</td>\n                                <td style=\'padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;\'>Thanh toán khi nhận hàng (COD)</td>\n                            </tr>\n                            <tr>\n                                <td style=\'padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;\'>Tổng thanh toán:</td>\n                                <td style=\'padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;\'>750.000đ</td>\n                            </tr>\n                        </table>\n                    </div>\n                </div>\n                \n                <!-- Call to Action -->\n                <div style=\'text-align: center; margin: 40px 0;\'>\n                    <a href=\'http://localhost/PETSHOP\' style=\'background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;\'>Tiếp tục mua sắm</a>\n                </div>\n                \n                <p style=\'color: #475569; line-height: 1.6; font-size: 15px; text-align: center;\'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>\n                \n                <!-- Footer -->\n                <div style=\'text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;\'>\n                    <p style=\'color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;\'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>\n                    <p style=\'color: #64748b; font-size: 13px; margin: 0;\'><strong>&copy; 2026 PETSHOP.</strong> All rights reserved.</p>\n                </div>\n            </div>\n        </div>', 'sent', '2026-06-14 14:44:31'),
(16, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00087 từ PETSHOP', '\n        <div style=\'background-color: #f8fafc; padding: 40px 0;\'>\n            <div style=\'font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);\'>\n                \n                <!-- Header -->\n                <div style=\'text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;\'>\n                    <h1 style=\'color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;\'>PETSHOP</h1>\n                    <p style=\'color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;\'>Nơi yêu thương bắt đầu</p>\n                </div>\n                \n                <!-- Greeting -->\n                <h2 style=\'color: #0f172a; font-size: 20px; margin-top: 0;\'>Xin chào Nguyễn Văn A,</h2>\n                <p style=\'color: #475569; line-height: 1.6; font-size: 16px;\'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>\n                \n                <!-- Order Details Box -->\n                <div style=\'background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;\'>\n                    <div style=\'display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;\'>\n                        <div>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Mã đơn hàng</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 18px;\'>#ORD-00087</h3>\n                        </div>\n                        <div style=\'text-align: right;\'>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Ngày đặt</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;\'>14/06/2026</h3>\n                        </div>\n                    </div>\n                    \n                    <!-- Items -->\n                    <table style=\'width: 100%; border-collapse: collapse; margin-top: 15px;\'>\n                        <thead>\n                            <tr style=\'border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;\'>\n                                <th style=\'padding: 10px 5px;\'>Sản phẩm</th>\n                                <th style=\'padding: 10px 5px; text-align: center;\'>SL</th>\n                                <th style=\'padding: 10px 5px; text-align: right;\'>Thành tiền</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Mèo Thần Miến Điện (Birman)</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Khao-Manee</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>American Bully</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Central Asian Shepherd Dog (Alabai).</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr></tbody></table>\n                    \n                    <!-- Summary -->\n                    <div style=\'margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;\'>\n                        <table style=\'width: 100%; border-collapse: collapse;\'>\n                            <tr>\n                                <td style=\'padding: 5px 0; color: #64748b;\'>Phương thức thanh toán:</td>\n                                <td style=\'padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;\'>Thanh toán khi nhận hàng (COD)</td>\n                            </tr>\n                            <tr>\n                                <td style=\'padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;\'>Tổng thanh toán:</td>\n                                <td style=\'padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;\'>118.998.000đ</td>\n                            </tr>\n                        </table>\n                    </div>\n                </div>\n                \n                <!-- Call to Action -->\n                <div style=\'text-align: center; margin: 40px 0;\'>\n                    <a href=\'http://localhost/PETSHOP\' style=\'background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;\'>Tiếp tục mua sắm</a>\n                </div>\n                \n                <p style=\'color: #475569; line-height: 1.6; font-size: 15px; text-align: center;\'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>\n                \n                <!-- Footer -->\n                <div style=\'text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;\'>\n                    <p style=\'color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;\'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>\n                    <p style=\'color: #64748b; font-size: 13px; margin: 0;\'><strong>&copy; 2026 PETSHOP.</strong> All rights reserved.</p>\n                </div>\n            </div>\n        </div>', 'sent', '2026-06-14 14:55:42');
INSERT INTO `email_logs` (`id`, `recipient_email`, `subject`, `body`, `status`, `created_at`) VALUES
(17, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00089 từ PETSHOP', '\n        <div style=\'background-color: #f8fafc; padding: 40px 0;\'>\n            <div style=\'font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);\'>\n                \n                <!-- Header -->\n                <div style=\'text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;\'>\n                    <h1 style=\'color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;\'>PETSHOP</h1>\n                    <p style=\'color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;\'>Nơi yêu thương bắt đầu</p>\n                </div>\n                \n                <!-- Greeting -->\n                <h2 style=\'color: #0f172a; font-size: 20px; margin-top: 0;\'>Xin chào Nguyễn Văn A,</h2>\n                <p style=\'color: #475569; line-height: 1.6; font-size: 16px;\'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>\n                \n                <!-- Order Details Box -->\n                <div style=\'background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;\'>\n                    <div style=\'display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;\'>\n                        <div>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Mã đơn hàng</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 18px;\'>#ORD-00089</h3>\n                        </div>\n                        <div style=\'text-align: right;\'>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Ngày đặt</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;\'>14/06/2026</h3>\n                        </div>\n                    </div>\n                    \n                    <!-- Items -->\n                    <table style=\'width: 100%; border-collapse: collapse; margin-top: 15px;\'>\n                        <thead>\n                            <tr style=\'border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;\'>\n                                <th style=\'padding: 10px 5px;\'>Sản phẩm</th>\n                                <th style=\'padding: 10px 5px; text-align: center;\'>SL</th>\n                                <th style=\'padding: 10px 5px; text-align: right;\'>Thành tiền</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Purevax RCP-FeLV</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr></tbody></table>\n                    \n                    <!-- Summary -->\n                    <div style=\'margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;\'>\n                        <table style=\'width: 100%; border-collapse: collapse;\'>\n                            <tr>\n                                <td style=\'padding: 5px 0; color: #64748b;\'>Phương thức thanh toán:</td>\n                                <td style=\'padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;\'>Thanh toán khi nhận hàng (COD)</td>\n                            </tr>\n                            <tr>\n                                <td style=\'padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;\'>Tổng thanh toán:</td>\n                                <td style=\'padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;\'>350.000đ</td>\n                            </tr>\n                        </table>\n                    </div>\n                </div>\n                \n                <!-- Call to Action -->\n                <div style=\'text-align: center; margin: 40px 0;\'>\n                    <a href=\'http://localhost/PETSHOP\' style=\'background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;\'>Tiếp tục mua sắm</a>\n                </div>\n                \n                <p style=\'color: #475569; line-height: 1.6; font-size: 15px; text-align: center;\'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>\n                \n                <!-- Footer -->\n                <div style=\'text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;\'>\n                    <p style=\'color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;\'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>\n                    <p style=\'color: #64748b; font-size: 13px; margin: 0;\'><strong>&copy; 2026 PETSHOP.</strong> All rights reserved.</p>\n                </div>\n            </div>\n        </div>', 'sent', '2026-06-14 15:02:02'),
(18, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00090 từ PETSHOP', '\n        <div style=\'background-color: #f8fafc; padding: 40px 0;\'>\n            <div style=\'font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);\'>\n                \n                <!-- Header -->\n                <div style=\'text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;\'>\n                    <h1 style=\'color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;\'>PETSHOP</h1>\n                    <p style=\'color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;\'>Nơi yêu thương bắt đầu</p>\n                </div>\n                \n                <!-- Greeting -->\n                <h2 style=\'color: #0f172a; font-size: 20px; margin-top: 0;\'>Xin chào Nguyễn Văn A,</h2>\n                <p style=\'color: #475569; line-height: 1.6; font-size: 16px;\'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>\n                \n                <!-- Order Details Box -->\n                <div style=\'background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;\'>\n                    <div style=\'display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;\'>\n                        <div>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Mã đơn hàng</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 18px;\'>#ORD-00090</h3>\n                        </div>\n                        <div style=\'text-align: right;\'>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Ngày đặt</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;\'>14/06/2026</h3>\n                        </div>\n                    </div>\n                    \n                    <!-- Items -->\n                    <table style=\'width: 100%; border-collapse: collapse; margin-top: 15px;\'>\n                        <thead>\n                            <tr style=\'border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;\'>\n                                <th style=\'padding: 10px 5px;\'>Sản phẩm</th>\n                                <th style=\'padding: 10px 5px; text-align: center;\'>SL</th>\n                                <th style=\'padding: 10px 5px; text-align: right;\'>Thành tiền</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Purevax RCP-FeLV</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr></tbody></table>\n                    \n                    <!-- Summary -->\n                    <div style=\'margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;\'>\n                        <table style=\'width: 100%; border-collapse: collapse;\'>\n                            <tr>\n                                <td style=\'padding: 5px 0; color: #64748b;\'>Phương thức thanh toán:</td>\n                                <td style=\'padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;\'>Thanh toán khi nhận hàng (COD)</td>\n                            </tr>\n                            <tr>\n                                <td style=\'padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;\'>Tổng thanh toán:</td>\n                                <td style=\'padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;\'>400.000đ</td>\n                            </tr>\n                        </table>\n                    </div>\n                </div>\n                \n                <!-- Call to Action -->\n                <div style=\'text-align: center; margin: 40px 0;\'>\n                    <a href=\'http://localhost/PETSHOP\' style=\'background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;\'>Tiếp tục mua sắm</a>\n                </div>\n                \n                <p style=\'color: #475569; line-height: 1.6; font-size: 15px; text-align: center;\'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>\n                \n                <!-- Footer -->\n                <div style=\'text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;\'>\n                    <p style=\'color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;\'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>\n                    <p style=\'color: #64748b; font-size: 13px; margin: 0;\'><strong>&copy; 2026 PETSHOP.</strong> All rights reserved.</p>\n                </div>\n            </div>\n        </div>', 'sent', '2026-06-14 15:11:37'),
(19, 'khachhang1@gmail.com', 'Xác nhận đơn hàng #ORD-00091 từ PETSHOP', '\n        <div style=\'background-color: #f8fafc; padding: 40px 0;\'>\n            <div style=\'font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);\'>\n                \n                <!-- Header -->\n                <div style=\'text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;\'>\n                    <h1 style=\'color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;\'>PETSHOP</h1>\n                    <p style=\'color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;\'>Nơi yêu thương bắt đầu</p>\n                </div>\n                \n                <!-- Greeting -->\n                <h2 style=\'color: #0f172a; font-size: 20px; margin-top: 0;\'>Xin chào Nguyễn Văn A,</h2>\n                <p style=\'color: #475569; line-height: 1.6; font-size: 16px;\'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>\n                \n                <!-- Order Details Box -->\n                <div style=\'background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;\'>\n                    <div style=\'display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;\'>\n                        <div>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Mã đơn hàng</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 18px;\'>#ORD-00091</h3>\n                        </div>\n                        <div style=\'text-align: right;\'>\n                            <p style=\'margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;\'>Ngày đặt</p>\n                            <h3 style=\'margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;\'>14/06/2026</h3>\n                        </div>\n                    </div>\n                    \n                    <!-- Items -->\n                    <table style=\'width: 100%; border-collapse: collapse; margin-top: 15px;\'>\n                        <thead>\n                            <tr style=\'border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;\'>\n                                <th style=\'padding: 10px 5px;\'>Sản phẩm</th>\n                                <th style=\'padding: 10px 5px; text-align: center;\'>SL</th>\n                                <th style=\'padding: 10px 5px; text-align: right;\'>Thành tiền</th>\n                            </tr>\n                        </thead>\n                        <tbody>\n                            <tr style=\'border-bottom: 1px solid #f1f5f9;\'>\n                                <td style=\'padding: 12px 5px; color: #334155; font-weight: 500;\'>Purevax RCP-FeLV</td>\n                                <td style=\'padding: 12px 5px; text-align: center; color: #64748b;\'>x1</td>\n                                <td style=\'padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;\'>0đ</td>\n                            </tr></tbody></table>\n                    \n                    <!-- Summary -->\n                    <div style=\'margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;\'>\n                        <table style=\'width: 100%; border-collapse: collapse;\'>\n                            <tr>\n                                <td style=\'padding: 5px 0; color: #64748b;\'>Phương thức thanh toán:</td>\n                                <td style=\'padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;\'>Thanh toán khi nhận hàng (COD)</td>\n                            </tr>\n                            <tr>\n                                <td style=\'padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;\'>Tổng thanh toán:</td>\n                                <td style=\'padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;\'>360.000đ</td>\n                            </tr>\n                        </table>\n                    </div>\n                </div>\n                \n                <!-- Call to Action -->\n                <div style=\'text-align: center; margin: 40px 0;\'>\n                    <a href=\'http://localhost/PETSHOP\' style=\'background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;\'>Tiếp tục mua sắm</a>\n                </div>\n                \n                <p style=\'color: #475569; line-height: 1.6; font-size: 15px; text-align: center;\'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>\n                \n                <!-- Footer -->\n                <div style=\'text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;\'>\n                    <p style=\'color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;\'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>\n                    <p style=\'color: #64748b; font-size: 13px; margin: 0;\'><strong>&copy; 2026 PETSHOP.</strong> All rights reserved.</p>\n                </div>\n            </div>\n        </div>', 'sent', '2026-06-14 15:39:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_code` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `cccd` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `fullname`, `cccd`, `address`, `image`, `created_at`) VALUES
(1, 4, 'BS001', 'Trần Công Tử', '123456789', 'Vĩnh Phúc', '1778752735_American-Bully.jpg', '2026-05-14 09:58:55'),
(2, 5, 'BS002', 'Heo men', '324124144', 'Vĩnh Phúc', '1778752765_Beagle.jpg', '2026-05-14 09:59:25'),
(4, 7, 'TN0001', 'Chu Thúy Huệ', '231456789', 'Hải Dương', '1778778580_9336ba28-47f5-4100-80f6-69d5962292ae.png', '2026-05-14 17:09:40'),
(6, 9, 'QL001', 'Nguyễn Minh Tuấn', '12354689787', 'Vĩnh Phúc', '1778779517_wallhaven-2e1qxm.png', '2026-05-14 17:25:17'),
(8, 11, 'NV001', 'Bùi Thùy Linh', '12565', '675873', '1778789304_Screenshot_2026-04-05_201632.png', '2026-05-14 20:08:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `health_records`
--

CREATE TABLE `health_records` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `visit_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `health_records`
--

INSERT INTO `health_records` (`id`, `pet_id`, `appointment_id`, `doctor_id`, `diagnosis`, `treatment`, `notes`, `visit_date`, `created_at`) VALUES
(1, 1, 55, 5, 'Đi ngoài', 'Thuốc đi ngoài', 'Uống thuốc 3 ngày , mỗi ngày 2 lần', '2026-06-12', '2026-06-12 09:53:52'),
(2, 1, 56, 5, 'Khám lâm sàng: Thú cưng mệt mỏi, sốt nhẹ', 'Cho uống vitamin bổ sung đề kháng', 'Tái khám nếu sốt cao trở lại', '2026-06-12', '2026-06-12 10:22:41'),
(3, 1, 58, 5, 'aaa', 'aaa', 'đấ', '2026-06-12', '2026-06-12 10:48:48'),
(4, 1, 59, 5, 'd', 'd', 'd', '2026-06-13', '2026-06-13 04:39:21'),
(5, NULL, 60, 5, 'a', 'a', 'a', '2026-06-13', '2026-06-13 04:51:12'),
(6, 1, 61, 5, 'xd', 'ad', 'ada', '2026-06-13', '2026-06-13 05:49:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `health_record_prescriptions`
--

CREATE TABLE `health_record_prescriptions` (
  `id` int(11) NOT NULL,
  `health_record_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `instruction` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `health_record_prescriptions`
--

INSERT INTO `health_record_prescriptions` (`id`, `health_record_id`, `product_id`, `quantity`, `instruction`) VALUES
(1, 3, 26, 1, 'dđ'),
(2, 4, 26, 1, 'sadad'),
(3, 5, 26, 1, 'a');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `membership_level` varchar(50) DEFAULT 'Đồng',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `members`
--

INSERT INTO `members` (`id`, `user_id`, `phone`, `address`, `membership_level`, `created_at`) VALUES
(1, 12, '1111111111', '', 'Bạch kim', '2026-05-15 05:13:10'),
(2, 13, '123456789', NULL, 'Bạc', '2026-06-11 03:20:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `membership_benefits`
--

CREATE TABLE `membership_benefits` (
  `id` int(11) NOT NULL,
  `membership_level` varchar(50) NOT NULL,
  `benefit_text` text NOT NULL,
  `discount_percent` int(11) DEFAULT 0,
  `free_service` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `membership_benefits`
--

INSERT INTO `membership_benefits` (`id`, `membership_level`, `benefit_text`, `discount_percent`, `free_service`, `created_at`) VALUES
(1, 'Đồng', 'Tích lũy điểm thưởng cơ bản', 0, 0, '2026-05-15 05:26:36'),
(2, 'Bạc', 'Giảm giá 5% cho tất cả sản phẩm', 5, 0, '2026-05-15 05:26:36'),
(3, 'Vàng', 'Giảm giá 10% cho tất cả sản phẩm & dịch vụ', 10, 0, '2026-05-15 05:26:36'),
(4, 'Bạch kim', 'Giảm giá 15% cho tất cả sản phẩm & dịch vụ', 15, 0, '2026-05-15 05:26:36'),
(5, 'VIP', 'Miễn phí hoàn toàn tất cả các dịch vụ chăm sóc & Giảm 20% sản phẩm', 20, 0, '2026-05-15 05:26:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(45, 13, 'Dịch vụ đã được xác nhận', 'Lịch hẹn #00045 của bạn đã được quản lý xác nhận và xếp nhân sự. Đặt dịch vụ thành công!', 'appointment', 0, '2026-06-11 03:30:52'),
(46, 13, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00061 của bạn hiện đã hoàn thành.', 'order', 0, '2026-06-12 08:30:18'),
(47, 13, 'Chúc mừng thăng hạng!', 'Tuyệt vời! Bạn đã được thăng hạng lên Bạc. Hãy tận hưởng những ưu đãi mới nhé!', 'rank', 0, '2026-06-12 08:30:18'),
(49, 12, 'Nhắc nhở lịch tiêm phòng', 'Hôm nay là ngày hẹn tiêm nhắc lại mũi vắc xin Tiêm phòng & xét nghiệm cho bé MiMI. Bạn hãy sắp xếp đưa bé đến phòng khám nhé!', 'vaccine_reminder', 1, '2026-06-14 14:30:45'),
(50, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00084 đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.', 'order', 1, '2026-06-14 14:42:21'),
(51, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00085 đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.', 'order', 1, '2026-06-14 14:43:01'),
(52, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00085 của bạn hiện đã hoàn thành.', 'order', 1, '2026-06-14 14:43:48'),
(53, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00086 đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.', 'order', 1, '2026-06-14 14:44:43'),
(54, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00086 của bạn hiện đã hoàn thành.', 'order', 1, '2026-06-14 14:44:47'),
(55, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00087 đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.', 'order', 1, '2026-06-14 14:55:48'),
(56, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00087 của bạn hiện đã hoàn thành.', 'order', 1, '2026-06-14 14:55:49'),
(57, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00089 đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.', 'order', 1, '2026-06-14 15:02:18'),
(58, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00088 của bạn hiện đã hoàn thành.', 'order', 1, '2026-06-14 15:02:21'),
(59, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00089 của bạn hiện đã hoàn thành.', 'order', 1, '2026-06-14 15:02:22'),
(60, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00090 đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.', 'order', 1, '2026-06-14 15:11:48'),
(61, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00090 của bạn hiện đã hoàn thành.', 'order', 1, '2026-06-14 15:11:50'),
(62, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00091 đã được xác nhận. Đặt hàng thành công! Kiện hàng đang trên đường giao đến bạn.', 'order', 1, '2026-06-14 15:39:55'),
(63, 12, 'Cập nhật đơn hàng', 'Đơn hàng #ORD-00091 của bạn hiện đã hoàn thành.', 'order', 1, '2026-06-14 15:39:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','shipping','completed','cancelled') DEFAULT 'pending',
  `cancel_reason` text DEFAULT NULL,
  `voucher_code` varchar(20) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','transfer','cod','vnpay') DEFAULT 'cash',
  `order_type` enum('online','pos') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_notified` tinyint(1) DEFAULT 0,
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `admin_note` text DEFAULT NULL,
  `refund_bank` varchar(100) DEFAULT NULL,
  `refund_account` varchar(50) DEFAULT NULL,
  `refund_name` varchar(100) DEFAULT NULL,
  `refund_status` enum('none','pending','completed') DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `customer_name`, `customer_phone`, `total_amount`, `status`, `cancel_reason`, `voucher_code`, `discount_amount`, `payment_method`, `order_type`, `created_at`, `customer_notified`, `shipping_name`, `shipping_phone`, `shipping_address`, `receipt_image`, `paid_amount`, `admin_note`, `refund_bank`, `refund_account`, `refund_name`, `refund_status`) VALUES
(29, NULL, 'Nguyễn V ăn A', '11111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-05-17 08:24:30', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(30, 12, 'Nguyễn Văn A', '1111111111', 596000.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-05-17 08:26:20', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(31, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-05-17 08:33:45', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(32, 12, NULL, NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-05-18 11:00:15', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(33, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-05-18 11:54:35', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(34, NULL, 'Khách lẻ', NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-05-18 12:11:18', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(35, 12, NULL, NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-18 12:17:34', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(36, NULL, 'Khách lẻ', NULL, 4998000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'pos', '2026-05-18 12:36:01', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(37, NULL, 'Khách lẻ', NULL, 130000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'pos', '2026-05-18 12:38:36', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(38, NULL, 'Khách lẻ', NULL, 130000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'pos', '2026-05-18 12:38:45', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(39, 12, 'Nguyễn Văn A', '1111111111', 93500.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'pos', '2026-05-18 12:38:59', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(40, NULL, 'Khách lẻ', NULL, 110000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'pos', '2026-05-18 12:39:31', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(41, 12, 'Nguyễn Văn A', '1111111111', 505750.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'pos', '2026-05-18 12:40:16', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(42, 12, NULL, NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-21 07:55:44', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(43, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-21 07:59:13', 0, 'Nguyễn Văn A', '1111111111', 'dádas', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(44, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-21 08:05:19', 0, 'Nguyễn Văn A', '1111111111', 'ầdhfj', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(45, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-21 08:06:35', 0, 'Nguyễn Văn A', '1111111111', 'dsj', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(46, 12, NULL, NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-21 08:09:31', 0, 'Nguyễn Văn A', '1111111111', 'SẠHJK', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(47, 12, NULL, NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-21 08:16:02', 0, 'Nguyễn Văn A', '1111111111', 'sà', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(48, 12, NULL, NULL, 125000.00, 'completed', NULL, NULL, 0.00, 'transfer', 'online', '2026-05-21 08:26:58', 0, 'Nguyễn Văn A', '1111111111', 'sgdsfs', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(49, 12, NULL, NULL, 10000000.00, 'cancelled', 'hh', NULL, 0.00, 'transfer', 'online', '2026-05-21 08:43:53', 0, 'Nguyễn Văn A', '1111111111', 'sfsgfdhg', NULL, 10000000.00, '', 'MB Bank', '0947647052', 'Nguyen minh tuan', 'completed'),
(50, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:24:35', 0, 'Nguyễn Văn A', '1111111111', 'ádfffff', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(51, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:31:19', 0, 'Nguyễn Văn A', '1111111111', 'ádda', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(52, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:33:04', 0, 'Nguyễn Văn A', '1111111111', 'dsad', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(53, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:34:50', 0, 'Nguyễn Văn A', '1111111111', 'ádsdsdsdsdsdsdsd', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(54, 12, NULL, NULL, 130000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:39:51', 0, 'Nguyễn Văn A', '1111111111', 'AAAAAAAA', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(55, 12, NULL, NULL, 130000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:44:39', 0, 'Nguyễn Văn A', '1111111111', 'aa', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(56, 12, NULL, NULL, 130000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:49:30', 0, 'Nguyễn Văn A', '1111111111', 'á', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(57, 12, NULL, NULL, 130000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 14:54:18', 0, 'Nguyễn Văn A', '1111111111', 'đa', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(58, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-06-10 15:11:09', 0, 'Nguyễn Văn A', '1111111111', 'adsd', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(59, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 15:11:29', 0, 'Nguyễn Văn A', '1111111111', 'adsd', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(60, 12, NULL, NULL, 596000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-10 15:33:04', 0, 'Nguyễn Văn A', '1111111111', 'S', NULL, 596000.00, NULL, NULL, NULL, NULL, 'none'),
(61, 13, NULL, NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'vnpay', 'online', '2026-06-11 03:21:05', 0, 'NGUYEN VAN A', '123456789', '123456789', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(62, 12, 'NGUYEN VAN A', '123456789', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-11 03:33:54', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(63, 12, 'NGUYEN VAN A', '123456789', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 08:30:01', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(64, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 08:53:38', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(65, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 08:56:09', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(66, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 08:56:44', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(67, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 09:00:23', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(68, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 09:01:32', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(69, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 09:37:19', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(70, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 09:43:37', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(71, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 09:44:36', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(72, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, '', 'pos', '2026-06-12 09:47:13', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(73, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 09:54:25', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(74, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 10:23:37', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(75, 12, 'Nguyễn Văn A', '1111111111', 170000.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-12 10:49:09', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(76, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-13 04:35:40', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(77, 12, 'Nguyễn Văn A', '1111111111', 170000.00, 'completed', NULL, NULL, 0.00, '', 'pos', '2026-06-13 04:39:47', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(78, 12, 'Nguyễn Văn A', '1111111111', 170000.00, 'completed', NULL, NULL, 0.00, '', 'pos', '2026-06-13 04:51:57', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(79, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-13 05:50:00', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(80, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, 'cash', 'pos', '2026-06-13 05:54:15', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(81, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, '', 'pos', '2026-06-13 05:56:07', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(82, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, '', 'pos', '2026-06-13 15:12:19', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(83, 12, 'Nguyễn Văn A', '1111111111', 0.00, 'completed', NULL, NULL, 0.00, '', 'pos', '2026-06-13 15:21:29', 0, NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(84, 12, NULL, NULL, 450000.00, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-06-14 14:42:07', 0, 'Nguyễn Văn A', '1111111111', 'czxc', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(85, 12, NULL, NULL, 2997000.00, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-06-14 14:42:58', 0, 'Nguyễn Văn A', '1111111111', 'cz', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(86, 12, NULL, NULL, 750000.00, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-06-14 14:44:31', 0, 'Nguyễn Văn A', '1111111111', 'gf', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(87, 12, NULL, NULL, 99999999.99, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-06-14 14:55:42', 0, 'Nguyễn Văn A', '1111111111', 'd', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(88, 12, NULL, NULL, 350000.00, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-06-14 15:00:47', 0, 'Nguyễn Văn A', '1111111111', 'aD', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(89, 12, NULL, NULL, 350000.00, 'completed', NULL, NULL, 0.00, 'cod', 'online', '2026-06-14 15:02:02', 0, 'Nguyễn Văn A', '1111111111', 'f', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(90, 12, 'Nguyễn Văn A', '1111111111', 400000.00, 'completed', NULL, 'PET32605853', 50000.00, 'cod', 'online', '2026-06-14 15:11:37', 0, 'Nguyễn Văn A', '1111111111', 'd', NULL, 0.00, NULL, NULL, NULL, NULL, 'none'),
(91, 12, 'Nguyễn Văn A', '1111111111', 360000.00, 'completed', NULL, 'PET551903B0', 90000.00, 'cod', 'online', '2026-06-14 15:39:44', 0, 'Nguyễn Văn A', '1111111111', 'd', NULL, 0.00, NULL, NULL, NULL, NULL, 'none');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(19, 30, 23, 1, 596000.00),
(20, 32, 24, 1, 2997000.00),
(21, 34, 24, 1, 2997000.00),
(22, 35, 24, 1, 2997000.00),
(23, 36, 22, 1, 4998000.00),
(24, 37, 11, 1, 130000.00),
(25, 38, 11, 1, 130000.00),
(26, 39, 14, 1, 93500.00),
(27, 40, 14, 1, 110000.00),
(28, 41, 8, 1, 505750.00),
(29, 42, 24, 1, 2997000.00),
(30, 43, 23, 1, 596000.00),
(31, 44, 23, 1, 596000.00),
(32, 45, 23, 1, 596000.00),
(33, 46, 24, 1, 2997000.00),
(34, 47, 24, 1, 2997000.00),
(35, 48, 12, 1, 125000.00),
(36, 49, 18, 1, 10000000.00),
(37, 50, 23, 1, 596000.00),
(38, 51, 23, 1, 596000.00),
(39, 52, 23, 1, 596000.00),
(40, 53, 23, 1, 596000.00),
(41, 54, 11, 1, 130000.00),
(42, 55, 11, 1, 130000.00),
(43, 56, 11, 1, 130000.00),
(44, 57, 11, 1, 130000.00),
(45, 58, 23, 1, 596000.00),
(46, 59, 23, 1, 596000.00),
(47, 60, 23, 1, 596000.00),
(48, 61, 24, 1, 2997000.00),
(49, 75, 26, 1, 170000.00),
(50, 77, 26, 1, 170000.00),
(51, 78, 26, 1, 170000.00),
(52, 84, 34, 1, 450000.00),
(53, 85, 24, 1, 2997000.00),
(54, 86, 34, 1, 450000.00),
(55, 86, 33, 1, 300000.00),
(56, 87, 21, 1, 15000000.00),
(57, 87, 20, 1, 80000000.00),
(58, 87, 17, 1, 9000000.00),
(59, 87, 16, 1, 14998000.00),
(60, 89, 34, 1, 450000.00),
(61, 90, 34, 1, 450000.00),
(62, 91, 34, 1, 450000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `month` tinyint(4) NOT NULL,
  `year` smallint(6) NOT NULL,
  `base_salary` decimal(12,2) DEFAULT 0.00,
  `bonus` decimal(12,2) DEFAULT 0.00,
  `deductions` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payrolls`
--

INSERT INTO `payrolls` (`id`, `user_id`, `month`, `year`, `base_salary`, `bonus`, `deductions`, `created_at`) VALUES
(5, 7, 5, 2026, 6000000.00, 300000.00, 0.00, '2026-05-14 17:57:34'),
(6, 5, 5, 2026, 6000000.00, 500000.00, 0.00, '2026-05-14 17:57:46'),
(7, 9, 5, 2026, 6000000.00, 1000000.00, 0.00, '2026-05-14 17:57:59'),
(8, 4, 5, 2026, 6000000.00, 500000.00, 0.00, '2026-05-14 17:58:16'),
(9, 11, 4, 2026, 5500000.00, 0.00, 0.00, '2026-05-15 04:37:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `pet_code` varchar(50) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `species` varchar(50) NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('male','female','unknown') DEFAULT 'unknown',
  `color` varchar(100) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `pets`
--

INSERT INTO `pets` (`id`, `pet_code`, `customer_id`, `name`, `species`, `breed`, `age`, `gender`, `color`, `weight`, `image`, `created_at`) VALUES
(1, 'PET-MOS57X', 12, 'MiMI', 'Mèo', 'Mèo anh lông ngắn', 12, 'female', 'Xám', 3.50, 'pets/1781256580_Meo-Anh-Long-Ngan.jpg', '2026-06-12 09:29:40'),
(2, 'PET-OAUHB9', 12, 'HeoChan', 'Chó', 'Akita', 10, 'male', 'Vàng', 6.70, 'pets/1781257248_Akita.jpg', '2026-06-12 09:40:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pet_health_logs`
--

CREATE TABLE `pet_health_logs` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `symptoms` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pet_milestones`
--

CREATE TABLE `pet_milestones` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `milestone_date` date NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pet_vaccinations`
--

CREATE TABLE `pet_vaccinations` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `vaccine_name` varchar(150) NOT NULL,
  `vaccinated_date` date NOT NULL,
  `next_due_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `appointment_id` int(11) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL COMMENT 'CÔn n?ng (kg)',
  `temperature` decimal(4,2) DEFAULT NULL COMMENT 'ThÔn nhi?t (░C)',
  `batch_number` varchar(50) DEFAULT NULL COMMENT 'S? l¶ v?c-xin',
  `veterinarian_name` varchar(100) DEFAULT NULL COMMENT 'Bßc si ph? trßch',
  `test_result` text DEFAULT NULL COMMENT 'K?t qu? xÚt nghi?m & SÓng l?c',
  `reaction_notes` text DEFAULT NULL COMMENT 'Theo d§i ph?n ?ng sau tiÛm',
  `is_emailed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pet_vaccinations`
--

INSERT INTO `pet_vaccinations` (`id`, `pet_id`, `vaccine_name`, `vaccinated_date`, `next_due_date`, `notes`, `created_at`, `appointment_id`, `weight`, `temperature`, `batch_number`, `veterinarian_name`, `test_result`, `reaction_notes`, `is_emailed`) VALUES
(1, 1, 'Tiêm phòng & xét nghiệm', '2026-06-13', '2026-06-14', 'Kết quả xét nghiệm: bthg | Ghi chú: tiêm vc3', '2026-06-13 05:54:05', 62, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 1, 'Tiêm phòng & xét nghiệm', '2026-06-13', NULL, 'Kết quả xét nghiệm: a | Ghi chú: a', '2026-06-13 05:56:00', 63, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, 1, 'Tiêm phòng & xét nghiệm', '2026-06-13', NULL, 'qqqq', '2026-06-13 15:12:02', 64, 3.50, 38.50, 'VC011', 'Heo men', 'tốt', 'qqeqe', 0),
(4, 1, 'V?c-xin 7 b?nh (Mui 1)', '2025-01-10', '2025-02-10', 'BÚ kh?e m?nh, kh¶ng ph?n ?ng ph?', '2026-06-13 16:03:35', NULL, NULL, NULL, NULL, 'Bs. Tuan', NULL, NULL, 0),
(5, 1, 'V?c-xin 7 b?nh (Mui 2)', '2025-02-10', '2025-03-10', 'BÚ hoi l? d? nh? sau tiÛm, dÒ h?t sau 1 ngÓy', '2026-06-13 16:03:35', NULL, NULL, NULL, NULL, 'Bs. Tuan', NULL, NULL, 0),
(6, 1, 'V?c-xin D?i (Rabies)', '2025-03-10', '2026-03-10', 'TiÛm ph‗ng d?i hÓng nam', '2026-06-13 16:03:35', NULL, NULL, NULL, NULL, 'Bs. Tuan', NULL, NULL, 0),
(7, 2, 'V?c-xin Tai Xanh (PRRS)', '2025-02-15', '2025-08-15', 'Mui 1 cho heo', '2026-06-13 16:03:35', NULL, NULL, NULL, NULL, 'Bs. Tuan', NULL, NULL, 0),
(8, 1, 'Advocate (Bayer)', '2026-06-13', NULL, NULL, '2026-06-13 16:06:50', 67, NULL, NULL, NULL, 'Heo men', 'tốt', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiry_date` date DEFAULT NULL,
  `batch_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `image`, `created_at`, `expiry_date`, `batch_number`) VALUES
(6, 1, 'Royal Canin Medium Adult', 'Mô tả: Thức ăn khô dành cho chó trưởng thành giống vừa (11–25kg), hỗ trợ tiêu hóa khỏe mạnh, tăng cường miễn dịch.\r\n\r\nĐặc điểm nổi bật: hạt nhỏ dễ nhai, giàu protein chất lượng cao, bổ sung omega-3 cho da và lông bóng mượt.', 630000.00, 196, '1778744184_thucanpet1.png', '2026-05-14 07:36:24', NULL, NULL),
(7, 1, 'Royal Canin Maxi Puppy 15kg', 'Dành cho chó con từ 2–15 tháng tuổi, giàu DHA và canxi.', 1750000.00, 200, '1778744336_thucanpet2.png', '2026-05-14 07:38:56', NULL, NULL),
(8, 1, 'SmartHeart Adult Beef Flavor 20kg', 'Thức ăn khô vị bò cho chó trưởng thành, giàu omega-3 và vitamin E.', 595000.00, 199, '1778744375_thucanpet3.png', '2026-05-14 07:39:35', NULL, NULL),
(9, 1, 'Ganador Premium Adult Lamb & Rice 20kg', 'Công thức thịt cừu và gạo, phù hợp cho chó nhạy cảm với protein gà.', 1090000.00, 200, '1778744421_thucanpet4.png', '2026-05-14 07:40:21', NULL, NULL),
(10, 1, 'Reflex Plus Puppy Chicken 3kg', 'Thức ăn khô cao cấp cho chó con, vị gà, bổ sung prebiotic và DHA.', 350000.00, 194, '1778744454_thucanpet5.png', '2026-05-14 07:40:54', NULL, NULL),
(11, 2, 'Royal Canin Kitten', 'Thức ăn khô cho mèo con từ 2–12 tháng tuổi, giàu DHA và canxi, hỗ trợ phát triển xương và trí não.', 130000.00, 194, '1778745042_thucanmeo1.png', '2026-05-14 07:50:42', NULL, NULL),
(12, 2, 'Royal Canin Indoor 27', 'Dành cho mèo trưởng thành nuôi trong nhà, công thức giảm mùi phân và hỗ trợ tiêu hóa.', 125000.00, 199, '1778745086_thucanmeo2.png', '2026-05-14 07:51:26', NULL, NULL),
(13, 2, 'Royal Canin Mother & Babycat', 'Thức ăn cho mèo mẹ và mèo con mới cai sữa, hạt nhỏ dễ ăn, hỗ trợ miễn dịch.', 130000.00, 200, '1778745150_thucanmeo3.png', '2026-05-14 07:52:30', NULL, NULL),
(14, 2, 'Whiskas Pate Cho Mèo Con', 'Pate cho mèo con từ 2–12 tháng tuổi, giàu DHA và taurine, giúp mắt sáng khỏe.', 110000.00, 190, '1778745233_thucanmeo4.png', '2026-05-14 07:53:53', NULL, NULL),
(15, 10, 'Akita Inu (犬) – chó vùng Akita, Nhật Bản.', 'Ngoại hình: Cao 60–75 cm, nặng 35–60 kg, lông kép dày, đuôi cuộn tròn trên lưng.\r\nTính cách: Trung thành, thông minh, độc lập, gắn bó sâu sắc với chủ.\r\nSức khỏe: Khỏe mạnh, chịu lạnh tốt, cần không gian rộng để vận động.\r\nBiểu tượng văn hóa: Gắn liền với câu chuyện Hachiko – chú chó trung thành chờ chủ suốt 9 năm.', 29997000.00, 200, '1778749361_Akita.jpg', '2026-05-14 09:02:41', NULL, NULL),
(16, 10, 'Central Asian Shepherd Dog (Alabai).', 'Ngoại hình: Thân hình to lớn, cơ bắp săn chắc, đầu to, mõm rộng, mắt sâu.\r\nTính cách:\r\n + Trung thành tuyệt đối với chủ.\r\n + Thông minh, nhanh nhẹn, dễ huấn luyện.\r\n + Bản năng bảo vệ mạnh mẽ, đôi khi hơi hung hãn với người lạ.\r\nKhả năng thích nghi: Chịu lạnh rất tốt, chịu nóng trung bình.', 14998000.00, 199, '1778749579_Alabai.jpg', '2026-05-14 09:06:19', NULL, NULL),
(17, 10, 'American Bully', 'Ngoại hình: Thân hình cơ bắp, ngực rộng, đầu to vuông vức, lông ngắn mượt.\r\nTính cách: Thân thiện, trung thành, hiền hòa hơn Pit Bull, nhưng cần huấn luyện và xã hội hóa từ nhỏ.\r\nTuổi thọ: 10–14 năm.', 9000000.00, 199, '1778749768_American-Bully.jpg', '2026-05-14 09:09:28', NULL, NULL),
(18, 10, 'Chó Bichon', 'Ngoại hình: Bộ lông trắng muốt, xoăn tít như bông gòn; mắt đen tròn; dáng nhỏ gọn (3–5 kg).\r\nTính cách: Thân thiện, hoạt bát, thông minh, rất gắn bó với chủ.\r\nTuổi thọ: 12–15 năm.\r\nChăm sóc đặc biệt: Lông cần được chải và grooming thường xuyên (2–4 tuần/lần).', 10000000.00, 199, '1778749888_Bichon.jpg', '2026-05-14 09:11:28', NULL, NULL),
(19, 10, 'Border Collie', 'Thông minh & nhanh nhẹn: Rất giỏi trong các môn thể thao chó (agility, obedience).\r\nNăng lượng cao: Cần nhiều vận động, không phù hợp với người ít thời gian.\r\nTrung thành & tình cảm: Gắn bó chặt chẽ với chủ, thích làm việc và học hỏi.\r\nHuấn luyện: Dễ huấn luyện nhưng cần kiên nhẫn và nhất quán.', 17000000.00, 200, '1778776909_Border-Collie.jpg', '2026-05-14 09:12:31', NULL, NULL),
(20, 11, 'Khao-Manee', 'Thân thiện, tình cảm: Rất gắn bó với chủ, thích sự chú ý.\r\nThông minh, hiếu kỳ: Dễ huấn luyện, thích khám phá.\r\nNăng động: Cần không gian chơi đùa, phù hợp với gia đình yêu thích vận động.', 80000000.00, 199, '1778750033_Khao-Manee.jpg', '2026-05-14 09:13:53', NULL, NULL),
(21, 11, 'Mèo Thần Miến Điện (Birman)', 'Thân thiện, hiền hòa: Rất gắn bó với chủ, thích được vuốt ve.\r\nThông minh, dễ huấn luyện: Có thể học các trò đơn giản.\r\nNăng động vừa phải: Không quá hiếu động như mèo Xiêm, nhưng vẫn thích chơi đùa.\r\nPhù hợp: Với gia đình có trẻ nhỏ hoặc người lớn tuổi vì tính cách dịu dàng.', 15000000.00, 198, '1778750102_meo-than-mien-dien-Birman.jpg', '2026-05-14 09:15:02', NULL, NULL),
(22, 11, 'Mèo Ba Tư (Persian Cat)', 'Hiền lành, tình cảm: Rất gắn bó với chủ, thích được vuốt ve.\r\nÍt vận động: Thích nằm nghỉ ngơi, phù hợp với môi trường sống trong nhà.\r\nDễ nuôi: Không quá hiếu động, thích hợp với người bận rộn.\r\nCần chăm sóc lông thường xuyên: Chải lông mỗi ngày để tránh rối và rụng.', 4998000.00, 199, '1778750154_Meo-ba-tu.jpg', '2026-05-14 09:15:54', NULL, NULL),
(23, 11, 'Mèo Tuxedo', 'Thông minh, nhanh nhẹn: Nhiều nghiên cứu cho thấy mèo tuxedo thường lanh lợi hơn.\r\nThân thiện, tình cảm: Gắn bó với chủ, thích chơi đùa.\r\nNăng động: Thích khám phá, leo trèo.\r\nĐặc biệt: Có nhiều câu chuyện dân gian cho rằng mèo tuxedo mang lại may mắn.', 596000.00, 187, '1778750207_meo-tuxedo.jpg', '2026-05-14 09:16:47', NULL, NULL),
(24, 11, 'Mèo Anh lông ngắn (British Shorthair)', 'Hiền lành, điềm tĩnh: Rất gắn bó với chủ, ít quậy phá.\r\nThân thiện: Hòa đồng với trẻ em và các vật nuôi khác.\r\nÍt vận động: Thích nằm nghỉ ngơi, phù hợp nuôi trong nhà.\r\nThông minh: Dễ huấn luyện, nhưng đôi khi hơi “lười biếng”.', 2997000.00, 82, '1778750275_Meo-Anh-Long-Ngan.jpg', '2026-05-14 09:17:55', NULL, NULL),
(25, 12, 'Frontline Plus (Merial)', 'Nhỏ gáy diệt bọ chét, ve; hiệu lực 1 tháng', 150000.00, 1000, '1781258326_FrontlinePlus.jpg', '2026-06-12 09:58:46', NULL, NULL),
(26, 12, 'Advocate (Bayer)', 'Phòng giun sán, bọ chét, ve, bệnh tim; dạng spot-on', 200000.00, 996, '1781258458_thuuoc2.jpg', '2026-06-12 10:00:58', NULL, NULL),
(27, 13, 'Nobivac DHPPi', 'Vắc xin 4 bệnh cốt lõi cho chó, được sử dụng rất phổ biến trong các phòng khám thú y.\r\nPhòng bệnh:\r\nCare (Distemper)\r\nViêm gan truyền nhiễm\r\nParvovirus\r\nParainfluenza', 250000.00, 1000, '1781367492_xmbJ5HIKYc3BGlAd6o7Mo2FjP6Y16TcuFVuuVqJouTTefs9-1KDlkIgPZ_v_QHyW-g6DzYcZp3vYzYzcoCb6NbD0QIG5VwJnf2TDygKqGPNgYHtSuV2gF6c_4uz4FrvJzlDl9dH6ZsnRX-7RShu8witAq7dDMk2gEkMQUi5uhrQ.jpg', '2026-06-13 16:09:03', '2027-06-13', 'VNG-2025A'),
(28, 13, 'Nobivac Rabies', 'Vắc xin phòng dại bắt buộc cho chó và được khuyến nghị cho mèo.', 120000.00, 200, '1781367769_VviVIISiiSjtJ0Xj6Qrg_GfHDsZBB3knVUbNiSAxN4VmqAwhy72gdzDWVHN5Ww7k74H8tpQimp1kHd9WMiLAJ08wBtJs4Uy1qd_scKtKZ1FQBr1IvGkB0QKmrJD4DVabJ1IJeOI_LlcOb25PpTh4iPc_UL3GaXKpzPTd9czFxFc.jpg', '2026-06-13 16:09:03', '2027-06-13', 'FEL-2026B'),
(29, 13, 'Vanguard Plus', 'Dòng vắc xin nổi tiếng của Zoetis, thường dùng cho chó con.\r\nPhòng bệnh:\r\nCare\r\nViêm gan\r\nParvovirus\r\nParainfluenza', 280000.00, 100, '1781367873_oszpPgh0uFb3VpS5vDVJ5mBCmZnuZedjxOnuGGRWoxueaHPE55zuet8Hsu7zm4vJlSjZ9sKo9neu67ykBEB7UUyYgEIT9AUFTje_k-vvbKdEKSyrp3VMOF8xeewQfYhwUY4kwPyx2mqQH4ArFjrSD7A1eFIbUhGSffnKqFui1GE.jpg', '2026-06-13 16:09:03', '2027-06-13', 'RAB-2026C'),
(30, 13, 'Vanguard HTLP 5/CV-L (V8)', 'Loại vắc xin đa giá bảo vệ chó trước nhiều bệnh truyền nhiễm nguy hiểm.\r\nPhòng bệnh:\r\nCare\r\nViêm gan\r\nParvovirus\r\nParainfluenza\r\nCoronavirus\r\nLeptospira', 300000.00, 300, '1781368045_7j_7LAKDJORQUdMQwb0szYuNmq5mh8ME8cJwUo7qa5CMaoHEorP72BsGh4HySp4F3SA09LRxlxf8EBWEu3IMLKwwI3VU8P7v077_olcUs-KBg5bn6kLpURXMhgj-skaktoguhBz3yy9pelEHpwegisa3-VLCPygDe1TUchmSoIk.jpg', '2026-06-13 16:27:25', '2027-07-20', NULL),
(31, 13, 'Vanguard Plus 5 L4 CV (V10)', 'Một trong những loại vắc xin phổ biến nhất cho chó nuôi gia đình.\r\nPhòng 10 bệnh truyền nhiễm phổ biến ở chó', 350000.00, 1000, '1781368154_vo93U5E45RIh41p-lNdJq2OFgxIs4C976Mns_crYzrS_wY-unkSu1u2w_TY4ITnEfGLvg5qKBNc16BggD36B5nDfA7KMj5IhVUMzBnLhlh6aTn7qUlAQDq_-WqG1Q1_AApNqhUDRnVJpnP8Ml_o6ObUzILjnk1pN-Uu7bUf6g9k.jpg', '2026-06-13 16:29:14', '2028-07-13', NULL),
(32, 13, 'Nobivac KC', 'Khuyến nghị cho chó thường xuyên tiếp xúc với nhiều chó khác hoặc gửi khách sạn thú cưng.\r\nPhòng bệnh:\r\nHo cũi chó (Kennel Cough)', 250000.00, 500, '1781368252_HTm5Ors6BZgk75h0U1uPRmx1SRqLOLGxXpLzn0oianrLaRsKloygd7-XvEGqDNg-VUadSVa5H0avTKFUuW_iUq8cVSx8jlHb2nwgjLxX5fFi0_Wya57nbJqWTTfI2Bz3nHPuTHG587sukC75GquHLttASor2TrT7nkNo-mFjZbA.jpg', '2026-06-13 16:30:52', '2027-06-17', NULL),
(33, 13, 'Purevax RCP', 'Vắc xin 3 bệnh cốt lõi cho mèo, được sử dụng rộng rãi tại các phòng khám thú y.\r\nPhòng bệnh:\r\nRhinotracheitis\r\nCalicivirus\r\nPanleukopenia', 300000.00, 999, '1781368330_RHpH1AO_8U3Rua6KZXPI5BYRpL-AIqqMEi3-lZdZ-zATdAC5b2FXInEXLddciAt1HGt6cS2j-RTcExOWVyG7HLizhK-dfmXyo8qFC39gS2gFKJFv0FsoOYvyz04zSCpwBF6pZSAZ18RwkQ-58IyeSqBiyZQMaLcXpvAbH5h3NhQ.jpg', '2026-06-13 16:32:10', '2027-07-22', NULL),
(34, 13, 'Purevax RCP-FeLV', 'Dành cho mèo có nguy cơ tiếp xúc với mèo khác hoặc mèo nuôi thả.\r\nPhòng bệnh:\r\nRhinotracheitis\r\nCalicivirus\r\nPanleukopenia\r\nBạch cầu mèo (FeLV)', 450000.00, 596, '1781368410_z0rwgQy0U2O8JkC-mnCM80aMd3n4-yr-7pYmAAaKanwyrrWnKdOKFkQBPpqWaBfquK3A9iURY5PvIze1s9EkdN1-4VoGl01-e2tQw5cKVix97tR567DcwYLdlHcJQ9rWrOu3qiDAy8YCKlgRtHufI3kUojCHlrZNMXHrHl4aqsE.jpg', '2026-06-13 16:33:30', '2027-07-13', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `created_at`) VALUES
(1, 24, '1781255366_add_0_Khao-Manee.jpg', '2026-06-12 09:09:26'),
(2, 24, '1781255366_add_1_Maine-coon.jpg', '2026-06-12 09:09:26'),
(3, 24, '1781255366_add_2_Maltese.jpg', '2026-06-12 09:09:26'),
(4, 24, '1781255366_add_3_Meo-Anh-Long-Dai.jpg', '2026-06-12 09:09:26'),
(5, 24, '1781255366_add_4_Meo-Anh-Long-Ngan.jpg', '2026-06-12 09:09:26'),
(6, 23, '1781255635_add_0_Akita.jpg', '2026-06-12 09:13:55'),
(7, 23, '1781255635_add_1_Alabai.jpg', '2026-06-12 09:13:55'),
(8, 23, '1781255635_add_2_American-Bully.jpg', '2026-06-12 09:13:55'),
(9, 23, '1781255635_add_3_Australian-Shepherd.jpg', '2026-06-12 09:13:55'),
(10, 23, '1781255635_add_4_Bac-Kinh.jpg', '2026-06-12 09:13:55'),
(11, 27, '1781367492_add_0_Gh8E49UJIk3YeUG-tLVbw18lE4j2wRF5ch-l7L2zQkJxpeE9wWD9wWY4vXTckJjj_gVwaO5pVtje7ztCAonPhlBECE1GV2qHV8IYUbB-Sfjv1wPzYRt5_eg2dE03wo4iA9BxKZiHcGPJKE2gt5voUbPSvL0B5seRI_JF8gOlkDHgicAeA2Vln4RKIJyOoEPt.jpg', '2026-06-13 16:18:12'),
(12, 28, '1781367746_add_0_vdv64rHmmTk7ownHmSH3onlnOBOJ7aHXytkSUtgXo0UR11Pz0LJ2U3zD5ON3RjJtLIsQ8fbLwsDyq-y30XGhZwZTMsaK_73asSHuTM7M6-CCFAl8B4LP8K0P4DnJgIPf-tANRM8sPPP-AmVX8OS2uodUTDKoeNJCu630lnap8us.jpg', '2026-06-13 16:22:26'),
(13, 28, '1781367746_add_1_lV56utuwMR4n4l8pBAlbLtHF4J_RJTb-b2SecAz6IOGVwDeoCa3MR3fVWsQwlCKQktPacT7jR4_UmZp7qO0r9-73qyrVbN27aeussyrg2LBEb7F2JUojN4N8U_QCaoIf6UOFtfzFYnyih_cJuGt0IGJiKG6WFEQvjBLOWI-vki8.jpg', '2026-06-13 16:22:26'),
(14, 29, '1781367873_add_0_DSqO_Bhuae05ZNFdjZt2N0viya9q3e2kmiIw7wg1J4xnw7OqOPsjWXkBlTvEpNjm35FC3Jawv4YTN9HTqcpOPPufT-yrOq7NeI0t1YufcVaWqu_x9EXbUEWMGCzOx7rWGNemLSe0Dxjl1JjXQCicE8sKQA48kWqw7zoEY987SCMoeuI2CyGbCKwGsPPZIMdV.jpg', '2026-06-13 16:24:33'),
(15, 29, '1781367873_add_1_Y4-D4x8aDLws2ZM5rhXaC4ozUGQDCKWfea0eqW9XvqfrzXn7JvhtzypEnIbQ5mTGJB1er56W8EiD9njqVTWY4UlTAuJCduVBpI-6WpPJY1ldDivk4t6Hmx2TZrVi4QooOuOJ0249xsWau7LkFeu63d3V5v0H62bWKqMOXPTrSTb1skr0b8W4v-UqSR1yoMan.jpg', '2026-06-13 16:24:33'),
(16, 29, '1781367873_add_2_BUhZDtQRwlgDW0ohMjKdK-IXnk6FWxeQJ8xLTxZJJiTqL3L6bfNLEoMvyuJwVlszRnN4KZMEgcdlW_6WzfmdJittVrLsCSBvh5IsmPNZgrwVJ6VIAIkikugCuBfZDqpbjt50tHuNbPdgymNj-YiuxV4HOkNkpHHVoG27Ct5xtxg.jpg', '2026-06-13 16:24:33'),
(17, 30, '1781368045_add_0_jke0atscl3V8pXJgxbR_MuBeU1r3XJVhX24zRXDGhRcdNs0w12zfERpC3CLBUqiAFDBdrO4_4yFq7qhd5wBm8GzANnGRU7n9EzvGFPXWd3TtkdJ6j062xiTaK0GwkDBswFBinWtD_kUanl7tBHl-j9Q6kyenRWPbL2C_KkfDtafVQBWCMeXvbrnoHfC9WE4I.jpg', '2026-06-13 16:27:25'),
(18, 30, '1781368045_add_1_wHS1HkorrZxk7AA8QvQ9Qy7_qI8gPQwLg7SdIKmJwhEZgYw-arLgpidf5iY13aIIR6BE6On6IruzUM0dWNKwmYWiyp8qYUqDyRLPP9b_Kr_o9fgAGp-xUAda55Ls_WELeGc7GCrIgbU-83qU-X1ILMAVOrvOGJ7LbcX0dv3OnLw.jpg', '2026-06-13 16:27:25'),
(19, 30, '1781368045_add_2_M0bxmJYZsHjk2qVxl1GRaTlny8DbxPgNgbhST53-DCrHDeS74sxMI-OoRtoLUs7QMRK786CrfmS_Zi9Ing77f2um-_vIDRRIAjWPdms6zpx7RWyrj-5YTCGZXjtOisk50uRZ6tMoA5ttcKC89tFI-jeM57DwkH5OnyMCQd5Eh4U.jpg', '2026-06-13 16:27:25'),
(20, 31, '1781368154_add_0_fy5WwfpGOrDrDo2bCGbLhA4BpQ144PrrLygriMhq0lrJRcUfgrNtvtTM8oJN4Fzvcbp3pDKdH3MOIyo4rcWleD1TBGwROs3GvW0ehazoXE7Uj_dQCO6G8QOyt9IszpmdaNUPh_QKTZsoSVfa-fSZnC_hmy7flzOXrmHsXT9z_H4JzAsyvBcFkWkE4wppCI4D.jpg', '2026-06-13 16:29:14'),
(21, 31, '1781368154_add_1_RXrkdLj8D8gakgizp1yHmMTztF5qLGOprlvlKEWGRZaejRJ_QcIpTDHrG1XHIirOFY7kThRnntry9N0evNwoRiWNS7B4KkgbDC6o9ZZa3XToP3K_ySKl3eh34vl8FMw1FO_jB7UqxfL0n0mmLI8MI1wcCyeixqdl8KXLpYmBk2cPPY39tLOfsyKb7iVO_rQj.jpg', '2026-06-13 16:29:14'),
(22, 31, '1781368154_add_2_AoSsCMIze4QPNbhOjFNK6SwQtafAgAy5fSpAq1jutzT-2RgK03Gk8c3iByKQ25lX_AfYIlX5T9EOGZTiJgqpi7L0dWT4zkJzTPzBJok9xkagi0OO9ebSQmg45JILJICeE_9FoPexErfR52DnwAiULPJW4uxxlWDPT8apI387VDA.jpg', '2026-06-13 16:29:14'),
(23, 31, '1781368154_add_3_6DrLG4CJeOk2YIGTfm3WKlSs2NoC-JMkSEqALC63AQQ0gfQn9TZXudsUeBwqykW4oHUJ-9n_H-R4hD_STPeBjSgAgr54laL5k0uvFr62Yjk-j2-zkDS05c0n714undDO2Ye2HJvYO4fqHA0BJ_cP_DOv5hxHodCVn5FX0CA0LKA.jpg', '2026-06-13 16:29:14'),
(24, 32, '1781368252_add_0_g-APZqOuEBYTbrHUB5BSZ2C7kvHN1wug5eQXSt9ul6ucP0owh3TvYK6uSFRPJc4wTUD5AxsFB5-BTl6hdvdBn5x46SOylMFfSDOI5ZOQ92s_KuRu_TV3ay1-VWnYpXxzUDjfC0pi7S_Y9fGxZqLFollMwyS5h8MnxNydUJYGxJk.jpg', '2026-06-13 16:30:52'),
(25, 32, '1781368252_add_1_aO77M3qQn7iUPGEw4d6TunmzhorfQ1-bqMnYdWwmGvPjIAXUTuDz3Zdzz2m3eJrLHsV-no8HIblK9OZ87fuI1zqvCriV2dvqOWIBoyIcnnJkoNVu1qJ-4-X4jE6YXeu4fAguBUgs-tJI5nweEYEg-uiNUD7AIhxXhhiSvklT-x8.jpg', '2026-06-13 16:30:52'),
(26, 33, '1781368330_add_0_g5pR75w6BsOUpqmC0ytD7jHA5lBLuWawJoJvkcdk8092dqWaDmjLv6GBkYtWN4t23SfwggiJQzbWwYrrI7zKc2UQAbMIsohEm8p6rsdiXPceHgYLlvEjHlhb4x-f3zUnXfHMK8X8NuGfgcfokik8XJh4wj_AKljtnu-17KEFcA4.jpg', '2026-06-13 16:32:10'),
(27, 33, '1781368330_add_1_iKT2ejFWdrWms66kAP_4O7C-S4eeVAoPEHjMYoLCUTth7fWqOAHzm4nHTpm9YnGOj0F8UsQoHdg1_9mYfvIPDFDunAz_L2XhVdmJbSdcgZHGJm5UVLI3m_X2C2BwpZKGSbJSbDdhtQLuY8_kNHabD5DOBY02dxslR72pUGWkDZE.jpg', '2026-06-13 16:32:10'),
(28, 34, '1781368410_add_0_tLSTRlrsRuSX6-C5LCKeB5cI8PuXdv3cmmf3eHtPN7o9JXvqCu4tJievDbTx14a4mnaDjWf9ltZ_K5_IM7fR9cg-0_kfvNLwgl79RmErBbnmN7D7oW54bnI2D0YXLCQgXwsi8C6eaYOGhLRWtJDEtOhxx4gM2WUOudliBHgdp5MmBWyKNW5GHOrTjBsXm1ml.jpg', '2026-06-13 16:33:30'),
(29, 34, '1781368410_add_1_FergZyl0GoChFAe_ROwtvbcG5r8LW__i72UKCTivWkzg9yuy36DduP0hm08SUmPlkTC-49U3SNAKfnIjCMP2e0Efo7JgPlhmYHT510jA0rBYfKlFJ9WoF44pHFKYtRy_d7q4_cZ6LwDzbsSnPQubVbdg1p7wCeFQUmosdf8MtVDbdGrImdgFt0qppEWiFecR.jpg', '2026-06-13 16:33:30'),
(30, 34, '1781368410_add_2_Yi7e07Bvb8yV2sx_DWGCodW_12kSPaEVBbMh5g-gDoGZueeh0MMiP1ofbMIghwVGXqAgNKErrjcA2H9yU0ebsNbiXi5PpI6ifr5eBeYF0njToo3aXPDRkg6OB7g7xmeYF8AP2MKhHt1ZJuKz1pr8QxdrQqKbHD6qt2ZWWgou14I.jpg', '2026-06-13 16:33:30'),
(31, 34, '1781368410_add_3_yMYuco1DDnCzL1ac6aqB9hvQmX8K-w0Ad12YuBFneyxE1WHw6sH_2sLTcElllUGLj7uHJ-abwlwm-orF0SKWqLF8OurGSRo7JM17HjJe6AJf3ng8CgX_H60B4rV4gqUzN0fu2LGPBFggLwn-fAwiftC5g6vl9XjXgWNaV_suLlQ.jpg', '2026-06-13 16:33:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 24, 12, 5, 'ngoan, đẹp', '2026-05-29 13:29:36'),
(2, 24, 12, 5, 'ngoan, đẹp', '2026-05-29 13:30:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_minutes` int(11) DEFAULT 30,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `services`
--

INSERT INTO `services` (`id`, `category_id`, `name`, `description`, `price`, `duration_minutes`, `image`) VALUES
(8, 5, 'Khám bệnh cho thú cưng', 'Khám tổng quát: kiểm tra sức khỏe định kỳ, đo cân nặng, kiểm tra da, răng, mắt, tai,...', 0.00, 30, '1778748985_khambenh.PNG'),
(9, 5, 'Điều trị bệnh', 'Điều trị bệnh : viêm da, tiêu hóa, hô hấp, ký sinh trùng,...', 0.00, 30, '1778748994_dieutri.PNG'),
(10, 5, 'Tiêm phòng & xét nghiệm', 'Tiêm phòng & xét nghiệm: vaccine phòng bệnh, xét nghiệm máu, siêu âm,...', 0.00, 30, '1778749007_tiemphong.PNG'),
(11, 5, 'Phẫu thuật', 'Phẫu thuật cơ bản: triệt sản, xử lý vết thương, ....', 0.00, 30, '1778749021_phauthuat.PNG'),
(12, 6, 'Tắm & spa', 'Tắm & spa: tắm rửa, sấy khô, massage thư giãn,...', 100000.00, 30, '1778749030_spa.png'),
(13, 6, 'Cắt tỉa lông & móng', 'Cắt tỉa lông & móng: tạo kiểu lông, cắt móng an toàn,...', 100000.00, 18, '1778749038_cattia.PNG'),
(14, 6, 'Vệ sinh', 'Vệ sinh chuyên sâu: làm sạch tai, răng, tuyến hôi,...', 100000.00, 19, '1778749050_vesinh.PNG'),
(15, 6, 'Dinh dưỡng', 'Dinh dưỡng: tư vấn chế độ ăn phù hợp,...', 0.00, 10, '1778749064_dinhduong.png'),
(16, 7, 'Huấn luyện cơ bản', 'Huấn luyện cơ bản: nghe lệnh, đi vệ sinh đúng chỗ, ngồi/đứng theo hiệu lệnh.', 150000.00, 0, '1778751112_hlcoban.jpg'),
(17, 7, 'Huấn luyện nâng cao', 'Huấn luyện nâng cao: kỹ năng bảo vệ, giao tiếp xã hội.', 300000.00, 0, '1778751128_hlnangcao.jpg'),
(18, 7, 'Huấn luyện đặc biệt', 'Huấn luyện đặc biệt: cho thú cưng tham gia biểu diễn, thi đấu.', 1000000.00, 0, '1778751135_hldacbiet.jpg'),
(19, 8, 'Chuyên nghiệp', 'Studio chuyên nghiệp: chụp trong phòng với ánh sáng, phông nền đẹp.', 100000.00, 15, '1778751985_chupanh1.jpg'),
(20, 8, 'Ngoài trời', 'Ngoài trời: ghi lại khoảnh khắc tự nhiên khi đi dạo, chơi đùa.', 150000.00, 20, '1778751995_chupanh2.jpg'),
(21, 8, 'Gói kỷ niệm', 'Gói kỷ niệm: album ảnh theo chủ đề (sinh nhật, lễ hội).', 200000.00, 30, '1778752008_chupanh3.jpg'),
(23, 9, 'Trông giữ ngắn hạn', 'Trông giữ ngắn hạn: trông giữ trong ngày', 0.00, 30, '1778752081_tronggiu1.jpg'),
(24, 9, 'Trông giữ dài hạn', 'Trông giữ dài hạn: nhiều ngày, có chế độ ăn uống và vận động đầy đủ.', 0.00, 30, '1778752088_tronggiu.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff','doctor','customer','cashier','manager') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `coins` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `avatar`, `google_id`, `facebook_id`, `role`, `created_at`, `is_verified`, `otp_code`, `otp_expires_at`, `coins`) VALUES
(1, 'Admin', 'admin@petshop.com', '$2y$10$HKQlCXv240KMbWeEV0pJvOoElOLbTtwvaKutoiGrp0w4GmBGIeCrS', NULL, NULL, NULL, 'admin', '2026-05-14 03:22:33', 1, NULL, NULL, 0),
(4, 'Trần Công Tử', 'aaa@gmail.com', '$2y$10$P5sGMfLWpIOF/.imLv4N4.bDoTrevrOeMypOkxIHOoWObiv2xSKg.', NULL, NULL, NULL, 'doctor', '2026-05-14 09:58:55', 1, NULL, NULL, 0),
(5, 'Heo men', 'bbb@gmail.com', '$2y$10$TUIUDLID.j.QLtzoO7al1e0YbpZWli.TyRQPrP8Q9Ck804aBbWata', NULL, NULL, NULL, 'doctor', '2026-05-14 09:59:25', 1, NULL, NULL, 0),
(7, 'Chu Thúy Huệ', 'hue@gmail.com', '$2y$10$2L07Tofo0oMT34cCXqiyOeDgsff4Hd521lIe14TNyrxRXfGi.QmEO', NULL, NULL, NULL, 'cashier', '2026-05-14 17:09:40', 1, NULL, NULL, 0),
(9, 'Nguyễn Minh Tuấn', 'tuan@gmail.com', '$2y$10$ppTuwLxZa006rmtzKPj2uuuOohgoIEDnXMfr2buowHg5NwUkV6VI.', NULL, NULL, NULL, 'manager', '2026-05-14 17:25:17', 1, NULL, NULL, 0),
(11, 'Bùi Thùy Linh', 'nhanvien1@gmail.com', '$2y$10$ze1JGJHHwCespgDRJ9aohOHcujzQKa47X4WgK53BSlZ2GhnxyMW9u', NULL, NULL, NULL, 'staff', '2026-05-14 20:08:24', 1, NULL, NULL, 0),
(12, 'Nguyễn Văn A', 'khachhang1@gmail.com', '$2y$10$v1BvRLfXYGexYmASOEwxnOkkI7.UbnPw5QcS5zWcKQzvOLVNx8hTC', 'avatar_12_1781445526.jpg', NULL, NULL, 'customer', '2026-05-15 05:13:10', 1, NULL, NULL, 684),
(13, 'NGUYEN VAN A', 'abc@gmail.com', '$2y$10$Pq6ykPt9mi9w4tq571r1t.BOOHDDbFDVwfHtMV1haDGSRuNmklRoe', NULL, NULL, NULL, 'customer', '2026-06-11 03:20:33', 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_vouchers`
--

CREATE TABLE `user_vouchers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `unique_code` varchar(50) NOT NULL,
  `status` enum('active','used') DEFAULT 'active',
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user_vouchers`
--

INSERT INTO `user_vouchers` (`id`, `user_id`, `voucher_id`, `unique_code`, `status`, `used_at`, `created_at`) VALUES
(1, 12, 1, 'PETA0A1EB96', 'active', NULL, '2026-06-14 14:58:34'),
(2, 12, 1, 'PET435DE718', 'active', NULL, '2026-06-14 14:58:49'),
(3, 12, 1, 'PETFB74A4AD', 'active', NULL, '2026-06-14 14:59:22'),
(4, 12, 3, 'PET8FBE2252', 'used', '2026-06-14 15:02:02', '2026-06-14 14:59:31'),
(5, 12, 2, 'PET32605853', 'used', '2026-06-14 15:11:37', '2026-06-14 15:00:26'),
(6, 12, 5, 'PET551903B0', 'used', '2026-06-14 15:39:44', '2026-06-14 15:28:00'),
(7, 12, 2, 'PET2356CCC4', 'active', NULL, '2026-06-14 15:43:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `discount_type` enum('fixed','percent') NOT NULL DEFAULT 'fixed',
  `discount_amount` decimal(10,2) NOT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `min_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `category_id` int(11) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `usage_per_user` int(11) DEFAULT 1,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `cost_coins` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_combinable` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `title`, `code`, `description`, `start_date`, `end_date`, `discount_type`, `discount_amount`, `max_discount`, `min_order_value`, `category_id`, `usage_limit`, `usage_per_user`, `used_count`, `cost_coins`, `is_active`, `created_at`, `is_combinable`) VALUES
(1, 'Voucher Giảm 20K', NULL, 'Sử dụng để giảm 20.000đ cho đơn hàng hoặc dịch vụ.', NULL, NULL, 'fixed', 20000.00, NULL, 0.00, NULL, NULL, 1, 0, 10, 1, '2026-06-14 14:27:53', 0),
(2, 'Voucher Giảm 50K', NULL, 'Sử dụng để giảm 50.000đ cho đơn hàng hoặc dịch vụ.', NULL, NULL, 'fixed', 50000.00, NULL, 0.00, NULL, NULL, 1, 0, 25, 1, '2026-06-14 14:27:53', 0),
(3, 'Voucher Giảm 100K', NULL, 'Sử dụng để giảm 100.000đ cho đơn hàng hoặc dịch vụ.', NULL, NULL, 'fixed', 100000.00, NULL, 0.00, NULL, NULL, 1, 0, 50, 1, '2026-06-14 14:27:53', 0),
(5, 'Voucher Giảm 20%', NULL, 'Voucher Giảm 20% tối đa 2.000.000vnđ', NULL, NULL, 'percent', 20.00, 1997000.00, 0.00, NULL, NULL, 1, 0, 100, 1, '2026-06-14 15:26:52', 0),
(6, 'Giảm 50%', NULL, 'Giảm 50% cho tất cả sản phẩm', NULL, NULL, 'percent', 50.00, NULL, 0.00, NULL, NULL, 1, 0, 1000, 1, '2026-06-14 15:41:32', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ai_analyses`
--
ALTER TABLE `ai_analyses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Chỉ mục cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Chỉ mục cho bảng `appointment_reviews`
--
ALTER TABLE `appointment_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_id` (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Chỉ mục cho bảng `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coin_history`
--
ALTER TABLE `coin_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `health_records`
--
ALTER TABLE `health_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Chỉ mục cho bảng `health_record_prescriptions`
--
ALTER TABLE `health_record_prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `health_record_id` (`health_record_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `membership_benefits`
--
ALTER TABLE `membership_benefits`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `idx_orders_customer_status` (`customer_id`,`status`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pet_code` (`pet_code`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `pet_health_logs`
--
ALTER TABLE `pet_health_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Chỉ mục cho bảng `pet_milestones`
--
ALTER TABLE `pet_milestones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Chỉ mục cho bảng `pet_vaccinations`
--
ALTER TABLE `pet_vaccinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_reviews_product` (`product_id`);

--
-- Chỉ mục cho bảng `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`unique_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `voucher_id` (`voucher_id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT cho bảng `ai_analyses`
--
ALTER TABLE `ai_analyses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT cho bảng `appointment_reviews`
--
ALTER TABLE `appointment_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `coin_history`
--
ALTER TABLE `coin_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `health_records`
--
ALTER TABLE `health_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `health_record_prescriptions`
--
ALTER TABLE `health_record_prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `membership_benefits`
--
ALTER TABLE `membership_benefits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `pet_health_logs`
--
ALTER TABLE `pet_health_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pet_milestones`
--
ALTER TABLE `pet_milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pet_vaccinations`
--
ALTER TABLE `pet_vaccinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `user_vouchers`
--
ALTER TABLE `user_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ai_analyses`
--
ALTER TABLE `ai_analyses`
  ADD CONSTRAINT `ai_analyses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ai_analyses_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_4` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `appointment_reviews`
--
ALTER TABLE `appointment_reviews`
  ADD CONSTRAINT `appointment_reviews_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_reviews_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_reviews_ibfk_4` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `coin_history`
--
ALTER TABLE `coin_history`
  ADD CONSTRAINT `coin_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `health_records`
--
ALTER TABLE `health_records`
  ADD CONSTRAINT `health_records_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `health_records_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `health_records_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `health_record_prescriptions`
--
ALTER TABLE `health_record_prescriptions`
  ADD CONSTRAINT `health_record_prescriptions_ibfk_1` FOREIGN KEY (`health_record_id`) REFERENCES `health_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `health_record_prescriptions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `pet_health_logs`
--
ALTER TABLE `pet_health_logs`
  ADD CONSTRAINT `pet_health_logs_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `pet_milestones`
--
ALTER TABLE `pet_milestones`
  ADD CONSTRAINT `pet_milestones_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `pet_vaccinations`
--
ALTER TABLE `pet_vaccinations`
  ADD CONSTRAINT `pet_vaccinations_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pet_vaccinations_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `user_vouchers`
--
ALTER TABLE `user_vouchers`
  ADD CONSTRAINT `user_vouchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vouchers_ibfk_2` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
