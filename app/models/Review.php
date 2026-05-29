<?php
class Review {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getReviewsByProductId($product_id) {
        $this->db->query("SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = :product_id ORDER BY r.created_at DESC");
        $this->db->bind(':product_id', $product_id);
        return $this->db->resultSet();
    }

    public function getAverageRating($product_id) {
        $this->db->query("SELECT AVG(rating) as avg_rating, COUNT(id) as total_reviews FROM reviews WHERE product_id = :product_id");
        $this->db->bind(':product_id', $product_id);
        $row = $this->db->single();
        return [
            'avg' => $row->avg_rating ? round($row->avg_rating, 1) : 0,
            'count' => $row->total_reviews
        ];
    }

    public function addReview($data) {
        $this->db->query("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (:product_id, :user_id, :rating, :comment)");
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);
        return $this->db->execute();
    }
}
