<?php
function e($string) {
    if (is_null($string)) return '';
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function clean_price($price_str) {
    if (is_null($price_str) || $price_str === '') return 0;
    $price_str = trim((string)$price_str);
    $price_str = str_replace(' ', '', $price_str);
    if (preg_match('/[.,]00$/', $price_str)) {
        $price_str = substr($price_str, 0, -3);
    }
    $has_dot = (strpos($price_str, '.') !== false);
    $has_comma = (strpos($price_str, ',') !== false);
    if ($has_dot && $has_comma) {
        if (strrpos($price_str, '.') > strrpos($price_str, ',')) {
            $price_str = str_replace(',', '', $price_str);
        } else {
            $price_str = str_replace('.', '', $price_str);
            $price_str = str_replace(',', '.', $price_str);
        }
    } elseif ($has_dot) {
        $price_str = str_replace('.', '', $price_str);
    } elseif ($has_comma) {
        $price_str = str_replace(',', '', $price_str);
    }
    return floatval($price_str);
}
