<?php
include_once 'includes/session_init.php';
include_once 'includes/role_middleware.php';
check_role(['FIRM_ADMIN', 'Firm']);
include_once 'db_con/dbCon.php';
include_once 'includes/image_util.php';

$firm_id = $_SESSION['firm_id'];
$conn = connect();

$firm_name = mysqli_real_escape_string($conn, $_POST['firm_name']);
$contact = mysqli_real_escape_string($conn, $_POST['contact']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
$speciality = mysqli_real_escape_string($conn, $_POST['speciality']);
$num_lawyers = intval($_POST['num_lawyers']);
$education = mysqli_real_escape_string($conn, $_POST['education']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$current_image = isset($_POST['current_image']) ? $_POST['current_image'] : '';
$new_image = $current_image;

if(isset($_FILES['firm_logo']) && !empty($_FILES['firm_logo']['name'])){
    $target_dir = "images/upload/";
    $base = basename($_FILES['firm_logo']['name']);
    $newName = date('YmdHis_') . $base;
    $target_file = $target_dir . $newName;
    $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = array("jpg","jpeg","png","gif","webp");
    $ok = in_array($ext, $allowed) && $_FILES['firm_logo']['size'] <= 8000000;
    
    if($ok){
        if(db_store_uploaded_image($_FILES['firm_logo']['tmp_name'], $newName)){
            $new_image = $newName;
            // Update session if firm name changed
            $_SESSION['first_Name'] = $firm_name;
        }
    }
}

// Update user table (firm name is stored in first_Name)
mysqli_query($conn,"UPDATE user SET first_Name='$firm_name' WHERE u_id='$firm_id'");

// Update firm table
$sql = "UPDATE firm SET 
        firm_name='$firm_name', 
        contact='$contact', 
        address='$address', 
        city='$city', 
        zip_code='$zip_code', 
        speciality='$speciality', 
        num_lawyers=$num_lawyers, 
        education='$education', 
        description='$description', 
        image='$new_image' 
        WHERE firm_id='$firm_id'";

if(mysqli_query($conn,$sql)){
    $_SESSION['first_Name'] = $firm_name; // Ensure session name is updated
    header('location:firm_edit_profile.php?ok');
} else {
    echo "Error updating profile: " . mysqli_error($conn);
}
?>
