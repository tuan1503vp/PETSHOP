<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$db->query("SELECT id, fullname FROM users WHERE role = 'customer'");
$users = $db->resultSet();

foreach ($users as $u) {
    echo "User: {$u->fullname} (ID: {$u->id})\n";
    
    $db->query("SELECT SUM(total_amount) as total FROM orders WHERE customer_id = :id AND status = 'completed'");
    $db->bind(':id', $u->id);
    $order_sum = $db->single()->total ?? 0;
    echo "  Orders Sum: " . number_format($order_sum) . "\n";
    
    $db->query("SELECT SUM(final_price) as total FROM appointments WHERE customer_id = :id AND status = 'completed'");
    $db->bind(':id', $u->id);
    $app_sum = $db->single()->total ?? 0;
    echo "  Appts Sum: " . number_format($app_sum) . "\n";
    
    echo "  Total: " . number_format($order_sum + $app_sum) . "\n\n";
}

$db->query("SELECT id, customer_name, total_amount FROM orders WHERE customer_id IS NULL");
$pos_orders = $db->resultSet();
echo "POS Orders (No Customer ID):\n";
foreach ($pos_orders as $po) {
    echo "  Order #{$po->id}: {$po->customer_name} - " . number_format($po->total_amount) . "\n";
}
