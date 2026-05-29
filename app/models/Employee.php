<?php
class Employee {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Lấy danh sách toàn bộ nhân viên (JOIN với users để lấy role)
    public function getEmployees() {
        $this->db->query('SELECT e.*, u.email, u.role 
                          FROM employees e 
                          JOIN users u ON e.user_id = u.id 
                          ORDER BY e.created_at DESC');
        return $this->db->resultSet();
    }

    // Lấy thông tin nhân viên theo ID
    public function getEmployeeById($id) {
        $this->db->query('SELECT e.*, u.email, u.role 
                          FROM employees e 
                          JOIN users u ON e.user_id = u.id 
                          WHERE e.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Thêm nhân viên mới
    public function addEmployee($data) {
        $this->db->query('INSERT INTO employees (user_id, employee_code, fullname, cccd, address, image) 
                          VALUES (:user_id, :employee_code, :fullname, :cccd, :address, :image)');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':employee_code', $data['employee_code']);
        $this->db->bind(':fullname', $data['fullname']);
        $this->db->bind(':cccd', $data['cccd']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':image', $data['image']);

        return $this->db->execute();
    }

    // Xóa nhân viên
    public function deleteEmployee($id) {
        // Lấy user_id trước khi xóa
        $this->db->query('SELECT user_id FROM employees WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if ($row) {
            // Xóa user (on delete cascade sẽ xóa employee)
            $this->db->query('DELETE FROM users WHERE id = :user_id');
            $this->db->bind(':user_id', $row->user_id);
            return $this->db->execute();
        }
        return false;
    }

    // Lấy thông tin nhân viên theo User ID
    public function getEmployeeByUserId($user_id) {
        $this->db->query('SELECT * FROM employees WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }
}
