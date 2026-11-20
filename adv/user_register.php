<?php
	include_once 'includes/session_init.php';
	include("db_con/dbCon.php");
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' 'unsafe-inline' https://code.jquery.com https://cdnjs.cloudflare.com https://stackpath.bootstrapcdn.com; style-src 'self' 'unsafe-inline' https://stackpath.bootstrapcdn.com https://use.fontawesome.com https://fonts.googleapis.com; font-src 'self' https://use.fontawesome.com https://fonts.gstatic.com; img-src 'self' data: https:;">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<link rel="stylesheet" href="css/media.css">
		<title>User Registration - Lawyer Match AI</title>
	</head>
	<body>
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg navbar-light glass-nav">
			<div class="container-fluid">
				<a class="navbar-brand cus-a font-weight-bold" href="index.php">
					<i class="fas fa-balance-scale mr-2"></i>Lawyer Match AI
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ml-auto align-items-center">
						<li class="nav-item">
							<a class="nav-link cus-a" href="index.php">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link cus-a" href="lawyers.php">Find Lawyers</a>
						</li>
						<li class="nav-item">
							<a class="nav-link cus-a" href="index.php#about">About Us</a>
						</li>
						<li class="nav-item">
							<a class="nav-link cus-a" href="login.php">Login</a>
						</li>
						<li class="nav-item dropdown ml-2">
							<a class="btn btn-modern dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Get Started
							</a>
							<div class="dropdown-menu glass-panel border-0 mt-2" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="lawyer_register.php">Register as Lawyer</a>
								<a class="dropdown-item" href="user_register.php">Register as User</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- Registration Section -->
		<div class="container py-5">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="glass-panel p-5 animate-fade-in">
						<div class="text-center mb-5">
							<h2 class="font-weight-bold">Create User Account</h2>
							<p class="text-muted">Join us to find the best legal help</p>
						</div>
						
						<form action="user_registation.php" method="POST" enctype="multipart/form-data" id="validateForm">
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">First Name</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-user"></i></span>
										</div>
										<input type="text" class="form-control" name="first_Name" placeholder="First Name" required>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Last Name</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-user"></i></span>
										</div>
										<input type="text" class="form-control" name="last_Name" placeholder="Last Name" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="font-weight-bold">Email Address</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-envelope"></i></span>
									</div>
									<input type="email" class="form-control" name="email" placeholder="Email Address" required>
								</div>
							</div>
							<div class="form-group">
								<label class="font-weight-bold">Contact Number</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-phone"></i></span>
									</div>
									<input type="text" class="form-control" name="contact_number" placeholder="Contact Number" required>
								</div>
							</div>
							<div class="form-group">
								<label class="font-weight-bold">Profile Image</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" name="fileToUpload" id="customFile" required>
									<label class="custom-file-label" for="customFile">Choose file</label>
								</div>
							</div>
							
							<hr class="my-4">
							<h5 class="mb-3 font-weight-bold text-primary">Address Details</h5>
							
							<div class="form-group">
								<label class="font-weight-bold">Full Address</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
									</div>
									<input type="text" class="form-control" name="full_address" placeholder="Street Address" required>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">City</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-city"></i></span>
										</div>
										<select name="city" class="form-control" required>
											<option value="" selected disabled>Choose City...</option>
											<option value="Mumbai">Mumbai</option>
											<option value="Nashik">Nashik</option>
											<option value="Pune">Pune</option>
											<option value="Delhi">Delhi</option>
											<option value="Nagpur">Nagpur</option>
											<option value="Jalgaon">Jalgaon</option>
											<option value="Sambhaji Nagar">Sambhaji Nagar</option>
											<option value="Thane">Thane</option>
										</select>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Zip Code</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-map-pin"></i></span>
										</div>
										<input type="text" class="form-control" name="zip_code" placeholder="Zip Code" required>
									</div>
								</div>
							</div>

							<div class="form-group mt-4">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="terms" name="agree" value="y" required>
									<label class="custom-control-label" for="terms">I agree to the <a href="#">Terms and Conditions</a></label>
								</div>
							</div>

							<button type="submit" class="btn btn-modern btn-block btn-lg mt-4">Register Now</button>
						</form>
						<div class="text-center mt-4">
							<p class="text-muted mb-0">Already have an account? <a href="login.php" class="text-primary font-weight-bold">Login</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
	</body>
</html>
