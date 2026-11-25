<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='User'){
		
		include("db_con/dbCon.php");
		
	?>
	<!doctype html>
	<html lang="en">
		<head>
			<!-- Required meta tags -->
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; script-src 'self' 'unsafe-inline' https://code.jquery.com https://cdnjs.cloudflare.com https://stackpath.bootstrapcdn.com; style-src 'self' 'unsafe-inline' https://stackpath.bootstrapcdn.com https://use.fontawesome.com https://fonts.googleapis.com; font-src 'self' https://use.fontawesome.com https://fonts.gstatic.com; img-src 'self' data: https:;">
			
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
			<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
			<link rel="stylesheet" href="css/simple-sidebar.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/style.css">
			<link rel="stylesheet" href="css/media.css">
			<link rel="stylesheet" href="css/modern_style.css">
			<title>User Dashboard - Lawyer Match AI</title>
			<style>
				body {
					background-color: #0a0a0a;
					color: #e6e6e6;
					font-family: 'Inter', sans-serif;
				}
				h2, h4 { color: #f5f5f5; }
				.text-muted { color: #a3a3a3 !important; }
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
					background: rgba(12, 12, 14, 0.95);
					border-right: 1px solid rgba(255,255,255,0.08);
					box-shadow: 4px 0 20px rgba(0,0,0,0.5);
				}
				#sidebar-wrapper .sidebar-heading {
					padding: 1.5rem 1.25rem;
					font-size: 1.2rem;
					font-weight: bold;
					color: #64b5ff;
					border-bottom: 1px solid rgba(255,255,255,0.08);
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
					color: #c7c7c7 !important;
					background: transparent !important;
					transition: all 0.3s;
				}
				.list-group-item:hover {
					background-color: rgba(100,181,255,0.08) !important;
					color: #64b5ff !important;
					padding-left: 25px;
				}
				.list-group-item.active,
				.list-group-item.active-modern {
					background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
					color: white !important;
				}
				.dashboard-card {
					background: rgba(20, 20, 24, 0.85);
					border: 1px solid rgba(255,255,255,0.08);
					border-radius: 15px;
					padding: 25px;
					box-shadow: 0 8px 30px rgba(0,0,0,0.5);
					height: 100%;
					transition: transform 0.3s;
					backdrop-filter: blur(10px);
				}
				.dashboard-card:hover {
					transform: translateY(-5px);
					box-shadow: 0 12px 40px rgba(0,0,0,0.7), 0 0 20px rgba(100,181,255,0.15);
				}
				.dashboard-card .bg-light {
					background: rgba(255,255,255,0.08) !important;
				}
				.user-avatar {
					width: 100px;
					height: 100px;
					border-radius: 50%;
					object-fit: cover;
					border: 4px solid rgba(255,255,255,0.1);
					box-shadow: var(--shadow-md);
				}
				.btn-outline-primary,
				.btn-outline-success,
				.btn-outline-warning {
					border-color: rgba(100,181,255,0.4);
					color: #64b5ff;
				}
				.btn-outline-success { color: #4ade80; border-color: rgba(74,222,128,0.4); }
				.btn-outline-warning { color: #fbbf24; border-color: rgba(251,191,36,0.4); }
				.btn-outline-primary:hover { background: rgba(100,181,255,0.15); color: #64b5ff; }
				.btn-outline-success:hover { background: rgba(74,222,128,0.15); color: #4ade80; }
				.btn-outline-warning:hover { background: rgba(251,191,36,0.15); color: #fbbf24; }
				.table { color: #d4d4d4; }
				.table-hover tbody tr:hover { background: rgba(255,255,255,0.04); }
				.thead-light th { background: rgba(255,255,255,0.06) !important; color: #e5e7eb !important; border-color: rgba(255,255,255,0.08) !important; }
				.table td, .table th { border-color: rgba(255,255,255,0.08); }
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
						<a href="user_dashboard.php" class="list-group-item list-group-item-action active-modern">
							<i class="fas fa-tachometer-alt mr-2"></i> Dashboard
						</a>
						<a href="user_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark">
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
						<?php if(isset($_GET['done'])){
							echo "<div class='alert alert-success alert-dismissible fade show shadow-sm border-0' role='alert'>
							<i class='fas fa-check-circle mr-2'></i> Welcome back! You are logged in successfully.
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
							</div>";
						} else if(isset($_GET['firm_req'])) {
							if($_GET['firm_req']=='1'){
								echo "<div class='alert alert-success alert-dismissible fade show shadow-sm border-0' role='alert'>
								<i class='fas fa-check-circle mr-2'></i> Your firm request has been submitted successfully.
								<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								</div>";
							} else {
								echo "<div class='alert alert-danger alert-dismissible fade show shadow-sm border-0' role='alert'>
								<i class='fas fa-exclamation-circle mr-2'></i> Unable to submit firm request. Please try again.
								<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								</div>";
							}
						}?>
						
						<div class="row mb-4">
							<div class="col-12">
								<h2 class="font-weight-bold">Dashboard Overview</h2>
								<p class="text-muted">Manage your profile and legal consultations.</p>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-4">
								<div class="dashboard-card animate-fade-in">
									<div class="text-center mb-3">
										<div class="d-inline-block p-3 rounded-circle bg-light text-primary mb-2">
											<i class="fas fa-user fa-2x"></i>
										</div>
										<h4 class="font-weight-bold">My Profile</h4>
									</div>
									<p class="text-muted text-center small">Update your personal information and contact details.</p>
									<a href="user_profile.php" class="btn btn-outline-primary btn-block rounded-pill">Edit Profile</a>
								</div>
							</div>
							
							<div class="col-md-6 mb-4">
								<div class="dashboard-card animate-fade-in" style="animation-delay: 0.1s;">
									<div class="text-center mb-3">
										<div class="d-inline-block p-3 rounded-circle bg-light text-success mb-2">
											<i class="fas fa-calendar-alt fa-2x"></i>
										</div>
										<h4 class="font-weight-bold">Bookings</h4>
									</div>
									<p class="text-muted text-center small">View and manage your consultation requests.</p>
									<a href="user_booking.php" class="btn btn-outline-success btn-block rounded-pill">View Bookings</a>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-4">
								<div class="dashboard-card animate-fade-in" style="animation-delay: 0.2s;">
									<div class="text-center mb-3">
										<div class="d-inline-block p-3 rounded-circle bg-light text-warning mb-2">
											<i class="fas fa-search fa-2x"></i>
										</div>
										<h4 class="font-weight-bold">Find Lawyer</h4>
									</div>
									<p class="text-muted text-center small">Search for new legal experts for your case.</p>
									<a href="searchLawyer.php" class="btn btn-outline-warning btn-block rounded-pill">Search Now</a>
								</div>
							</div>

							<div class="col-md-6 mb-4">
								<div class="dashboard-card animate-fade-in" style="animation-delay: 0.3s;">
									<div class="text-center mb-3">
										<div class="d-inline-block p-3 rounded-circle bg-light text-primary mb-2">
											<i class="fas fa-building fa-2x"></i>
										</div>
										<h4 class="font-weight-bold">Find Law Firm</h4>
									</div>
									<p class="text-muted text-center small">Request a team for complex matters.</p>
									<a href="searchFirm.php" class="btn btn-outline-primary btn-block rounded-pill">Find Firm</a>
								</div>
							</div>
						</div>
						
						<div class="row mt-4">
							<div class="col-12">
								<div class="glass-panel p-4">
									<h4 class="font-weight-bold mb-3">Recent Activity</h4>
									<div class="table-responsive">
										<table class="table table-hover">
											<thead class="thead-light">
												<tr>
													<th scope="col">Date</th>
													<th scope="col">Activity</th>
													<th scope="col">Status</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Today</td>
													<td>Login successful</td>
													<td><span class="badge badge-success">Success</span></td>
												</tr>
												<!-- Placeholder for dynamic activity if available in future -->
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<!-- /#page-content-wrapper -->
				
			</div>
			<!-- /#wrapper -->
			
			<?php include 'includes/footer.php'; ?>

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
