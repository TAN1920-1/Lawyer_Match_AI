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
		<title>Find Lawyers - Lawyer Match AI</title>
	</head>
	<body>
		<!-- Navbar -->
		<?php include 'includes/navbar.php'; ?>
		
		<!-- Hero Section -->
		<section class="hero-section text-center py-5 mb-5" style="background: linear-gradient(180deg, rgba(240, 245, 255, 0) 0%, rgba(240, 245, 255, 1) 100%);">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-8 animate-fade-in">
						<h1 class="display-4 font-weight-bold mb-3">Meet Our <span class="text-gradient">Legal Experts</span></h1>
						<p class="lead text-muted mb-4">Browse through our curated list of top-rated lawyers specializing in various fields of law.</p>
						<div class="d-flex justify-content-center gap-3">
							<a href="searchLawyer.php" class="btn btn-modern btn-lg shadow-lg"><i class="fas fa-search mr-2"></i>Advanced Search</a>
							<button onclick="toggleChat()" class="btn btn-outline-modern btn-lg ml-3"><i class="fas fa-robot mr-2"></i>Ask AI Assistant</button>
						</div>
					</div>
				</div>
			</div>
		</section>

		<div class="container pb-5">
			<div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in" style="position: relative; z-index: 10;">
				<h3 class="font-weight-bold">All Lawyers</h3>
				<div class="dropdown">
					<button class="btn btn-outline-secondary dropdown-toggle rounded-pill" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-sort mr-2"></i>Sort By
					</button>
					<div class="dropdown-menu dropdown-menu-right border-0 shadow-sm" aria-labelledby="sortDropdown">
						<a class="dropdown-item" href="#">Rating</a>
						<a class="dropdown-item" href="#">Experience</a>
						<a class="dropdown-item" href="#">Name</a>
					</div>
				</div>
			</div>
			
			<div class="row">
				<?php
					include_once 'db_con/dbCon.php';
					$conn = connect();
					
					$result = mysqli_query($conn,"SELECT * FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id AND user.status='Active'");
					
					while($row = mysqli_fetch_array($result)) {
					?>
					<div class="col-md-6 col-lg-4 mb-4 animate-fade-in">
						<div class="lawyer-card h-100">
							<div class="position-relative">
								<img src="<?php echo img_src($row["image"]); ?>" alt="<?php echo $row["first_Name"]; ?>" class="card-img-top">
								<div class="position-absolute" style="top: 10px; right: 10px;">
									<span class="badge badge-light shadow-sm p-2"><i class="fas fa-star text-warning mr-1"></i> 4.8</span>
								</div>
							</div>
							<div class="card-body d-flex flex-column">
								<h5 class="card-title font-weight-bold"><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></h5>
								<div class="speciality mb-2"><i class="fas fa-gavel mr-2"></i><?php echo $row["speciality"]; ?></div>
								<div class="experience mb-3"><i class="far fa-clock mr-2"></i><?php echo $row["practise_Length"]; ?> Experience</div>
								<div class="mt-auto">
									<a href="single_lawyer.php?u_id=<?php echo $row["u_id"]; ?>" class="btn btn-outline-modern btn-block btn-sm rounded-pill">View Full Profile</a>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
				?>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
	</body>
</html>
