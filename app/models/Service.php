<?php
class Service {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Lấy tất cả dịch vụ
    public function getServices() {
        $this->db->query('SELECT s.*, c.name as category_name 
                          FROM services s 
                          LEFT JOIN categories c ON s.category_id = c.id');
        
        return $this->db->resultSet();
    }

    // Lấy danh sách các danh mục thuộc loại dịch vụ
    public function getServiceCategories() {
        $this->db->query("SELECT * FROM categories WHERE type = 'service'");
        return $this->db->resultSet();
    }

    // Lấy dịch vụ theo danh mục
    public function getServicesByCategory($category_id) {
        $this->db->query('SELECT s.*, c.name as category_name 
                          FROM services s 
                          LEFT JOIN categories c ON s.category_id = c.id
                          WHERE s.category_id = :category_id');
        $this->db->bind(':category_id', $category_id);
        return $this->db->resultSet();
    }

    // Lấy chi tiết dịch vụ theo ID
    public function getServiceById($id) {
        $this->db->query('SELECT s.*, c.name as category_name 
                          FROM services s 
                          LEFT JOIN categories c ON s.category_id = c.id
                          WHERE s.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Thêm dịch vụ
    public function addService($data) {
        $this->db->query('INSERT INTO services (category_id, name, description, price, duration_minutes, image) VALUES (:category_id, :name, :description, :price, :duration_minutes, :image)');
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':duration_minutes', $data['duration_minutes']);
        $this->db->bind(':image', $data['image']);
        
        return $this->db->execute();
    }

    // Cập nhật dịch vụ
    public function updateService($data) {
        $sql = 'UPDATE services SET category_id = :category_id, name = :name, description = :description, price = :price, duration_minutes = :duration_minutes';
        
        if(!empty($data['image'])) {
            $sql .= ', image = :image';
        }
        
        $sql .= ' WHERE id = :id';
        
        $this->db->query($sql);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':duration_minutes', $data['duration_minutes']);
        
        if(!empty($data['image'])) {
            $this->db->bind(':image', $data['image']);
        }
        
        return $this->db->execute();
    }

    // Xóa dịch vụ
    public function deleteService($id) {
        $this->db->query('DELETE FROM services WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
