<?php
	include_once 'includes/session_init.php';
	include("db_con/dbCon.php");
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Match With a Lawyer - Lawyer Match AI</title>
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
		<!-- Removed local Font Awesome CSS to avoid missing webfonts; using CDN only -->
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<link rel="stylesheet" href="css/media.css">
		<style>
			.step-container {
				display: none;
			}
			.step-container.active {
				display: block;
				animation: fadeIn 0.5s ease-in-out;
			}
			.category-card {
				cursor: pointer;
				transition: all 0.3s ease;
				border: 2px solid transparent;
			}
			.category-card:hover, .category-card.selected {
				border-color: var(--primary-color);
				background: rgba(var(--primary-rgb), 0.05);
				transform: translateY(-5px);
			}
			.progress-bar-custom {
				height: 6px;
				background-color: #e9ecef;
				border-radius: 10px;
				overflow: hidden;
				margin-bottom: 30px;
			}
			.progress-fill {
				height: 100%;
				background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
				width: 0%;
				transition: width 0.5s ease;
			}
		</style>
	</head>
	<body>
		<!-- Navbar -->
		<?php include 'includes/navbar.php'; ?>

		<div class="container py-5">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="glass-panel p-5">
						
						<!-- Progress Bar -->
						<div class="progress-bar-custom">
							<div class="progress-fill" id="progressBar"></div>
						</div>

						<form id="matchForm" action="searchLawyer.php" method="POST">
							<!-- Hidden Inputs for Final Submission -->
							<input type="hidden" name="speciality" id="finalSpeciality">
							<input type="hidden" name="location" id="finalLocation">
							<input type="hidden" name="experience" id="finalExperience" value=""> <!-- Default to Any -->
							<input type="hidden" name="submit" value="submit">

							<!-- Step 1: Category -->
							<div class="step-container active" id="step1">
								<div class="text-center mb-5">
									<h2 class="font-weight-bold">What kind of legal help do you need?</h2>
									<p class="text-muted">Select the category that best fits your situation.</p>
								</div>
								
								<div class="row">
									<div class="col-md-4 mb-3">
										<div class="card category-card p-3 text-center" onclick="selectCategory('Criminal law', this)">
											<i class="fas fa-gavel fa-3x mb-3 text-primary"></i>
											<h6 class="font-weight-bold">Criminal Defense</h6>
										</div>
									</div>
									<div class="col-md-4 mb-3">
										<div class="card category-card p-3 text-center" onclick="selectCategory('Family law', this)">
											<i class="fas fa-home fa-3x mb-3 text-success"></i>
											<h6 class="font-weight-bold">Family & Divorce</h6>
										</div>
									</div>
									<div class="col-md-4 mb-3">
										<div class="card category-card p-3 text-center" onclick="selectCategory('Property law', this)">
											<i class="fas fa-building fa-3x mb-3 text-info"></i>
											<h6 class="font-weight-bold">Property & Real Estate</h6>
										</div>
									</div>
									<div class="col-md-4 mb-3">
										<div class="card category-card p-3 text-center" onclick="selectCategory('Corporate law', this)">
											<i class="fas fa-briefcase fa-3x mb-3 text-warning"></i>
											<h6 class="font-weight-bold">Corporate / Business</h6>
										</div>
									</div>
									<div class="col-md-4 mb-3">
										<div class="card category-card p-3 text-center" onclick="selectCategory('Civil law', this)">
											<i class="fas fa-scale-balanced fa-3x mb-3 text-danger"></i>
											<h6 class="font-weight-bold">Civil Litigation</h6>
										</div>
									</div>
									<div class="col-md-4 mb-3">
										<div class="card category-card p-3 text-center" onclick="selectCategory('Other', this)">
											<i class="fas fa-question-circle fa-3x mb-3 text-secondary"></i>
											<h6 class="font-weight-bold">Other / Unsure</h6>
										</div>
									</div>
								</div>

								<div class="text-center mt-4">
									<button type="button" class="btn btn-modern btn-lg px-5 disabled" id="step1Btn" onclick="nextStep(2)">Next Step <i class="fas fa-arrow-right ml-2"></i></button>
								</div>
							</div>

							<!-- Step 2: Location -->
							<div class="step-container" id="step2">
								<div class="text-center mb-5">
									<h2 class="font-weight-bold">Where are you located?</h2>
									<p class="text-muted">We'll find lawyers near you.</p>
								</div>

								<div class="form-group">
									<select id="locationSelect" class="form-control custom-select-lg border-0 bg-light" onchange="enableStep2()">
										<option value="" selected disabled>Select City...</option>
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

								<div class="text-center mt-4">
									<button type="button" class="btn btn-outline-secondary mr-3" onclick="prevStep(1)">Back</button>
									<button type="button" class="btn btn-modern btn-lg px-5 disabled" id="step2Btn" onclick="nextStep(3)">Next Step <i class="fas fa-arrow-right ml-2"></i></button>
								</div>
							</div>

							<!-- Step 3: Description (Optional/AI Hook) -->
							<div class="step-container" id="step3">
								<div class="text-center mb-5">
									<h2 class="font-weight-bold">Describe your case</h2>
									<p class="text-muted">Briefly tell us what happened. Our AI will analyze this to refine the match.</p>
								</div>

								<div class="form-group">
									<textarea class="form-control bg-light border-0" rows="5" placeholder="e.g. I was unfairly terminated from my job..."></textarea>
								</div>

								<div class="text-center mt-4">
									<button type="button" class="btn btn-outline-secondary mr-3" onclick="prevStep(2)">Back</button>
									<button type="button" class="btn btn-modern btn-lg px-5" onclick="startMatching()">Find My Lawyer <i class="fas fa-search ml-2"></i></button>
								</div>
							</div>

							<!-- Step 4: AI Loading -->
							<div class="step-container" id="step4">
								<div class="text-center py-5">
									<div class="spinner-border text-primary mb-4" role="status" style="width: 3rem; height: 3rem;">
										<span class="sr-only">Loading...</span>
									</div>
									<h3 class="font-weight-bold mb-2">AI is analyzing your case...</h3>
									<p class="text-muted" id="loadingText">Scanning for best legal matches in your area...</p>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
		<script>
			let currentStep = 1;
			const totalSteps = 3;

			function updateProgress() {
				const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
				document.getElementById('progressBar').style.width = progress + '%';
			}

			function nextStep(step) {
				document.getElementById('step' + currentStep).classList.remove('active');
				currentStep = step;
				document.getElementById('step' + currentStep).classList.add('active');
				updateProgress();
			}

			function prevStep(step) {
				document.getElementById('step' + currentStep).classList.remove('active');
				currentStep = step;
				document.getElementById('step' + currentStep).classList.add('active');
				updateProgress();
			}

			function selectCategory(category, element) {
				// Remove selected class from all cards
				document.querySelectorAll('.category-card').forEach(card => card.classList.remove('selected'));
				// Add selected class to clicked card
				element.classList.add('selected');
				
				// Set hidden input value
				document.getElementById('finalSpeciality').value = category;
				
				// Enable Next button
				document.getElementById('step1Btn').classList.remove('disabled');
			}

			function enableStep2() {
				const location = document.getElementById('locationSelect').value;
				if(location) {
					document.getElementById('finalLocation').value = location;
					document.getElementById('step2Btn').classList.remove('disabled');
				}
			}

			function startMatching() {
				nextStep(4); // Show loading screen
				
				const loadingTexts = [
					"Analyzing case details...",
					"Checking lawyer availability...",
					"Filtering by specialization...",
					"Ranking best matches..."
				];
				
				let i = 0;
				const interval = setInterval(() => {
					if(i < loadingTexts.length) {
						document.getElementById('loadingText').innerText = loadingTexts[i];
						i++;
					}
				}, 800);

				setTimeout(() => {
					clearInterval(interval);
					document.getElementById('matchForm').submit();
				}, 3500); // 3.5 seconds delay for effect
			}
		</script>
	</body>
</html>
