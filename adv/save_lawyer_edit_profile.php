
<?php
	include_once 'includes/session_init.php';
	include_once 'db_con/dbCon.php';
	include_once 'includes/image_util.php';
	$a=$_SESSION['lawyer_id'];
	$okFlag = TRUE;
	if($okFlag){
		$first_Name = $_POST['first_Name'];
		$last_Name = $_POST['last_Name'];
		$contact_number = $_POST['contact_number'];
		$university_College = $_POST['university_College'];
		$degree = $_POST['degree'];
		$passing_year = $_POST['passing_year'];
		$full_address = $_POST['full_address'];
		$city = $_POST['city'];
		$zip_code = $_POST['zip_code'];
		$practise_Length = $_POST['practise_Length'];
		$speciality = $_POST['speciality'];
        $current_image = isset($_POST['current_image']) ? $_POST['current_image'] : '';
        $new_image = $current_image;
        if(isset($_FILES['profile_image']) && !empty($_FILES['profile_image']['name'])){
            $target_dir = "images/upload/";
            $base = basename($_FILES['profile_image']['name']);
            $newName = date('YmdHis_') . $base;
            $target_file = $target_dir . $newName;
            $ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed = array("jpg","jpeg","png","gif","webp");
            $ok = in_array($ext, $allowed) && $_FILES['profile_image']['size'] <= 8000000;
            $check = @getimagesize($_FILES['profile_image']['tmp_name']);
            if($check === false){ $ok = false; }
            if($ok){
                if(db_store_uploaded_image($_FILES['profile_image']['tmp_name'], $newName)){
                    $new_image = $newName;
                }
            }
        }
		
		$conn = connect();
		$sql = "UPDATE `user` SET first_Name ='$first_Name',last_Name='$last_Name' WHERE u_id='$a'";
		$fresult=mysqli_query($conn, $sql) or die(mysqli_error ($conn));
		if($fresult==1)
		{
			$sql2= "UPDATE lawyer SET contact_number='$contact_number',university_College='$university_College',degree='$degree',passing_year='$passing_year',full_address='$full_address',city='$city',zip_code='$zip_code',practise_Length='$practise_Length',speciality='$speciality',image='$new_image' WHERE lawyer_id='$a'";
			
			$fresult2=mysqli_query($conn, $sql2) or die(mysqli_error ($conn));
			if ($fresult2==1)
			{
				header('location:lawyer_edit_profile.php?ok');
				
			}
		}
		
	}
?>
