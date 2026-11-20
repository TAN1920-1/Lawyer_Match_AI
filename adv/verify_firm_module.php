<?php
include_once 'db_con/dbCon.php';
$conn = connect();

echo "--- Testing Firm Module ---\n";

// 1. Verify user table columns
$res = mysqli_query($conn, "DESCRIBE user");
$cols = [];
while($row = mysqli_fetch_assoc($res)) $cols[] = $row['Field'];
if(in_array('firm_id', $cols) && in_array('is_active', $cols)){
    echo "[PASS] User table has firm_id and is_active columns.\n";
} else {
    echo "[FAIL] User table missing columns.\n";
}

// 2. Verify audit_logs table
$res = mysqli_query($conn, "SHOW TABLES LIKE 'audit_logs'");
if(mysqli_num_rows($res) > 0){
    echo "[PASS] Audit logs table exists.\n";
} else {
    echo "[FAIL] Audit logs table missing.\n";
}

// 3. Verify FIRM_ADMIN role update
$res = mysqli_query($conn, "SELECT COUNT(*) as count FROM user WHERE role='FIRM_ADMIN'");
$count = mysqli_fetch_assoc($res)['count'];
echo "[INFO] Found $count FIRM_ADMIN accounts.\n";

echo "--- Test Complete ---\n";
?>
