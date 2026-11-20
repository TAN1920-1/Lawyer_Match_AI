<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Admin'){
		
		//session_start();
		include("db_con/dbCon.php");
		include_once 'includes/image_util.php';
		$conn = connect();


	?>
	<!doctype html>
	<html lang="en">
		<head>
			<!-- Required meta tags -->
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
			<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
			<link rel="stylesheet" href="css/simple-sidebar.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/style.css">
			<link rel="stylesheet" href="css/media.css">
            <link rel="stylesheet" href="css/modern_style.css">
			<title>Admin - Lawyers List</title>
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
				.table-responsive {
					display: block;
					width: 100%;
					overflow-x: auto;
					-webkit-overflow-scrolling: touch;
                    border-radius: 10px;
				}
				.table {
					min-width: 1400px !important;
                    margin-bottom: 0;
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
						<a href="admin_lawyer.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-gavel mr-2"></i>See Lawyers</a>
						<a href="admin_user.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-users mr-2"></i>See Users</a>
                        <a href="admin_firm.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-building mr-2"></i>See Firms</a>
                        <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-key mr-2"></i>Update Password</a>
					</div>
				</div>
				<!-- /#sidebar-wrapper -->
				
				<!-- Page Content -->
				<div id="page-content-wrapper">
					<div class="container-fluid p-4 animate-fade-in">
						<div class="glass-panel p-4">   
							<div class="d-flex justify-content-between align-items-center mb-4">
								<h3 class="mb-0 text-dark font-weight-bold"><i class="fas fa-gavel mr-2 text-primary"></i>Registered Lawyers</h3>
                                <span class="badge badge-pill badge-primary p-2 px-3">Total Lawyers</span>
							</div>
							
							<div class="table-responsive">
								<table class="table table-hover table-borderless">
									<thead class="thead-light">
										<tr>
											<th>No.</th>
											<th>ID</th>
											<th>Name</th>
											<th>Email</th>
											<th>Contact</th>
											<th>Location</th>
											<th>Image</th>
											<th>Education</th>
											<th>Experience</th>
											<th>Speciality</th>
											<th>Status</th>
										</tr>
									</thead>
									<?php
										include_once 'db_con/dbCon.php';
										$conn = connect();
										$result = mysqli_query($conn,"SELECT * FROM user INNER JOIN lawyer on user.u_id=lawyer.lawyer_id");
										$counter = 0;
										while($row = mysqli_fetch_array($result)) {
										?>
										<tbody id="myTable">
											<tr class="border-bottom">
												<td><?php echo ++$counter ;?></td>
												<td><?php echo $row["u_id"]; ?></td>
												<td>
                                                    <div class="font-weight-bold"><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></div>
                                                </td>
												<td><?php echo $row["email"]; ?></td>
												<td>+88<?php echo $row["contact_Number"]; ?></td>
												<td><?php echo $row["city"]; ?></td>
                                                <td class="align-middle">
                                                    <?php 
                                                        $img = $row["image"]; 
                                                        $p = img_src($img, "images/actors/close-up-lawyer-signing-papers.jpg");
                                                        
                                                    ?>
                                                    <div style="width:50px;height:70px;overflow:hidden;border-radius:8px;box-shadow:var(--shadow-sm);background:#0a0a0a;">
                                                        <img src="<?php echo $p; ?>" alt="Lawyer" style="width:100%;height:100%;object-fit:cover;object-position:center;">
                                                    </div>
                                                </td>
												<td>
                                                    <small class="d-block text-muted"><?php echo $row["university_College"]; ?></small>
                                                    <span class="badge badge-light"><?php echo $row["degree"]; ?></span>
                                                </td>
												<td><?php echo $row["practise_Length"]; ?></td>
												<td><span class="badge badge-info"><?php echo $row["speciality"]; ?></span></td>
												<?php if ($row['status']=='Active'){ ?>
													<td>
														<span class="badge badge-pill badge-success px-3 py-2">Active</span>
													</td>
													<?php }
													else{?>
													<td>
														<a class="btn btn-sm btn-warning rounded-pill shadow-sm" href="approve_lawyer.php?unblock_id=<?=$row['u_id']?>"><i class="fas fa-check"></i> Approve</a>
													</td>
												<?php }?>
											</tr>
											<?php
											}
										?>
									</tbody>
                                </table>
								
							</div> 
							
						</div>
					</div>
				</div>
			
			</div>
			<!-- /#wrapper -->
			
            <?php include 'includes/footer.php'; ?>
			
			<!-- Optional JavaScript -->
			<!-- jQuery -->
			
			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
		</body>
	</html>
	<?php
	
	}else 
	header('location:login.php?deactivate');
	?>																	
