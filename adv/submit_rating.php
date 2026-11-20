<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';

if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active' && $_SESSION['role'] == 'User') {
    $conn = connect();
    $client_id = $_SESSION['client_id'];
    $booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
    $lawyer_id = mysqli_real_escape_string($conn, $_POST['lawyer_id']);
    $rating = intval($_POST['rating']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        header("Location: user_booking.php?error=invalid_rating");
        exit;
    }

    // Check if booking is accepted
    $check_booking = mysqli_query($conn, "SELECT status FROM booking WHERE booking_id='$booking_id' AND client_id='$client_id'");
    $booking_data = mysqli_fetch_assoc($check_booking);
    
    if (!$booking_data || stripos($booking_data['status'], 'accept') === false) {
        header("Location: user_booking.php?error=not_accepted");
        exit;
    }

    // Check if already rated
    $check_rated = mysqli_query($conn, "SELECT id FROM lawyer_ratings WHERE booking_id='$booking_id'");
    if (mysqli_num_rows($check_rated) > 0) {
        header("Location: user_booking.php?error=already_rated");
        exit;
    }

    // Insert rating
    $sql = "INSERT INTO lawyer_ratings (booking_id, client_id, lawyer_id, rating, feedback) 
            VALUES ('$booking_id', '$client_id', '$lawyer_id', $rating, '$feedback')";
    
    if (mysqli_query($conn, $sql)) {
        // Update lawyer stats
        $stats_query = "SELECT AVG(rating) as avg_r, COUNT(*) as count_r FROM lawyer_ratings WHERE lawyer_id='$lawyer_id'";
        $stats_res = mysqli_query($conn, $stats_query);
        $stats = mysqli_fetch_assoc($stats_res);
        
        $avg = round($stats['avg_r'], 2);
        $count = $stats['count_r'];
        
        mysqli_query($conn, "UPDATE lawyer SET avg_rating=$avg, review_count=$count WHERE lawyer_id='$lawyer_id'");
        
        header("Location: user_booking.php?rated=success");
    } else {
        header("Location: user_booking.php?error=db_error");
    }
} else {
    header("Location: login.php");
}
?>
