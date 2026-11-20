<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='User'){
		
		//session_start();
		include("db_con/dbCon.php");
		
	?>
	<!doctype html>
	<html lang="en">
		<head>
			<!-- Required meta tags -->
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
			<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
			<link rel="stylesheet" href="css/simple-sidebar.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/style.css">
			<link rel="stylesheet" href="css/media.css">
			<link rel="stylesheet" href="css/modern_style.css">
			<title>Update Password - Lawyer Match AI</title>
			<style>
				body {
					background-color: var(--bg-color);
					font-family: 'Inter', sans-serif;
				}
				#wrapper {
					overflow-x: hidden;
				}
				#sidebar-wrapper {
					min-height: 100vh;
					margin-left: -15rem;
					-webkit-transition: margin .25s ease-out;
					-moz-transition: margin .25s ease-out;
					-o-transition: margin .25s ease-out;
					transition: margin .25s ease-out;
					background: white;
					border-right: 1px solid rgba(0,0,0,0.05);
					box-shadow: var(--shadow-sm);
				}
				#sidebar-wrapper .sidebar-heading {
					padding: 1.5rem 1.25rem;
					font-size: 1.2rem;
					font-weight: bold;
					color: var(--primary-color);
					border-bottom: 1px solid rgba(0,0,0,0.05);
				}
				#sidebar-wrapper .list-group {
					width: 15rem;
				}
				#page-content-wrapper {
					min-width: 100vw;
				}
				#wrapper.toggled #sidebar-wrapper {
					margin-left: 0;
				}
				@media (min-width: 768px) {
					#sidebar-wrapper {
						margin-left: 0;
					}
					#page-content-wrapper {
						min-width: 0;
						width: 100%;
					}
					#wrapper.toggled #sidebar-wrapper {
						margin-left: -15rem;
					}
				}
				.list-group-item {
					border: none;
					padding: 15px 20px;
					font-weight: 500;
					color: #666;
					transition: all 0.3s;
				}
				.list-group-item:hover {
					background-color: var(--bg-color);
					color: var(--primary-color);
					padding-left: 25px;
				}
				.list-group-item.active {
					background-color: var(--primary-color);
					color: white;
				}
			</style>
		</head>
		<body>
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
												<i class="fas fa-user-circle mr-1"></i>
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
						<div class="sidebar-heading text-primary font-weight-bold">User Dashboard</div>
						<div class="list-group list-group-flush">
							<a href="user_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark">
								<i class="fas fa-tachometer-alt mr-2"></i> Dashboard
							</a>
							<a href="user_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark">
								<i class="fas fa-user-edit mr-2"></i> Edit Profile
							</a>
							<a href="user_booking.php" class="list-group-item list-group-item-action bg-transparent text-dark">
								<i class="fas fa-calendar-check mr-2"></i> My Bookings
							</a>
							<a href="update_password.php" class="list-group-item list-group-item-action active-modern">
								<i class="fas fa-key mr-2"></i> Update Password
							</a>
						</div>
					</div>
					<!-- /#sidebar-wrapper -->
					
					<!-- Page Content -->
					<div id="page-content-wrapper">
						<div class="container-fluid p-4">
							<div class="row mb-4">
								<div class="col-md-6">
									<h2 class="font-weight-bold">Update Password</h2>
									<p class="text-muted">Ensure your account stays secure.</p>
								</div>
								<div class="col-md-6 text-md-right">
									<button class="btn btn-modern-outline btn-sm" id="menu-toggle">
										<i class="fas fa-bars"></i> Toggle Menu
									</button>
								</div>
							</div>

							<div class="row">
								<div class="col-md-11 col-lg-10">
									<div class="glass-panel p-4 p-md-5 animate-fade-in">
										<?php
											include_once 'db_con/dbCon.php';
											$con = connect();
											if(isset($_POST['update'])){											
												$email = $_SESSION['email'];
												$password = mysqli_real_escape_string($con, $_POST['current']);
												$new_password=$_POST['new_password'];
												$p_length=strlen($new_password);
												//echo $p_length;
												if($p_length <=5){
													echo "<div class='alert alert-danger alert-dismissible fade show shadow-sm border-0'>
													<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
													<i class='fas fa-exclamation-circle mr-2'></i> Sorry, new password must be at least 6 characters.
													</div>";
													}else{
													$result = mysqli_query($con, "SELECT * FROM user WHERE email = '" . $email. "' and password = '" . $password. "' and role='User'");
													if ($row = mysqli_fetch_array($result)) {													
														$query="UPDATE user set password='$new_password' where email='$email'";
														if(mysqli_query($con,$query)){
															echo "<div class='alert alert-success alert-dismissible fade show shadow-sm border-0'>
															<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
															<i class='fas fa-check-circle mr-2'></i> <strong>Success!</strong> Password successfully updated.
															</div>";
														}
														}else{
														echo "<div class='alert alert-danger alert-dismissible fade show shadow-sm border-0'>
														<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
														<i class='fas fa-times-circle mr-2'></i> Sorry, current password is incorrect. Please try again.
														</div>";
													}
												}
											}
										?>
										
										<form autocomplete="off" method="post" action="update_password.php" class="mt-4">				  
											<div class="form-group">
												<label for="inputPassword" class="font-weight-bold">Current Password</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
													</div>
													<input type="password" name="current" class="form-control bg-light border-0" required id="inputPassword" placeholder="Enter current password">
												</div>
											</div>
											
											<div class="form-group">
												<label for="password" class="font-weight-bold">New Password</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text bg-light border-0"><i class="fas fa-key text-muted"></i></span>
													</div>
													<input type="password" name="new_password" onblur="checkLength(this)" class="form-control bg-light border-0" id='password' maxlength="30" required placeholder="Enter new password">
												</div>
												<small class="form-text text-muted">Minimum 6 characters.</small>
											</div>
											
											<div class="form-group">
												<label for="confirm_password" class="font-weight-bold">Confirm Password</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text bg-light border-0"><i class="fas fa-check-double text-muted"></i></span>
													</div>
													<input type="password" name="confirm_password" class="form-control bg-light border-0" id='confirm_password' required placeholder="Confirm new password">
												</div>
											</div>
											
											<div class="form-group mt-4">
												<button type="submit" name="update" value="Update" class="btn btn-modern btn-block rounded-pill">
													Update Password
												</button>
											</div>
										</form>
										
										<script>
											var password = document.getElementById("password")
											, confirm_password = document.getElementById("confirm_password");
											
											function validatePassword(){
												if(password.value != confirm_password.value) {
													confirm_password.setCustomValidity("Passwords Don't Match");
													} else {
													confirm_password.setCustomValidity('');
												}
											}											
											password.onchange = validatePassword;
											confirm_password.onkeyup = validatePassword;																								
										</script>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /#page-content-wrapper -->
					
				</div>
				<!-- /#wrapper -->
				
				<?php include 'includes/footer.php'; ?>
				
			</body>
			<!-- Optional JavaScript -->
			<!-- jQuery -->
			
			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
			<script>
				$("#menu-toggle").click(function(e) {
					e.preventDefault();
					$("#wrapper").toggleClass("toggled");
				});
			</script>
		</body>
	</html>				
	<?php
		
	}else 
	header('location:login.php?deactivate');
?>
