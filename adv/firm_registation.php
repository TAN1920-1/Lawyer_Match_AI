<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';
include_once 'includes/image_util.php';
$conn = connect();
if(!$conn){ header('location:login.php'); exit; }

$firm_name = $_POST['firm_name'];
$email = $_POST['email'];
$contact = $_POST['contact'];

function generateRandomString($length = 10)  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$password = generateRandomString(6);

$num_lawyers = intval($_POST['num_lawyers']);
$address = $_POST['address'];
$city = $_POST['city'];
$zip_code = $_POST['zip_code'];
$speciality = $_POST['speciality'];
$education = $_POST['education'];
$description = isset($_POST['description']) ? $_POST['description'] : '';

$logo_name = '';
if(isset($_FILES['firm_logo']) && !empty($_FILES['firm_logo']['name'])){
    $target_dir = "images/upload/";
    $base = basename($_FILES['firm_logo']['name']);
    $logo_name = date('YmdHis_') . $base;
    $target_file = $target_dir . $logo_name;
    $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed = array("jpg","jpeg","png","gif","webp");
    $ok = in_array($ext, $allowed) && $_FILES['firm_logo']['size'] <= 8000000;
    $check = @getimagesize($_FILES['firm_logo']['tmp_name']);
    if($check === false){ $ok = false; }
    if($ok && db_store_uploaded_image($_FILES['firm_logo']['tmp_name'], $logo_name)){
        // stored in TiDB
    } else {
        $logo_name = '';
    }
}

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS firm (
    firm_id varchar(50) PRIMARY KEY,
    firm_name varchar(255),
    email varchar(255),
    contact varchar(50),
    num_lawyers int,
    address text,
    city varchar(100),
    zip_code varchar(20),
    speciality varchar(255),
    education text,
    description text,
    image varchar(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS firm_booking (
    id int AUTO_INCREMENT PRIMARY KEY,
    client_id varchar(50),
    firm_id varchar(50),
    date date,
    description text,
    status varchar(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$u_id = uniqid('Firm');
include 'send_mail_firm.php';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$duplicate=mysqli_query($conn,"select * from user where email='$email'");
if (mysqli_num_rows($duplicate)>0){
    header('location:login.php');
    exit;
}

$sqlUser = "INSERT INTO `user` (`u_id`,`first_Name`, `last_Name`, `email`, `password`, `status`, `role`, `firm_id`) 
VALUES ('$u_id','$firm_name', '', '$email', '$hashed_password', 'Pending', 'FIRM_ADMIN', '$u_id')";
$ok1 = mysqli_query($conn, $sqlUser);
if($ok1){
    $sqlFirm = "INSERT INTO firm (firm_id, firm_name, email, contact, num_lawyers, address, city, zip_code, speciality, education, description, image)
    VALUES ('$u_id','$firm_name','$email','$contact',$num_lawyers,'$address','$city','$zip_code','$speciality','$education','$description','$logo_name')";
    $ok2 = mysqli_query($conn, $sqlFirm);
    if($ok2){
        $_SESSION['registered_firm_email'] = $email;
        $_SESSION['registered_firm_password'] = $password;
        header('location:login.php?firm_created=1');
        exit;
    }
}
header('location:index.php');
