<?php
class Payroll {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPayrolls($month, $year) {
        // Query to get current month payroll or fallback to latest base salary from any previous month
        $this->db->query('SELECT u.id as user_id, u.fullname, u.role, e.employee_code, 
                                 p.base_salary, p.bonus, p.deductions,
                                 (SELECT base_salary FROM payrolls WHERE user_id = u.id ORDER BY year DESC, month DESC LIMIT 1) as last_base_salary
                          FROM users u 
                          LEFT JOIN employees e ON u.id = e.user_id
                          LEFT JOIN payrolls p ON u.id = p.user_id AND p.month = :month AND p.year = :year
                          WHERE u.role != "customer" AND u.role != "admin"
                          ORDER BY u.fullname ASC');
        $this->db->bind(':month', $month);
        $this->db->bind(':year', $year);
        return $this->db->resultSet();
    }

    public function savePayroll($data) {
        // Check if exists
        $this->db->query('SELECT id FROM payrolls WHERE user_id = :user_id AND month = :month AND year = :year');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':month', $data['month']);
        $this->db->bind(':year', $data['year']);
        $row = $this->db->single();

        if ($row) {
            $this->db->query('UPDATE payrolls SET base_salary = :base_salary, bonus = :bonus, deductions = :deductions WHERE id = :id');
            $this->db->bind(':id', $row->id);
        } else {
            $this->db->query('INSERT INTO payrolls (user_id, month, year, base_salary, bonus, deductions) VALUES (:user_id, :month, :year, :base_salary, :bonus, :deductions)');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':month', $data['month']);
            $this->db->bind(':year', $data['year']);
        }
        
        $this->db->bind(':base_salary', $data['base_salary']);
        $this->db->bind(':bonus', $data['bonus']);
        $this->db->bind(':deductions', $data['deductions']);
        return $this->db->execute();
    }
}
