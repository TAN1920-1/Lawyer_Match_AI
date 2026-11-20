<?php
header('Content-Type: application/json');
include_once '../../db_con/dbCon.php';
include_once '../../includes/role_middleware.php';
include_once '../../includes/audit_util.php';

check_role(['FIRM_ADMIN']);

$conn = connect();
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$firm_id = $_SESSION['firm_id'];

if ($method === 'GET') {
    // List all bookings for the firm
    $sql = "SELECT b.*, u.first_Name as lawyer_name, u.last_Name as lawyer_last_name, c.first_Name as client_first, c.last_Name as client_last
            FROM booking b
            INNER JOIN user u ON b.lawyer_id = u.u_id
            INNER JOIN user c ON b.client_id = c.u_id
            WHERE u.firm_id = '$firm_id'
            ORDER BY b.date DESC";
    
    $result = mysqli_query($conn, $sql);
    $bookings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
    echo json_encode(['success' => true, 'bookings' => $bookings]);

} else if ($method === 'PATCH') {
    // Reassign booking
    $uri = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $uri);
    $booking_id = end($parts);
    
    $data = json_decode(file_get_contents('php://input'), true);
    $new_lawyer_id = $data['new_lawyer_id'];

    // Validate booking exists and belongs to firm
    $check_booking = mysqli_query($conn, "SELECT b.* FROM booking b INNER JOIN user u ON b.lawyer_id = u.u_id WHERE b.booking_id='$booking_id' AND u.firm_id='$firm_id'");
    if (mysqli_num_rows($check_booking) === 0) {
        echo json_encode(['error' => 'Booking not found or not part of your firm']);
        exit;
    }

    // Validate new lawyer belongs to same firm
    $check_lawyer = mysqli_query($conn, "SELECT * FROM user WHERE u_id='$new_lawyer_id' AND firm_id='$firm_id' AND role='FIRM_LAWYER'");
    if (mysqli_num_rows($check_lawyer) === 0) {
        echo json_encode(['error' => 'New lawyer not found or not part of your firm']);
        exit;
    }

    $sql = "UPDATE booking SET lawyer_id='$new_lawyer_id' WHERE booking_id='$booking_id'";
    if (mysqli_query($conn, $sql)) {
        log_firm_action('REASSIGN_BOOKING', $firm_id, $new_lawyer_id, "Reassigned booking $booking_id");
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to reassign booking']);
    }
} else {
    echo json_encode(['error' => 'Method not allowed']);
}
?>
