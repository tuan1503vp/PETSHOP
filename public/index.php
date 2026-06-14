<?php
// Bật hiển thị lỗi dựa trên môi trường (Local / Host) để bảo mật và tránh làm vỡ giao diện
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if ($host === 'localhost' || strpos($host, '127.0.0.1') !== false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Cấu hình Session Cookie tự động hết hạn khi đóng trình duyệt (0)
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || ($_SERVER['SERVER_PORT'] ?? 80) == 443 
            || (strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '', 'https') === 0);

// Xác định domain cho cookie (loại bỏ cổng nếu có, ví dụ localhost:8080 -> localhost)
$domain = parse_url('http://' . $host, PHP_URL_HOST);
$cookieDomain = ($domain === 'localhost' || $domain === '127.0.0.1') ? null : $domain;

session_set_cookie_params([
    'lifetime' => 0, 
    'path' => '/',
    'domain' => $cookieDomain,
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Lax'
]);
// Sử dụng @ để tắt các cảnh báo không mong muốn về thư mục session của nhà cung cấp hosting (ví dụ trên InfinityFree)
@session_start();

// Import cấu hình và các file core
require_once '../app/config/config.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/Mailer.php';
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/App.php';

// Khởi tạo ứng dụng
$app = new App();
