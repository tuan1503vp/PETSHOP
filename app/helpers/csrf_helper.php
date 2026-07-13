<?php
// app/helpers/csrf_helper.php

/**
 * Generate CSRF token and store it in session
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate the hidden input field for CSRF
 */
function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Verify CSRF token from POST request
 */
function verify_csrf_token() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Bỏ qua kiểm tra CSRF cho các yêu cầu Webhook/IPN bên thứ ba (ví dụ VNPay IPN)
        $url = $_GET['url'] ?? '';
        if (strpos($url, 'vnpay/ipn') !== false) {
            return;
        }

        // Bỏ qua kiểm tra CSRF cho các API dạng JSON (đã có cơ chế bảo vệ CORS mặc định của trình duyệt)
        $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            return;
        }

        // Lấy token từ POST data hoặc HTTP header X-CSRF-Token (cho các thư viện AJAX tự động gửi)
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        if (!$token || $token !== $sessionToken) {
            flash('csrf_error', 'Yêu cầu không hợp lệ hoặc phiên làm việc đã hết hạn. Vui lòng tải lại trang.', 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4');
            
            // Redirect về trang trước hoặc trang chủ
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (defined('URLROOT') ? URLROOT : '/');
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
}

/**
 * Tự động chèn trường ẩn csrf_token vào tất cả các form POST trong HTML output buffer
 */
function inject_csrf_token($buffer) {
    if (empty($buffer)) {
        return $buffer;
    }
    
    $token = generate_csrf_token();
    
    // Tìm các thẻ <form ... method="post" ...> hoặc <form ... method="POST" ...>
    // Sử dụng preg_replace_callback để chèn thêm csrf_field vào ngay sau thẻ mở form
    return preg_replace_callback('/<form([^>]*method=["\']post["\'][^>]*)>/i', function($matches) use ($token) {
        $formTag = $matches[0];
        $csrfInput = "\n" . '<input type="hidden" name="csrf_token" value="' . $token . '">';
        return $formTag . $csrfInput;
    }, $buffer);
}
