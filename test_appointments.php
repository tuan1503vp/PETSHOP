<?php
require_once 'app/config/config.php';
require_once 'app/helpers/session_helper.php';
require_once 'app/core/Database.php';
require_once 'app/core/Controller.php';
require_once 'app/models/Product.php';

$productModel = new Product();
$products = $productModel->getProducts();

try {
    $mapped = array_map(function($p) {
        $expiry_text = '';
        $is_expired = false;
        $is_near_expiry = false;
        if (!empty($p->expiry_date)) {
            $expiry_time = strtotime($p->expiry_date);
            $now = time();
            $diff_days = ($expiry_time - $now) / (60 * 60 * 24);
            if ($expiry_time < $now) {
                $is_expired = true;
                $expiry_text = ' (HẾT HẠN - ' . date('d/m/Y', $expiry_time) . ')';
            } elseif ($diff_days <= 30) {
                $is_near_expiry = true;
                $expiry_text = ' (SẮP HẾT HẠN - ' . date('d/m/Y', $expiry_time) . ')';
            } else {
                $expiry_text = ' (HSD: ' . date('d/m/Y', $expiry_time) . ')';
            }
        }
        return [
            'id' => (int)$p->id,
            'name' => $p->name . $expiry_text,
            'price' => (float)$p->price,
            'stock' => (int)$p->stock_quantity,
            'is_expired' => $is_expired,
            'is_near_expiry' => $is_near_expiry
        ];
    }, $products);
    
    echo "SUCCESS! Mapped " . count($mapped) . " products.\n";
    echo "First 3 products:\n";
    print_r(array_slice($mapped, 0, 3));
} catch (Throwable $e) {
    echo "ERROR! " . $e->getMessage() . "\n";
}
?>
