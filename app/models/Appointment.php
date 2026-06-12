<?php
class Appointment {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Đặt lịch mới
    public function book($data) {
        $this->db->query('INSERT INTO appointments (customer_id, pet_id, service_id, doctor_id, appointment_date, appointment_time, duration_value, duration_unit, notes) 
                          VALUES (:customer_id, :pet_id, :service_id, :doctor_id, :appointment_date, :appointment_time, :duration_value, :duration_unit, :notes)');
        
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':pet_id', $data['pet_id']);
        $this->db->bind(':service_id', $data['service_id']);
        $this->db->bind(':doctor_id', $data['doctor_id']);
        $this->db->bind(':appointment_date', $data['appointment_date']);
        $this->db->bind(':appointment_time', $data['appointment_time']);
        $this->db->bind(':duration_value', $data['duration_value'] ?? 1);
        $this->db->bind(':duration_unit', $data['duration_unit'] ?? 'none');
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    // Lấy lịch hẹn của khách hàng
    public function getAppointmentsByCustomer($customer_id) {
        $this->db->query('SELECT a.*, s.name as service_name, p.name as pet_name, u.fullname as doctor_name
                          FROM appointments a
                          JOIN services s ON a.service_id = s.id
                          LEFT JOIN pets p ON a.pet_id = p.id
                          LEFT JOIN users u ON a.doctor_id = u.id
                          WHERE a.customer_id = :customer_id
                          ORDER BY a.appointment_date DESC, a.appointment_time DESC');
        
        $this->db->bind(':customer_id', $customer_id);
        return $this->db->resultSet();
    }

    public function getAllAppointments($filters = []) {
        $sql = 'SELECT a.*, s.name as service_name, s.price as service_price, cat.name as category_name, 
                       COALESCE(c.fullname, a.customer_name) as customer_name, 
                       c.email as customer_email, 
                       COALESCE(m.phone, a.customer_phone) as customer_phone, 
                       p.name as pet_name, p.species as pet_species, u.fullname as doctor_name
                          FROM appointments a
                          JOIN services s ON a.service_id = s.id
                          LEFT JOIN categories cat ON s.category_id = cat.id
                          LEFT JOIN users c ON a.customer_id = c.id
                          LEFT JOIN members m ON c.id = m.user_id
                          LEFT JOIN pets p ON a.pet_id = p.id
                          LEFT JOIN users u ON a.doctor_id = u.id';
        
        $whereClauses = [];
        if (isset($filters['status_not'])) {
            $whereClauses[] = 'a.status != :status_not';
        }
        if (isset($filters['status'])) {
            $whereClauses[] = 'a.status = :status';
        }

        if (!empty($whereClauses)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
        }

        $sql .= " ORDER BY 
                  CASE 
                    WHEN a.status = 'pending' THEN 1 
                    WHEN a.status = 'confirmed' AND a.final_price IS NULL THEN 2 
                    WHEN a.status = 'confirmed' AND a.final_price IS NOT NULL THEN 3 
                    WHEN a.status = 'completed' THEN 4 
                    ELSE 5 
                  END ASC, 
                  a.appointment_date DESC, a.appointment_time DESC";

        $this->db->query($sql);

        if (isset($filters['status_not'])) {
            $this->db->bind(':status_not', $filters['status_not']);
        }
        if (isset($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }

        return $this->db->resultSet();
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE appointments SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAppointmentById($id) {
        $this->db->query('SELECT a.*, s.name as service_name, cat.name as category_name, 
                          COALESCE(c.fullname, a.customer_name) as customer_name, 
                          c.email as customer_email, 
                          COALESCE(m.phone, a.customer_phone) as customer_phone, 
                          p.name as pet_name, p.species as pet_species, u.fullname as doctor_name
                          FROM appointments a
                          JOIN services s ON a.service_id = s.id
                          LEFT JOIN categories cat ON s.category_id = cat.id
                          LEFT JOIN users c ON a.customer_id = c.id
                          LEFT JOIN members m ON c.id = m.user_id
                          LEFT JOIN pets p ON a.pet_id = p.id
                          LEFT JOIN users u ON a.doctor_id = u.id
                          WHERE a.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Kiểm tra bác sĩ có rảnh vào thời gian này không
    public function checkDoctorAvailability($doctor_id, $date, $time) {
        $targetDateTime = $date . ' ' . $time;
        $this->db->query('SELECT id FROM appointments 
                          WHERE doctor_id = :doctor_id 
                          AND status NOT IN ("cancelled", "completed")
                          AND (
                              (appointment_date = :date AND appointment_time = :time)
                              OR (CONCAT(appointment_date, " ", appointment_time) <= :target_datetime)
                          )');
        $this->db->bind(':doctor_id', $doctor_id);
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);
        $this->db->bind(':target_datetime', $targetDateTime);
        
        $this->db->single();
        return ($this->db->rowCount() == 0);
    }

    // Tìm bác sĩ rảnh vào thời gian này
    public function findAvailableDoctor($date, $time) {
        $targetDateTime = $date . ' ' . $time;
        // Lấy danh sách bác sĩ rảnh (không có lịch hẹn nào trùng giờ hoặc chưa hoàn thành trước đó)
        $this->db->query('SELECT id FROM users 
                          WHERE role = "doctor" 
                          AND id NOT IN (
                              SELECT doctor_id FROM appointments 
                              WHERE doctor_id IS NOT NULL 
                              AND status NOT IN ("cancelled", "completed")
                              AND (
                                  (appointment_date = :date AND appointment_time = :time)
                                  OR (CONCAT(appointment_date, " ", appointment_time) <= :target_datetime)
                              )
                          )
                          LIMIT 1');
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);
        $this->db->bind(':target_datetime', $targetDateTime);
        
        $row = $this->db->single();
        return ($row) ? $row->id : false;
    }

    // Lấy danh sách nhân viên rảnh vào thời gian này theo role (doctor / staff)
    public function getAvailableUsersByRole($date, $time, $role) {
        $targetDateTime = $date . ' ' . $time;
        $this->db->query('SELECT id, fullname, role FROM users 
                          WHERE role = :role 
                          AND id NOT IN (
                              SELECT doctor_id FROM appointments 
                              WHERE doctor_id IS NOT NULL 
                              AND status NOT IN ("cancelled", "completed")
                              AND (
                                  (appointment_date = :date AND appointment_time = :time)
                                  OR (CONCAT(appointment_date, " ", appointment_time) <= :target_datetime)
                              )
                          )');
        $this->db->bind(':date', $date);
        $this->db->bind(':time', $time);
        $this->db->bind(':target_datetime', $targetDateTime);
        $this->db->bind(':role', $role);
        
        return $this->db->resultSet();
    }

    // Lấy tất cả lịch hẹn đã hoàn thành
    public function getAllCompletedAppointments() {
        $this->db->query('SELECT a.*, s.name as service_name, 
                          COALESCE(c.fullname, a.customer_name) as customer_name, 
                          p.name as pet_name, u.fullname as doctor_name
                          FROM appointments a
                          JOIN services s ON a.service_id = s.id
                          LEFT JOIN users c ON a.customer_id = c.id
                          LEFT JOIN pets p ON a.pet_id = p.id
                          LEFT JOIN users u ON a.doctor_id = u.id
                          WHERE a.status = "completed"
                          ORDER BY a.appointment_date DESC, a.appointment_time DESC');
        return $this->db->resultSet();
    }

    // Lấy các lịch hẹn đã hoàn thành của một nhân viên/bác sĩ cụ thể
    public function getCompletedAppointmentsByDoctor($doctor_id, $month = null, $year = null) {
        $sql = 'SELECT a.*, s.name as service_name, 
                COALESCE(c.fullname, a.customer_name) as customer_name, p.name as pet_name
                FROM appointments a
                JOIN services s ON a.service_id = s.id
                LEFT JOIN users c ON a.customer_id = c.id
                LEFT JOIN pets p ON a.pet_id = p.id
                WHERE a.status = "completed" AND a.doctor_id = :doctor_id';
        
        if ($month && $year) {
            $sql .= ' AND MONTH(a.appointment_date) = :month AND YEAR(a.appointment_date) = :year';
        }
        
        $sql .= ' ORDER BY a.appointment_date DESC, a.appointment_time DESC';

        $this->db->query($sql);
        $this->db->bind(':doctor_id', $doctor_id);
        
        if ($month && $year) {
            $this->db->bind(':month', $month);
            $this->db->bind(':year', $year);
        }

        return $this->db->resultSet();
    }

    // Phân công bác sĩ cho lịch hẹn
    public function assignDoctor($appointment_id, $doctor_id) {
        $this->db->query('UPDATE appointments SET doctor_id = :doctor_id, status = "confirmed" WHERE id = :id');
        $this->db->bind(':doctor_id', $doctor_id);
        $this->db->bind(':id', $appointment_id);
        return $this->db->execute();
    }

    // Cập nhật số tiền thực tế sau khi khám xong (Chờ thanh toán)
    public function updateFinalPrice($id, $price) {
        $this->db->query('UPDATE appointments SET final_price = :price WHERE id = :id');
        $this->db->bind(':price', $price);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAppointmentsForDoctor($doctor_id) {
        $this->db->query('SELECT a.*, s.name as service_name, cat.name as category_name, 
                          COALESCE(c.fullname, a.customer_name) as customer_name, 
                          c.email as customer_email, 
                          COALESCE(m.phone, a.customer_phone) as customer_phone,
                          p.name as pet_name, p.species as pet_species, u.fullname as doctor_name
                          FROM appointments a
                          JOIN services s ON a.service_id = s.id
                          LEFT JOIN categories cat ON s.category_id = cat.id
                          LEFT JOIN users c ON a.customer_id = c.id
                          LEFT JOIN members m ON c.id = m.user_id
                          LEFT JOIN pets p ON a.pet_id = p.id
                          LEFT JOIN users u ON a.doctor_id = u.id
                          WHERE (a.doctor_id IS NULL AND a.status = "pending" AND LOWER(cat.name) LIKE "%khám%")
                             OR (a.doctor_id = :doctor_id AND a.status IN ("pending","confirmed"))
                          ORDER BY a.appointment_date ASC, a.appointment_time ASC');
        $this->db->bind(':doctor_id', $doctor_id);
        return $this->db->resultSet();
    }

    public function getCompletedByDoctor($doctor_id) {
        $this->db->query('SELECT a.*, s.name as service_name, 
                          COALESCE(c.fullname, a.customer_name) as customer_name, 
                          c.email as customer_email, 
                          COALESCE(m.phone, a.customer_phone) as customer_phone,
                          p.name as pet_name, p.species as pet_species, u.fullname as doctor_name
                          FROM appointments a
                          JOIN services s ON a.service_id = s.id
                          LEFT JOIN users c ON a.customer_id = c.id
                          LEFT JOIN members m ON c.id = m.user_id
                          LEFT JOIN pets p ON a.pet_id = p.id
                          LEFT JOIN users u ON a.doctor_id = u.id
                          WHERE a.doctor_id = :doctor_id AND a.status = "completed"
                          ORDER BY a.appointment_date DESC, a.appointment_time DESC');
        $this->db->bind(':doctor_id', $doctor_id);
        return $this->db->resultSet();
    }

    public function getStaffSchedules() {
        $this->db->query('SELECT a.appointment_date, a.appointment_time, a.status, a.doctor_id,
                          u.id as staff_id, u.fullname as staff_name, u.role as staff_role,
                          s.name as service_name, COALESCE(c.fullname, a.customer_name) as customer_name
                          FROM appointments a
                          JOIN users u ON a.doctor_id = u.id
                          JOIN services s ON a.service_id = s.id
                          LEFT JOIN users c ON a.customer_id = c.id
                          WHERE a.status IN ("pending","confirmed")
                          AND a.appointment_date >= CURDATE()
                          ORDER BY u.fullname ASC, a.appointment_date ASC, a.appointment_time ASC');
        return $this->db->resultSet();
    }
}
