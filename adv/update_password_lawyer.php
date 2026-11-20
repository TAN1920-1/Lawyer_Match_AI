<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Lawyer'){
        include("db_con/dbCon.php");
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link rel="stylesheet" href="css/simple-sidebar.css">
        <link rel="stylesheet" href="css/modern_style.css">
        <title>Update Password - Lawyer</title>
    </head>
    <body class="bg-light-modern">
        <header class="customnav glass-nav">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-expand-lg ">
                            <a class="navbar-brand cus-a font-weight-bold" href="index.php">LAWYER MATCH AI</a>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto align-items-center">
                                    <li class="nav-item">
                                        <a class="nav-link cus-a" href="index.php">
                                            <i class="fas fa-home mr-1"></i> Home
                                        </a>
                                    </li>
                                    <?php 
                                        include_once 'includes/notification_dropdown.php';
                                    ?>
                                    <li class="">
                                        <a class="nav-link cus-a" href="#">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            <?php echo $_SESSION['first_Name'];?> <?php echo $_SESSION['last_Name'];?>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a class="nav-link cus-a" href="logout.php">
                                            <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <div class="d-flex" id="wrapper">
            <!-- Sidebar -->
            <div class="glass-sidebar border-right" id="sidebar-wrapper">
                <div class="sidebar-heading text-primary font-weight-bold">Lawyer Dashboard</div>
                <div class="list-group list-group-flush">
                    <a href="lawyer_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="lawyer_edit_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profile
                    </a>
                    <a href="lawyer_booking.php" class="list-group-item list-group-item-action bg-transparent text-dark">
                        <i class="fas fa-calendar-check mr-2"></i> Booking Requests
                    </a>
                    <a href="update_password_lawyer.php" class="list-group-item list-group-item-action active-modern">
                        <i class="fas fa-key mr-2"></i> Update Password
                    </a>
                </div>
            </div>
            <!-- /#sidebar-wrapper -->

            <div id="page-content-wrapper">
                <div class="container-fluid p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h2 class="font-weight-bold">Update Password</h2>
                            <p class="text-muted">Keep your account secure.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-11 col-lg-10">
                            <div class="glass-panel p-4 p-md-5">
                                <?php
                                    $con = connect();
                                    if(isset($_POST['update'])){
                                        $email = $_SESSION['email'];
                                        $password = mysqli_real_escape_string($con, $_POST['current']);
                                        $new_password = $_POST['new_password'];
                                        $p_length = strlen($new_password);
                                        if($p_length <= 5){
                                            echo "<div class='alert alert-danger alert-dismissible fade show border-0'>
                                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                            Minimum 6 characters required.
                                            </div>";
                                        } else {
                                            $result = mysqli_query($con, "SELECT * FROM user WHERE email = '" . $email. "' and password = '" . $password. "' and role='Lawyer'");
                                            if ($row = mysqli_fetch_array($result)) {
                                                $query = "UPDATE user SET password='$new_password' WHERE email='$email'";
                                                if(mysqli_query($con,$query)){
                                                    echo "<div class='alert alert-success alert-dismissible fade show border-0'>
                                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                    Password updated successfully.
                                                    </div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger alert-dismissible fade show border-0'>
                                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                                Current password is incorrect.
                                                </div>";
                                            }
                                        }
                                    }
                                ?>
                                <form autocomplete="off" method="post" action="update_password_lawyer.php" class="mt-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Current Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                            <input type="password" name="current" class="form-control" required placeholder="Enter current password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">New Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-key"></i></span></div>
                                            <input type="password" name="new_password" class="form-control" required placeholder="Enter new password">
                                        </div>
                                        <small class="form-text text-muted">Minimum 6 characters.</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Confirm Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-check-double"></i></span></div>
                                            <input type="password" id="confirm_password" class="form-control" required placeholder="Confirm new password">
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" name="update" value="Update" class="btn btn-modern btn-block rounded-pill">Update Password</button>
                                    </div>
                                </form>
                                <script>
                                    var np = document.getElementsByName('new_password')[0];
                                    var cp = document.getElementById('confirm_password');
                                    function v(){
                                        if(np.value !== cp.value){ cp.setCustomValidity("Passwords Don't Match"); }
                                        else { cp.setCustomValidity(''); }
                                    }
                                    np.onchange = v;
                                    cp.onkeyup = v;
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>
<?php
    } else {
        header('location:login.php?deactivate');
    }
?>
