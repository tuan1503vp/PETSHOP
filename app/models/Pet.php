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

    // Thêm thú cưng mới
    public function addPet($data) {
        $this->db->query('INSERT INTO pets (customer_id, name, species, breed, age, gender, weight, image) 
                          VALUES (:customer_id, :name, :species, :breed, :age, :gender, :weight, :image)');
        
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':species', $data['species']);
        $this->db->bind(':breed', $data['breed']);
        $this->db->bind(':age', $data['age']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':weight', $data['weight']);
        $this->db->bind(':image', $data['image']);

        return $this->db->execute();
    }
}
