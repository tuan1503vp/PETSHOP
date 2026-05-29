<?php
require 'app/config/config.php';
require 'app/core/Database.php';
$db = new Database();
try {
    $db->query("DESCRIBE orders");
    $res = $db->resultSet();
    foreach($res as $col) {
        echo $col->Field . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
