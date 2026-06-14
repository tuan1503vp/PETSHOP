<?php
$ch = curl_init('https://openrouter.ai/api/v1/models');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
$data = json_decode($res, true);
foreach ($data['data'] as $m) {
    if (strpos($m['id'], ':free') !== false) {
        echo $m['id'] . "\n";
    }
}
