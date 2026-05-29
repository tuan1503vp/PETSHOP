<?php
class AiAnalysis {
    private $db;

    public function __construct() {
        $this->db = new Database;
        
        // Tự động tạo bảng ai_analyses nếu chưa tồn tại
        $this->db->query("CREATE TABLE IF NOT EXISTS ai_analyses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            symptoms TEXT NOT NULL,
            ai_response TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        $this->db->execute();
    }

    // Lưu kết quả chẩn đoán AI
    public function saveAnalysis($userId, $symptoms, $response) {
        $this->db->query("INSERT INTO ai_analyses (user_id, symptoms, ai_response) VALUES (:user_id, :symptoms, :ai_response)");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':symptoms', $symptoms);
        $this->db->bind(':ai_response', $response);
        return $this->db->execute();
    }

    // Lấy lịch sử chẩn đoán của một khách hàng cụ thể
    public function getHistoryByUser($userId) {
        $this->db->query("SELECT * FROM ai_analyses WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Lấy lịch sử chẩn đoán dựa trên số điện thoại hoặc tên khách hàng (phục vụ cho POS)
    public function getHistoryByPhoneOrName($phone, $name) {
        $this->db->query("SELECT a.* 
                          FROM ai_analyses a 
                          JOIN users u ON a.user_id = u.id 
                          WHERE u.phone = :phone OR u.fullname = :name 
                          ORDER BY a.created_at DESC");
        $this->db->bind(':phone', $phone);
        $this->db->bind(':name', $name);
        return $this->db->resultSet();
    }
}
