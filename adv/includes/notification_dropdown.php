<?php
include_once __DIR__ . '/../db_con/dbCon.php';

if (!function_exists('get_unread_notifications_count')) {
    function get_unread_notifications_count($user_id) {
        $conn = connect();
        $res = mysqli_query($conn, "SELECT COUNT(*) as count FROM notifications WHERE user_id = '$user_id' AND is_read = 0");
        $data = mysqli_fetch_assoc($res);
        return $data ? $data['count'] : 0;
    }
}

if (!function_exists('get_notifications')) {
    function get_notifications($user_id, $limit = 10) {
        $conn = connect();
        $res = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC LIMIT $limit");
        $notifications = [];
        while($row = mysqli_fetch_assoc($res)) {
            $notifications[] = $row;
        }
        return $notifications;
    }
}

$u_id = '';
if(isset($_SESSION['lawyer_id'])) $u_id = $_SESSION['lawyer_id'];
else if(isset($_SESSION['client_id'])) $u_id = $_SESSION['client_id'];
else if(isset($_SESSION['firm_id'])) $u_id = $_SESSION['firm_id'];

$count = get_unread_notifications_count($u_id);
?>

<li class="nav-item mr-3">
    <a class="nav-link position-relative" href="javascript:void(0)" onclick="toggleNotifications()" id="notifBell" style="cursor: pointer;">
        <i class="fas fa-bell"></i>
        <?php if($count > 0): ?>
            <span class="badge badge-danger badge-pill position-absolute" style="top: 0; right: 0; font-size: 0.6rem;"><?php echo $count; ?></span>
        <?php endif; ?>
    </a>
</li>