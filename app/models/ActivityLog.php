<?php
class ActivityLog {
    private $db;

    public function __construct() {
        $this->db = new Database;
        
        // Tự động tạo bảng activity_logs nếu chưa tồn tại (Bọc try-catch đề phòng phân quyền kém trên host)
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS activity_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                username VARCHAR(100) NULL,
                role VARCHAR(50) NULL,
                action VARCHAR(255) NOT NULL,
                details TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $this->db->execute();
        } catch (Exception $e) {
            // Bỏ qua lỗi nếu không có quyền tạo bảng
        }
    }

    // Ghi nhật ký mới
    public function log($userId, $username, $role, $action, $details = null) {
        try {
            $this->db->query("INSERT INTO activity_logs (user_id, username, role, action, details) 
                              VALUES (:user_id, :username, :role, :action, :details)");
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':username', $username);
            $this->db->bind(':role', $role);
            $this->db->bind(':action', $action);
            $this->db->bind(':details', $details);
            return $this->db->execute();
        } catch (Exception $e) {
            // Bỏ qua lỗi ghi log để tránh làm gián đoạn các luồng nghiệp vụ khác (như đăng xuất)
            return false;
        }
    }

    // Lấy toàn bộ nhật ký hành vi
    public function getLogs($limit = 200) {
        $this->db->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Tìm kiếm nhật ký hành vi
    public function searchLogs($query, $limit = 200) {
        $this->db->query("SELECT * FROM activity_logs 
                          WHERE username LIKE :q OR action LIKE :q OR details LIKE :q OR role LIKE :q
                          ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':q', '%' . $query . '%');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
