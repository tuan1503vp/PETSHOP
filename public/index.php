<?php
// Cấu hình Session Cookie tự động hết hạn khi đóng trình duyệt (0)
session_set_cookie_params([
    'lifetime' => 0, // 0 = Hết hạn khi đóng trình duyệt
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Import cấu hình và các file core
require_once '../app/config/config.php';
require_once '../app/helpers/session_helper.php';
require_once '../app/helpers/Mailer.php';
require_once '../app/core/Database.php';
require_once '../app/core/Controller.php';
require_once '../app/core/App.php';

// Khởi tạo ứng dụng
$app = new App();
