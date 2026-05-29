<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$db->query("SELECT a.id, a.final_price, s.name, s.price as service_price, cat.name as category_name 
            FROM appointments a 
            JOIN services s ON a.service_id = s.id 
            LEFT JOIN categories cat ON s.category_id = cat.id 
            WHERE a.status = 'confirmed'
            LIMIT 5");
$res = $db->resultSet();
header('Content-Type: text/plain');
print_r($res);
