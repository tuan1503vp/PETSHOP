<?php
class Category {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getCategoriesByType($type) {
        $this->db->query("SELECT * FROM categories WHERE type = :type ORDER BY name ASC");
        $this->db->bind(':type', $type);
        return $this->db->resultSet();
    }

    public function addCategory($data) {
        $this->db->query("INSERT INTO categories (name, type, description) VALUES (:name, :type, :description)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':description', $data['description']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    public function deleteCategoryByName($name, $type) {
        $this->db->query("DELETE FROM categories WHERE name = :name AND type = :type");
        $this->db->bind(':name', $name);
        $this->db->bind(':type', $type);
        return $this->db->execute();
    }
}
