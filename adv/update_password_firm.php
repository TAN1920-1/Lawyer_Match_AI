<?php
include_once 'includes/session_init.php';
include_once 'includes/role_middleware.php';
check_role(['FIRM_ADMIN']);
include_once 'db_con/dbCon.php';
$conn = connect();

if (isset($_POST['set'])) {
    $firm_id = $_SESSION['firm_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $res = mysqli_query($conn, "SELECT password FROM user WHERE firm_id='$firm_id' AND role='FIRM_ADMIN'");
        $row = mysqli_fetch_assoc($res);
        
        if (password_verify($old_password, $row['password'])) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE user SET password='$hashed' WHERE firm_id='$firm_id' AND role='FIRM_ADMIN'");
            $success = "Password updated successfully!";
        } else {
            $error = "Old password is incorrect!";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="css/modern_style.css">
    <title>Update Password - Lawyer Match AI</title>
</head>
<body class="bg-light-modern">
    <header class="customnav glass-nav">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand cus-a font-weight-bold" href="index.php">LAWYER MATCH AI</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ml-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link text-dark"><i class="fas fa-building mr-1"></i> <?php echo $_SESSION['first_Name'];?></span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-sm btn-outline-danger rounded-pill ml-3" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> Log Out</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="glass-sidebar border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-primary font-weight-bold p-3">Firm Management</div>
            <div class="list-group list-group-flush">
                <a href="firm_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
                <a href="firm_lawyers.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-users mr-2"></i>Manage Lawyers</a>
                <a href="firm_unified_bookings.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-calendar-alt mr-2"></i>All Bookings</a>
                <a href="firm_edit_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-user-edit mr-2"></i>Edit Profile</a>
                <a href="update_password_firm.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-key mr-2"></i>Update Password</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <div class="container-fluid p-4 animate-fade-in">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="glass-panel p-5">
                            <div class="text-center mb-4">
                                <div class="d-inline-block p-3 rounded-circle bg-light text-primary mb-3">
                                    <i class="fas fa-lock fa-2x"></i>
                                </div>
                                <h3 class="font-weight-bold">Update Password</h3>
                                <p class="text-muted">Ensure your account stays secure</p>
                            </div>

                            <?php if(isset($success)) echo "<div class='alert alert-success border-0 shadow-sm'><i class='fas fa-check-circle mr-2'></i>$success</div>"; ?>
                            <?php if(isset($error)) echo "<div class='alert alert-danger border-0 shadow-sm'><i class='fas fa-exclamation-circle mr-2'></i>$error</div>"; ?>

                            <form action="" method="post">
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-uppercase text-muted">Current Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-key text-muted"></i></span>
                                        </div>
                                        <input type="password" name="old_password" class="form-control bg-light border-0" required>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-uppercase text-muted">New Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                        </div>
                                        <input type="password" name="new_password" class="form-control bg-light border-0" required>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-uppercase text-muted">Confirm New Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-shield-alt text-muted"></i></span>
                                        </div>
                                        <input type="password" name="confirm_password" class="form-control bg-light border-0" required>
                                    </div>
                                </div>

                                <button type="submit" name="set" class="btn btn-modern btn-block rounded-pill py-2 font-weight-bold">
                                    Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
