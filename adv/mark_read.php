<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';

if(isset($_SESSION['login']) && $_SESSION['login'] == TRUE) {
    $conn = connect();
    $u_id = '';
    if(isset($_SESSION['lawyer_id'])) $u_id = $_SESSION['lawyer_id'];
    else if(isset($_SESSION['client_id'])) $u_id = $_SESSION['client_id'];
    else if(isset($_SESSION['firm_id'])) $u_id = $_SESSION['firm_id'];
    
    if($u_id !== '') {
        mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = '$u_id'");
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
