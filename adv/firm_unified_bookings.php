<?php
include_once 'includes/session_init.php';
include_once 'includes/role_middleware.php';
check_role(['FIRM_ADMIN']);
include("db_con/dbCon.php");
$conn = connect();
$firm_id = $_SESSION['firm_id'];

// Reassign handler
if (isset($_POST['reassign'])) {
    $bid = mysqli_real_escape_string($conn, $_POST['booking_id']);
    $lid = mysqli_real_escape_string($conn, $_POST['new_lawyer_id']);
    mysqli_query($conn, "UPDATE booking SET lawyer_id='$lid' WHERE booking_id='$bid'");
    include_once 'includes/audit_util.php';
    log_firm_action('REASSIGN_BOOKING', $firm_id, $lid, "Reassigned booking $bid");
    header("Location: firm_unified_bookings.php?success=reassigned");
    exit;
}

// Cancel handler
if (isset($_GET['cancel_id'])) {
    $bid = mysqli_real_escape_string($conn, $_GET['cancel_id']);
    mysqli_query($conn, "UPDATE booking SET status='Cancelled' WHERE booking_id='$bid'");
    include_once 'includes/audit_util.php';
    log_firm_action('CANCEL_BOOKING', $firm_id, null, "Cancelled booking $bid");
    header("Location: firm_unified_bookings.php?success=cancelled");
    exit;
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
    <title>Unified Booking Dashboard - Lawyer Match AI</title>
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
                <a href="firm_unified_bookings.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-calendar-alt mr-2"></i>All Bookings</a>
                <a href="firm_edit_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-user-edit mr-2"></i>Edit Profile</a>
                <a href="update_password_firm.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-key mr-2"></i>Update Password</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <div class="container-fluid p-4">
                <div class="glass-panel p-4 mb-4">
                    <h4 class="font-weight-bold mb-4">Unified Firm Bookings</h4>
                    <?php if(isset($_GET['success'])) echo "<div class='alert alert-success'>Action completed successfully!</div>"; ?>
                    
                    <!-- Filters -->
                    <form class="form-row mb-4">
                        <div class="col-md-3">
                            <label class="small font-weight-bold text-muted">Lawyer</label>
                            <select class="form-control bg-light border-0" name="lawyer_id">
                                <option value="">All Lawyers</option>
                                <?php
                                $lawyers = mysqli_query($conn, "SELECT u_id, first_Name FROM user WHERE firm_id='$firm_id' AND role='FIRM_LAWYER' AND is_active=1");
                                while($l = mysqli_fetch_assoc($lawyers)) {
                                    $sel = (isset($_GET['lawyer_id']) && $_GET['lawyer_id'] == $l['u_id']) ? 'selected' : '';
                                    echo "<option value='{$l['u_id']}' $sel>{$l['first_Name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small font-weight-bold text-muted">Status</label>
                            <select class="form-control bg-light border-0" name="status">
                                <option value="">All Status</option>
                                <option value="Pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Accepted" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Accepted') ? 'selected' : ''; ?>>Accepted</option>
                                <option value="Completed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="Cancelled" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Filter</button>
                            <a href="firm_unified_bookings.php" class="btn btn-outline-secondary rounded-pill px-4">Reset</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Lawyer</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $where = ["u.firm_id='$firm_id'"];
                                if(!empty($_GET['lawyer_id'])) $where[] = "b.lawyer_id='" . mysqli_real_escape_string($conn, $_GET['lawyer_id']) . "'";
                                if(!empty($_GET['status'])) $where[] = "b.status='" . mysqli_real_escape_string($conn, $_GET['status']) . "'";
                                
                                $sql = "SELECT b.*, u.first_Name as lawyer_name, c.first_Name as client_name 
                                        FROM booking b 
                                        INNER JOIN user u ON b.lawyer_id = u.u_id 
                                        INNER JOIN user c ON b.client_id = c.u_id 
                                        WHERE " . implode(' AND ', $where) . " 
                                        ORDER BY b.date DESC";
                                
                                $result = mysqli_query($conn, $sql);
                                while($r = mysqli_fetch_assoc($result)) {
                                    $status_class = $r['status'] == 'Pending' ? 'warning' : ($r['status'] == 'Accepted' ? 'success' : 'danger');
                                    echo "<tr>
                                        <td>{$r['client_name']}</td>
                                        <td>{$r['lawyer_name']}</td>
                                        <td>{$r['date']}</td>
                                        <td>{$r['description']}</td>
                                        <td><span class='badge badge-$status_class'>{$r['status']}</span></td>
                                        <td>
                                            <button class='btn btn-sm btn-outline-info rounded-pill' data-toggle='modal' data-target='#reassignModal{$r['booking_id']}'>Reassign</button>
                                            <a href='firm_unified_bookings.php?cancel_id={$r['booking_id']}' class='btn btn-sm btn-outline-danger rounded-pill' onclick='return confirm(\"Are you sure you want to cancel this booking?\")'>Cancel</a>
                                            
                                            <!-- Reassign Modal -->
                                            <div class='modal fade' id='reassignModal{$r['booking_id']}' tabindex='-1'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content border-0 shadow-lg' style='border-radius: 20px;'>
                                                        <form method='POST'>
                                                            <div class='modal-header border-0 p-4'>
                                                                <h5 class='modal-title font-weight-bold'>Reassign Booking</h5>
                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                            </div>
                                                            <div class='modal-body p-4'>
                                                                <input type='hidden' name='booking_id' value='{$r['booking_id']}'>
                                                                <div class='form-group'>
                                                                    <label class='small font-weight-bold text-muted'>New Lawyer</label>
                                                                    <select class='form-control bg-light border-0' name='new_lawyer_id' required>";
                                                                    $all_l = mysqli_query($conn, "SELECT u_id, first_Name FROM user WHERE firm_id='$firm_id' AND role='FIRM_LAWYER' AND is_active=1");
                                                                    while($al = mysqli_fetch_assoc($all_l)) {
                                                                        if($al['u_id'] == $r['lawyer_id']) continue;
                                                                        echo "<option value='{$al['u_id']}'>{$al['first_Name']}</option>";
                                                                    }
                                                                    echo "</select>
                                                                </div>
                                                            </div>
                                                            <div class='modal-footer border-0 p-4'>
                                                                <button type='submit' name='reassign' class='btn btn-primary rounded-pill px-4 font-weight-bold'>Confirm Reassign</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                                if(mysqli_num_rows($result) == 0) echo "<tr><td colspan='6' class='text-center text-muted'>No bookings found.</td></tr>";
                                ?>
                            </tbody>
                        </table>
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
