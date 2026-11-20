<?php
include_once 'includes/session_init.php';
include("db_con/dbCon.php");
include_once 'includes/image_util.php';
$conn = connect();
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" crossorigin="anonymous">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<title>Find Law Firms</title>
		<style>
			.lawyer-card .lawyer-img-container{position:relative}
			.lawyer-card .lawyer-img-container img{width:100%;height:220px;object-fit:cover}
			.match-score{position:absolute;bottom:10px;right:10px;background:rgba(0,0,0,0.6);color:#fff;border-radius:12px;padding:6px 10px}
		</style>
	</head>
	<body>
		<?php include 'includes/navbar.php'; ?>
		<section class="py-5">
			<div class="container">
				<div class="glass-panel p-4 mb-4">
					<form method="get" class="row">
						<div class="col-md-4 mb-2">
							<select class="form-control" name="speciality">
								<option value="">Speciality</option>
								<option>Criminal Law</option>
								<option>Civil Law</option>
								<option>Company Law</option>
								<option>Contract Law</option>
								<option>Commercial Law</option>
								<option>Construction Law</option>
								<option>IT Law</option>
								<option>Family Law</option>
								<option>Investment Law</option>
								<option>Labour Law</option>
								<option>Property Law</option>
								<option>Taxation Law</option>
							</select>
						</div>
						<div class="col-md-4 mb-2">
							<select class="form-control" name="city">
								<option value="">City</option>
								<option>Mumbai</option>
								<option>Nashik</option>
								<option>Pune</option>
								<option>Delhi</option>
								<option>Nagpur</option>
								<option>Jalgaon</option>
								<option>Sambhaji Nagar</option>
								<option>Thane</option>
							</select>
						</div>
						<div class="col-md-4 mb-2">
							<button class="btn btn-modern btn-block">Search Firms</button>
						</div>
					</form>
				</div>
				<div class="row">
					<?php
						if($conn){
							mysqli_query($conn,"CREATE TABLE IF NOT EXISTS firm (
								firm_id varchar(50) PRIMARY KEY,
								firm_name varchar(255),
								email varchar(255),
								contact varchar(50),
								num_lawyers int,
								address text,
								city varchar(100),
								zip_code varchar(20),
								speciality varchar(255),
								education text,
								description text,
								image varchar(255)
							)");
							$w = [];
							if(!empty($_GET['speciality'])) $w[] = "speciality='".mysqli_real_escape_string($conn,$_GET['speciality'])."'";
							if(!empty($_GET['city'])) $w[] = "city='".mysqli_real_escape_string($conn,$_GET['city'])."'";
							$where = count($w)?("WHERE ".implode(" AND ",$w)):"";
							$res = mysqli_query($conn,"SELECT * FROM firm $where");
							while($row = mysqli_fetch_array($res)){
								$img = $row['image'];
								$p = img_src($img, "images/actors/close-up-lawyer-signing-papers.jpg");
								
								$match = rand(85,98);
								echo '<div class="col-md-6 col-lg-4 mb-4">
									<div class="card lawyer-card h-100">
										<div class="lawyer-img-container">
											<img src="'.$p.'" alt="'.$row['firm_name'].'">
											<div class="match-score"><i class="fas fa-users"></i> '.$row['num_lawyers'].' Lawyers</div>
										</div>
										<div class="card-body p-4">
											<h5 class="card-title font-weight-bold mb-1">'.$row['firm_name'].'</h5>
											<p class="text-primary mb-2 font-weight-medium">'.$row['speciality'].'</p>
											<div class="text-muted small mb-3"><i class="fas fa-map-marker-alt mr-1"></i> '.$row['city'].' • <i class="fas fa-phone mr-1"></i> '.$row['contact'].'</div>
											<a href="firm_book.php?firm_id='.$row['firm_id'].'" class="btn btn-outline-primary btn-block rounded-pill">Request Firm</a>
										</div>
									</div>
								</div>';
							}
						}
					?>
				</div>
			</div>
		</section>
		<?php include 'includes/footer.php'; ?>
	</body>
</html>
