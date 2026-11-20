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
		<title>Lawyer Registration - Lawyer Match AI</title>
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<link rel="stylesheet" href="css/media.css">
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
				<div class="col-lg-10">
					<div class="glass-panel p-5 animate-fade-in">
						<div class="text-center mb-5">
							<h2 class="font-weight-bold">Lawyer Registration</h2>
							<p class="text-muted">Join our network of top legal professionals</p>
						</div>
						
						<form action="lawyer_registation.php" method="POST" enctype="multipart/form-data" id="validateForm">
							
							<!-- Personal Details -->
							<h5 class="mb-3 font-weight-bold text-primary"><i class="fas fa-user mr-2"></i>Personal Information</h5>
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
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Email</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-envelope"></i></span>
										</div>
										<input type="email" class="form-control" name="email" placeholder="Email Address" required>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Contact Number</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-phone"></i></span>
										</div>
										<input type="text" class="form-control" name="contact_number" placeholder="Contact Number" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="font-weight-bold">Profile Image</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" name="fileToUpload" id="customFile" required>
									<label class="custom-file-label" for="customFile">Choose Profile Picture</label>
								</div>
							</div>

							<hr class="my-4">
							
							<!-- Education -->
							<h5 class="mb-3 font-weight-bold text-primary"><i class="fas fa-graduation-cap mr-2"></i>Education</h5>
							<div class="form-group">
								<label class="font-weight-bold">University / College</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-university"></i></span>
									</div>
									<input type="text" class="form-control" name="university_College" placeholder="Institute Name" required>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Degree</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-certificate"></i></span>
										</div>
										<select name="degree" class="form-control" required>
											<option value="" selected disabled>Choose Degree...</option>
											<option value="LLB">LLB</option>
											<option value="LLM">LLM</option>
										</select>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Passing Year</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
										</div>
										<select name="passing_year" class="form-control" required>
											<option value="" selected disabled>Choose Year...</option>
											<?php 
												for($i=2000; $i<=2030; $i++){ 
													echo "<option value='$i'>$i</option>"; 
												} 
											?>
										</select>
									</div>
								</div>
							</div>

							<hr class="my-4">

							<!-- Professional Details -->
							<h5 class="mb-3 font-weight-bold text-primary"><i class="fas fa-briefcase mr-2"></i>Professional Details</h5>
							<div class="form-group">
								<label class="font-weight-bold">Chamber Address</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
									</div>
									<input type="text" class="form-control" name="full_address" placeholder="Full Address" required>
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
											<option value="Nashik">Nashik</option>
											<option value="Pune">Pune</option>
											<option value="Surat">Surat</option>
											<option value="Bhusawal">Bhusawal</option>
											<option value="Thane">Thane</option>
											<option value="Sambhaji Nagar">Sambhaji Nagar</option>
											<option value="Rajkot">Rajkot</option>
											<option value="Nagpur">Nagpur</option>
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
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Length of Practice</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-clock"></i></span>
										</div>
										<select name="practise_Length" class="form-control" required>
											<option value="" selected disabled>Choose...</option>
											<option value="1-5 years">1-5 years</option>
											<option value="6-10 years">6-10 years</option>
											<option value="11-15 years">11-15 years</option>
											<option value="16-20 years">16-20 years</option>
											<option value="Most Senior">Most Senior</option>
										</select>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Primary Speciality</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-gavel"></i></span>
										</div>
										<select name="speciality" class="form-control" required>
											<option value="" selected disabled>Choose...</option>
											<option value="Criminal Law">Criminal Law</option>
											<option value="Civil Law">Civil Law</option>
											<option value="Writ Jurisdiction">Writ Jurisdiction</option>
											<option value="Company Law">Company Law</option>
											<option value="Contract Law">Contract Law</option>
											<option value="Commercial Law">Commercial Law</option>
											<option value="Construction Law">Construction Law</option>
											<option value="IT Law">IT Law</option>
											<option value="Family Law">Family Law</option>
											<option value="Religious Law">Religious Law</option>
											<option value="Investment Law">Investment Law</option>
											<option value="Labour Law">Labour Law</option>
											<option value="Property Law">Property Law</option>
											<option value="Taxation Law">Taxation Law</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="form-group mt-3">
								<label class="font-weight-bold mb-3">Case Types Handled (Select all that apply)</label>
								<div class="row">
									<div class="col-md-6">
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Criminal matter" id="crime">
											<label class="custom-control-label" for="crime">Criminal matter</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Civil matter" id="civil">
											<label class="custom-control-label" for="civil">Civil matter</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Writ Jurisdiction" id="writ">
											<label class="custom-control-label" for="writ">Writ Jurisdiction</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Company law" id="comp">
											<label class="custom-control-label" for="comp">Company law</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Contract law" id="cont">
											<label class="custom-control-label" for="cont">Contract law</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Commercial matter" id="comm">
											<label class="custom-control-label" for="comm">Commercial matter</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Construction law" id="cons">
											<label class="custom-control-label" for="cons">Construction law</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Information Technology" id="it">
											<label class="custom-control-label" for="it">Information Technology</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Family Law" id="fam">
											<label class="custom-control-label" for="fam">Family Law</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Religious Matter" id="rel">
											<label class="custom-control-label" for="rel">Religious Matter</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Investment Matter" id="inv">
											<label class="custom-control-label" for="inv">Investment Matter</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Labour Law" id="lab">
											<label class="custom-control-label" for="lab">Labour Law</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Property Law" id="prop">
											<label class="custom-control-label" for="prop">Property Law</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Taxation Matter" id="tax">
											<label class="custom-control-label" for="tax">Taxation Matter</label>
										</div>
										<div class="custom-control custom-checkbox mb-2">
											<input type="checkbox" class="custom-control-input" name="case_handle[]" value="Others" id="oth">
											<label class="custom-control-label" for="oth">Others</label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group mt-4">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="terms" name="agree" value="y" required>
									<label class="custom-control-label" for="terms">I agree to the <a href="#">Terms and Conditions</a></label>
								</div>
							</div>

							<button type="submit" class="btn btn-modern btn-block btn-lg mt-4">Register as Lawyer</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
	</body>
</html>
