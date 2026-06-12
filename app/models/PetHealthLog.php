<?php
class PetHealthLog {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Lấy danh sách nhật ký theo thú cưng
    public function getLogsByPet($pet_id) {
        $this->db->query('SELECT * FROM pet_health_logs WHERE pet_id = :pet_id ORDER BY log_date DESC, created_at DESC');
        $this->db->bind(':pet_id', $pet_id);
        return $this->db->resultSet();
    }

    // Lấy chi tiết một nhật ký
    public function getLogById($id) {
        $this->db->query('SELECT * FROM pet_health_logs WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Thêm nhật ký mới
    public function addLog($data) {
        $this->db->query('INSERT INTO pet_health_logs (pet_id, log_date, weight, temperature, status, symptoms, notes) 
                          VALUES (:pet_id, :log_date, :weight, :temperature, :status, :symptoms, :notes)');
        
        $this->db->bind(':pet_id', $data['pet_id']);
        $this->db->bind(':log_date', $data['log_date']);
        $this->db->bind(':weight', $data['weight']);
        $this->db->bind(':temperature', $data['temperature']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':symptoms', $data['symptoms']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    // Cập nhật nhật ký
    public function updateLog($data) {
        $this->db->query('UPDATE pet_health_logs SET log_date = :log_date, weight = :weight, temperature = :temperature, 
                          status = :status, symptoms = :symptoms, notes = :notes WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':log_date', $data['log_date']);
        $this->db->bind(':weight', $data['weight']);
        $this->db->bind(':temperature', $data['temperature']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':symptoms', $data['symptoms']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    // Xóa nhật ký
    public function deleteLog($id) {
        $this->db->query('DELETE FROM pet_health_logs WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
