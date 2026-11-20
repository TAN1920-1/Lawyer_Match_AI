<?php
include_once __DIR__ . '/../db_con/dbCon.php';

if(isset($_SESSION['login']) && $_SESSION['login'] == TRUE){
    $u_id = '';
    if(isset($_SESSION['lawyer_id'])) $u_id = $_SESSION['lawyer_id'];
    else if(isset($_SESSION['client_id'])) $u_id = $_SESSION['client_id'];
    else if(isset($_SESSION['firm_id'])) $u_id = $_SESSION['firm_id'];
    
    // Function definitions if not already present
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
    
    $count = get_unread_notifications_count($u_id);
?>
<!-- Notification Side Panel Structure -->
<div class="notification-side-panel shadow-lg" id="notificationPanel">
    <div class="notif-header">
        <div class="d-flex align-items-center">
            <i class="fas fa-bell mr-2"></i>
            <h6 class="mb-0 font-weight-bold">Notifications</h6>
        </div>
        <button class="btn btn-sm text-white" onclick="toggleNotifications()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="notif-body">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white sticky-top">
            <span class="small text-muted font-weight-bold"><?php echo $count; ?> Unread</span>
            <?php if($count > 0): ?>
                <a href="mark_read.php" class="small text-primary">Mark all as read</a>
            <?php endif; ?>
        </div>
        
        <div class="notification-items bg-white">
        <?php 
        $notifs = get_notifications($u_id);
        if(empty($notifs)): 
        ?>
            <div class="p-5 text-center text-muted">
                <i class="fas fa-bell-slash d-block mb-3 opacity-30 fa-3x"></i>
                <p>No notifications yet</p>
            </div>
        <?php else: ?>
            <?php foreach($notifs as $n): 
                $bg = $n['is_read'] ? 'bg-white' : 'bg-light';
                $icon = 'fa-info-circle text-primary';
                if($n['type'] == 'new_booking') $icon = 'fa-calendar-plus text-success';
                if($n['type'] == 'booking_accepted') $icon = 'fa-check-circle text-success';
                if($n['type'] == 'booking_rejected') $icon = 'fa-times-circle text-danger';
            ?>
                <div class="p-3 border-bottom <?php echo $bg; ?> position-relative" style="cursor: default;">
                    <div class="d-flex">
                        <div class="mr-3 mt-1"><i class="fas <?php echo $icon; ?>"></i></div>
                        <div style="flex: 1;">
                            <div class="small font-weight-bold text-dark"><?php echo $n['title']; ?></div>
                            <div class="small text-muted mb-1" style="line-height: 1.3;"><?php echo $n['message']; ?></div>
                            <div class="x-small text-muted" style="font-size: 0.7rem;"><?php echo date('M d, H:i', strtotime($n['created_at'])); ?></div>
                        </div>
                    </div>
                    <?php if(!$n['is_read']): ?>
                        <span class="position-absolute" style="top: 15px; right: 15px; width: 8px; height: 8px; background: #2563eb; border-radius: 50%;"></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </div>
    <div class="p-3 text-center border-top bg-white mt-auto">
        <a href="<?php echo (isset($_SESSION['lawyer_id']) ? 'lawyer_booking.php' : 'user_booking.php'); ?>" class="btn btn-outline-primary btn-sm btn-block rounded-pill font-weight-bold">View All Bookings</a>
    </div>
</div>

<script>
function toggleNotifications() {
    const panel = document.getElementById('notificationPanel');
    if(panel) {
        panel.classList.toggle('active');
    }
}
</script>
<?php } ?>