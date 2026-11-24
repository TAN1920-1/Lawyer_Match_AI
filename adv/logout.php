<?php
//logout
include_once 'includes/session_init.php';
session_unset();
session_destroy();

// Also clear the cookies
$session_names = ['USERSESSID', 'LAWYERSESSID', 'ADMINSESSID', 'FIRMSESSID', session_name()];
foreach ($session_names as $name) {
    if (isset($_COOKIE[$name])) {
        setcookie($name, '', time() - 3600, '/');
    }
}

header('location:login.php');
?>
