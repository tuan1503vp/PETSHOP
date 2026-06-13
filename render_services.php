<?php
// Mock the session and view data
$_SESSION['user_id'] = 5;
$_SESSION['user_role'] = 'doctor';
$_SESSION['user_name'] = 'Heo men';

define('APPROOT', __DIR__ . '/app');
define('URLROOT', 'http://localhost/PETSHOP');

require_once 'app/config/config.php';
require_once 'app/helpers/session_helper.php';
require_once 'app/core/Database.php';
require_once 'app/models/Product.php';
require_once 'app/models/Appointment.php';

$productModel = new Product();
$products = $productModel->getProducts();

$appointmentModel = new Appointment();
$appointments = $appointmentModel->getAppointmentsForDoctor(5);

$data = [
    'appointments' => $appointments,
    'completed_appointments' => [],
    'is_doctor_view' => true,
    'busy_slots' => [],
    'work_minutes' => 0,
    'free_minutes' => 480,
    'products' => $products
];

// Capture view output
ob_start();
include 'app/views/admin/services.php';
$output = ob_get_clean();

// Find the x-data block
if (preg_match('/x-data="\{.*?\}"/s', $output, $matches)) {
    echo "Alpine x-data block:\n";
    echo $matches[0] . "\n\n";
} else {
    echo "Could not find x-data block!\n";
}

// Find button render
if (preg_match_all('/<button type="button" @click="openModal\(.*?\)"[^>]*>.*?<\/button>/s', $output, $buttonMatches)) {
    echo "Rendered buttons:\n";
    foreach ($buttonMatches[0] as $btn) {
        echo htmlspecialchars($btn) . "\n";
    }
} else {
    echo "No buttons matching openModal found!\n";
}
?>
