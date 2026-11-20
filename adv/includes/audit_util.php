<?php
include_once __DIR__ . '/../db_con/dbCon.php';

function log_firm_action($action, $admin_id, $target_id = null, $details = null) {
    $conn = connect();
    if (!$conn) return false;

    $action = mysqli_real_escape_string($conn, $action);
    $admin_id = mysqli_real_escape_string($conn, $admin_id);
    $target_id = $target_id ? "'" . mysqli_real_escape_string($conn, $target_id) . "'" : "NULL";
    $details = $details ? "'" . mysqli_real_escape_string($conn, $details) . "'" : "NULL";

    $sql = "INSERT INTO audit_logs (action, admin_id, target_id, details) VALUES ('$action', '$admin_id', $target_id, $details)";
    return mysqli_query($conn, $sql);
}
?>
