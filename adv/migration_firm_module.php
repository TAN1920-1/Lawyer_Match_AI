<?php
include_once 'db_con/dbCon.php';
$conn = connect();
if(!$conn){ echo "Connection failed"; exit; }

// 1. Add firm_id and is_active to user table
$queries = [
    "ALTER TABLE user ADD COLUMN firm_id VARCHAR(50) DEFAULT NULL",
    "ALTER TABLE user ADD COLUMN is_active TINYINT(1) DEFAULT 1",
    "UPDATE user SET role = 'FIRM_ADMIN' WHERE role = 'Firm'",
    "CREATE TABLE IF NOT EXISTS audit_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        action VARCHAR(255) NOT NULL,
        admin_id VARCHAR(50) NOT NULL,
        target_id VARCHAR(50) DEFAULT NULL,
        details TEXT,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
];

foreach($queries as $query){
    if(mysqli_query($conn, $query)){
        echo "Executed: $query\n";
    } else {
        echo "Error executing $query: " . mysqli_error($conn) . "\n";
    }
}
?>
