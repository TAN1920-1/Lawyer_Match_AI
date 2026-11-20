<?php
	include_once 'includes/session_init.php';
	include("db_con/dbCon.php");
	include_once 'includes/image_util.php';
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
		<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<link rel="stylesheet" href="css/media.css">
		<title>Lawyer Profile - Lawyer Match AI</title>
		<style>
			.profile-header {
				text-align: center;
				padding: 20px;
			}
			.profile-header img {
				width: 150px;
				height: 150px;
				border-radius: 50%;
				object-fit: cover;
				box-shadow: var(--card-shadow);
				margin-bottom: 20px;
			}
			.info-row {
				padding: 15px 0;
				border-bottom: 1px solid rgba(0,0,0,0.05);
			}
			.info-row:last-child {
				border-bottom: none;
			}
			.info-label {
				font-weight: 600;
				color: var(--text-secondary);
			}
		</style>
	</head>
	<body>
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg navbar-light navbar-custom glass-nav">
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
						<?php if(isset($_SESSION['login']) && $_SESSION['login'] == TRUE){ ?>
							<li class="nav-item">
								<a class="nav-link cus-a" href="user_dashboard.php">Dashboard</a>
							</li>
							<li class="nav-item">
								<a class="btn btn-outline-modern ml-3" href="logout.php">Logout</a>
							</li>
						<?php }else{ ?>
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
						<?php }?>
					</ul>
				</div>
			</div>
		</nav>
		
		<div class="container py-5">
			<?php
				include_once 'db_con/dbCon.php';
				$conn = connect();
				$result = mysqli_query($conn,"SELECT * FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id AND user.status='Active' AND user.u_id='" . $_GET['u_id'] . "'");
				while($row = mysqli_fetch_array($result)) {
			?>
			<div class="row">
				<!-- Sidebar Profile -->
				<div class="col-lg-4 mb-4">
					<div class="glass-panel h-100 animate-fade-in">
						<div class="profile-header">
							<?php 
								$img = $row["image"]; 
								$p = img_src($img, "images/actors/lawyer-working-document-with-scales-justice.jpg");
								
							?>
							<img src="<?php echo $p; ?>" alt="Profile Picture">
							<h3><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></h3>
							<div class="text-primary font-weight-bold mb-2"><?php echo $row["speciality"]; ?></div>
							<div class="text-muted mb-3"><i class="fas fa-map-marker-alt mr-2"></i><?php echo $row["city"]; ?></div>
                            
                            <!-- Rating Info -->
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div class="rating-badge mr-2" style="background: #fff9db; color: #f08c00; padding: 5px 15px; border-radius: 50px; font-weight: 700; border: 1px solid #ffe066;">
                                    <i class="fas fa-star mr-1"></i> <?php echo number_format($row['avg_rating'], 1); ?>
                                </div>
                                <span class="text-muted small"><?php echo $row['review_count']; ?> Reviews</span>
                            </div>
						</div>
						<div class="p-4">
							<a href="#book-now" class="btn btn-modern btn-block">Book Consultation</a>
						</div>
					</div>
				</div>

				<!-- Main Content -->
				<div class="col-lg-8 animate-fade-in" style="animation-delay: 0.2s;">
					<div class="glass-panel p-4 mb-4">
						<h4 class="mb-4">Professional Details</h4>
						
						<div class="info-row row">
							<div class="col-md-4 info-label">Contact Number</div>
							<div class="col-md-8"><?php echo $row["contact_Number"]; ?></div>
						</div>
						
						<div class="info-row row">
							<div class="col-md-4 info-label">Email</div>
							<div class="col-md-8"><?php echo $row["email"]; ?></div>
						</div>
						
						<div class="info-row row">
							<div class="col-md-4 info-label">Education</div>
							<div class="col-md-8">
								<div><?php echo $row["degree"]; ?></div>
								<div class="text-muted small"><?php echo $row["university_College"]; ?> (<?php echo $row["passing_year"]; ?>)</div>
							</div>
						</div>
						
						<div class="info-row row">
							<div class="col-md-4 info-label">Practice Location</div>
							<div class="col-md-8">
								<?php echo $row["full_address"]; ?><br>
								<?php echo $row["city"]; ?> - <?php echo $row["zip_code"]; ?>
							</div>
						</div>
						
						<div class="info-row row">
							<div class="col-md-4 info-label">Experience</div>
							<div class="col-md-8"><?php echo $row["practise_Length"]; ?></div>
						</div>
						
						<div class="info-row row">
							<div class="col-md-4 info-label">Case Types</div>
							<div class="col-md-8"><?php echo $row["case_handle"]; ?></div>
						</div>
					</div>

                    <!-- Client Reviews Section -->
                    <div class="glass-panel p-4 mb-4">
                        <h4 class="mb-4">Client Reviews</h4>
                        <?php
                            $reviews_query = "SELECT lr.*, u.first_Name, u.last_Name 
                                            FROM lawyer_ratings lr 
                                            JOIN user u ON lr.client_id = u.u_id 
                                            WHERE lr.lawyer_id = '{$row['u_id']}' 
                                            ORDER BY lr.created_at DESC";
                            $reviews_res = mysqli_query($conn, $reviews_query);
                            
                            if(mysqli_num_rows($reviews_res) > 0) {
                                // Rating Distribution
                                $dist_query = "SELECT rating, COUNT(*) as count FROM lawyer_ratings WHERE lawyer_id = '{$row['u_id']}' GROUP BY rating";
                                $dist_res = mysqli_query($conn, $dist_query);
                                $dist = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
                                while($dr = mysqli_fetch_assoc($dist_res)) $dist[$dr['rating']] = $dr['count'];
                                $total = array_sum($dist);
                                
                                echo '<div class="row mb-5 align-items-center">
                                        <div class="col-md-4 text-center border-right">
                                            <h1 class="display-3 font-weight-bold mb-0">'.number_format($row['avg_rating'], 1).'</h1>
                                            <div class="text-warning mb-2">';
                                            for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=round($row['avg_rating'])?'':'text-muted opacity-20').'"></i>';
                                            echo '</div>
                                            <p class="text-muted small">Based on '.$total.' reviews</p>
                                        </div>
                                        <div class="col-md-8">';
                                        foreach([5,4,3,2,1] as $s) {
                                            $pct = $total > 0 ? ($dist[$s]/$total)*100 : 0;
                                            echo '<div class="d-flex align-items-center mb-1">
                                                    <span class="small mr-2" style="min-width:40px;">'.$s.' star</span>
                                                    <div class="progress flex-grow-1" style="height: 8px; border-radius: 10px;">
                                                        <div class="progress-bar bg-warning" style="width: '.$pct.'%"></div>
                                                    </div>
                                                    <span class="small ml-2 text-muted" style="min-width:30px;">'.round($pct).'%</span>
                                                  </div>';
                                        }
                                echo '</div></div>';

                                while($rev = mysqli_fetch_assoc($reviews_res)) {
                                ?>
                                <div class="mb-4 pb-4 border-bottom">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="font-weight-bold mb-0"><?php echo $rev['first_Name'] . ' ' . $rev['last_Name']; ?></h6>
                                        <small class="text-muted"><?php echo date('M d, Y', strtotime($rev['created_at'])); ?></small>
                                    </div>
                                    <div class="text-warning small mb-2">
                                        <?php for($i=1;$i<=5;$i++) echo '<i class="fas fa-star '.($i<=$rev['rating']?'':'text-muted opacity-20').'"></i>'; ?>
                                    </div>
                                    <p class="text-secondary mb-0 small"><?php echo htmlspecialchars($rev['feedback']); ?></p>
                                </div>
                                <?php
                                }
                            } else {
                                echo '<div class="text-center py-4"><p class="text-muted mb-0">No reviews yet for this lawyer.</p></div>';
                            }
                        ?>
                    </div>

					<!-- Booking Form -->
					<div id="book-now" class="glass-panel p-4">
						<h4 class="mb-4"><i class="far fa-calendar-check mr-2"></i>Book Appointment</h4>
						
						<form action="save_booking.php" method="post">
							<input type="hidden" name="lawyer_id" value="<?php echo $row['u_id']; ?>">
							<input type="hidden" name="client_id" value="<?php echo isset($_SESSION['client_id']) ? $_SESSION['client_id'] : ''; ?>">
							
							<div class="row">
								<div class="col-md-6 mb-3">
									<label class="font-weight-bold">Select Date</label>
									<input type="date" name="date" class="form-control" style="border-radius: 10px;" required>
								</div>
								<div class="col-md-12 mb-3">
									<label class="font-weight-bold">Case Description</label>
									<textarea name="description" class="form-control" rows="4" placeholder="Briefly describe your legal issue..." style="border-radius: 10px;" required></textarea>
								</div>
							</div>
							
							<?php if(isset($_SESSION['login']) && $_SESSION['login'] == TRUE){ ?>
								<button type="submit" name="post" class="btn btn-modern btn-block">Confirm Booking</button>
							<?php }else{ ?>
								<div class="alert alert-warning">
									Please <a href="login.php" class="font-weight-bold">Login</a> or <a href="user_register.php" class="font-weight-bold">Register</a> to book an appointment.
								</div>
							<?php }?> 

							<!-- <a href="save_booking.php" class="btn btn-outline-modern btn-block btn-sm">confirm booking</a> -->
						</form>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>

		<?php include 'includes/footer.php'; ?>

		<!-- Bootstrap Scripts -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	</body>
</html>
