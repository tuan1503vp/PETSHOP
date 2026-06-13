<?php
class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function add($data) {
        $this->db->query('INSERT INTO notifications (user_id, title, message, type) VALUES(:user_id, :title, :message, :type)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':type', $data['type']);
        return $this->db->execute();
    }

    private static $vaccineChecked = false;

    private function checkVaccineReminders($user_id) {
        if (self::$vaccineChecked) {
            return;
        }
        self::$vaccineChecked = true;

        try {
            // 1. Lấy danh sách thú cưng của khách hàng
            $this->db->query("SELECT id, name FROM pets WHERE customer_id = :customer_id");
            $this->db->bind(':customer_id', $user_id);
            $pets = $this->db->resultSet();
            
            if (empty($pets)) {
                return;
            }
            
            $pet_list = [];
            foreach ($pets as $p) {
                $pet_list[] = $p;
            }
            
            foreach ($pet_list as $pet) {
                // 2. Tìm các mũi tiêm có lịch nhắc lại trong 3 ngày tới
                $this->db->query("SELECT id, vaccine_name, next_due_date, is_emailed FROM pet_vaccinations WHERE pet_id = :pet_id AND next_due_date IS NOT NULL AND next_due_date <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND is_emailed = 0");
                $this->db->bind(':pet_id', $pet->id);
                $vaccines = $this->db->resultSet();
                
                $vac_list = [];
                foreach ($vaccines as $v) {
                    $vac_list[] = $v;
                }
                
                foreach ($vac_list as $vac) {
                    $title = "Nhắc nhở lịch tiêm phòng";
                    $message = "Hôm nay là ngày hẹn tiêm nhắc lại mũi vắc xin " . $vac->vaccine_name . " cho bé " . $pet->name . ". Bạn hãy sắp xếp đưa bé đến phòng khám nhé!";
                    
                    // 3. Kiểm tra xem đã gửi thông báo này chưa
                    $this->db->query("SELECT id FROM notifications 
                                      WHERE user_id = :user_id 
                                      AND type = 'vaccine_reminder' 
                                      AND message = :message 
                                      LIMIT 1");
                    $this->db->bind(':user_id', $user_id);
                    $this->db->bind(':message', $message);
                    $existingNotif = $this->db->single();
                    
                    if (!$existingNotif) {
                        // 4. Thêm thông báo mới
                        $this->db->query('INSERT INTO notifications (user_id, title, message, type) VALUES(:user_id, :title, :message, :type)');
                        $this->db->bind(':user_id', $user_id);
                        $this->db->bind(':title', $title);
                        $this->db->bind(':message', $message);
                        $this->db->bind(':type', 'vaccine_reminder');
                        $this->db->execute();
                    }

                    // 5. Gửi Email nhắc nhở qua Resend API
                    $this->db->query("SELECT email, name FROM users WHERE id = :id");
                    $this->db->bind(':id', $user_id);
                    $user = $this->db->single();

                    if ($user && !empty($user->email)) {
                        $apiKey = defined('RESEND_API_KEY') ? trim(RESEND_API_KEY) : '';
                        if (!empty($apiKey)) {
                            $dueDate = date('d/m/Y', strtotime($vac->next_due_date));
                            $emailHtml = "
                                <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                                    <h2 style='color: #10b981;'>PETSHOP - Nhắc lịch tiêm phòng</h2>
                                    <p>Chào <strong>{$user->name}</strong>,</p>
                                    <p>Đây là tin nhắn nhắc nhở tự động từ PetShop. Thú cưng <strong>{$pet->name}</strong> của bạn có lịch tiêm phòng sắp tới.</p>
                                    <div style='background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid #10b981; margin: 15px 0;'>
                                        <p style='margin: 0 0 5px 0;'><strong>Tên Vắc-xin/Mũi tiêm:</strong> {$vac->vaccine_name}</p>
                                        <p style='margin: 0;'><strong>Ngày hẹn:</strong> <span style='color: #ef4444; font-weight: bold;'>{$dueDate}</span></p>
                                    </div>
                                    <p>Vui lòng sắp xếp thời gian đưa bé đến phòng khám để được phục vụ tốt nhất.</p>
                                    <br>
                                    <p>Trân trọng,<br><strong>Đội ngũ Bác sĩ PetShop</strong></p>
                                </div>
                            ";
                            
                            $ch = curl_init('https://api.resend.com/emails');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Authorization: Bearer ' . $apiKey,
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                                'from' => 'PetShop Clinic <onboarding@resend.dev>',
                                'to' => [$user->email],
                                'subject' => '[PetShop] Nhắc lịch tiêm phòng cho ' . $pet->name,
                                'html' => $emailHtml
                            ]));
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_exec($ch);
                            curl_close($ch);
                        }
                    }

                    // 6. Đánh dấu đã gửi
                    $this->db->query("UPDATE pet_vaccinations SET is_emailed = 1 WHERE id = :id");
                    $this->db->bind(':id', $vac->id);
                    $this->db->execute();
                }
            }
        } catch (Exception $e) {
            // Bỏ qua nếu lỗi DB để không ảnh hưởng luồng chính
        }
    }

    public function getNotificationsByUser($user_id) {
        $this->checkVaccineReminders($user_id);
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 20');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getUnreadCount($user_id) {
        $this->checkVaccineReminders($user_id);
        $this->db->query('SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = 0');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row ? $row->count : 0;
    }

    public function markAsRead($id) {
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function markAllAsRead($user_id) {
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function deleteAll($user_id) {
        $this->db->query('DELETE FROM notifications WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }
}
