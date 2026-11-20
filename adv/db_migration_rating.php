<?php
include_once 'db_con/dbCon.php';
$conn = connect();

// Create lawyer_ratings table
$sql_create_ratings = "CREATE TABLE IF NOT EXISTS lawyer_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    client_id VARCHAR(20) NOT NULL,
    lawyer_id VARCHAR(20) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_booking_rating (booking_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($conn, $sql_create_ratings)) {
    echo "Table 'lawyer_ratings' created or already exists.\n";
} else {
    echo "Error creating table 'lawyer_ratings': " . mysqli_error($conn) . "\n";
}

// Add average_rating and review_count to lawyer table for caching/sorting
$sql_add_cols = [
    "ALTER TABLE lawyer ADD COLUMN IF NOT EXISTS avg_rating DECIMAL(3,2) DEFAULT 0.00",
    "ALTER TABLE lawyer ADD COLUMN IF NOT EXISTS review_count INT DEFAULT 0",
    "ALTER TABLE lawyer ADD COLUMN IF NOT EXISTS hourly_rate DECIMAL(10,2) DEFAULT 0.00" // Added for price sorting
];

foreach ($sql_add_cols as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "Column updated successfully.\n";
    } else {
        echo "Error updating column: " . mysqli_error($conn) . "\n";
    }
}
?>
