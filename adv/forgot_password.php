<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';
include_once 'includes/mail_util.php';

// Create the reset table on demand (idempotent, works on TiDB and MySQL).
function ensure_password_resets_table($con) {
    mysqli_query($con, "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token_hash CHAR(64) NOT NULL,
        expires_at INT NOT NULL,
        used TINYINT(1) NOT NULL DEFAULT 0,
        created_at INT NOT NULL,
        INDEX idx_token (token_hash),
        INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $con = connect();
        if ($con) {
            ensure_password_resets_table($con);

            $stmt = mysqli_prepare($con, "SELECT u_id, first_Name, role FROM user WHERE email = ? LIMIT 1");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $u_id, $first_Name, $role);
                $found = mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
            }

            if (!empty($found)) {
                $token = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $token);
                $now = time();
                $expires = $now + 3600; // 1 hour

                // Invalidate any previous tokens for this email.
                $del = mysqli_prepare($con, "DELETE FROM password_resets WHERE email = ?");
                if ($del) {
                    mysqli_stmt_bind_param($del, 's', $email);
                    mysqli_stmt_execute($del);
                    mysqli_stmt_close($del);
                }

                $ins = mysqli_prepare($con, "INSERT INTO password_resets (email, token_hash, expires_at, used, created_at) VALUES (?, ?, ?, 0, ?)");
                if ($ins) {
                    mysqli_stmt_bind_param($ins, 'ssii', $email, $token_hash, $expires, $now);
                    mysqli_stmt_execute($ins);
                    mysqli_stmt_close($ins);

                    $reset_link = site_base_url() . 'reset_password.php?email=' . urlencode($email) . '&token=' . $token;
                    $name = !empty($first_Name) ? $first_Name : 'there';
                    $subject = "Reset your Lawyer Match AI password";
                    $body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
                        <div style='background: #2563eb; color: white; padding: 20px; text-align: center;'>
                            <h2 style='margin: 0;'>Password Reset Request</h2>
                        </div>
                        <div style='padding: 30px; background: #fff; color: #333; line-height: 1.6;'>
                            <p>Hi " . htmlspecialchars($name) . ",</p>
                            <p>We received a request to reset the password for your Lawyer Match AI account.</p>
                            <p style='text-align: center; margin: 30px 0;'>
                                <a href='" . $reset_link . "' style='background: #2563eb; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 50px; display: inline-block;'>Reset My Password</a>
                            </p>
                            <p style='font-size: 13px; color: #666;'>Or copy this link into your browser:<br>
                                <a href='" . $reset_link . "'>" . $reset_link . "</a>
                            </p>
                            <p style='font-size: 13px; color: #999;'>This link expires in 1 hour. If you did not request a password reset, you can safely ignore this email.</p>
                        </div>
                    </div>";
                    send_notification_email($email, $subject, $body);
                }
            }
        }
    }
    // Always show the same message to avoid revealing whether an account exists.
    header('Location: forgot_password.php?status=sent');
    exit();
}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/modern_style.css">
		<title>Forgot Password - Lawyer Match AI</title>
		<style>
			body { background-color: var(--bg-color); font-family: 'Inter', sans-serif; }
			.report-container { max-width: 500px; margin: 60px auto; }
		</style>
	</head>
	<body>
		<?php include 'includes/navbar.php'; ?>

		<div class="container report-container">
			<div class="glass-panel p-4 p-md-5 animate-fade-in">
				<div class="text-center mb-4">
					<div class="d-inline-block p-3 rounded-circle bg-light text-primary mb-3">
						<i class="fas fa-key fa-2x"></i>
					</div>
					<h2 class="font-weight-bold">Forgot Password?</h2>
					<p class="text-muted">Enter your registered email and we'll send you a reset link.</p>
				</div>

				<?php if(isset($_GET['status']) && $_GET['status'] == 'sent'): ?>
					<div class="alert alert-success border-0 shadow-sm mb-4">
						<i class="fas fa-check-circle mr-2"></i> If an account exists for that email, a password reset link has been sent. Please check your inbox (and spam folder).
					</div>
				<?php endif; ?>

				<form action="forgot_password.php" method="post">
					<div class="form-group">
						<label class="font-weight-bold small text-uppercase text-muted">Email Address</label>
						<div class="input-group">
							<div class="input-group-prepend"><span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span></div>
							<input type="email" name="email" class="form-control bg-light border-0" placeholder="your@email.com" required>
						</div>
					</div>

					<button type="submit" name="submit" class="btn btn-modern btn-block rounded-pill mt-4">
						<i class="fas fa-paper-plane mr-2"></i> Send Reset Link
					</button>
				</form>
				<div class="text-center mt-4">
					<a href="login.php" class="text-muted small"><i class="fas fa-arrow-left mr-1"></i> Back to Login</a>
				</div>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
	</body>
</html>
