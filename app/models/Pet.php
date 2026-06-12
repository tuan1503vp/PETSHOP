<?php
class Pet {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Lấy danh sách thú cưng của khách hàng
    public function getPetsByCustomer($customer_id) {
        $this->db->query('SELECT * FROM pets WHERE customer_id = :customer_id ORDER BY name ASC');
        $this->db->bind(':customer_id', $customer_id);
        return $this->db->resultSet();
    }

    // Lấy thông tin chi tiết thú cưng theo ID
    public function getPetById($id) {
        $this->db->query('SELECT p.*, u.fullname as owner_name, u.email as owner_email, u.phone as owner_phone 
                          FROM pets p 
                          JOIN users u ON p.customer_id = u.id 
                          WHERE p.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Tạo mã thú cưng ngẫu nhiên và duy nhất
    public function generatePetCode() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $code = 'PET-';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            $this->db->query('SELECT id FROM pets WHERE pet_code = :pet_code');
            $this->db->bind(':pet_code', $code);
            $this->db->single();
            $exists = $this->db->rowCount() > 0;
        } while ($exists);
        return $code;
    }

    // Thêm thú cưng mới
    public function addPet($data) {
        $this->db->query('INSERT INTO pets (pet_code, customer_id, name, species, breed, age, gender, color, weight, image) 
                          VALUES (:pet_code, :customer_id, :name, :species, :breed, :age, :gender, :color, :weight, :image)');
        
        $this->db->bind(':pet_code', $data['pet_code']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':species', $data['species']);
        $this->db->bind(':breed', $data['breed']);
        $this->db->bind(':age', $data['age']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':weight', $data['weight']);
        $this->db->bind(':image', $data['image']);

        return $this->db->execute();
    }

    // Cập nhật thông tin thú cưng
    public function updatePet($data) {
        $this->db->query('UPDATE pets SET name = :name, species = :species, breed = :breed, age = :age, 
                          gender = :gender, color = :color, weight = :weight, image = :image WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':species', $data['species']);
        $this->db->bind(':breed', $data['breed']);
        $this->db->bind(':age', $data['age']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':weight', $data['weight']);
        $this->db->bind(':image', $data['image']);

        return $this->db->execute();
    }

    // Xóa thú cưng
    public function deletePet($id) {
        $this->db->query('DELETE FROM pets WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Lấy toàn bộ danh sách thú cưng (dùng cho Admin)
    public function getAllPetsForAdmin($search = '') {
        if (!empty($search)) {
            $this->db->query('SELECT p.*, u.fullname as owner_name, u.email as owner_email, u.phone as owner_phone 
                              FROM pets p 
                              JOIN users u ON p.customer_id = u.id 
                              WHERE p.pet_code LIKE :search 
                                 OR p.name LIKE :search 
                                 OR u.fullname LIKE :search 
                                 OR u.phone LIKE :search
                              ORDER BY p.created_at DESC');
            $this->db->bind(':search', '%' . $search . '%');
        } else {
            $this->db->query('SELECT p.*, u.fullname as owner_name, u.email as owner_email, u.phone as owner_phone 
                              FROM pets p 
                              JOIN users u ON p.customer_id = u.id 
                              ORDER BY p.created_at DESC');
        }
        return $this->db->resultSet();
    }
}
