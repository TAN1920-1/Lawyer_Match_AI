<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Admin'){
		
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
			<title>Admin Dashboard - Lawyer Match AI</title>
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
				.chart-container {
					width: 100%;
					height: 300px;
					overflow: hidden;
				}
			</style>
			<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<script type="text/javascript">
				google.charts.load('current', {'packages':['corechart']});
				google.charts.setOnLoadCallback(drawChart);
				
				function drawChart() {
					// Chart 1
					var data = google.visualization.arrayToDataTable([
					['Role','Number'],
					<?php
						$con = connect();
						$query="SELECT count(*) as number,role FROM user where NOT role='Admin' GROUP BY role";
						$result=mysqli_query($con,$query);
						while($row=mysqli_fetch_array($result))
						{
							echo "['".$row["role"]."',".$row["number"]."],";
						}
					?>
					]);
					var options = {
						'title':'Users vs Lawyers',
						'legend':'bottom',
						'is3D':true,
						'colors': ['#4e54c8', '#8f94fb']
					};
					var chart = new google.visualization.PieChart(document.getElementById('total'));
					chart.draw(data, options);

					// Chart 2
					var data2 = google.visualization.arrayToDataTable([
					['Speciality','Number'],
					<?php
						$con = connect();
						$query="SELECT count(speciality) as number,speciality FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id GROUP BY speciality";
						$result=mysqli_query($con,$query);
						while($row=mysqli_fetch_array($result))
						{
							echo "['".$row["speciality"]."',".$row["number"]."],";
						}
					?>
					]);
					var options2 = {
						'title':'Lawyer Specialities',
						'legend':'bottom',
						'is3D':true
					};
					var chart2 = new google.visualization.PieChart(document.getElementById('total2'));
					chart2.draw(data2, options2);

					// Chart 3
					var data3 = google.visualization.arrayToDataTable([
					['Degree','Number'],
					<?php
						$con = connect();
						$query="SELECT count(degree) as number,degree FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id GROUP BY degree";
						$result=mysqli_query($con,$query);
						while($row=mysqli_fetch_array($result))
						{
							echo "['".$row["degree"]."',".$row["number"]."],";
						}
					?>
					]);
					var options3 = {
						'title':'Lawyer Degrees',
						'legend':'bottom',
						'is3D':true
					};
					var chart3 = new google.visualization.PieChart(document.getElementById('total3'));
					chart3.draw(data3, options3);

					// Chart 4
					var data4 = google.visualization.arrayToDataTable([
					['Practise Length','Number'],
					<?php
						$con = connect();
						$query="SELECT count(practise_Length) as number,practise_Length FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id GROUP BY practise_Length";
						$result=mysqli_query($con,$query);
						while($row=mysqli_fetch_array($result))
						{
							echo "['".$row["practise_Length"]."',".$row["number"]."],";
						}
					?>
					]);
					var options4 = {
						'title':'Experience Levels',
						'legend':'bottom',
						'is3D':true
					};
					var chart4 = new google.visualization.PieChart(document.getElementById('total4'));
					chart4.draw(data4, options4);
				}
			</script>
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
						<a href="admin_dashboard.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
						<a href="admin_lawyer.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-gavel mr-2"></i>See Lawyers</a>
						<a href="admin_user.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-users mr-2"></i>See Users</a>
                        <a href="admin_firm.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-building mr-2"></i>See Firms</a>
                        <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-key mr-2"></i>Update Password</a>
					</div>
				</div>
				<!-- /#sidebar-wrapper -->
				
				<div id="page-content-wrapper">
					<div class="container-fluid p-4 animate-fade-in">
                        <div class="d-flex justify-content-between align-items-center mb-4">
							<h3 class="mb-0 text-dark font-weight-bold">Dashboard</h3>
                            <span class="badge badge-pill badge-primary p-2 px-3">Admin Access</span>
						</div>
						
						<div class="row">
							<div class="col-md-6 mb-4">
								<div class="glass-panel p-4 h-100">
                                    <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-chart-pie mr-2"></i>User Distribution</h5>
									<div id="total" class="chart-container"></div>
								</div>
							</div>
							<div class="col-md-6 mb-4">
								<div class="glass-panel p-4 h-100">
                                    <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-balance-scale mr-2"></i>Lawyer Specialities</h5>
									<div id="total2" class="chart-container"></div>
								</div>
							</div>
							<div class="col-md-6 mb-4">
								<div class="glass-panel p-4 h-100">
                                    <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-graduation-cap mr-2"></i>Lawyer Degrees</h5>
									<div id="total3" class="chart-container"></div>
								</div>
							</div>
							<div class="col-md-6 mb-4">
								<div class="glass-panel p-4 h-100">
                                    <h5 class="font-weight-bold mb-3 text-primary"><i class="fas fa-briefcase mr-2"></i>Experience Levels</h5>
									<div id="total4" class="chart-container"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /#wrapper -->

			<?php include 'includes/footer.php'; ?>

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
