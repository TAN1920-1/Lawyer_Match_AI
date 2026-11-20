<?php include_once 'includes/session_init.php'; ?>
<nav class="navbar navbar-expand-lg navbar-light glass-nav">
	<div class="container">
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
					<a class="nav-link cus-a" href="searchFirm.php">Find Law Firms</a>
				</li>
				<li class="nav-item">
					<a class="nav-link cus-a" href="index.php#about">About Us</a>
				</li>
				<?php if(isset($_SESSION['login']) && $_SESSION['login'] == TRUE){ 
						$dash = 'user_dashboard.php';
						if(isset($_SESSION['lawyer_id'])) $dash = 'lawyer_dashboard.php';
						if(isset($_SESSION['firm_id'])) $dash = 'firm_dashboard.php';
                        include_once 'includes/notification_dropdown.php';
					?>
					<li class="nav-item">
						<a class="nav-link cus-a" href="<?php echo $dash; ?>">Dashboard</a>
					</li>
					<li class="nav-item">
						<a class="btn btn-outline-modern ml-3" href="logout.php">Logout</a>
					</li>
				<?php }else{ ?>
					<li class="nav-item">
						<a class="nav-link cus-a" href="login.php">Login</a>
					</li>
					<li class="nav-item ml-2">
						<a class="btn btn-modern" href="register_options.php">Get Started</a>
					</li>
				<?php }?>
				<li class="nav-item ml-3">
					<button id="themeToggle" class="btn btn-outline-modern btn-sm" type="button" aria-label="Toggle theme">
						<i class="fas fa-moon"></i>
					</button>
				</li>
			</ul>
		</div>
	</div>
</nav>
