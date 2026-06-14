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

    public function sendOTP($email, $fullname, $otp) {
        $subject = "Mã xác thực tài khoản PETSHOP";
        $body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px; text-align: center;'>
            <h1 style='color: #4f46e5; margin: 0;'>PETSHOP</h1>
            <h2 style='color: #0f172a;'>Xin chào $fullname,</h2>
            <p style='color: #334155; line-height: 1.6;'>Mã xác thực (OTP) của bạn là:</p>
            <h1 style='color: #e11d48; letter-spacing: 5px; font-size: 36px; background: #f8fafc; padding: 15px; border-radius: 8px; display: inline-block; margin: 10px 0;'>$otp</h1>
            <p style='color: #64748b; font-size: 14px;'>Mã này sẽ hết hạn sau 10 phút. Vui lòng không chia sẻ mã này cho bất kỳ ai.</p>
        </div>";
        return $this->send($email, $subject, $body);
    }

    // -----------------------------------------------------------------------
    // Xác nhận đơn hàng
    // -----------------------------------------------------------------------
    public function sendOrderConfirmation($email, $customer_name, $order_id, $total_amount, $payment_method) {
        $subject = "Xác nhận đơn hàng #ORD-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . " từ PETSHOP";
        $payment_text = ($payment_method == 'transfer') ? 'Chuyển khoản ngân hàng' : 'Thanh toán khi nhận hàng (COD)';
        
        // Lấy chi tiết sản phẩm trong đơn hàng
        $this->db->query("SELECT oi.*, p.name as product_name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        $items = $this->db->resultSet();
        
        $items_html = "<table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>
                        <thead>
                            <tr style='border-bottom: 2px solid #e2e8f0; text-align: left; color: #64748b; font-size: 14px;'>
                                <th style='padding: 10px 5px;'>Sản phẩm</th>
                                <th style='padding: 10px 5px; text-align: center;'>SL</th>
                                <th style='padding: 10px 5px; text-align: right;'>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>";
                        
        foreach ($items as $item) {
            $items_html .= "
                            <tr style='border-bottom: 1px solid #f1f5f9;'>
                                <td style='padding: 12px 5px; color: #334155; font-weight: 500;'>{$item->product_name}</td>
                                <td style='padding: 12px 5px; text-align: center; color: #64748b;'>x{$item->quantity}</td>
                                <td style='padding: 12px 5px; text-align: right; color: #0f172a; font-weight: 600;'>" . number_format($item->price * $item->quantity, 0, ',', '.') . "đ</td>
                            </tr>";
        }
        $items_html .= "</tbody></table>";

        $url_home = defined('URLROOT') ? URLROOT : 'https://pet.kesug.com';
        $body = "
        <div style='background-color: #f8fafc; padding: 40px 0;'>
            <div style='font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);'>
                
                <!-- Header -->
                <div style='text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;'>
                    <h1 style='color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;'>PETSHOP</h1>
                    <p style='color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;'>Nơi yêu thương bắt đầu</p>
                </div>
                
                <!-- Greeting -->
                <h2 style='color: #0f172a; font-size: 20px; margin-top: 0;'>Xin chào $customer_name,</h2>
                <p style='color: #475569; line-height: 1.6; font-size: 16px;'>Cảm ơn bạn đã tin tưởng mua sắm tại <strong>PETSHOP</strong>! Chúng tôi rất vui mừng thông báo đơn hàng của bạn đã được hệ thống ghi nhận và đang được xử lý.</p>
                
                <!-- Order Details Box -->
                <div style='background-color: #ffffff; border: 1px solid #e2e8f0; padding: 25px; border-radius: 12px; margin: 30px 0;'>
                    <div style='display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;'>
                        <div>
                            <p style='margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;'>Mã đơn hàng</p>
                            <h3 style='margin: 5px 0 0 0; color: #0f172a; font-size: 18px;'>#ORD-" . str_pad($order_id, 5, '0', STR_PAD_LEFT) . "</h3>
                        </div>
                        <div style='text-align: right;'>
                            <p style='margin: 0; color: #64748b; font-size: 13px; text-transform: uppercase;'>Ngày đặt</p>
                            <h3 style='margin: 5px 0 0 0; color: #0f172a; font-size: 16px; font-weight: 500;'>" . date('d/m/Y') . "</h3>
                        </div>
                    </div>
                    
                    <!-- Items -->
                    $items_html
                    
                    <!-- Summary -->
                    <div style='margin-top: 20px; padding-top: 15px; border-top: 2px dashed #e2e8f0;'>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 5px 0; color: #64748b;'>Phương thức thanh toán:</td>
                                <td style='padding: 5px 0; text-align: right; color: #0f172a; font-weight: 500;'>$payment_text</td>
                            </tr>
                            <tr>
                                <td style='padding: 10px 0; color: #0f172a; font-size: 18px; font-weight: bold;'>Tổng thanh toán:</td>
                                <td style='padding: 10px 0; text-align: right; color: #e11d48; font-size: 20px; font-weight: 800;'>" . number_format($total_amount, 0, ',', '.') . "đ</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div style='text-align: center; margin: 40px 0;'>
                    <a href='{$url_home}' style='background-color: #4f46e5; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block;'>Tiếp tục mua sắm</a>
                </div>
                
                <p style='color: #475569; line-height: 1.6; font-size: 15px; text-align: center;'>Đội ngũ PETSHOP sẽ sớm liên hệ với bạn để giao hàng. Chúc bạn và thú cưng một ngày vui vẻ!</p>
                
                <!-- Footer -->
                <div style='text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;'>
                    <p style='color: #94a3b8; font-size: 13px; margin: 0 0 10px 0;'>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>
                    <p style='color: #64748b; font-size: 13px; margin: 0;'><strong>&copy; " . date('Y') . " PETSHOP.</strong> All rights reserved.</p>
                </div>
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
        $url_home = defined('URLROOT') ? URLROOT : 'https://pet.kesug.com';

        $body = "
        <div style='background-color: #f8fafc; padding: 40px 0;'>
            <div style='font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);'>
                
                <!-- Header -->
                <div style='text-align: center; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 30px;'>
                    <h1 style='color: #4f46e5; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;'>PETSHOP</h1>
                    <p style='color: #64748b; margin: 5px 0 0 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;'>Hỗ trợ Khách hàng</p>
                </div>
                
                <h2 style='color: #0f172a; font-size: 20px; margin-top: 0;'>Xin chào $customer_name,</h2>
                <p style='color: #475569; line-height: 1.6; font-size: 16px;'>Cảm ơn bạn đã liên hệ với <strong>PETSHOP</strong>. Đội ngũ chăm sóc khách hàng đã nhận được yêu cầu của bạn và xin gửi đến bạn thông tin phản hồi như sau:</p>
                
                <!-- Reply Content -->
                <div style='background-color: #eef2ff; padding: 20px 25px; border-left: 5px solid #4f46e5; border-radius: 0 12px 12px 0; margin: 25px 0;'>
                    <p style='margin: 0; color: #1e1b4b; line-height: 1.7; font-size: 15px; white-space: pre-wrap;'>" . htmlspecialchars($reply_message) . "</p>
                </div>
                
                <p style='color: #475569; line-height: 1.6; font-size: 15px;'>Nếu bạn vẫn còn bất kỳ thắc mắc nào khác, xin đừng ngần ngại trả lời trực tiếp email này hoặc truy cập trang web của chúng tôi.</p>
                
                <!-- Call to Action -->
                <div style='text-align: center; margin: 35px 0;'>
                    <a href='{$url_home}' style='background-color: #ffffff; color: #4f46e5; border: 2px solid #4f46e5; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; transition: all 0.3s;'>Trở lại trang chủ</a>
                </div>
                
                <!-- Footer -->
                <div style='text-align: center; border-top: 1px solid #e2e8f0; margin-top: 40px; padding-top: 20px;'>
                    <p style='color: #64748b; font-size: 14px; font-weight: bold; margin: 0 0 10px 0;'>Trân trọng,<br/>Đội ngũ CSKH PETSHOP</p>
                    <p style='color: #94a3b8; font-size: 12px; margin: 0;'>&copy; " . date('Y') . " PETSHOP. All rights reserved.</p>
                </div>
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
        $subject = "Yêu cầu liên hệ mới từ: $customer_name";
        $url_admin = defined('URLROOT') ? URLROOT . '/admin/contacts' : 'https://pet.kesug.com/admin/contacts';

        $body = "
        <div style='background-color: #f1f5f9; padding: 40px 0;'>
            <div style='font-family: \"Inter\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 0; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); overflow: hidden;'>
                
                <!-- Banner -->
                <div style='background-color: #f59e0b; padding: 25px 20px; text-align: center;'>
                    <h1 style='color: #ffffff; margin: 0; font-size: 24px; display: flex; align-items: center; justify-content: center;'>
                        ⚠️ Yêu Cầu Hỗ Trợ Mới
                    </h1>
                </div>
                
                <div style='padding: 30px;'>
                    <p style='color: #475569; font-size: 16px; margin-top: 0;'>Chào Admin, hệ thống vừa ghi nhận một liên hệ mới từ khách hàng cần được hỗ trợ.</p>
                    
                    <div style='background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 25px 0;'>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 8px 0; width: 120px; color: #64748b; font-size: 14px;'>Khách hàng:</td>
                                <td style='padding: 8px 0; color: #0f172a; font-weight: 600; font-size: 15px;'>$customer_name</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #64748b; font-size: 14px;'>Email LH:</td>
                                <td style='padding: 8px 0; color: #0f172a; font-weight: 600; font-size: 15px;'><a href='mailto:$customer_email' style='color: #4f46e5; text-decoration: none;'>$customer_email</a></td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #64748b; font-size: 14px;'>Thời gian:</td>
                                <td style='padding: 8px 0; color: #0f172a; font-weight: 600; font-size: 15px;'>" . date('H:i d/m/Y') . "</td>
                            </tr>
                        </table>
                        
                        <div style='margin-top: 15px; padding-top: 15px; border-top: 1px solid #cbd5e1;'>
                            <p style='margin: 0 0 10px 0; color: #64748b; font-size: 14px;'>Nội dung tin nhắn:</p>
                            <p style='margin: 0; background-color: #ffffff; padding: 15px; border: 1px solid #e2e8f0; border-radius: 6px; color: #1e293b; line-height: 1.6; font-size: 14px; white-space: pre-wrap; font-style: italic;'>" . htmlspecialchars($message_content) . "</p>
                        </div>
                    </div>
                    
                    <div style='text-align: center; margin-top: 30px;'>
                        <a href='{$url_admin}' style='background-color: #f59e0b; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; display: inline-block;'>Đăng nhập xử lý ngay</a>
                    </div>
                </div>
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
