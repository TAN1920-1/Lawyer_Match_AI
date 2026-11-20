<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<title>Report a Bug - Lawyer Match AI</title>
		<style>
			body { background-color: var(--bg-color); font-family: 'Inter', sans-serif; }
			.report-container { max-width: 600px; margin: 50px auto; }
            .form-icon { width: 40px; text-align: center; color: var(--primary-color); }
		</style>
	</head>
	<body>
		<?php include 'includes/navbar.php'; ?>

		<div class="container report-container">
			<div class="glass-panel p-4 p-md-5 animate-fade-in">
				<div class="text-center mb-4">
					<div class="d-inline-block p-3 rounded-circle bg-light text-danger mb-3">
						<i class="fas fa-bug fa-2x"></i>
					</div>
					<h2 class="font-weight-bold">Report a Bug</h2>
					<p class="text-muted">Help us improve. Describe the issue you encountered.</p>
				</div>

                <?php if(isset($_GET['status'])): ?>
                    <?php if($_GET['status'] == 'success'): ?>
                        <div class="alert alert-success border-0 shadow-sm mb-4">
                            <i class="fas fa-check-circle mr-2"></i> Thank you! Your report has been submitted to our technical team.
                        </div>
                    <?php elseif($_GET['status'] == 'error'): ?>
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <i class="fas fa-times-circle mr-2"></i> Sorry, there was an issue sending your report. Please try again or contact us directly.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

				<form action="send_bug_report.php" method="post">
					<div class="form-group">
						<label class="font-weight-bold small text-uppercase text-muted">Full Name</label>
						<div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span></div>
						    <input type="text" name="name" class="form-control bg-light border-0" placeholder="Enter your name" required 
                                value="<?php echo isset($_SESSION['first_Name']) ? $_SESSION['first_Name'].' '.$_SESSION['last_Name'] : ''; ?>">
                        </div>
					</div>

					<div class="form-group">
						<label class="font-weight-bold small text-uppercase text-muted">Email Address</label>
						<div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span></div>
						    <input type="email" name="email" class="form-control bg-light border-0" placeholder="your@email.com" required
                                value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
                        </div>
					</div>

                    <div class="form-group">
						<label class="font-weight-bold small text-uppercase text-muted">Contact Number</label>
						<div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light border-0"><i class="fas fa-phone text-muted"></i></span></div>
						    <input type="text" name="contact" class="form-control bg-light border-0" placeholder="Mobile number" required>
                        </div>
					</div>

					<div class="form-group">
						<label class="font-weight-bold small text-uppercase text-muted">Bug Type</label>
						<div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light border-0"><i class="fas fa-tags text-muted"></i></span></div>
                            <select name="bug_type" class="form-control bg-light border-0" required>
                                <option value="" disabled selected>Select bug category</option>
                                <option value="UI/Design">UI/Layout Issue</option>
                                <option value="Functionality">Feature Not Working</option>
                                <option value="Search/AI">Search or AI Issue</option>
                                <option value="Performance">Slow Loading</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
					</div>

					<div class="form-group">
						<label class="font-weight-bold small text-uppercase text-muted">Description</label>
						<textarea name="description" class="form-control bg-light border-0" rows="5" placeholder="Please provide steps to reproduce the bug..." required></textarea>
					</div>

					<button type="submit" name="submit_bug" class="btn btn-modern btn-block rounded-pill mt-4">
						<i class="fas fa-paper-plane mr-2"></i> Submit Report
					</button>
				</form>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
	</body>
</html>
