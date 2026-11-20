<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Admin'){
		
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
				.glass-panel {
					width: 100%;
					overflow: hidden;
                    margin-bottom: 2rem;
				}
			</style>
		</head>
		<body class="bg-light-modern">
			<header class="customnav glass-nav">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<nav class="navbar navbar-expand-lg ">
								<a class="navbar-brand cus-a font-weight-bold" href="#">LAWYER MATCH AI</a>
								<div class="collapse navbar-collapse" id="navbarSupportedContent">
									<ul class="navbar-nav ml-auto align-items-center">
                                        <li class="nav-item">
                                            <a class="nav-link cus-a" href="index.php">
                                                <i class="fas fa-home mr-1"></i> Home
                                            </a>
                                        </li>
										<li class="">
											<a class="nav-link cus-a" href="logout.php">Log Out</a>
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
					<div class="sidebar-heading text-primary font-weight-bold">Admin Panel</div>
					<div class="list-group list-group-flush">
						<a href="admin_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
						<a href="admin_lawyer.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-gavel mr-2"></i>See Lawyers</a>
						<a href="admin_user.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-users mr-2"></i>See Users</a>
                        <a href="admin_firm.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-building mr-2"></i>See Firms</a>
                        <a href="update_password_admin.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-key mr-2"></i>Update Password</a>
					</div>
				</div>
				<!-- /#sidebar-wrapper -->
				
				<!-- Page Content -->
				<div id="page-content-wrapper">
					<div class="container-fluid p-4 animate-fade-in">
                        <div class="d-flex justify-content-between align-items-center mb-4">
							<h3 class="mb-0 text-dark font-weight-bold">Update Password</h3>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="glass-panel p-4 p-md-5 animate-fade-in" style="width: 100%; max-width: 1000px;">
									<?php
										$con = connect();
										if(isset($_POST['update'])){											
											$email = $_SESSION['email'];
											$password = $_POST['current']; 
                                            $password = mysqli_real_escape_string($con, $password);
											$new_password=$_POST['new_password'];
                                            $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
											$p_length=strlen($new_password);
											
											if($p_length <=5){
												echo "<div class='alert alert-danger alert-dismissible fade show shadow-sm border-0'>
												<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
												<i class='fas fa-exclamation-circle mr-2'></i> Sorry, new password must be at least 6 characters.
												</div>";
												}else if($new_password !== $confirm_password){
                                                echo "<div class='alert alert-danger alert-dismissible fade show shadow-sm border-0'>
												<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
												<i class='fas fa-exclamation-circle mr-2'></i> New password and confirm password do not match.
												</div>";
                                                }else{
												$result = mysqli_query($con, "SELECT * FROM user WHERE email = '" . $email. "' and password = '" . $password. "' and role='Admin'");
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
									
									<form autocomplete="off" method="post" action="update_password_admin.php" class="mt-4">				  
										<div class="form-group">
											<label for="inputPassword" class="font-weight-bold">Current Password</label>
											<div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                                </div>
												<input type="password" class="form-control bg-light border-0" id="inputPassword" placeholder="Enter current password" name="current" required>
											</div>
										</div>
										<div class="form-group">
											<label for="inputPasswordNew" class="font-weight-bold">New Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-0"><i class="fas fa-key text-muted"></i></span>
                                                </div>
											    <input type="password" class="form-control bg-light border-0" id="inputPasswordNew" placeholder="Enter new password" name="new_password" required>
                                            </div>
                                            <small class="form-text text-muted">Minimum 6 characters required.</small>
										</div>
                                        <div class="form-group">
											<label for="inputPasswordConfirm" class="font-weight-bold">Confirm Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light border-0"><i class="fas fa-check-double text-muted"></i></span>
                                                </div>
											    <input type="password" class="form-control bg-light border-0" id="inputPasswordConfirm" placeholder="Confirm new password" name="confirm_password" required>
                                            </div>
										</div>
										<button type="submit" class="btn btn-modern btn-block mt-4 rounded-pill" name="update">Update Password</button>
									</form>
                                    <script>
                                        var newPwd = document.getElementById("inputPasswordNew"),
                                            confirmPwd = document.getElementById("inputPasswordConfirm");
                                        function validatePwd(){
                                            if(newPwd.value !== confirmPwd.value){
                                                confirmPwd.setCustomValidity("Passwords Don't Match");
                                            }else{
                                                confirmPwd.setCustomValidity('');
                                            }
                                        }
                                        newPwd.onchange = validatePwd;
                                        confirmPwd.onkeyup = validatePwd;
                                    </script>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /#page-content-wrapper -->
				
			</div>
			<!-- /#wrapper -->
			
            <footer class="footer-modern mt-auto">
                <div class="container text-center">
                    <p class="mb-0 text-muted">&copy; 2025 Lawyer Match AI. All rights reserved.</p>
                </div>
            </footer>
			
			<!-- Optional JavaScript -->
			<!-- jQuery -->
			
			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
            <script src="js/theme.js"></script>
		</body>
	</html>
	<?php
	
	}else 
	header('location:login.php?deactivate');
	?>
