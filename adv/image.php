<?php
/**
 * Streams an image that was stored in TiDB back to the browser.
 * Usage: image.php?f=<stored file name>
 */

require_once __DIR__ . '/db_con/dbCon.php';

$name = isset($_GET['f']) ? basename($_GET['f']) : '';
if ($name === '') {
    http_response_code(404);
    exit;
}

$con = connect();
if (!$con) {
    http_response_code(500);
    exit;
}

$stmt = @mysqli_prepare($con, "SELECT mime_type, image_data FROM uploaded_images WHERE image_name = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $name);
    mysqli_stmt_execute($stmt);
    $mime = '';
    $data = '';
    mysqli_stmt_bind_result($stmt, $mime, $data);
    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        header('Content-Type: ' . ($mime ? $mime : 'application/octet-stream'));
        header('Cache-Control: public, max-age=86400');
        header('Content-Length: ' . strlen($data));
        echo $data;
        exit;
    }
    mysqli_stmt_close($stmt);
}

// Fall back to the copy that ships with the repository, if any.
$path = __DIR__ . '/images/upload/' . $name;
if (is_file($path)) {
    $mime = function_exists('mime_content_type') ? @mime_content_type($path) : '';
    header('Content-Type: ' . ($mime ? $mime : 'application/octet-stream'));
    header('Cache-Control: public, max-age=86400');
    readfile($path);
    exit;
}

// Final fallback: show the shared placeholder so no image ever renders broken.
$placeholder = __DIR__ . '/images/random.png';
if (is_file($placeholder)) {
    $mime = function_exists('mime_content_type') ? @mime_content_type($placeholder) : '';
    header('Content-Type: ' . ($mime ? $mime : 'image/png'));
    header('Cache-Control: public, max-age=86400');
    header('Content-Length: ' . filesize($placeholder));
    readfile($placeholder);
    exit;
}

http_response_code(404);
