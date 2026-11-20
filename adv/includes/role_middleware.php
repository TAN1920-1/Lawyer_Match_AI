<?php
require_once __DIR__ . '/session_init.php';

function check_role($allowed_roles) {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== TRUE) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json', true, 401);
            echo json_encode(['error' => 'Unauthorized. Please login again.']);
            exit;
        }
        // Redirect to login at root
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = '/adv/login.php'; // Hardcoded for this project structure
        header("Location: $protocol://$host$path");
        exit;
    }

    if (!in_array($_SESSION['role'], $allowed_roles)) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json', true, 403);
            echo json_encode(['error' => 'Access Denied. You do not have permission.']);
            exit;
        }
        echo "Access Denied. You do not have permission to view this page.";
        exit;
    }
}

function is_firm_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'FIRM_ADMIN';
}

function is_firm_lawyer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'FIRM_LAWYER';
}
?>
