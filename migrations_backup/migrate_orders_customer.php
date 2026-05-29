<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
try {
    $db->query("ALTER TABLE orders ADD COLUMN customer_name VARCHAR(255) DEFAULT NULL AFTER customer_id, ADD COLUMN customer_phone VARCHAR(20) DEFAULT NULL AFTER customer_name");
    $db->execute();
    echo "Success";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
