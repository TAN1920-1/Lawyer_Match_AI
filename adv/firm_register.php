<?php
	include_once 'includes/session_init.php';
	include("db_con/dbCon.php");
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" crossorigin="anonymous">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<link rel="stylesheet" href="css/media.css">
		<title>Law Firm Registration - Lawyer Match AI</title>
	</head>
	<body>
		<?php include 'includes/navbar.php'; ?>
		<div class="container py-5">
			<div class="row justify-content-center">
				<div class="col-lg-10">
					<div class="glass-panel p-5 animate-fade-in">
						<div class="text-center mb-5">
							<h2 class="font-weight-bold">Law Firm Registration</h2>
							<p class="text-muted">Create a firm profile and accept client requests</p>
						</div>
						<form action="firm_registation.php" method="POST" enctype="multipart/form-data">
							
							<!-- Firm Details -->
							<h5 class="mb-3 font-weight-bold text-primary"><i class="fas fa-building mr-2"></i>Firm Information</h5>
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Firm Name</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-building"></i></span>
										</div>
										<input type="text" class="form-control" name="firm_name" placeholder="Enter Firm Name" required>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Email</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-envelope"></i></span>
										</div>
										<input type="email" class="form-control" name="email" placeholder="Email Address" required>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Contact Number</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-phone"></i></span>
										</div>
										<input type="text" class="form-control" name="contact" placeholder="Contact Number" required>
									</div>
								</div>
								<div class="col-md-6 form-group">
									<label class="font-weight-bold">Number of Lawyers</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-users"></i></span>
										</div>
										<input type="number" min="1" class="form-control" name="num_lawyers" placeholder="e.g. 5" required>
									</div>
								</div>
							</div>

							<hr class="my-4">

							<!-- Location & Speciality -->
							<h5 class="mb-3 font-weight-bold text-primary"><i class="fas fa-map-marked-alt mr-2"></i>Location & Speciality</h5>
							<div class="form-group">
								<label class="font-weight-bold">Address</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
									</div>
									<input type="text" class="form-control" name="address" placeholder="Full Address" required>
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
											<option value="" disabled selected>Select City...</option>
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
							<div class="form-group">
								<label class="font-weight-bold">Primary Speciality</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-gavel"></i></span>
									</div>
									<select name="speciality" class="form-control" required>
										<option value="" disabled selected>Choose...</option>
										<option value="Criminal Law">Criminal Law</option>
										<option value="Civil Law">Civil Law</option>
										<option value="Company Law">Company Law</option>
										<option value="Contract Law">Contract Law</option>
										<option value="Commercial Law">Commercial Law</option>
										<option value="Construction Law">Construction Law</option>
										<option value="IT Law">IT Law</option>
										<option value="Family Law">Family Law</option>
										<option value="Investment Law">Investment Law</option>
										<option value="Labour Law">Labour Law</option>
										<option value="Property Law">Property Law</option>
										<option value="Taxation Law">Taxation Law</option>
									</select>
								</div>
							</div>

							<hr class="my-4">

							<!-- Additional Details -->
							<h5 class="mb-3 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i>Firm Profile</h5>
							<div class="form-group">
								<label class="font-weight-bold">Education Summary</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-graduation-cap"></i></span>
									</div>
									<textarea class="form-control" name="education" rows="3" placeholder="Summary of educational background of key members" required></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="font-weight-bold">Firm Overview</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-info-circle"></i></span>
									</div>
									<textarea class="form-control" name="description" rows="3" placeholder="Describe your firm's expertise and history"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="font-weight-bold">Firm Logo / Image</label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" name="firm_logo" id="customFile" accept=".jpg,.jpeg,.png,.gif,.webp" required>
									<label class="custom-file-label" for="customFile">Choose Image</label>
								</div>
							</div>

							<div class="form-group mt-4">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="terms" name="agree" value="y" required>
									<label class="custom-control-label" for="terms">I agree to the <a href="#">Terms and Conditions</a></label>
								</div>
							</div>

							<button type="submit" class="btn btn-modern btn-block btn-lg mt-4">Register Firm</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php include 'includes/footer.php'; ?>
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	</body>
</html>
