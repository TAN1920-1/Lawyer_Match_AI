<?php
header('Content-Type: application/json');
include_once '../../db_con/dbCon.php';
include_once '../../includes/role_middleware.php';
include_once '../../includes/audit_util.php';
include_once '../../includes/image_util.php';

check_role(['FIRM_ADMIN']);

$conn = connect();
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$firm_id = $_SESSION['firm_id'];

if ($method === 'POST') {
    try {
        // Handle FormData instead of JSON
        $data = $_POST;
        
        if (empty($data)) {
            throw new Exception("No data received");
        }

        $full_name = mysqli_real_escape_string($conn, $data['full_name'] ?? '');
        $email = mysqli_real_escape_string($conn, $data['email'] ?? '');
        $password = mysqli_real_escape_string($conn, $data['password'] ?? substr(uniqid(), -8));
        $dob = mysqli_real_escape_string($conn, $data['dob'] ?? '');
        $bar_reg = mysqli_real_escape_string($conn, $data['bar_registration_number'] ?? '');
        $phone = mysqli_real_escape_string($conn, $data['phone'] ?? '');
        $university = mysqli_real_escape_string($conn, $data['university_College'] ?? '');
        $experience = intval($data['experience_years'] ?? 0);
        $speciality = mysqli_real_escape_string($conn, $data['speciality'] ?? '');
        
        // Handle case_handle checkboxes
        $case_handle_arr = isset($data['case_handle']) ? $data['case_handle'] : [];
        $case_handle = mysqli_real_escape_string($conn, implode(',', $case_handle_arr));

        // Handle Image Upload
        $logo_name = '';
        if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])){
            $target_dir = "../../images/upload/";
            $base = basename($_FILES['fileToUpload']['name']);
            $logo_name = date('YmdHis_') . $base;
            $target_file = $target_dir . $logo_name;
            $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed = array("jpg","jpeg","png","gif","webp");
            $ok = in_array($ext, $allowed) && $_FILES['fileToUpload']['size'] <= 8000000;
            if($ok && db_store_uploaded_image($_FILES['fileToUpload']['tmp_name'], $logo_name)){
                // stored in TiDB
            } else {
                throw new Exception("Invalid image file or size too large.");
            }
        }

        if (!$full_name || !$email) {
            throw new Exception("Full Name and Email are required");
        }

        // Check duplicate email
        $duplicate = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
        if (mysqli_num_rows($duplicate) > 0) {
            echo json_encode(['error' => 'Email already exists']);
            exit;
        }

        $lawyer_id = uniqid('Lawyer');
        $sqlUser = "INSERT INTO user (u_id, first_Name, email, password, status, role, firm_id, is_active, dob) 
                    VALUES ('$lawyer_id', '$full_name', '$email', '$password', 'Active', 'FIRM_LAWYER', '$firm_id', 1, '$dob')";
        
        if (mysqli_query($conn, $sqlUser)) {
            $sqlLawyer = "INSERT INTO lawyer (lawyer_id, contact_Number, speciality, case_handle, experience_years, university_College, image) 
                          VALUES ('$lawyer_id', '$phone', '$speciality', '$case_handle', $experience, '$university', '$logo_name')";
            if (mysqli_query($conn, $sqlLawyer)) {
                log_firm_action('ADD_LAWYER', $firm_id, $lawyer_id, "Added lawyer $full_name");
                
                // Notification for new lawyer
                try {
                    include_once '../../includes/mail_util.php';
                    $subject = "Your New Lawyer Account";
                    $msg = "Dear $full_name, your account has been created by {$_SESSION['first_Name']}.<br>Email: $email<br>Password: $password";
                    send_notification_email($email, $subject, $msg);
                } catch (Exception $e) {
                    // Log mail error but don't fail the whole request
                    error_log("Mail error: " . $e->getMessage());
                }
                
                echo json_encode(['success' => true, 'lawyer_id' => $lawyer_id]);
            } else {
                mysqli_query($conn, "DELETE FROM user WHERE u_id='$lawyer_id'");
                throw new Exception('Failed to create lawyer profile: ' . mysqli_error($conn));
            }
        } else {
            throw new Exception('Failed to create user: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        header('Content-Type: application/json', true, 500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }

} else if ($method === 'DELETE') {
    // Handle DELETE /api/firm/lawyers/:lawyerId
    $uri = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $uri);
    $lawyer_id = end($parts);
    
    // Validate lawyer belongs to firm
    $check = mysqli_query($conn, "SELECT * FROM user WHERE u_id='$lawyer_id' AND firm_id='$firm_id' AND role='FIRM_LAWYER'");
    if (mysqli_num_rows($check) === 0) {
        echo json_encode(['error' => 'Lawyer not found or not part of your firm']);
        exit;
    }

    // Soft delete
    $sql = "UPDATE user SET is_active=0 WHERE u_id='$lawyer_id'";
    if (mysqli_query($conn, $sql)) {
        log_firm_action('REMOVE_LAWYER', $firm_id, $lawyer_id, "Soft deleted lawyer");
        // TODO: Send emails as per requirement
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to remove lawyer']);
    }
} else {
    echo json_encode(['error' => 'Method not allowed']);
}
?>
