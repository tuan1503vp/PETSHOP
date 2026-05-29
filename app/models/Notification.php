<?php
class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function add($data) {
        $this->db->query('INSERT INTO notifications (user_id, title, message, type) VALUES(:user_id, :title, :message, :type)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':type', $data['type']);
        return $this->db->execute();
    }

    public function getNotificationsByUser($user_id) {
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 20');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getUnreadCount($user_id) {
        $this->db->query('SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = 0');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row ? $row->count : 0;
    }

    public function markAsRead($id) {
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function markAllAsRead($user_id) {
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }

    public function deleteAll($user_id) {
        $this->db->query('DELETE FROM notifications WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }
}
