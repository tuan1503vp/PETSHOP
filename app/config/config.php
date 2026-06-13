<?php

// Thiết lập múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Tên thư mục gốc của project
define('APPROOT', dirname(dirname(__FILE__)));

// URL gốc của ứng dụng (Tự động nhận diện môi trường Local/Host để tránh lỗi định tuyến)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
             || ($_SERVER['SERVER_PORT'] ?? 80) == 443 
             || (strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '', 'https') === 0)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if ($host === 'localhost' || strpos($host, '127.0.0.1') !== false) {
    define('URLROOT', $protocol . $host . '/PETSHOP');
} else {
    define('URLROOT', $protocol . $host);
}

// Tên Website
define('SITENAME', 'PetShop - Quản Lý & Chăm Sóc Thú Cưng');

// Tải các API key và mật khẩu bảo mật từ file secrets.php (không tracked bởi git)
if (file_exists(APPROOT . '/config/secrets.php')) {
    require_once APPROOT . '/config/secrets.php';
}

// Cấu hình Database
if ($host === 'localhost' || strpos($host, '127.0.0.1') !== false) {
    // Môi trường Local (XAMPP trên máy tính)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'petshop_db');
} else {
    // Môi trường Live (InfinityFree Hosting)
    define('DB_HOST', 'sql302.infinityfree.com');
    define('DB_USER', 'if0_41982653');
    define('DB_PASS', 'pF5bZygy7oK');
    define('DB_NAME', 'if0_41982653_petshop_db');
}

// Cấu hình AI (OpenRouter API)
if (!defined('OPENROUTER_API_KEY')) {
    define('OPENROUTER_API_KEY', 'mock');
}

// Cấu hình SMTP Email (dự phòng - InfinityFree thường chặn cổng này)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); // 587 cho TLS/STARTTLS, hoặc 465 cho SSL
define('SMTP_USER', 'nmtvp11223311@gmail.com');
if (!defined('SMTP_PASS')) {
    define('SMTP_PASS', getenv('SMTP_PASS') ?: ''); // Mật khẩu ứng dụng Gmail
}
define('SMTP_FROM_EMAIL', 'nmtvp11223311@gmail.com');
define('SMTP_FROM_NAME', 'PETSHOP');

// Cấu hình Resend Email API
// Đăng ký miễn phí tại https://resend.com → API Keys → Create API Key
// Miễn phí 3000 email/tháng, hoạt động trên mọi host (dùng HTTPS, không cần cổng SMTP)
if (!defined('RESEND_API_KEY')) {
    define('RESEND_API_KEY', getenv('RESEND_API_KEY') ?: '');
}
