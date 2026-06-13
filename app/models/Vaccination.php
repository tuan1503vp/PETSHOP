<?php
class Vaccination {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Lấy danh sách lịch sử tiêm chủng của bé
    public function getVaccinationsByPet($pet_id) {
        $this->db->query('SELECT * FROM pet_vaccinations WHERE pet_id = :pet_id ORDER BY vaccinated_date DESC, created_at DESC');
        $this->db->bind(':pet_id', $pet_id);
        return $this->db->resultSet();
    }

    // Lấy thông tin chi tiết một mũi tiêm
    public function getVaccinationById($id) {
        $this->db->query('SELECT * FROM pet_vaccinations WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Thêm mũi tiêm phòng mới
    public function addVaccination($data) {
        $this->db->query('INSERT INTO pet_vaccinations (pet_id, vaccine_name, vaccinated_date, next_due_date, notes, appointment_id, weight, temperature, batch_number, veterinarian_name, test_result, reaction_notes) 
                          VALUES (:pet_id, :vaccine_name, :vaccinated_date, :next_due_date, :notes, :appointment_id, :weight, :temperature, :batch_number, :veterinarian_name, :test_result, :reaction_notes)');
        
        $this->db->bind(':pet_id', $data['pet_id'] ?? null);
        $this->db->bind(':vaccine_name', $data['vaccine_name'] ?? '');
        $this->db->bind(':vaccinated_date', $data['vaccinated_date'] ?? null);
        $this->db->bind(':next_due_date', !empty($data['next_due_date']) ? $data['next_due_date'] : null);
        $this->db->bind(':notes', !empty($data['notes']) ? $data['notes'] : null);
        $this->db->bind(':appointment_id', !empty($data['appointment_id']) ? $data['appointment_id'] : null);
        $this->db->bind(':weight', !empty($data['weight']) ? $data['weight'] : null);
        $this->db->bind(':temperature', !empty($data['temperature']) ? $data['temperature'] : null);
        $this->db->bind(':batch_number', !empty($data['batch_number']) ? $data['batch_number'] : null);
        $this->db->bind(':veterinarian_name', !empty($data['veterinarian_name']) ? $data['veterinarian_name'] : null);
        $this->db->bind(':test_result', !empty($data['test_result']) ? $data['test_result'] : null);
        $this->db->bind(':reaction_notes', !empty($data['reaction_notes']) ? $data['reaction_notes'] : null);

        return $this->db->execute();
    }

    // Xóa mũi tiêm
    public function deleteVaccination($id) {
        $this->db->query('DELETE FROM pet_vaccinations WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
