<?php
	include_once 'includes/session_init.php';
	include("db_con/dbCon.php");

	// Redirect if already logged in
	if(isset($_SESSION['login']) && $_SESSION['login'] === TRUE) {
		if(isset($_SESSION['lawyer_id'])) header("Location:lawyer_dashboard.php");
		else if(isset($_SESSION['client_id'])) header("Location:user_dashboard.php");
		else if(isset($_SESSION['firm_id'])) header("Location:firm_dashboard.php");
		else if($_SESSION['role'] === 'Admin') header("Location:admin_dashboard.php");
	}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<link rel="stylesheet" href="css/media.css">
		<title>Login - Lawyer Match AI</title>
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
						<li class="nav-item active">
							<a class="nav-link cus-a" href="login.php">Login</a>
						</li>
						<li class="nav-item ml-2">
							<a class="btn btn-modern" href="register_options.php">
								Get Started
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- Login Section -->
		<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
			<div class="glass-panel p-5 animate-fade-in" style="max-width: 500px; width: 100%;">
				<div class="text-center mb-4">
					<h2 class="font-weight-bold">Welcome Back</h2>
					<p class="text-muted">Login to access your dashboard</p>
				</div>
				
				<?php
					if(isset($_GET['error']))
					{
						echo "
						<div class='alert alert-danger alert-dismissible fade show' role='alert'>
							<i class='fas fa-exclamation-circle mr-2'></i>Incorrect Email or Password.
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>";
					}
					else if(isset($_GET['pending']) && $_GET['pending']=='firm')
					{
						echo "
						<div class='alert alert-info alert-dismissible fade show' role='alert'>
							<i class='fas fa-info-circle mr-2'></i>Your firm account is pending admin approval. Please try again after approval.
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>";
					}
					else if(isset($_GET['firm_created']))
					{
						echo "
						<div class='alert alert-success alert-dismissible fade show' role='alert'>
							<i class='fas fa-check-circle mr-2'></i>Firm registered successfully! Your login password has been sent to your email. Please use it to login after admin approval.
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>";
					}
					else if(isset($_GET['deactivate']))
					{
						echo "
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							<i class='fas fa-user-lock mr-2'></i>Account Deactivated. Please contact support.
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>";
					}
					
				?>

				<form action="db_con/db_login.php" method="POST" id="validateForm">
					<div class="form-group">
						<label class="font-weight-bold">Email Address</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-envelope"></i></span>
							</div>
							<input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
						</div>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">Password</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-lock"></i></span>
							</div>
							<input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
						</div>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">Login As</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user-tag"></i></span>
							</div>
							<select class="form-control" name="role" id="role" required>
								<option value="" disabled selected>Select role</option>
								<option value="User">User</option>
								<option value="Lawyer">Lawyer</option>
								<option value="Admin">Admin</option>
								<option value="Firm">Firm</option>
							</select>
						</div>
					</div>
					<button type="submit" name="login" class="btn btn-modern btn-block mt-4">Login</button>

					<!-- <button type="submit" class="btn btn-modern btn-block mt-4" name="login">Login</button> -->
					<div class="text-center mt-3">
						<a href="forgot_password.php" class="text-muted small">Forgot Password?</a>
					</div>
				</form>
				<div class="text-center mt-4">
					<p class="text-muted mb-0">Don't have an account? <a href="user_register.php" class="text-primary font-weight-bold">Sign Up</a></p>
				</div>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	</body>
</html>
