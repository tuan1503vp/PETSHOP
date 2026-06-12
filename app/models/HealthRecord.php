<?php
class HealthRecord {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Lấy danh sách hồ sơ khám bệnh của thú cưng (kèm tên bác sĩ khám)
    public function getRecordsByPet($pet_id) {
        $this->db->query('SELECT hr.*, u.fullname as doctor_name 
                          FROM health_records hr
                          JOIN users u ON hr.doctor_id = u.id
                          WHERE hr.pet_id = :pet_id 
                          ORDER BY hr.visit_date DESC, hr.created_at DESC');
        $this->db->bind(':pet_id', $pet_id);
        return $this->db->resultSet();
    }

    // Lấy chi tiết một hồ sơ khám bệnh
    public function getRecordById($id) {
        $this->db->query('SELECT hr.*, u.fullname as doctor_name 
                          FROM health_records hr
                          JOIN users u ON hr.doctor_id = u.id
                          WHERE hr.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Thêm hồ sơ khám bệnh mới
    public function addRecord($data) {
        $this->db->query('INSERT INTO health_records (pet_id, appointment_id, doctor_id, diagnosis, treatment, notes, visit_date) 
                          VALUES (:pet_id, :appointment_id, :doctor_id, :diagnosis, :treatment, :notes, :visit_date)');
        
        $this->db->bind(':pet_id', $data['pet_id']);
        $this->db->bind(':appointment_id', !empty($data['appointment_id']) ? $data['appointment_id'] : null);
        $this->db->bind(':doctor_id', $data['doctor_id']);
        $this->db->bind(':diagnosis', $data['diagnosis']);
        $this->db->bind(':treatment', $data['treatment']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':visit_date', $data['visit_date']);

        return $this->db->execute();
    }

    // Xóa hồ sơ khám bệnh
    public function deleteRecord($id) {
        $this->db->query('DELETE FROM health_records WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
