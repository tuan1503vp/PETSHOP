<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
try {
    $db->query("ALTER TABLE appointments MODIFY COLUMN duration_unit ENUM('hour', 'day', 'month', 'none') DEFAULT 'none'");
    $db->execute();
    echo "Success";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
