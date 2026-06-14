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
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Log it or handle it
            flash('error', 'Token bảo mật không hợp lệ hoặc đã hết hạn. Vui lòng thử lại.', 'error');
            // Gửi một header redirect lại trang trước đó hoặc trang chủ
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
