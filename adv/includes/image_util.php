<?php
/**
 * Image storage helpers.
 *
 * The Vercel container filesystem is ephemeral, so images uploaded at runtime
 * are stored in TiDB (the `uploaded_images` table) instead of on disk.
 * Images that ship with the repository (in images/upload/) are still served
 * straight from disk for speed.
 */

require_once __DIR__ . '/../db_con/dbCon.php';

/**
 * Persist an uploaded file into the `uploaded_images` table.
 *
 * @param string $tmpPath      $_FILES[...]['tmp_name']
 * @param string $filename     Stored file name (e.g. 20260301150047_logo.jpg)
 * @param string $fallbackMime MIME used when it cannot be detected
 * @return bool true on success
 */
function db_store_uploaded_image($tmpPath, $filename, $fallbackMime = 'application/octet-stream')
{
    $filename = trim((string) $filename);
    if ($filename === '' || !is_file($tmpPath)) {
        return false;
    }

    $data = @file_get_contents($tmpPath);
    if ($data === false) {
        return false;
    }

    $mime = '';
    if (function_exists('mime_content_type')) {
        $mime = @mime_content_type($tmpPath);
    }
    if (!$mime) {
        $mime = $fallbackMime;
    }

    $con = connect();
    if (!$con) {
        return false;
    }

    $stmt = @mysqli_prepare(
        $con,
        "INSERT INTO uploaded_images (image_name, mime_type, image_data) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE mime_type = VALUES(mime_type), image_data = VALUES(image_data)"
    );
    if (!$stmt) {
        return false;
    }

    $null = null;
    mysqli_stmt_bind_param($stmt, 'ssb', $filename, $mime, $null);
    mysqli_stmt_send_long_data($stmt, 2, $data);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return (bool) $ok;
}

/**
 * Build a usable <img> src for a stored image name.
 *
 * @param string $img      value stored in the database image column
 * @param string $fallback relative path used when no image is present
 * @return string
 */
function img_src($img, $fallback = 'images/random.png')
{
    $img = trim((string) $img);
    if ($img === '') {
        return $fallback;
    }

    // Repository images live on disk; runtime uploads live in TiDB.
    if (is_file(__DIR__ . '/../images/upload/' . $img)) {
        return 'images/upload/' . rawurlencode($img);
    }

    return 'image.php?f=' . rawurlencode($img);
}
