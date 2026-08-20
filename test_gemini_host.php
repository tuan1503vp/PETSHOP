<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "<h1>Test Gemini API tren Hosting</h1>";
$secrets_file = __DIR__ . '/app/config/secrets.php';
if (file_exists($secrets_file)) require_once $secrets_file;
$apiKey = defined('GEMINI_API_KEY') ? trim(GEMINI_API_KEY) : '';
if (empty($apiKey)) { echo "<p>Loi: Khong co GEMINI_API_KEY</p>"; exit; }
$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . $apiKey;
$payload = ["contents" => [["role" => "user", "parts" => [["text" => "Test from Vietnam hosting"]]]]];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$err = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "<ul><li>HTTP Code: {$httpCode}</li><li>cURL Error: {$err}</li></ul>";
echo "<h3>Response:</h3><textarea style='width:100%; height:200px;'>" . htmlspecialchars($response) . "</textarea>";
?>
