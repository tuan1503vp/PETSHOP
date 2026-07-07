<?php
/**
 * Simple Auto Deployment script for PETSHOP
 * URL: https://petshop.id.vn/deploy.php
 */

// Đặt Header để hiển thị text rõ ràng
header('Content-Type: text/plain; charset=utf-8');

echo "=== ĐANG KHỞI CHẠY TIẾN TRÌNH AUTO DEPLOY ===\n\n";

// Chạy lệnh git pull
$output = [];
$return_var = 0;
exec('git pull 2>&1', $output, $return_var);

// Hiển thị kết quả ra màn hình
echo "Lệnh thực thi: git pull\n";
echo "Kết quả trả về:\n";
echo implode("\n", $output) . "\n\n";

if ($return_var === 0) {
    echo "=== HOÀN TẤT DEPLOY THÀNH CÔNG ===";
} else {
    echo "=== GẶP LỖI KHI DEPLOY (Mã lỗi: $return_var) ===";
}
