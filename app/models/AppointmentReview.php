<?php
class AppointmentReview {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addReview($data) {
        $this->db->query("INSERT INTO appointment_reviews (appointment_id, user_id, service_id, doctor_id, rating, comment) 
                          VALUES (:appointment_id, :user_id, :service_id, :doctor_id, :rating, :comment)");
        $this->db->bind(':appointment_id', $data['appointment_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':service_id', $data['service_id']);
        $this->db->bind(':doctor_id', $data['doctor_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);
        return $this->db->execute();
    }

    public function getReviewByAppointmentId($appointment_id) {
        $this->db->query("SELECT * FROM appointment_reviews WHERE appointment_id = :appointment_id");
        $this->db->bind(':appointment_id', $appointment_id);
        return $this->db->single();
    }

    public function getReviewsByServiceId($service_id) {
        $this->db->query("SELECT r.*, u.fullname as user_name, u.avatar as user_avatar, doc.fullname as doctor_name 
                          FROM appointment_reviews r 
                          JOIN users u ON r.user_id = u.id 
                          LEFT JOIN users doc ON r.doctor_id = doc.id 
                          WHERE r.service_id = :service_id 
                          ORDER BY r.created_at DESC");
        $this->db->bind(':service_id', $service_id);
        return $this->db->resultSet();
    }

    public function getAverageRatingForService($service_id) {
        $this->db->query("SELECT AVG(rating) as avg_rating, COUNT(id) as total_reviews FROM appointment_reviews WHERE service_id = :service_id");
        $this->db->bind(':service_id', $service_id);
        $row = $this->db->single();
        return [
            'rating' => $row ? round(floatval($row->avg_rating), 1) : 0,
            'count' => $row ? intval($row->total_reviews) : 0
        ];
    }
}
