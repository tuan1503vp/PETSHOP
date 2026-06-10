<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once APPROOT . '/libraries/PHPMailer/Exception.php';
require_once APPROOT . '/libraries/PHPMailer/PHPMailer.php';
require_once APPROOT . '/libraries/PHPMailer/SMTP.php';

class Mailer {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // -----------------------------------------------------------------------
    // RESEND API (ưu tiên) — hoạt động trên mọi host, không cần mở cổng SMTP
    // Đăng ký miễn phí tại https://resend.com bằng Google 1 click
    // -----------------------------------------------------------------------
    private function sendResend($toEmail, $subject, $htmlBody, $replyToEmail = null) {
        if (!defined('RESEND_API_KEY') || empty(RESEND_API_KEY) || RESEND_API_KEY === 'your_resend_api_key_here') {
            return false; // Chưa cấu hình, thử SMTP
        }

        $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'onboarding@resend.dev';
        $fromName  = defined('SMTP_FROM_NAME')  ? SMTP_FROM_NAME  : 'PETSHOP';

        // Nếu chưa xác minh domain, dùng địa chỉ mặc định của Resend để test
        if (strpos($fromEmail, '@gmail.com') !== false || strpos($fromEmail, '@yahoo.com') !== false) {
            $fromEmail = 'onboarding@resend.dev';
        }

        $payload = [
            'from'    => "$fromName <$fromEmail>",
            'to'      => [$toEmail],
            'subject' => $subject,
            'html'    => $htmlBody,
        ];

        if ($replyToEmail) {
            $payload['reply_to'] = [$replyToEmail];
        }

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . RESEND_API_KEY,
            ],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            error_log("Resend cURL Error: $curlError");
            return false;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        error_log("Resend API Error (HTTP $httpCode): $response");
        return false;
    }

    // -----------------------------------------------------------------------
    // SMTP / PHPMailer (dự phòng)
    // -----------------------------------------------------------------------
    private function sendSMTP($toEmail, $subject, $htmlBody, $replyToEmail = null) {
        if (!defined('SMTP_PASS') || empty(SMTP_PASS) || SMTP_PASS === 'app_password_here') {
            error_log("PHPMailer: SMTP_PASS not configured. Email to $toEmail not sent.");
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = (SMTP_PORT == 465)
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addAddress($toEmail);
            if ($replyToEmail) {
                $mail->addReplyTo($replyToEmail);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }

    // -----------------------------------------------------------------------
    // Hàm gửi tổng quát: Resend trước, fallback sang SMTP
    // -----------------------------------------------------------------------
    private function send($toEmail, $subject, $htmlBody, $replyToEmail = null) {
        if ($this->sendResend($toEmail, $subject, $htmlBody, $replyToEmail)) {
            return true;
        }
        return $this->sendSMTP($toEmail, $subject, $htmlBody, $replyToEmail);
    }

    // -----------------------------------------------------------------------
    // Xác nhận đơn hàng
    // -----------------------------------------------------------------------
    public function sendOrderConfirmation($email, $customer_name, $order_id, $total_amount, $payment_method) {
        $subject = "Xác nhận đơn hàng #ORD-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " từ PETSHOP";
        $payment_text = ($payment_method == 'transfer') ? 'Chuyển khoản ngân hàng' : 'Thanh toán khi nhận hàng (COD)';

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
        </div>";

        $this->db->query("INSERT INTO email_logs (recipient_email, subject, body, status) VALUES (:email, :subject, :body, 'sent')");
        $this->db->bind(':email', $email);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':body', $body);
        $log_success = $this->db->execute();

        $this->send($email, $subject, $body);
        return $log_success;
    }

    // -----------------------------------------------------------------------
    // Phản hồi liên hệ từ Admin → Khách hàng
    // -----------------------------------------------------------------------
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
            <p style='color: #334155; line-height: 1.6;'>Nếu bạn có bất kỳ câu hỏi nào khác, vui lòng liên hệ lại với chúng tôi.</p>
            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;'>
            <div style='text-align: center; color: #94a3b8; font-size: 12px;'>
                <p>Bộ phận Chăm sóc khách hàng - PETSHOP</p>
                <p>&copy; " . date('Y') . " PETSHOP. All rights reserved.</p>
            </div>
        </div>";

        $this->db->query("INSERT INTO email_logs (recipient_email, subject, body, status) VALUES (:email, :subject, :body, 'sent')");
        $this->db->bind(':email', $email);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':body', $body);
        $log_success = $this->db->execute();

        $this->send($email, $subject, $body);
        return $log_success;
    }

    // -----------------------------------------------------------------------
    // Thông báo liên hệ mới → Admin
    // -----------------------------------------------------------------------
    public function sendContactNotificationToAdmin($customer_name, $customer_email, $message_content) {
        $admin_email = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'nmtvp11223311@gmail.com';
        $subject = "Yêu cầu liên hệ mới từ khách hàng: $customer_name";

        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h1 style='color: #4f46e5; margin: 0;'>PETSHOP</h1>
                <p style='color: #64748b; margin: 5px 0 0 0;'>Nơi yêu thương bắt đầu</p>
            </div>
            <h2 style='color: #0f172a;'>Có yêu cầu liên hệ mới từ khách hàng!</h2>
            <p style='color: #334155; line-height: 1.6;'>Dưới đây là chi tiết yêu cầu:</p>
            <div style='background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0; color: #334155;'>
                <p><strong>Họ và tên:</strong> $customer_name</p>
                <p><strong>Email khách hàng:</strong> $customer_email</p>
                <p><strong>Nội dung:</strong></p>
                <p style='white-space: pre-wrap; background-color: #ffffff; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;'>" . htmlspecialchars($message_content) . "</p>
            </div>
            <p style='color: #334155; line-height: 1.6;'>Bạn có thể đăng nhập vào hệ thống Admin của PETSHOP để phản hồi khách hàng.</p>
            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;'>
            <div style='text-align: center; color: #94a3b8; font-size: 12px;'>
                <p>&copy; " . date('Y') . " PETSHOP. All rights reserved.</p>
            </div>
        </div>";

        $this->db->query("INSERT INTO email_logs (recipient_email, subject, body, status) VALUES (:email, :subject, :body, 'sent')");
        $this->db->bind(':email', $admin_email);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':body', $body);
        $log_success = $this->db->execute();

        $this->send($admin_email, $subject, $body, $customer_email);
        return $log_success;
    }
}
