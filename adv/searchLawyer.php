<?php include_once 'includes/session_init.php'; include_once 'includes/image_util.php'; ?>
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
		<title>Find Your Lawyer - Lawyer Match AI</title>
		<style>
			body {
				background-color: var(--bg-color);
				font-family: 'Inter', sans-serif;
			}
            .ai-recommendation-section {
                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                border-radius: 30px;
                padding: 40px 20px;
                margin-bottom: 50px;
                border: 2px dashed #bae6fd;
                position: relative;
            }
            .ai-badge-top {
                position: absolute;
                top: -15px;
                left: 50%;
                transform: translateX(-50%);
                background: var(--primary-color);
                color: white;
                padding: 5px 20px;
                border-radius: 50px;
                font-weight: bold;
                font-size: 0.9rem;
                box-shadow: var(--shadow-md);
                z-index: 10;
            }
            .fitness-badge {
                position: absolute;
                bottom: 15px;
                left: 15px;
                background: rgba(255, 255, 255, 0.9);
                padding: 4px 12px;
                border-radius: 50px;
                font-size: 0.75rem;
                font-weight: 700;
                color: var(--primary-color);
                border: 1px solid var(--primary-color);
            }
			.search-header {
				background: linear-gradient(135deg, #a5f3fc 0%, #93c5fd 30%, #c084fc 70%, #d8b4fe 100%);
				padding: 80px 0;
				color: #1e293b;
				margin-bottom: 40px;
				border-radius: 0 0 40px 40px;
				box-shadow: var(--shadow-md);
				position: relative;
				overflow: hidden;
			}
			.search-header::before {
				content: '';
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background: radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.4) 0%, transparent 50%),
				            radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.3) 0%, transparent 50%);
				pointer-events: none;
			}
			.search-form-container {
				background: rgba(255, 255, 255, 0.95);
				backdrop-filter: blur(10px);
				border-radius: 20px;
				padding: 30px;
				box-shadow: var(--shadow-lg);
				margin-top: -60px;
				border: 1px solid rgba(255, 255, 255, 0.5);
			}
			.lawyer-card {
				transition: transform 0.3s ease, box-shadow 0.3s ease;
				border: none;
				border-radius: 20px;
				overflow: hidden;
				background: white;
				height: 100%;
				box-shadow: var(--shadow-sm);
			}
			.lawyer-card:hover {
				transform: translateY(-5px);
				box-shadow: var(--shadow-lg);
			}
			.lawyer-img-container {
				height: 240px;
				overflow: hidden;
				position: relative;
				display: flex;
				align-items: center;
				justify-content: center;
				background: #f8f9fa;
			}
			.lawyer-img-container img {
				max-width: 100%;
				max-height: 100%;
				width: auto;
				height: auto;
				object-fit: contain;
				transition: opacity 0.3s ease;
			}
			.match-score {
				position: absolute;
				top: 15px;
				right: 15px;
				background: rgba(255, 255, 255, 0.9);
				padding: 5px 10px;
				border-radius: 20px;
				font-weight: bold;
				color: var(--success-color);
				box-shadow: 0 2px 10px rgba(0,0,0,0.1);
				display: flex;
				align-items: center;
				gap: 5px;
			}
            .rating-badge {
                background: #fff9db;
                color: #f08c00;
                padding: 4px 12px;
                border-radius: 50px;
                font-weight: 700;
                font-size: 0.85rem;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                border: 1px solid #ffe066;
            }
            .sort-container {
                display: flex;
                align-items: center;
                gap: 15px;
                background: white;
                padding: 15px 25px;
                border-radius: 15px;
                box-shadow: var(--shadow-sm);
                margin-bottom: 25px;
            }
		</style>
	</head>
	<body>
		<!-- Navbar -->
		<?php include 'includes/navbar.php'; ?>
		
		<div class="search-header text-center">
			<div class="container">
				<h1 class="display-4 font-weight-bold mb-3">Find Your Perfect Legal Match</h1>
				<p class="lead opacity-80">Tell us what you need, and our AI will connect you with the right expert.</p>
				<div style="height: 40px;"></div>
			</div>
		</div>

		<section class="lawyerscard pb-5">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-10">
						<div class="search-form-container animate-fade-in">
							<form method="post" novalidate="novalidate" >
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="experience" class="font-weight-bold text-muted text-uppercase small">Experience Level</label>
											<select name="experience" class="form-control custom-select-lg border-0 bg-light">
												<option value="" selected>Any Experience</option>
												<option value="1-5 years">1-5 years (Junior)</option>
												<option value="6-10 years">6-10 years (Associate)</option>
												<option value="11-15 years">11-15 years (Senior)</option>
												<option value="16-20 years">16-20 years (Expert)</option>
												<option value="Most Senior">Most Senior</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="speciality" class="font-weight-bold text-muted text-uppercase small">Legal Issue / Speciality</label>
											<select name="speciality" class="form-control custom-select-lg border-0 bg-light">
												<option value="" selected>Choose Area of Law...</option>
												<option value="Criminal Law">Criminal Defense</option>
												<option value="Civil Law">Civil Litigation</option>
												<option value="Writ Jurisdiction">Writ Jurisdiction</option>
												<option value="Company Law">Corporate / Company Law</option>
												<option value="Contract Law">Contract Law</option>
												<option value="Commercial Law">Commercial Law</option>
												<option value="Construction Law">Construction Law</option>
												<option value="IT Law">IT & Cyber Law</option>
												<option value="Family Law">Family & Divorce</option>
												<option value="Religious Law">Religious Law</option>
												<option value="Investment Law">Investment Law</option>
												<option value="Labour Law">Labour & Employment</option>
												<option value="Property Law">Property & Real Estate</option>
												<option value="Taxation Law">Taxation Law</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="location" class="font-weight-bold text-muted text-uppercase small">Location</label>
											<select name="location" class="form-control custom-select-lg border-0 bg-light"> 
												<option selected>Select City...</option>
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
								</div>
								<div class="row mt-3">
									<div class="col-12 text-center">
										<button id="button" type="submit" class="btn btn-modern btn-lg px-5" name="submit" value="submit">
											<i class="fa fa-search mr-2"></i> Find Lawyers
										</button>
									</div>	
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="row mt-5">
					<?php
						include_once 'db_con/dbCon.php';
						include_once 'includes/ai_recommendation_engine.php';
						$conn = connect();

						if (isset($_POST['submit']) || isset($_GET['search']) || isset($_GET['sort'])){
							$experience = isset($_POST['experience']) ? $_POST['experience'] : (isset($_GET['experience']) ? $_GET['experience'] : '');
							$speciality = isset($_POST['speciality']) ? $_POST['speciality'] : (isset($_GET['speciality']) ? $_GET['speciality'] : '');
							$location = isset($_POST['location']) ? $_POST['location'] : (isset($_GET['location']) ? $_GET['location'] : '');
							$sort = isset($_GET['sort']) ? $_GET['sort'] : 'best_rated';

                            // 1. Get User City for AI if logged in
                            $user_city = '';
                            if(isset($_SESSION['login']) && $_SESSION['role'] == 'User'){
                                $client_id = $_SESSION['client_id'];
                                $city_res = mysqli_query($conn, "SELECT city FROM client WHERE client_id='$client_id'");
                                if($city_row = mysqli_fetch_assoc($city_res)) $user_city = $city_row['city'];
                            }

                            // 2. Section: AI Recommendations
                            $ai_engine = new AIRecommendationEngine($conn, $user_city);
                            $recommendations = $ai_engine->getRecommendations($speciality, 3);

                            if(!empty($recommendations)){
                                echo '<div class="col-12 mb-5">
                                        <div class="ai-recommendation-section animate-fade-in">
                                            <div class="ai-badge-top"><i class="fas fa-robot mr-2"></i>AI Recommendations for Your Need</div>
                                            <div class="row px-3">';
                                foreach($recommendations as $lawyer){
                                    $avg_rating = number_format($lawyer['avg_rating'], 1);
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card lawyer-card h-100 shadow-lg border-primary">
                                            <div class="lawyer-img-container">
                                                <img src="<?php echo img_src($lawyer["image"]); ?>" alt="<?php echo $lawyer["first_Name"]; ?>">
                                                <div class="match-score bg-primary text-white">
                                                    <i class="fas fa-magic"></i> AI Pick
                                                </div>
                                                <div class="fitness-badge">Score: <?php echo round($lawyer['fitness']); ?></div>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h5 class="card-title font-weight-bold mb-0 text-primary"><?php echo $lawyer["first_Name"]; ?> <?php echo $lawyer["last_Name"]; ?></h5>
                                                    <div class="rating-badge">
                                                        <i class="fas fa-star"></i> <?php echo $avg_rating; ?>
                                                    </div>
                                                </div>
                                                <p class="text-muted mb-2 small font-weight-medium"><?php echo $lawyer["speciality"]; ?></p>
                                                <div class="d-flex align-items-center mb-3 text-muted small">
                                                    <span class="mr-3"><i class="fas fa-briefcase mr-1"></i> <?php echo $lawyer["practise_Length"]; ?></span>
                                                    <span><i class="fas fa-map-marker-alt mr-1"></i> <?php echo $lawyer["city"]; ?></span>
                                                </div>
                                                <a href="single_lawyer.php?u_id=<?php echo $lawyer["u_id"]; ?>" class="btn btn-primary btn-block rounded-pill font-weight-bold shadow-sm">View Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                echo '      </div>
                                        </div>
                                      </div>';
                            }

							$whereConditions = [];
							$whereConditions[] = "user.status='Active'";
							
							if (!empty($experience)) {
								$whereConditions[] = "practise_Length LIKE '%$experience%'";
							}
							if (!empty($speciality)) {
								$whereConditions[] = "speciality LIKE '%$speciality%'";
							}
							if (!empty($location) && $location != 'Select City...') {
								$whereConditions[] = "city LIKE '%$location%'";
							}

							$whereClause = implode(' AND ', $whereConditions);
							
                            // Sorting logic
                            $orderBy = "lawyer.avg_rating DESC, lawyer.review_count DESC";
                            if($sort == 'most_reviewed') $orderBy = "lawyer.review_count DESC, lawyer.avg_rating DESC";
                            elseif($sort == 'experience') $orderBy = "FIELD(practise_Length, 'Most Senior', '16-20 years', '11-15 years', '6-10 years', '1-5 years') DESC";

							$result = mysqli_query($conn,"SELECT * FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id AND $whereClause ORDER BY $orderBy");

							if(mysqli_num_rows($result) > 0) {
								echo '<div class="col-12 mb-4 d-flex justify-content-between align-items-center">
                                        <h3 class="font-weight-bold mb-0">Search Results</h3>
                                        <div class="sort-container mb-0 py-2">
                                            <span class="text-muted small font-weight-bold text-uppercase">Sort By:</span>
                                            <select class="form-control form-control-sm border-0 bg-light font-weight-bold" style="width: auto;" onchange="location.href=this.value">
                                                <option value="?search=1&experience='.$experience.'&speciality='.$speciality.'&location='.$location.'&sort=best_rated" '.($sort=='best_rated'?'selected':'').'>Best Rated</option>
                                                <option value="?search=1&experience='.$experience.'&speciality='.$speciality.'&location='.$location.'&sort=most_reviewed" '.($sort=='most_reviewed'?'selected':'').'>Most Reviewed</option>
                                                <option value="?search=1&experience='.$experience.'&speciality='.$speciality.'&location='.$location.'&sort=experience" '.($sort=='experience'?'selected':'').'>Experience Level</option>
                                            </select>
                                        </div>
                                      </div>';
								while($row = mysqli_fetch_array($result)) {
									// Simulate match score
									$match_score = rand(85, 99);
                                    $avg_rating = number_format($row['avg_rating'], 1);
                                    $review_count = $row['review_count'];
								?>
								<div class="col-md-6 col-lg-4 mb-4">
									<div class="card lawyer-card h-100 animate-fade-in">
										<div class="lawyer-img-container">
											<img src="<?php echo img_src($row["image"]); ?>" alt="<?php echo $row["first_Name"]; ?>">
											<div class="match-score">
												<i class="fas fa-bolt"></i> <?php echo $match_score; ?>% Match
											</div>
										</div>
										<div class="card-body p-4">
											<div class="d-flex justify-content-between align-items-start mb-2">
												<h5 class="card-title font-weight-bold mb-0 text-primary"><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></h5>
												<div class="rating-badge">
													<i class="fas fa-star"></i> <?php echo number_format($row['avg_rating'], 1); ?>
												</div>
											</div>
											<p class="text-muted mb-2 small font-weight-medium"><?php echo $row["speciality"]; ?></p>
											<div class="d-flex align-items-center mb-3 text-muted small">
												<span class="mr-3"><i class="fas fa-briefcase mr-1"></i> <?php echo $row["practise_Length"]; ?></span>
												<span><i class="fas fa-map-marker-alt mr-1"></i> <?php echo $row["city"]; ?></span>
											</div>
											<a href="single_lawyer.php?u_id=<?php echo $row["u_id"]; ?>" class="btn btn-primary btn-block rounded-pill font-weight-bold shadow-sm">View Profile</a>
										</div>
									</div>
								</div>
								<?php
								}
							} else {
								echo '<div class="col-12 text-center py-5"><h3 class="text-muted">No lawyers found matching your criteria. Try adjusting your search.</h3></div>';
							}
						}
					?>
				</div>
			</div>
		</section>
		
		<?php include 'includes/footer.php'; ?>
	</body>
</html>
