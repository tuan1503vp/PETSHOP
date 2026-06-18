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
        
        $this->db->bind(':pet_id', !empty($data['pet_id']) ? $data['pet_id'] : null);
        $this->db->bind(':appointment_id', !empty($data['appointment_id']) ? $data['appointment_id'] : null);
        $this->db->bind(':doctor_id', $data['doctor_id']);
        $this->db->bind(':diagnosis', $data['diagnosis']);
        $this->db->bind(':treatment', $data['treatment']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':visit_date', $data['visit_date']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Xóa hồ sơ khám bệnh
    public function deleteRecord($id) {
        $this->db->query('DELETE FROM health_records WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Lưu đơn thuốc cho hồ sơ y tế
    public function addPrescriptions($health_record_id, $prescriptions) {
        foreach ($prescriptions as $p) {
            if (empty($p['product_id']) || empty($p['quantity'])) continue;
            $this->db->query('INSERT INTO health_record_prescriptions (health_record_id, product_id, quantity, instruction) 
                              VALUES (:health_record_id, :product_id, :quantity, :instruction)');
            $this->db->bind(':health_record_id', $health_record_id);
            $this->db->bind(':product_id', $p['product_id']);
            $this->db->bind(':quantity', $p['quantity']);
            $this->db->bind(':instruction', !empty($p['instruction']) ? $p['instruction'] : null);
            $this->db->execute();
        }
        return true;
    }

    // Lấy đơn thuốc theo mã lịch hẹn (đồng bộ POS)
    public function getPrescriptionsByAppointment($appointment_id) {
        $this->db->query('SELECT hrp.*, p.name as product_name, p.price as product_price, p.stock_quantity
                          FROM health_record_prescriptions hrp
                          JOIN health_records hr ON hrp.health_record_id = hr.id
                          JOIN products p ON hrp.product_id = p.id
                          WHERE hr.appointment_id = :appointment_id');
        $this->db->bind(':appointment_id', $appointment_id);
        return $this->db->resultSet();
    }

    // Lấy đơn thuốc theo danh sách mã lịch hẹn (tối ưu hóa hiệu năng, tránh N+1 query)
    public function getPrescriptionsByAppointmentIds($appointment_ids) {
        if (empty($appointment_ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($appointment_ids), '?'));
        $this->db->query("SELECT hrp.*, hr.appointment_id, p.name as product_name, p.price as product_price, p.stock_quantity
                          FROM health_record_prescriptions hrp
                          JOIN health_records hr ON hrp.health_record_id = hr.id
                          JOIN products p ON hrp.product_id = p.id
                          WHERE hr.appointment_id IN ($placeholders)");
        foreach ($appointment_ids as $index => $id) {
            $this->db->bind($index + 1, $id);
        }
        return $this->db->resultSet();
    }

    // Lấy đơn thuốc theo hồ sơ bệnh án
    public function getPrescriptionsByRecord($health_record_id) {
        $this->db->query('SELECT hrp.*, p.name as product_name
                          FROM health_record_prescriptions hrp
                          JOIN products p ON hrp.product_id = p.id
                          WHERE hrp.health_record_id = :health_record_id');
        $this->db->bind(':health_record_id', $health_record_id);
        return $this->db->resultSet();
    }
}
