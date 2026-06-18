<?php
function is_valid_image($file_array) {
    if ($file_array['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file_array['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_exts)) {
        return false;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file_array['tmp_name']);
    finfo_close($finfo);
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowed_mimes)) {
        return false;
    }
    return true;
}

function compressImage($source, $destination, $maxWidth = 1200, $quality = 80) {
    $info = getimagesize($source);
    if ($info === false) {
        return move_uploaded_file($source, $destination);
    }

    $mime = $info['mime'];
    switch ($mime) {
        case 'image/jpeg':
            $image = @imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = @imagecreatefrompng($source);
            if ($image) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
            }
            break;
        case 'image/gif':
            $image = @imagecreatefromgif($source);
            break;
        case 'image/webp':
            $image = @imagecreatefromwebp($source);
            break;
        default:
            return move_uploaded_file($source, $destination);
    }

    if (!$image) {
        return move_uploaded_file($source, $destination);
    }

    $origWidth = imagesx($image);
    $origHeight = imagesy($image);

    if ($origWidth > $maxWidth) {
        $newWidth = $maxWidth;
        $newHeight = intval($origHeight * ($maxWidth / $origWidth));
        
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        if ($resizedImage) {
            if ($mime == 'image/png' || $mime == 'image/gif' || $mime == 'image/webp') {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $resizedImage;
        }
    }

    $success = false;
    switch ($mime) {
        case 'image/jpeg':
            $success = imagejpeg($image, $destination, $quality);
            break;
        case 'image/png':
            $pngQuality = 9 - round(($quality * 9) / 100);
            $success = imagepng($image, $destination, $pngQuality);
            break;
        case 'image/gif':
            $success = imagegif($image, $destination);
            break;
        case 'image/webp':
            $success = imagewebp($image, $destination, $quality);
            break;
        default:
            $success = false;
    }

    imagedestroy($image);
    return $success;
}

