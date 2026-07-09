<?php
class Contact {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getContacts() {
        $this->db->query("SELECT * FROM contacts ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function addContact($data) {
        $this->db->query("INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':message', $data['message']);
        return $this->db->execute();
    }

    public function updateStatus($id, $status, $replyMessage = null) {
        if ($status == 'replied' && $replyMessage !== null) {
            $this->db->query("UPDATE contacts SET status = :status, reply_message = :reply_message, replied_at = NOW() WHERE id = :id");
            $this->db->bind(':reply_message', $replyMessage);
        } else {
            $this->db->query("UPDATE contacts SET status = :status WHERE id = :id");
        }
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function getContactById($id) {
        $this->db->query("SELECT * FROM contacts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
