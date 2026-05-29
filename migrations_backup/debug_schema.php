<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$db->query("DESCRIBE orders");
$res = $db->resultSet();
print_r($res);
