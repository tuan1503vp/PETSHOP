<?php
class Coin {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addCoins($user_id, $amount, $reason) {
        $this->db->query("UPDATE users SET coins = coins + :amount WHERE id = :user_id");
        $this->db->bind(':amount', $amount);
        $this->db->bind(':user_id', $user_id);
        
        if ($this->db->execute()) {
            $this->db->query("INSERT INTO coin_history (user_id, amount, reason) VALUES (:user_id, :amount, :reason)");
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':amount', $amount);
            $this->db->bind(':reason', $reason);
            return $this->db->execute();
        }
        return false;
    }

    public function deductCoins($user_id, $amount, $reason) {
        $this->db->query("UPDATE users SET coins = coins - :amount WHERE id = :user_id AND coins >= :amount");
        $this->db->bind(':amount', $amount);
        $this->db->bind(':user_id', $user_id);
        
        if ($this->db->execute() && $this->db->rowCount() > 0) {
            $this->db->query("INSERT INTO coin_history (user_id, amount, reason) VALUES (:user_id, :amount, :reason)");
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':amount', -$amount); // Negative for deduction
            $this->db->bind(':reason', $reason);
            return $this->db->execute();
        }
        return false;
    }

    public function getHistory($user_id) {
        $this->db->query("SELECT * FROM coin_history WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }
}
?>
