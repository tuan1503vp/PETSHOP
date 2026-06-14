<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (fullname, email, password, role) VALUES(:fullname, :email, :password, :role)');
        
        $this->db->bind(':fullname', $data['fullname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role'] ?? 'customer');

        if ($this->db->execute()) {
            $user_id = $this->db->lastInsertId();
            
            // Nếu là khách hàng, lưu thêm vào bảng members
            if (($data['role'] ?? 'customer') == 'customer') {
                $this->db->query('INSERT INTO members (user_id, phone, address) VALUES(:user_id, :phone, :address)');
                $this->db->bind(':user_id', $user_id);
                $this->db->bind(':phone', $data['phone'] ?? null);
                $this->db->bind(':address', $data['address'] ?? null);
                $this->db->execute();
            }
            
            return $user_id;
        } else {
            return false;
        }
    }

    public function updateOTP($email, $otp, $expiresAt) {
        $this->db->query('UPDATE users SET otp_code = :otp_code, otp_expires_at = :otp_expires_at WHERE email = :email');
        $this->db->bind(':otp_code', $otp);
        $this->db->bind(':otp_expires_at', $expiresAt);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    public function verifyOTP($email, $otp) {
        $this->db->query('SELECT * FROM users WHERE email = :email AND otp_code = :otp_code AND otp_expires_at > NOW()');
        $this->db->bind(':email', $email);
        $this->db->bind(':otp_code', $otp);
        $row = $this->db->single();
        if ($row) {
            $this->db->query('UPDATE users SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = :id');
            $this->db->bind(':id', $row->id);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function checkOTP($email, $otp) {
        $this->db->query('SELECT * FROM users WHERE email = :email AND otp_code = :otp_code AND otp_expires_at > NOW()');
        $this->db->bind(':email', $email);
        $this->db->bind(':otp_code', $otp);
        $row = $this->db->single();
        return $row ? true : false;
    }

    public function clearOTP($email) {
        $this->db->query('UPDATE users SET otp_code = NULL, otp_expires_at = NULL WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    // Kiểm tra email đã tồn tại chưa
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function findUserByGoogleId($id) {
        $this->db->query('SELECT * FROM users WHERE google_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findUserByFacebookId($id) {
        $this->db->query('SELECT * FROM users WHERE facebook_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function registerOAuthUser($data) {
        $this->db->query('INSERT INTO users (fullname, email, password, google_id, facebook_id, is_verified, role) VALUES(:fullname, :email, :password, :google_id, :facebook_id, 1, "customer")');
        
        $this->db->bind(':fullname', $data['fullname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT)); // Random pass
        $this->db->bind(':google_id', $data['google_id'] ?? null);
        $this->db->bind(':facebook_id', $data['facebook_id'] ?? null);

        if ($this->db->execute()) {
            $user_id = $this->db->lastInsertId();
            $this->db->query('INSERT INTO members (user_id) VALUES(:user_id)');
            $this->db->bind(':user_id', $user_id);
            $this->db->execute();
            
            return $this->getUserById($user_id);
        }
        return false;
    }

    public function updateOAuthId($user_id, $provider, $provider_id) {
        $this->db->query("UPDATE users SET {$provider}_id = :provider_id WHERE id = :user_id");
        $this->db->bind(':provider_id', $provider_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    // Đăng nhập user
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                return $row;
            } else {
                return false;
            }
        }
        return false;
    }

    public function getUserById($id) {
        $this->db->query('SELECT u.*, m.phone, m.address, m.membership_level 
                          FROM users u 
                          LEFT JOIN members m ON u.id = m.user_id 
                          WHERE u.id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();
        return $row;
    }

    public function getUsersByRole($role) {
        $this->db->query('SELECT u.*, m.phone, m.address, m.membership_level 
                          FROM users u 
                          LEFT JOIN members m ON u.id = m.user_id 
                          WHERE u.role = :role 
                          ORDER BY u.created_at DESC');
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }

    public function updateProfile($user_id, $fullname, $phone, $address, $avatar = null) {
        // Update user core info
        if ($avatar) {
            $this->db->query('UPDATE users SET fullname = :fullname, avatar = :avatar WHERE id = :id');
            $this->db->bind(':avatar', $avatar);
        } else {
            $this->db->query('UPDATE users SET fullname = :fullname WHERE id = :id');
        }
        $this->db->bind(':fullname', $fullname);
        $this->db->bind(':id', $user_id);
        $user_updated = $this->db->execute();

        // Check if member record exists
        $this->db->query('SELECT id FROM members WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        if ($this->db->single()) {
            $this->db->query('UPDATE members SET phone = :phone, address = :address WHERE user_id = :user_id');
        } else {
            $this->db->query('INSERT INTO members (user_id, phone, address) VALUES (:user_id, :phone, :address)');
        }
        $this->db->bind(':phone', $phone);
        $this->db->bind(':address', $address);
        $this->db->bind(':user_id', $user_id);
        $member_updated = $this->db->execute();

        return $user_updated && $member_updated;
    }

    public function updatePassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':password', $hashed_password);
        $this->db->bind(':id', $user_id);
        return $this->db->execute();
    }

    // Xóa user
    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }



    public function updatePasswordByEmail($email, $new_password) {
        $this->db->query('UPDATE users SET password = :password WHERE email = :email');
        $this->db->bind(':password', $new_password);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    public function getCustomersWithTotalSpent() {
        // Cập nhật hạng cho tất cả khách hàng trước khi lấy danh sách (để đảm bảo số liệu mới nhất)
        $this->db->query("SELECT id FROM users WHERE role = 'customer'");
        $customers = $this->db->resultSet();
        foreach($customers as $c) {
            $this->updateMembershipTier($c->id);
        }

        $this->db->query("SELECT u.*, m.phone, m.address, m.membership_level,
            (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = u.id AND status = 'completed') +
            (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = u.id AND status = 'completed') as total_spent,
            (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = u.id AND status = 'completed' AND YEAR(created_at) = YEAR(CURDATE())) +
            (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = u.id AND status = 'completed' AND YEAR(appointment_date) = YEAR(CURDATE())) as annual_spent
            FROM users u 
            LEFT JOIN members m ON u.id = m.user_id
            WHERE u.role = 'customer'
            ORDER BY total_spent DESC");
        return $this->db->resultSet();
    }

    public function updateMembershipTier($user_id) {
        // 1. Tính tổng chi trong năm hiện tại
        $this->db->query("SELECT 
            (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = :id1 AND status = 'completed' AND YEAR(created_at) = YEAR(CURDATE())) +
            (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = :id2 AND status = 'completed' AND YEAR(appointment_date) = YEAR(CURDATE())) as annual_spent");
        $this->db->bind(':id1', $user_id);
        $this->db->bind(':id2', $user_id);
        $res = $this->db->single();
        $annual_spent = $res ? $res->annual_spent : 0;

        // 2. Kiểm tra chi tiêu 6 tháng gần nhất để xét VIP
        $is_vip_eligible = true;
        $has_vip_drop_condition = false; // Một tháng không chi quá 500.000
        
        for ($i = 0; $i < 6; $i++) {
            $month = date('m', strtotime("-$i month"));
            $year = date('Y', strtotime("-$i month"));
            
            $this->db->query("SELECT 
                (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = :id1 AND status = 'completed' AND MONTH(created_at) = :m1 AND YEAR(created_at) = :y1) +
                (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = :id2 AND status = 'completed' AND MONTH(appointment_date) = :m2 AND YEAR(appointment_date) = :y2) as monthly_spent");
            $this->db->bind(':id1', $user_id); $this->db->bind(':id2', $user_id);
            $this->db->bind(':m1', $month); $this->db->bind(':m2', $month);
            $this->db->bind(':y1', $year); $this->db->bind(':y2', $year);
            $m_res = $this->db->single();
            $monthly_spent = $m_res ? $m_res->monthly_spent : 0;

            if ($monthly_spent < 5000000) $is_vip_eligible = false; // Lên VIP: 6 tháng mỗi tháng > 5tr
            if ($monthly_spent <= 500000) $has_vip_drop_condition = true; // Mất VIP: Có tháng <= 500k
        }

        // Lấy hạng hiện tại
        $this->db->query("SELECT membership_level FROM members WHERE user_id = :id");
        $this->db->bind(':id', $user_id);
        $m_row = $this->db->single();
        $current_level = $m_row ? $m_row->membership_level : 'Đồng';

        $new_level = 'Đồng';
        if ($annual_spent >= 10000000) {
            $new_level = 'Bạch kim';
            // Xét lên VIP hoặc duy trì VIP
            if ($is_vip_eligible) {
                $new_level = 'VIP';
            } elseif ($current_level == 'VIP' && !$has_vip_drop_condition) {
                $new_level = 'VIP';
            }
        } elseif ($annual_spent >= 5000000) {
            $new_level = 'Vàng';
        } elseif ($annual_spent >= 1000000) {
            $new_level = 'Bạc';
        }

        // Cập nhật hạng
        $this->db->query("UPDATE members SET membership_level = :level WHERE user_id = :id");
        $this->db->bind(':level', $new_level);
        $this->db->bind(':id', $user_id);
        $this->db->execute();
        
        // Gửi thông báo nếu có thay đổi hạng
        if ($new_level != $current_level) {
            $type = 'rank';
            $title = 'Cập nhật hạng hội viên';
            $msg = "Hạng hội viên của bạn đã thay đổi từ " . $current_level . " sang " . $new_level . ".";
            
            // Tùy biến thông báo thăng hạng/giáng hạng
            $levels = ['Đồng', 'Bạc', 'Vàng', 'Bạch kim', 'VIP'];
            $oldIdx = array_search($current_level, $levels);
            $newIdx = array_search($new_level, $levels);
            
            if ($newIdx > $oldIdx) {
                $title = "Chúc mừng thăng hạng!";
                $msg = "Tuyệt vời! Bạn đã được thăng hạng lên " . $new_level . ". Hãy tận hưởng những ưu đãi mới nhé!";
            }

            $this->db->query('INSERT INTO notifications (user_id, title, message, type) VALUES(:user_id, :title, :message, :type)');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':title', $title);
            $this->db->bind(':message', $msg);
            $this->db->bind(':type', $type);
            $this->db->execute();
        }
        
        return $new_level;
    }

    public function getCombinedHistory($user_id) {
        // Lấy đơn hàng
        $this->db->query("SELECT id, total_amount as amount, status, created_at as date, 'order' as type, order_type, payment_method, refund_status
                          FROM orders WHERE customer_id = :id1
                          UNION ALL
                          SELECT id, final_price as amount, status, appointment_date as date, 'appointment' as type, '' as order_type, '' as payment_method, '' as refund_status
                          FROM appointments WHERE customer_id = :id2
                          ORDER BY date DESC");
        $this->db->bind(':id1', $user_id);
        $this->db->bind(':id2', $user_id);
        $history = $this->db->resultSet();
        
        // Thêm chi tiết cho từng mục
        require_once APPROOT . '/models/Order.php';
        require_once APPROOT . '/models/Appointment.php';
        $orderModel = new Order();
        $apptModel = new Appointment();
        
        foreach($history as $item) {
            if($item->type == 'order') {
                $item->items = $orderModel->getOrderItems($item->id);
            } else {
                $item->details = $apptModel->getAppointmentById($item->id);
            }
        }
        
        return $history;
    }

    public function getMembershipFullInfo($user_id) {
        // 1. Tính chi tiêu
        $this->db->query("SELECT 
            (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = :id1 AND status = 'completed') +
            (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = :id2 AND status = 'completed') as total_spent,
            (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = :id3 AND status = 'completed' AND YEAR(created_at) = YEAR(CURDATE())) +
            (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = :id4 AND status = 'completed' AND YEAR(appointment_date) = YEAR(CURDATE())) as annual_spent,
            (SELECT IFNULL(SUM(total_amount), 0) FROM orders WHERE customer_id = :id5 AND status = 'completed' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) +
            (SELECT IFNULL(SUM(final_price), 0) FROM appointments WHERE customer_id = :id6 AND status = 'completed' AND MONTH(appointment_date) = MONTH(CURDATE()) AND YEAR(appointment_date) = YEAR(CURDATE())) as monthly_spent");
        
        $this->db->bind(':id1', $user_id); $this->db->bind(':id2', $user_id);
        $this->db->bind(':id3', $user_id); $this->db->bind(':id4', $user_id);
        $this->db->bind(':id5', $user_id); $this->db->bind(':id6', $user_id);
        
        $stats = $this->db->single();
        
        // 2. Lấy hạng và ưu đãi
        $this->db->query("SELECT m.membership_level, b.benefit_text, b.discount_percent, b.free_service
                          FROM members m
                          LEFT JOIN membership_benefits b ON m.membership_level = b.membership_level
                          WHERE m.user_id = :id");
        $this->db->bind(':id', $user_id);
        $member = $this->db->single();
        
        return [
            'stats' => $stats,
            'member' => $member
        ];
    }

    public function getMembershipDiscount($user_id) {
        $this->db->query("SELECT b.discount_percent, m.membership_level
                          FROM members m
                          LEFT JOIN membership_benefits b ON m.membership_level = b.membership_level
                          WHERE m.user_id = :id");
        $this->db->bind(':id', $user_id);
        $row = $this->db->single();
        if ($row) {
            return [
                'level' => $row->membership_level,
                'discount_percent' => (float)($row->discount_percent ?? 0)
            ];
        }
        return ['level' => 'Đồng', 'discount_percent' => 0];
    }
}
