<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';
require_once __DIR__ . '/app/models/User.php';

$userModel = new User();
$db = new Database();

echo "Bắt đầu cập nhật lại hạng cho tất cả khách hàng...\n";

// 1. Reset tất cả thành viên về Đồng trước (để xoá sạch nếu không có đơn hàng)
$db->query("UPDATE members SET membership_level = 'Đồng'");
$db->execute();

// 2. Chạy hàm updateMembershipTier cho từng khách hàng để xét theo dữ liệu chi tiêu hiện tại
$db->query("SELECT id FROM users WHERE role = 'customer'");
$customers = $db->resultSet();

$count = 0;
foreach($customers as $c) {
    // Tạm thời vô hiệu hoá thông báo để tránh rác DB khi reset hàng loạt
    // Ta làm bằng cách update trực tiếp thay vì gọi hàm updateMembershipTier
    
    $user_id = $c->id;
    
    // Tính tổng chi trong năm hiện tại
    $db->query("SELECT 
        (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = :id1 AND status = 'completed' AND YEAR(created_at) = YEAR(CURDATE())) +
        (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = :id2 AND status = 'completed' AND YEAR(appointment_date) = YEAR(CURDATE())) as annual_spent");
    $db->bind(':id1', $user_id);
    $db->bind(':id2', $user_id);
    $res = $db->single();
    $annual_spent = $res ? $res->annual_spent : 0;
    
    $new_level = 'Đồng';
    if ($annual_spent >= 10000000) {
        $new_level = 'Bạch kim'; // (Bỏ qua logic VIP rườm rà ở đây, chỉ set theo mức tiền cơ bản)
    } elseif ($annual_spent >= 5000000) {
        $new_level = 'Vàng';
    } elseif ($annual_spent >= 1000000) {
        $new_level = 'Bạc';
    }
    
    if ($new_level !== 'Đồng') {
        $db->query("UPDATE members SET membership_level = :level WHERE user_id = :id");
        $db->bind(':level', $new_level);
        $db->bind(':id', $user_id);
        $db->execute();
    }
    
    $count++;
}

echo "Hoàn tất! Đã cập nhật hạng (xóa lịch sử cũ) cho $count khách hàng.\n";
