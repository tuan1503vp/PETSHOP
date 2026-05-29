<?php

// Tên thư mục gốc của project
define('APPROOT', dirname(dirname(__FILE__)));

// URL gốc của ứng dụng (Tự động nhận diện host và port để tránh lỗi mất ảnh/styles)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('URLROOT', $protocol . $host . '/PETSHOP');

// Tên Website
define('SITENAME', 'PetShop - Quản Lý & Chăm Sóc Thú Cưng');

// Cấu hình Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'petshop_db');

// Cấu hình AI (OpenRouter API)
define('OPENROUTER_API_KEY', 'NHAP_API_KEY_CUA_CAU_VAO_DAY');
