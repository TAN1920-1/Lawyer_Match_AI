<?php
	include_once 'includes/session_init.php';
	include("db_con/dbCon.php");
	include_once 'includes/image_util.php';

	$folder = "images/actors/";
$slides = [
    'images/close-up-lawyer-signing-papers.jpg',
    'images/law-justice-gavel-statue-books.jpg',
    'images/lawyer-working-document-with-scales-justice.jpg',
    'images/still-life-with-scales-justice.jpg',
];
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
		<link rel="stylesheet" href="css/style.css"> <!-- Kept for legacy styles if needed -->
		<link rel="stylesheet" href="css/modern_style.css"> <!-- New Modern CSS -->
		<link rel="stylesheet" href="css/media.css">
		<title>Lawyer Match AI</title>


		<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Slider container */
.slider 
{


	position: relative;
    width: 80vw;
    height: 50vh;
	margin: 50px auto;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
    overflow: hidden;



/* 
    position: relative;
    max-width: 900px;
    height: 500px;
     */
}

/* Slides */
.slide {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slide.active {
    opacity: 1;
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Actor name overlay */
.caption {
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 20px;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    color: #fff;
    font-size: 24px;
    font-weight: bold;
}

/* Navigation buttons */
.nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 35px;
    color: #fff;
    background: rgba(0,0,0,0.5);
    padding: 10px 18px;
    cursor: pointer;
    user-select: none;
}

.prev { left: 15px; }
.next { right: 15px; }

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
</style>


		
	</head>
	<body>
		<!-- Navbar -->
		<?php include 'includes/navbar.php'; ?>

		<!-- Hero Section -->
		<section class="hero-section">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-6 hero-content animate-fade-in">



					<div class="slider">
    <?php foreach ($slides as $i => $src): ?>
        <div class="slide <?= $i === 0 ? 'active' : '' ?>">
            <img src="<?= $src ?>" alt="">
        </div>
    <?php endforeach; ?>

    <div class="nav prev" onclick="prevSlide()">❮</div>
    <div class="nav next" onclick="nextSlide()">❯</div>
</div>

<script>
let slides = document.querySelectorAll('.slide');
let index = 0;

function showSlide(i) {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[i].classList.add('active');
}

function nextSlide() {
    index = (index + 1) % slides.length;
    showSlide(index);
}

function prevSlide() {
    index = (index - 1 + slides.length) % slides.length;
    showSlide(index);
}

// Auto slide every 4 seconds
setInterval(nextSlide, 4000);
</script>
			
						<h1 class="font-weight-bold display-4 mb-4">Find the Right Lawyer.<br><span class="text-gradient">Powered by AI.</span></h1>
						<p class="lead text-muted mb-5">Connect with top-rated legal professionals instantly. Our intelligent matching system ensures you get the best legal representation for your needs.</p>
						<div class="d-flex flex-wrap gap-3">
							<a href="searchLawyer.php" class="btn btn-modern mr-3 btn-lg shadow-lg"><i class="fas fa-search mr-2"></i>Find a Lawyer</a>
							<button onclick="toggleChat()" class="btn btn-outline-modern btn-lg"><i class="fas fa-robot mr-2"></i>AI Assistant</button>
							<!-- <a href="http://localhost/law/" class="btn btn-outline-modern btn-lg"><i class="fas fa-robot mr-2"></i>AI Assistant</a> -->
							
						</div>
						<div class="mt-5 d-flex align-items-center text-muted small">
							<div class="mr-4"><i class="fas fa-check-circle text-success mr-2"></i>Verified Lawyers</div>
							<div class="mr-4"><i class="fas fa-check-circle text-success mr-2"></i>Free Matching</div>
							<div><i class="fas fa-check-circle text-success mr-2"></i>24/7 Support</div>
						</div>
					</div>
					<div class="col-lg-6 mt-5 mt-lg-0 animate-fade-in" style="animation-delay: 0.2s;">
						<div class="hero-image-container position-relative">
							<div class="absolute-bg-blob"></div>
							<!-- Floating Cards -->
							<div class="glass-panel p-3 position-absolute" style="top: 10%; right: -20px; z-index: 2; width: 200px; animation: float 6s ease-in-out infinite;">
								<div class="d-flex align-items-center">
									<div class="bg-primary text-white rounded-circle p-2 mr-3"><i class="fas fa-gavel"></i></div>
									<div>
										<h6 class="mb-0 font-weight-bold">Expert Legal Help</h6>
										<small class="text-muted">Just a click away</small>
									</div>
								</div>
							</div>
							<div class="glass-panel p-3 position-absolute" style="bottom: 10%; left: -20px; z-index: 2; width: 220px; animation: float 8s ease-in-out infinite reverse;">
								<div class="d-flex align-items-center">
									<div class="bg-success text-white rounded-circle p-2 mr-3"><i class="fas fa-shield-alt"></i></div>
									<div>
										<h6 class="mb-0 font-weight-bold">100% Secure</h6>
										<small class="text-muted">Confidential & Safe</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Features Section -->
		<section class="py-5">
			<div class="container">
				<div class="row text-center mb-5">
					<div class="col-lg-8 mx-auto">
						<h2 class="mb-3 font-weight-bold">Why Use Lawyer Match AI?</h2>
						<p class="text-muted">We combine legal expertise with advanced AI to give you the best experience.</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 mb-4">
						<div class="glass-panel p-4 h-100 text-center hover-lift">
							<div class="mb-4 text-primary"><i class="fas fa-brain fa-3x"></i></div>
							<h5 class="font-weight-bold mb-3">AI Smart Matching</h5>
							<p class="text-muted">Our AI algorithms analyze your case details to match you with the most suitable lawyers for your specific needs.</p>
						</div>
					</div>
					<div class="col-md-4 mb-4">
						<div class="glass-panel p-4 h-100 text-center hover-lift">
							<div class="mb-4 text-primary"><i class="fas fa-comments fa-3x"></i></div>
							<h5 class="font-weight-bold mb-3">Instant Consultation</h5>
							<p class="text-muted">Chat with our AI legal assistant to get preliminary advice and guidance before booking a consultation.</p>
						</div>
					</div>
					<div class="col-md-4 mb-4">
						<div class="glass-panel p-4 h-100 text-center hover-lift">
							<div class="mb-4 text-primary"><i class="fas fa-calendar-check fa-3x"></i></div>
							<h5 class="font-weight-bold mb-3">Easy Booking</h5>
							<p class="text-muted">Schedule appointments with top lawyers directly through our platform with just a few clicks.</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Lawyers Section -->
		<section class="py-5 bg-light">
			<div class="container">
				<div class="text-center mb-5">
					<h2 class="mb-3">Top Rated Lawyers</h2>
					<p class="text-muted">Expert legal professionals ready to help you</p>
				</div>
				<div class="row">
					<?php
						include_once 'db_con/dbCon.php';
						$conn = connect();
						if ($conn) {
							$result = mysqli_query($conn,"SELECT * FROM user,lawyer WHERE user.u_id=lawyer.lawyer_id AND user.status='Active' LIMIT 6");
							while($row = mysqli_fetch_array($result)) {
					?>
								<div class="col-md-6 col-lg-4 mb-4">
									<div class="lawyer-card">
										<?php 
											$img = $row["image"]; 
											$p = img_src($img, "images/actors/still-life-with-scales-justice.jpg");
											
                                        ?>
                                        <div class="lawyer-img-container">
                                            <img src="<?php echo $p; ?>" alt="<?php echo $row["first_Name"]; ?>">
                                        </div>
										<div class="card-body">
											<h5 class="card-title"><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></h5>
											<div class="speciality"><i class="fas fa-gavel mr-2"></i><?php echo $row["speciality"]; ?></div>
											<div class="experience"><i class="far fa-clock mr-2"></i><?php echo $row["practise_Length"]; ?></div>
											<a href="single_lawyer.php?u_id=<?php echo $row["u_id"]; ?>" class="btn btn-outline-modern btn-block btn-sm">View Profile</a>
										</div>
									</div>
								</div>
					<?php
							}
						} else {
					?>
							<div class="col-12">
								<div class="glass-panel p-4 text-center">
									<h5 class="mb-2">Top lawyers unavailable</h5>
									<p class="text-muted mb-0">Database connection is not available.</p>
								</div>
							</div>
					<?php
						}
					?>
				</div>
				<div class="text-center mt-4">
					<a href="lawyers.php" class="btn btn-link" style="color: var(--accent-primary);">View All Lawyers <i class="fas fa-arrow-right ml-1"></i></a>
				</div>
			</div>
		</section>

		<!-- About Section -->
		<section id="about" class="py-5">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-6 mb-4 mb-lg-0">
						<div class="glass-panel p-4">
							<h2 class="mb-4">Why Choose Lawyer Match AI?</h2>
							<p class="text-muted mb-4">LAWYER MATCH AI is an intelligent lawyer recommendation platform designed to connect individuals and businesses with the right legal professionals—quickly, accurately, and confidently.</p>
							
							<div class="d-flex align-items-center mb-3">
								<div class="mr-3 text-primary"><i class="fas fa-check-circle fa-2x"></i></div>
								<div>
									<h5 class="mb-1">AI-Powered Matching</h5>
									<small class="text-muted">Get matched with lawyers based on your specific case details.</small>
								</div>
							</div>
							
							<div class="d-flex align-items-center mb-3">
								<div class="mr-3 text-primary"><i class="fas fa-user-shield fa-2x"></i></div>
								<div>
									<h5 class="mb-1">Verified Professionals</h5>
									<small class="text-muted">All lawyers are verified and vetted for quality assurance.</small>
								</div>
							</div>

							<div class="mt-4">
								<h5>Contact Us</h5>
								<p class="mb-1"><i class="fas fa-map-marker-alt mr-2"></i>Lawyer Match AI, Pune</p>
								<p><i class="fas fa-envelope mr-2"></i>lawyermatch.ai@gmail.com</p>
								<!-- <h6 class="mt-3">Developers</h6> -->
								<!-- <p class="mb-1">1) Jeet A. Kumar</p>
								<p class="mb-1">2) Spandan S. Pathade</p>
								<p class="mb-1">3) Sarvesh V. Pendole</p>
								<p class="mb-3">4) Tanishq K. Rikame</p> -->
								<a href="developers.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">
									<i class="fas fa-info-circle mr-2"></i>Know More About Us
								</a>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<!-- Placeholder for map or image -->
						<div class="glass-panel p-2" style="height: 400px; border-radius: 20px; overflow: hidden;">
							<iframe src="https://maps.app.goo.gl/nwczsTkxSevMBETdA" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php include 'includes/footer.php'; ?>

	</body>
</html>
