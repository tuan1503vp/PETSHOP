<?php
class Attendance {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAttendanceByDate($date) {
        $this->db->query('SELECT u.id as user_id, u.fullname, u.role, e.employee_code, a.status, a.notes 
                          FROM users u 
                          LEFT JOIN employees e ON u.id = e.user_id
                          LEFT JOIN attendance a ON u.id = a.user_id AND a.date = :date
                          WHERE u.role != "customer" AND u.role != "admin"
                          ORDER BY u.fullname ASC');
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }

    public function saveAttendance($data) {
        // Check if exists
        $this->db->query('SELECT id FROM attendance WHERE user_id = :user_id AND date = :date');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':date', $data['date']);
        $row = $this->db->single();

        if ($row) {
            $this->db->query('UPDATE attendance SET status = :status, notes = :notes WHERE id = :id');
            $this->db->bind(':id', $row->id);
        } else {
            $this->db->query('INSERT INTO attendance (user_id, date, status, notes) VALUES (:user_id, :date, :status, :notes)');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':date', $data['date']);
        }
        
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }

    public function getAttendanceHistory($filters = []) {
        $sql = 'SELECT a.*, u.fullname, u.role, e.employee_code 
                FROM attendance a
                JOIN users u ON a.user_id = u.id
                LEFT JOIN employees e ON u.id = e.user_id
                WHERE 1=1';
        
        if (!empty($filters['user_id'])) {
            $sql .= ' AND a.user_id = :user_id';
        }
        if (!empty($filters['start_date'])) {
            $sql .= ' AND a.date >= :start_date';
        }
        if (!empty($filters['end_date'])) {
            $sql .= ' AND a.date <= :end_date';
        }
        
        $sql .= ' ORDER BY a.date DESC, u.fullname ASC';
        
        $this->db->query($sql);
        
        if (!empty($filters['user_id'])) $this->db->bind(':user_id', $filters['user_id']);
        if (!empty($filters['start_date'])) $this->db->bind(':start_date', $filters['start_date']);
        if (!empty($filters['end_date'])) $this->db->bind(':end_date', $filters['end_date']);
        
        return $this->db->resultSet();
    }
}
