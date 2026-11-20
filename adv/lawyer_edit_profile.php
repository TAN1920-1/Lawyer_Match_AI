<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Lawyer'){
		
		include("db_con/dbCon.php");
		include_once 'includes/image_util.php';
		
	?>
	<!doctype html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' 'unsafe-inline' https://code.jquery.com https://cdnjs.cloudflare.com https://stackpath.bootstrapcdn.com; style-src 'self' 'unsafe-inline' https://stackpath.bootstrapcdn.com https://use.fontawesome.com https://fonts.googleapis.com; font-src 'self' https://use.fontawesome.com https://fonts.gstatic.com; img-src 'self' data: https:;">
			
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
			<link rel="stylesheet" href="css/simple-sidebar.css">
			<link rel="stylesheet" href="css/modern_style.css">
			<title>Edit Profile - Lawyer Match AI</title>
		</head>
		<body class="bg-light-modern">
			<header class="customnav glass-nav">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<nav class="navbar navbar-expand-lg ">
								<a class="navbar-brand cus-a font-weight-bold" href="index.php">LAWYER MATCH AI</a>
								<div class="collapse navbar-collapse" id="navbarSupportedContent">
									<ul class="navbar-nav ml-auto align-items-center">
                                        <li class="nav-item">
                                            <a class="nav-link cus-a" href="index.php">
                                                <i class="fas fa-home mr-1"></i> Home
                                            </a>
                                        </li>
                                        <?php 
                                            include_once 'includes/notification_dropdown.php';
                                        ?>
										<li class="">
											<a class="nav-link cus-a" href="#">
												<i class="fas fa-user-tie mr-1"></i>
												<?php echo $_SESSION['first_Name'];?> <?php echo $_SESSION['last_Name'];?>
											</a>
										</li>
										<li class="">
											<a class="nav-link cus-a" href="logout.php">
												<i class="fas fa-sign-out-alt mr-1"></i> Log Out
											</a>
										</li>
									</ul>
								</div>
							</nav>
						</div>
					</div>
				</div>
			</header>

			<div class="d-flex" id="wrapper">
				
				<!-- Sidebar -->
				<div class="glass-sidebar border-right" id="sidebar-wrapper">
					<div class="sidebar-heading text-primary font-weight-bold">Lawyer Dashboard</div>
					<div class="list-group list-group-flush">
						<a href="lawyer_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-tachometer-alt mr-2"></i> Dashboard
						</a>
						<a href="lawyer_edit_profile.php" class="list-group-item list-group-item-action active-modern">
							<i class="fas fa-user-edit mr-2"></i> Edit Profile
						</a>
						<a href="lawyer_booking.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-calendar-check mr-2"></i> Booking Requests
						</a>
						<a href="update_password_lawyer.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-key mr-2"></i> Update Password
						</a>
					</div>
				</div>
				<!-- /#sidebar-wrapper -->
				
				<!-- Page Content -->
				<div id="page-content-wrapper">
					<div class="container-fluid p-4">
						<?php if(isset($_GET['ok'])){
							echo "<div class='alert alert-success alert-dismissible fade show animate-fade-in' role='alert'>
							<strong>Success!</strong> Your profile has been updated.
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>";
						}?>
						
						<h2 class="font-weight-bold mb-4 animate-fade-in">Edit Profile</h2>
						
						<?php
							$a=$_SESSION['lawyer_id'];
							$conn = connect();
							
							$result = mysqli_query($conn,"SELECT * FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id AND user.status='Active' AND user.u_id='$a'");
							
							while($row = mysqli_fetch_array($result)) {
							?>
							<div class="glass-panel p-4 animate-fade-in">
								<form action="save_lawyer_edit_Profile.php" method="post" enctype="multipart/form-data" id="validateForm">
                                    <div class="d-flex align-items-center mb-4">
                                        <?php 
                                            $img = $row["image"]; 
                                            $p = img_src($img, "images/actors/close-up-lawyer-signing-papers.jpg");
                                            
                                        ?>
                                        <img src="<?php echo $p; ?>" alt="Current" style="width:64px;height:64px;border-radius:8px;object-fit:cover;margin-right:12px;">
                                        <div class="custom-file" style="max-width:320px;">
                                            <input type="file" class="custom-file-input" id="profileImage" name="profile_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                                            <label class="custom-file-label" for="profileImage">Change Profile Image</label>
                                        </div>
                                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($row["image"]); ?>">
                                    </div>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="first_Name" class="text-muted font-weight-bold small text-uppercase">First Name</label>
											<input type="text" class="form-control bg-light border-0" id="first_Name" name="first_Name" value="<?php echo $row["first_Name"]; ?>" style="height: 50px; border-radius: 12px;">
										</div>
										<div class="form-group col-md-6">
											<label for="last_Name" class="text-muted font-weight-bold small text-uppercase">Last Name</label>
											<input type="text" class="form-control bg-light border-0" id="last_Name" name="last_Name" value="<?php echo $row["last_Name"]; ?>" style="height: 50px; border-radius: 12px;">
										</div>
									</div>
									
									<div class="form-group">
										<label for="contact_number" class="text-muted font-weight-bold small text-uppercase">Contact Number</label>
										<input type="text" class="form-control bg-light border-0" name="contact_number" id="contact_number" value="<?php echo $row["contact_Number"]; ?>" style="height: 50px; border-radius: 12px;">
									</div>
									
									<hr class="my-4">
									<h5 class="mb-3 text-primary">Education Details</h5>
									
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="university_College" class="text-muted font-weight-bold small text-uppercase">University / College</label>
											<input type="text" class="form-control bg-light border-0" id="university_College" name="university_College" value="<?php echo $row["university_College"]; ?>" style="height: 50px; border-radius: 12px;">
										</div>
										<div class="form-group col-md-3">
											<label for="degree" class="text-muted font-weight-bold small text-uppercase">Degree</label>
											<select id="degree" name="degree" class="form-control bg-light border-0" style="height: 50px; border-radius: 12px;">
												<option value=" " selected>Choose...</option>
												<option value="LLB" <?php if ($row['degree']=='LLB'){echo "selected";}?>>LLB</option>
												<option value="LLM" <?php if ($row['degree']=='LLM'){echo "selected";}?>>LLM</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="passing_year" class="text-muted font-weight-bold small text-uppercase">Passing Year</label>
											<select id="passing_year" name="passing_year" class="form-control bg-light border-0" style="height: 50px; border-radius: 12px;">
												<option value=" " selected>Choose...</option>
												<?php 
                                                    for($i=2000; $i<=2030; $i++){
                                                        $selected = ($row['passing_year']==$i) ? "selected" : "";
                                                        echo "<option value='$i' $selected>$i</option>";
                                                    }
                                                ?>
											</select>
										</div>
									</div>

                                    <hr class="my-4">
									<h5 class="mb-3 text-primary">Practice Details</h5>
									
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="full_address" class="text-muted font-weight-bold small text-uppercase">Chamber Address</label>
											<input type="text" class="form-control bg-light border-0" id="full_address" name="full_address" value="<?php echo $row["full_address"]; ?>" style="height: 50px; border-radius: 12px;">
										</div>
										<div class="form-group col-md-3">
											<label for="city" class="text-muted font-weight-bold small text-uppercase">City</label>
											<select id="city" name="city" class="form-control bg-light border-0" style="height: 50px; border-radius: 12px;">
												<option value=" " selected>Choose...</option>
												<option value="Dhaka" <?php if ($row['city']=='Dhaka'){echo "selected";}?>>Dhaka</option>
												<option value="Chittagong" <?php if ($row['city']=='Chittagong'){echo "selected";}?>>Chittagong</option>
												<option value="Sylhet" <?php if ($row['city']=='Sylhet'){echo "selected";}?>>Sylhet</option>
												<option value="Barishal" <?php if ($row['city']=='Barishal'){echo "selected";}?>>Barishal</option>
												<option value="Khulna" <?php if ($row['city']=='Khulna'){echo "selected";}?>>Khulna</option>
												<option value="Mymensingh" <?php if ($row['city']=='Mymensingh'){echo "selected";}?>>Mymensingh</option>
												<option value="Rajshahi" <?php if ($row['city']=='Rajshahi'){echo "selected";}?>>Rajshahi</option>
												<option value="Rangpur" <?php if ($row['city']=='Rangpur'){echo "selected";}?>>Rangpur</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="zip_code" class="text-muted font-weight-bold small text-uppercase">Zip Code</label>
											<input type="text" class="form-control bg-light border-0" id="zip_code" name="zip_code" value="<?php echo $row["zip_code"]; ?>" style="height: 50px; border-radius: 12px;">
										</div>
									</div>
									
									<div class="form-group">
										<label for="practise_Length" class="text-muted font-weight-bold small text-uppercase">Length of Practice</label>
										<select id="practise_Length" name="practise_Length" class="form-control bg-light border-0" style="height: 50px; border-radius: 12px;">
											<option value=" " selected>Choose...</option>
											<option value="1-5 years" <?php if ($row['practise_Length']=='1-5 years'){echo "selected";}?>>1-5 years</option>
											<option value="6-10 years" <?php if ($row['practise_Length']=='6-10 years'){echo "selected";}?>>6-10 years</option>
											<option value="11-15 years" <?php if ($row['practise_Length']=='11-15 years'){echo "selected";}?>>11-15 years</option>
											<option value="16-20 years" <?php if ($row['practise_Length']=='16-20 years'){echo "selected";}?>>16-20 years</option>
											<option value="Most Senior" <?php if ($row['practise_Length']=='Most Senior'){echo "selected";}?>>Most Senior</option>
										</select>
									</div>
									<div class="form-group">
										<label for="speciality" class="text-muted font-weight-bold small text-uppercase">Speciality</label>
										<select id="speciality" name="speciality" class="form-control bg-light border-0" style="height: 50px; border-radius: 12px;">
											<option value=" " selected>Choose...</option>
											<option value="Criminal Law" <?php if ($row['speciality']=='Criminal Law'){echo "selected";}?>>Criminal Law</option>
											<option value="Civil Law" <?php if ($row['speciality']=='Civil Law'){echo "selected";}?>>Civil Law</option>
											<option value="Writ Jurisdiction" <?php if ($row['speciality']=='Writ Jurisdiction'){echo "selected";}?>>Writ Jurisdiction</option>
											<option value="Company Law" <?php if ($row['speciality']=='Company Law'){echo "selected";}?>>Company Law</option>
											<option value="Contract Law" <?php if ($row['speciality']=='Contract Law'){echo "selected";}?>>Contract Law</option>
                                            <option value="Commercial Law" <?php if ($row['speciality']=='Commercial Law'){echo "selected";}?>>Commercial Law</option>
                                            <option value="Construction Law" <?php if ($row['speciality']=='Construction Law'){echo "selected";}?>>Construction Law</option>
                                            <option value="IT Law" <?php if ($row['speciality']=='IT Law'){echo "selected";}?>>IT Law</option>
                                            <option value="Family Law" <?php if ($row['speciality']=='Family Law'){echo "selected";}?>>Family Law</option>
                                            <option value="Religious Law" <?php if ($row['speciality']=='Religious Law'){echo "selected";}?>>Religious Law</option>
                                            <option value="Investment Law" <?php if ($row['speciality']=='Investment Law'){echo "selected";}?>>Investment Law</option>
                                            <option value="Labour Law" <?php if ($row['speciality']=='Labour Law'){echo "selected";}?>>Labour Law</option>
                                            <option value="Property Law" <?php if ($row['speciality']=='Property Law'){echo "selected";}?>>Property Law</option>
                                            <option value="Taxation Law" <?php if ($row['speciality']=='Taxation Law'){echo "selected";}?>>Taxation Law</option>
										</select>
									</div>
									
									<div class="mt-4">
										<button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                                            <i class="fas fa-save mr-2"></i> Save Changes
                                        </button>
									</div>
									
								</form>
							</div>
							<?php
							}
						?>
					</div>
				</div>
				<!-- /#page-content-wrapper -->
				
			</div>
			<!-- /#wrapper -->

            <?php include 'includes/footer.php'; ?>

			<script>
                // Update file label on selection
                $('#profileImage').on('change', function() {
                    let fileName = $(this).val().split('\\').pop();
                    $(this).next('.custom-file-label').addClass("selected").html(fileName);
                });
			</script>
		</body>
	</html>					
	<?php
		
	}else 
	header('location:login.php?deactivate');
?>
