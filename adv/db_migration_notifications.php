<?php
include_once 'db_con/dbCon.php';
$conn = connect();

// Create notifications table
$sql_notifications = "CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    booking_id INT,
    type VARCHAR(50), -- e.g., 'new_booking', 'booking_accepted', 'booking_rejected'
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_notifications)) {
    echo "Table 'notifications' created successfully.\n";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "\n";
}
?>
