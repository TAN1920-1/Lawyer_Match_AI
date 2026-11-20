<?php
include_once 'includes/session_init.php';
include_once 'includes/role_middleware.php';
check_role(['FIRM_ADMIN']);
include("db_con/dbCon.php");
$conn = connect();
$firm_id = $_SESSION['firm_id'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="css/modern_style.css">
    <title>Firm Dashboard - Lawyer Match AI</title>
    <style>
        .stats-card {
            border-radius: 15px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }
        .bg-gradient-primary { background: linear-gradient(45deg, #4e73df 0%, #224abe 100%); }
        .bg-gradient-success { background: linear-gradient(45deg, #1cc88a 0%, #13855c 100%); }
        .bg-gradient-info { background: linear-gradient(45deg, #36b9cc 0%, #258391 100%); }
    </style>
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
        <div class="glass-sidebar border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-primary font-weight-bold p-3">Firm Management</div>
            <div class="list-group list-group-flush">
                <a href="firm_dashboard.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
                <a href="firm_lawyers.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-users mr-2"></i>Manage Lawyers</a>
                <a href="firm_unified_bookings.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-calendar-alt mr-2"></i>All Bookings</a>
                <a href="firm_edit_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-user-edit mr-2"></i>Edit Profile</a>
                <a href="update_password_firm.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-key mr-2"></i>Update Password</a>
            </div>
        </div>

        <div id="page-content-wrapper" class="w-100">
            <div class="container-fluid p-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="font-weight-bold">Firm Dashboard</h2>
                        <p class="text-muted">Welcome back, <?php echo $_SESSION['first_Name']; ?>. Here's what's happening today.</p>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="row">
                    <?php
                    $lawyer_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM user WHERE firm_id='$firm_id' AND role='FIRM_LAWYER' AND is_active=1"))['count'];
                    $booking_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM booking b INNER JOIN user u ON b.lawyer_id = u.u_id WHERE u.firm_id='$firm_id'"))['count'];
                    $pending_booking = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM booking b INNER JOIN user u ON b.lawyer_id = u.u_id WHERE u.firm_id='$firm_id' AND b.status='Pending'"))['count'];
                    ?>
                    <div class="col-md-4">
                        <div class="stats-card bg-gradient-primary animate-fade-in">
                            <h5>Total Lawyers</h5>
                            <h3><?php echo $lawyer_count; ?></h3>
                            <i class="fas fa-users fa-2x float-right" style="opacity: 0.3; margin-top: -30px;"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card bg-gradient-success animate-fade-in" style="animation-delay: 0.1s;">
                            <h5>Total Bookings</h5>
                            <h3><?php echo $booking_count; ?></h3>
                            <i class="fas fa-calendar-check fa-2x float-right" style="opacity: 0.3; margin-top: -30px;"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card bg-gradient-info animate-fade-in" style="animation-delay: 0.2s;">
                            <h5>Pending Requests</h5>
                            <h3><?php echo $pending_booking; ?></h3>
                            <i class="fas fa-clock fa-2x float-right" style="opacity: 0.3; margin-top: -30px;"></i>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="glass-panel p-4 h-100">
                            <h5 class="font-weight-bold mb-4">Recent Bookings (All Lawyers)</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Lawyer</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $recent = mysqli_query($conn, "SELECT b.*, u.first_Name as lawyer_name, c.first_Name as client_name 
                                                                     FROM booking b 
                                                                     INNER JOIN user u ON b.lawyer_id = u.u_id 
                                                                     INNER JOIN user c ON b.client_id = c.u_id 
                                                                     WHERE u.firm_id='$firm_id' 
                                                                     ORDER BY b.date DESC LIMIT 5");
                                        while($r = mysqli_fetch_assoc($recent)) {
                                            $status_class = $r['status'] == 'Pending' ? 'warning' : ($r['status'] == 'Accepted' ? 'success' : 'danger');
                                            echo "<tr>
                                                <td>{$r['client_name']}</td>
                                                <td>{$r['lawyer_name']}</td>
                                                <td>{$r['date']}</td>
                                                <td><span class='badge badge-$status_class'>{$r['status']}</span></td>
                                            </tr>";
                                        }
                                        if(mysqli_num_rows($recent) == 0) echo "<tr><td colspan='4' class='text-center text-muted'>No recent bookings</td></tr>";
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <a href="firm_unified_bookings.php" class="btn btn-link p-0 mt-2">View all bookings <i class="fas fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-panel p-4">
                            <h5 class="font-weight-bold mb-4">Quick Actions</h5>
                            <a href="firm_lawyers.php#add" class="btn btn-modern btn-block mb-3 text-left">
                                <i class="fas fa-user-plus mr-2"></i> Add New Lawyer
                            </a>
                            <a href="firm_unified_bookings.php" class="btn btn-outline-primary btn-block mb-3 text-left">
                                <i class="fas fa-tasks mr-2"></i> Manage Bookings
                            </a>
                            <a href="firm_edit_profile.php" class="btn btn-outline-secondary btn-block text-left">
                                <i class="fas fa-id-card mr-2"></i> Firm Profile
                            </a>
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
