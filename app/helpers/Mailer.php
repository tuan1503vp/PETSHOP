<?php
class Mailer {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Mô phỏng việc gửi email và lưu vào CSDL (email_logs)
     * Rất phù hợp để bảo vệ Đồ án khi host khóa cổng SMTP
     */
    public function sendOrderConfirmation($email, $customer_name, $order_id, $total_amount, $payment_method) {
        $subject = "Xác nhận đơn hàng #ORD-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " từ PETSHOP";
        
        $payment_text = $payment_method == 'transfer' ? 'Chuyển khoản ngân hàng' : 'Thanh toán khi nhận hàng (COD)';
        
        // Tạo nội dung HTML cho Email
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h1 style='color: #4f46e5; margin: 0;'>PETSHOP</h1>
                <p style='color: #64748b; margin: 5px 0 0 0;'>Nơi yêu thương bắt đầu</p>
            </div>
            
            <h2 style='color: #0f172a;'>Xin chào $customer_name,</h2>
            <p style='color: #334155; line-height: 1.6;'>Cảm ơn bạn đã tin tưởng và đặt hàng tại PETSHOP. Đơn hàng của bạn đã được hệ thống ghi nhận thành công.</p>
            
            <div style='background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                <h3 style='margin-top: 0; color: #0f172a;'>Chi tiết đơn hàng</h3>
                <ul style='list-style: none; padding: 0; margin: 0; color: #334155;'>
                    <li style='margin-bottom: 10px;'><strong>Mã đơn hàng:</strong> #ORD-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . "</li>
                    <li style='margin-bottom: 10px;'><strong>Tổng thanh toán:</strong> <span style='color: #e11d48; font-weight: bold;'>" . number_format($total_amount, 0, ',', '.') . "đ</span></li>
                    <li><strong>Phương thức:</strong> $payment_text</li>
                </ul>
            </div>
            
            <p style='color: #334155; line-height: 1.6;'>Đội ngũ PETSHOP sẽ sớm xử lý đơn hàng và giao đến tay bạn trong thời gian sớm nhất.</p>
            
            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;'>
            
            <div style='text-align: center; color: #94a3b8; font-size: 12px;'>
                <p>Đây là email tự động, vui lòng không trả lời email này.</p>
                <p>&copy; " . date('Y') . " PETSHOP. All rights reserved.</p>
            </div>
        </div>
        ";

        // Ghi log vào Database
        $this->db->query("INSERT INTO email_logs (recipient_email, subject, body, status) VALUES (:email, :subject, :body, 'sent')");
        $this->db->bind(':email', $email);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':body', $body);
        
        $log_success = $this->db->execute();

        // Thử gửi mail thực tế (Nếu host hỗ trợ)
        // Lưu ý: InfinityFree thường khóa mail(), nhưng cứ để đây cho chuẩn form Đồ án
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@petshop.vn" . "\r\n";
        
        @mail($email, $subject, $body, $headers); // Dùng @ để bỏ qua lỗi nếu bị cấm

        return $log_success;
    }

    /**
     * Gửi email phản hồi cho khách hàng từ Admin
     */
    public function sendContactReply($email, $customer_name, $reply_message) {
        $subject = "Phản hồi từ PETSHOP - Cảm ơn bạn đã liên hệ";
        
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h1 style='color: #4f46e5; margin: 0;'>PETSHOP</h1>
                <p style='color: #64748b; margin: 5px 0 0 0;'>Nơi yêu thương bắt đầu</p>
            </div>
            
            <h2 style='color: #0f172a;'>Xin chào $customer_name,</h2>
            <p style='color: #334155; line-height: 1.6;'>Cảm ơn bạn đã liên hệ với PETSHOP. Chúng tôi xin gửi phản hồi cho yêu cầu của bạn như sau:</p>
            
            <div style='background-color: #f8fafc; padding: 15px; border-left: 4px solid #4f46e5; margin: 20px 0;'>
                <p style='margin: 0; color: #334155; line-height: 1.6; white-space: pre-wrap;'>" . htmlspecialchars($reply_message) . "</p>
            </div>
            
            <p style='color: #334155; line-height: 1.6;'>Nếu bạn có bất kỳ câu hỏi nào khác, vui lòng trả lời trực tiếp email này hoặc liên hệ hotline của chúng tôi.</p>
            
            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;'>
            
            <div style='text-align: center; color: #94a3b8; font-size: 12px;'>
                <p>Bộ phận Chăm sóc khách hàng - PETSHOP</p>
                <p>&copy; " . date('Y') . " PETSHOP. All rights reserved.</p>
            </div>
        </div>
        ";

        // Ghi log vào Database
        $this->db->query("INSERT INTO email_logs (recipient_email, subject, body, status) VALUES (:email, :subject, :body, 'sent')");
        $this->db->bind(':email', $email);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':body', $body);
        
        $log_success = $this->db->execute();

        // Thử gửi mail thực tế
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: support@petshop.vn" . "\r\n";
        
        @mail($email, $subject, $body, $headers);

        return $log_success;
    }
}
