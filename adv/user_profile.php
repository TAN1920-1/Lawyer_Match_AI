<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='User'){
		
		include("db_con/dbCon.php");
		
	?>
	<!doctype html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
			<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
			<link rel="stylesheet" href="css/simple-sidebar.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/style.css">
			<link rel="stylesheet" href="css/modern_style.css">
			<link rel="stylesheet" href="css/media.css">
			<title>Edit Profile - Lawyer Match AI</title>
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
						<a href="user_profile.php" class="list-group-item list-group-item-action active-modern">
							<i class="fas fa-user-edit mr-2"></i> Edit Profile
						</a>
						<a href="user_booking.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-calendar-check mr-2"></i> My Bookings
						</a>
						<a href="update_password.php" class="list-group-item list-group-item-action bg-transparent text-dark">
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
								<h2 class="font-weight-bold">Edit Profile</h2>
								<p class="text-muted">Update your personal information.</p>
							</div>
							<div class="col-md-6 text-md-right">
								<button class="btn btn-modern-outline btn-sm" id="menu-toggle">
									<i class="fas fa-bars"></i> Toggle Menu
								</button>
							</div>
						</div>

						<?php if(isset($_GET['ok'])){
							echo "<div class='alert alert-success alert-dismissible fade show animate-fade-in shadow-sm border-0' role='alert'>
							<i class='fas fa-check-circle mr-2'></i> <strong>Success!</strong> Your profile has been updated.
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
							</div>";
						}?>
						
						<?php
							$a=$_SESSION['client_id'];
							$conn = connect();
							
							$result = mysqli_query($conn,"SELECT * FROM user,client WHERE user.u_id=client.client_id AND user.status='Active' AND user.u_id='$a'");
							
							while($row = mysqli_fetch_array($result)) {
							?>
							<div class="glass-panel p-5 animate-fade-in">
								<form class="cusform" action="save_user_edit_profile.php" method="post">
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="first_Name" class="font-weight-bold">First Name</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
												</div>
												<input type="text" class="form-control bg-light border-0" name="first_Name" id="first_Name" value="<?php echo $row["first_Name"]; ?>">
											</div>
										</div>
										<div class="form-group col-md-6">
											<label for="last_Name" class="font-weight-bold">Last Name</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
												</div>
												<input type="text" class="form-control bg-light border-0" name="last_Name" id="last_Name" value="<?php echo $row["last_Name"]; ?>">
											</div>
										</div>
									</div>
									
									<div class="form-group">
										<label for="contact_number" class="font-weight-bold">Contact Number</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text bg-light border-0"><i class="fas fa-phone text-muted"></i></span>
											</div>
											<input type="text" class="form-control bg-light border-0" name="contact_number" id="contact_number" value="<?php echo $row["contact_number"]; ?>">
										</div>
									</div>
									
									<hr class="my-4">
									<h5 class="mb-3 text-primary font-weight-bold">Address Details</h5>
									
									<div class="form-row">
										<div class="form-group col-md-6">
											<label for="full_address" class="font-weight-bold">Full Address</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
												</div>
												<input type="text" class="form-control bg-light border-0" name="full_address" id="full_address" value="<?php echo $row["full_address"]; ?>">
											</div>
										</div>
										<div class="form-group col-md-3">
											<label for="city" class="font-weight-bold">City</label>
											<select id="city" name="city" class="form-control bg-light border-0">
												<option value=" " selected>Choose...</option>
												<option value="Dhaka" <?php if ($row['city']=='Dhaka'){echo "selected";}?>>Dhaka</option>
												<option value="Chittagong" <?php if ($row['city']=='Chittagong'){echo "selected";}?>>Chittagong</option>
												<option value="Sylhet" <?php if ($row['city']=='Sylhet'){echo "selected";}?>>Sylhet</option>
												<option value="Barishal" <?php if ($row['city']=='Barishal'){echo "selected";}?>>Barishal</option>
												<option value="Khulna" <?php if ($row['city']=='Khulna'){echo "selected";}?>>Khulna</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label for="zip_code" class="font-weight-bold">Zip Code</label>
											<input type="text" class="form-control bg-light border-0" name="zip_code" id="zip_code" value="<?php echo $row["zip_code"]; ?>">
										</div>
									</div>
									
									<div class="mt-4">
										<button type="submit" class="btn btn-modern btn-lg px-5 rounded-pill shadow-sm">
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
			
			<!-- Optional JavaScript -->
			<!-- jQuery first, then Popper.js, then Bootstrap JS -->
			<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
			
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
