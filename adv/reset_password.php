<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';

$error = '';
$success = false;
$email = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
$token = isset($_REQUEST['token']) ? trim($_REQUEST['token']) : '';

$con = connect();

/**
 * Returns the reset row id when the token is valid (unused + not expired),
 * otherwise false.
 */
function validate_reset_token($con, $email, $token) {
    if (!$con || $email === '' || $token === '') {
        return false;
    }
    $hash = hash('sha256', $token);
    $stmt = mysqli_prepare($con, "SELECT id FROM password_resets WHERE email = ? AND token_hash = ? AND used = 0 AND expires_at > ? LIMIT 1");
    if (!$stmt) {
        return false;
    }
    $now = time();
    mysqli_stmt_bind_param($stmt, 'ssi', $email, $hash, $now);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $rid);
    $found = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $found ? $rid : false;
}

$reset_id = validate_reset_token($con, $email, $token);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $new_password     = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (!$reset_id) {
        $error = "This reset link is invalid or has expired. Please request a new one.";
    } elseif (strlen($new_password) < 6) {
        $error = "Your new password must be at least 6 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error = "The two passwords do not match.";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $upd = mysqli_prepare($con, "UPDATE user SET password = ? WHERE email = ?");
        if ($upd) {
            mysqli_stmt_bind_param($upd, 'ss', $hashed, $email);
            mysqli_stmt_execute($upd);
            mysqli_stmt_close($upd);
        }
        $mark = mysqli_prepare($con, "UPDATE password_resets SET used = 1 WHERE id = ?");
        if ($mark) {
            mysqli_stmt_bind_param($mark, 'i', $reset_id);
            mysqli_stmt_execute($mark);
            mysqli_stmt_close($mark);
        }
        $success = true;
    }
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
		<title>Reset Password - Lawyer Match AI</title>
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
						<i class="fas fa-lock fa-2x"></i>
					</div>
					<h2 class="font-weight-bold">Set a New Password</h2>
				</div>

				<?php if($success): ?>
					<div class="alert alert-success border-0 shadow-sm mb-4">
						<i class="fas fa-check-circle mr-2"></i> Your password has been updated successfully. You can now log in with your new password.
					</div>
					<a href="login.php" class="btn btn-modern btn-block rounded-pill">Go to Login</a>
				<?php elseif(!$reset_id): ?>
					<div class="alert alert-danger border-0 shadow-sm mb-4">
						<i class="fas fa-times-circle mr-2"></i> This password reset link is invalid or has expired. Please request a new one.
					</div>
					<a href="forgot_password.php" class="btn btn-modern btn-block rounded-pill">Request New Link</a>
				<?php else: ?>
					<?php if($error !== ''): ?>
						<div class="alert alert-danger border-0 shadow-sm mb-4">
							<i class="fas fa-times-circle mr-2"></i> <?php echo htmlspecialchars($error); ?>
						</div>
					<?php endif; ?>

					<form action="reset_password.php" method="post" autocomplete="off">
						<input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
						<input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

						<div class="form-group">
							<label class="font-weight-bold small text-uppercase text-muted">New Password</label>
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text bg-light border-0"><i class="fas fa-key text-muted"></i></span></div>
								<input type="password" name="new_password" id="new_password" class="form-control bg-light border-0" placeholder="Enter new password" minlength="6" required>
							</div>
							<small class="form-text text-muted">Minimum 6 characters.</small>
						</div>

						<div class="form-group">
							<label class="font-weight-bold small text-uppercase text-muted">Confirm Password</label>
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text bg-light border-0"><i class="fas fa-check-double text-muted"></i></span></div>
								<input type="password" name="confirm_password" id="confirm_password" class="form-control bg-light border-0" placeholder="Confirm new password" required>
							</div>
						</div>

						<button type="submit" name="reset" class="btn btn-modern btn-block rounded-pill mt-4">
							<i class="fas fa-save mr-2"></i> Update Password
						</button>
					</form>

					<script>
						var p = document.getElementById("new_password"),
						    c = document.getElementById("confirm_password");
						function validatePassword() {
							if (p.value !== c.value) {
								c.setCustomValidity("Passwords Don't Match");
							} else {
								c.setCustomValidity('');
							}
						}
						p.onchange = validatePassword;
						c.onkeyup = validatePassword;
					</script>
				<?php endif; ?>
			</div>
		</div>

		<?php include 'includes/footer.php'; ?>
	</body>
</html>
