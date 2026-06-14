<?php

function flash($name = '', $message = '', $class = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            
            // Xác định loại toast dựa vào class cũ (để tương thích ngược)
            $type = 'success';
            if (strpos($class, 'red') !== false || strpos($class, 'danger') !== false) {
                $type = 'error';
            } elseif (strpos($class, 'yellow') !== false || strpos($class, 'warning') !== false) {
                $type = 'warning';
            }
            
            echo '<div class="custom-toast hidden" data-type="' . $type . '" data-message="' . htmlspecialchars($_SESSION[$name], ENT_QUOTES) . '"></div>';
            
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

// Tự động chuyển hướng tài khoản quản lý sang trang quản lý khi truy cập giao diện khách hàng
function redirectManagement() {
    if (isLoggedIn() && isset($_SESSION['user_role'])) {
        $managementRoles = ['admin', 'staff', 'doctor', 'cashier', 'manager'];
        if (in_array($_SESSION['user_role'], $managementRoles)) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }
    }
}
