<?php
class Voucher {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllVouchers() {
        $this->db->query("SELECT * FROM vouchers ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getAllActiveVouchers() {
        $this->db->query("SELECT * FROM vouchers WHERE is_active = 1 AND code IS NULL ORDER BY cost_coins ASC");
        return $this->db->resultSet();
    }

    public function getVoucherById($id) {
        $this->db->query("SELECT * FROM vouchers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getUserVouchers($user_id) {
        $this->db->query("SELECT uv.*, v.title, v.description, v.discount_amount, v.discount_type, v.max_discount, v.min_order_value, v.category_id 
                          FROM user_vouchers uv 
                          JOIN vouchers v ON uv.voucher_id = v.id 
                          WHERE uv.user_id = :user_id 
                          ORDER BY uv.status ASC, uv.created_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getActiveUserVouchers($user_id) {
        $this->db->query("SELECT uv.*, v.title, v.description, v.discount_amount, v.discount_type, v.max_discount, v.min_order_value, v.category_id 
                          FROM user_vouchers uv 
                          JOIN vouchers v ON uv.voucher_id = v.id 
                          WHERE uv.user_id = :user_id AND uv.status = 'active'
                          ORDER BY v.discount_amount DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getVoucherByCode($code, $user_id) {
        // Kiểm tra voucher cá nhân (chỉ kiểm tra nếu có user_id)
        if ($user_id) {
            $this->db->query("SELECT uv.unique_code as code, v.title, v.discount_type, v.discount_amount, v.max_discount, v.min_order_value, v.category_id, 'internal' as type 
                              FROM user_vouchers uv 
                              JOIN vouchers v ON uv.voucher_id = v.id 
                              WHERE uv.unique_code = :code AND uv.user_id = :user_id AND uv.status = 'active'");
            $this->db->bind(':code', $code);
            $this->db->bind(':user_id', $user_id);
            $internal = $this->db->single();
            if ($internal) return $internal;
        }

        // Kiểm tra voucher công khai (áp dụng cho cả khách lẻ và hội viên)
        $this->db->query("SELECT code, title, discount_type, discount_amount, max_discount, min_order_value, category_id, usage_limit, used_count, usage_per_user, 'external' as type 
                          FROM vouchers 
                          WHERE code = :code AND is_active = 1 
                          AND (usage_limit IS NULL OR used_count < usage_limit)
                          AND (start_date IS NULL OR start_date <= NOW())
                          AND (end_date IS NULL OR end_date >= NOW())");
        $this->db->bind(':code', $code);
        $external = $this->db->single();
        
        if ($external) {
            // Kiểm tra giới hạn lượt dùng của mỗi hội viên (chỉ áp dụng nếu có user_id)
            if ($user_id) {
                $this->db->query("SELECT COUNT(*) as user_usage_count FROM orders 
                                  WHERE customer_id = :user_id AND voucher_code = :code AND status != 'cancelled'");
                $this->db->bind(':user_id', $user_id);
                $this->db->bind(':code', $code);
                $usage = $this->db->single();
                if ($usage && $usage->user_usage_count >= $external->usage_per_user) {
                    return false; // Hội viên đã dùng hết số lượt cho phép
                }
            }
            return $external;
        }

        return false;
    }

    public function addVoucherToUser($user_id, $voucher_id) {
        $code = 'PET' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        
        $this->db->query("INSERT INTO user_vouchers (user_id, voucher_id, unique_code) VALUES (:user_id, :voucher_id, :unique_code)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':voucher_id', $voucher_id);
        $this->db->bind(':unique_code', $code);
        
        return $this->db->execute();
    }

    public function markVoucherAsUsed($code) {
        $this->db->query("UPDATE user_vouchers SET status = 'used', used_at = CURRENT_TIMESTAMP WHERE unique_code = :code AND status = 'active'");
        $this->db->bind(':code', $code);
        $this->db->execute();
        
        if ($this->db->rowCount() == 0) {
            $this->db->query("UPDATE vouchers SET used_count = used_count + 1 WHERE code = :code");
            $this->db->bind(':code', $code);
            $this->db->execute();
        }
        return true;
    }

    public function addVoucher($data) {
        $this->db->query("INSERT INTO vouchers (title, description, discount_type, discount_amount, max_discount, min_order_value, category_id, cost_coins, usage_limit, is_active, code, start_date, end_date, usage_per_user, is_combinable) 
                          VALUES (:title, :description, :discount_type, :discount_amount, :max_discount, :min_order_value, :category_id, :cost_coins, :usage_limit, :is_active, :code, :start_date, :end_date, :usage_per_user, :is_combinable)");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':discount_type', $data['discount_type'] ?? 'fixed');
        $this->db->bind(':discount_amount', $data['discount_amount']);
        $this->db->bind(':max_discount', $data['max_discount'] ?: null);
        $this->db->bind(':min_order_value', $data['min_order_value'] ?: 0);
        $this->db->bind(':category_id', $data['category_id'] ?: null);
        $this->db->bind(':cost_coins', $data['cost_coins'] ?: 0);
        $this->db->bind(':usage_limit', $data['usage_limit'] ?: null);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);
        $this->db->bind(':code', $data['code'] ?: null);
        $this->db->bind(':start_date', $data['start_date'] ?: null);
        $this->db->bind(':end_date', $data['end_date'] ?: null);
        $this->db->bind(':usage_per_user', $data['usage_per_user'] ?: 1);
        $this->db->bind(':is_combinable', $data['is_combinable'] ?? 0);
        
        return $this->db->execute();
    }

    public function updateVoucher($id, $data) {
        $this->db->query("UPDATE vouchers SET 
                            title = :title, 
                            description = :description, 
                            discount_type = :discount_type,
                            discount_amount = :discount_amount, 
                            max_discount = :max_discount,
                            min_order_value = :min_order_value,
                            category_id = :category_id,
                            cost_coins = :cost_coins, 
                            usage_limit = :usage_limit,
                            is_active = :is_active,
                            code = :code,
                            start_date = :start_date,
                            end_date = :end_date,
                            usage_per_user = :usage_per_user,
                            is_combinable = :is_combinable
                          WHERE id = :id");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':discount_type', $data['discount_type'] ?? 'fixed');
        $this->db->bind(':discount_amount', $data['discount_amount']);
        $this->db->bind(':max_discount', $data['max_discount'] ?: null);
        $this->db->bind(':min_order_value', $data['min_order_value'] ?: 0);
        $this->db->bind(':category_id', $data['category_id'] ?: null);
        $this->db->bind(':cost_coins', $data['cost_coins'] ?: 0);
        $this->db->bind(':usage_limit', $data['usage_limit'] ?: null);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);
        $this->db->bind(':code', $data['code'] ?: null);
        $this->db->bind(':start_date', $data['start_date'] ?: null);
        $this->db->bind(':end_date', $data['end_date'] ?: null);
        $this->db->bind(':usage_per_user', $data['usage_per_user'] ?: 1);
        $this->db->bind(':is_combinable', $data['is_combinable'] ?? 0);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function deleteVoucher($id) {
        $this->db->query("SELECT id FROM user_vouchers WHERE voucher_id = :id LIMIT 1");
        $this->db->bind(':id', $id);
        $exists = $this->db->single();
        
        if ($exists) {
            $this->db->query("UPDATE vouchers SET is_active = 0 WHERE id = :id");
        } else {
            $this->db->query("DELETE FROM vouchers WHERE id = :id");
        }
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>
