<?php
include_once 'includes/session_init.php';
include_once 'includes/role_middleware.php';
check_role(['FIRM_ADMIN']);
include("db_con/dbCon.php");
$conn = connect();
$firm_id = $_SESSION['firm_id'];

// Handle removal via PHP for simplicity in this project's style
if (isset($_GET['remove_id'])) {
    $rid = mysqli_real_escape_string($conn, $_GET['remove_id']);
    // Check pending bookings
    $pending_res = mysqli_query($conn, "SELECT * FROM booking WHERE lawyer_id='$rid' AND status='Pending'");
    $pending_count = mysqli_num_rows($pending_res);
    
    if ($pending_count > 0 && !isset($_GET['confirm'])) {
        $bookings_list = "";
        while($pb = mysqli_fetch_assoc($pending_res)) {
            $bookings_list .= "<li>Booking #{$pb['booking_id']} on {$pb['date']}</li>";
        }
        $error = "This lawyer has $pending_count pending bookings:<br><ul>$bookings_list</ul>Please reassign them before removing or confirm removal.";
    } else {
         // Get lawyer and firm details for email
         $lawyer_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email, first_Name FROM user WHERE u_id='$rid'"));
         $firm_email = $_SESSION['email'];
         $firm_name = $_SESSION['first_Name'];

         mysqli_query($conn, "UPDATE user SET is_active=0, status='INACTIVE' WHERE u_id='$rid' AND firm_id='$firm_id'");
         include_once 'includes/audit_util.php';
         log_firm_action('REMOVE_LAWYER', $firm_id, $rid, "Soft deleted lawyer and marked INACTIVE");
         
         // Notification logic
         include_once 'includes/mail_util.php';
         $subject = "Account Deactivation Notice";
         $msg = "Dear {$lawyer_data['first_Name']}, your account at $firm_name has been deactivated by the firm administrator.";
         send_notification_email($lawyer_data['email'], $subject, $msg);
         send_notification_email($firm_email, "Lawyer Removal Confirmation", "Lawyer {$lawyer_data['first_Name']} has been removed from your firm.");
         
         header("Location: firm_lawyers.php?success=removed");
         exit;
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
    <title>Manage Lawyers - Lawyer Match AI</title>
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
                <a href="firm_lawyers.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-users mr-2"></i>Manage Lawyers</a>
                <a href="firm_unified_bookings.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-calendar-alt mr-2"></i>All Bookings</a>
                <a href="firm_edit_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-user-edit mr-2"></i>Edit Profile</a>
                <a href="update_password_firm.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-key mr-2"></i>Update Password</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <div class="container-fluid p-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="glass-panel p-4 mb-4">
                            <h4 class="font-weight-bold mb-4">Current Lawyers</h4>
                            <?php if(isset($_GET['success'])) echo "<div class='alert alert-success'>Action completed successfully!</div>"; ?>
                            <?php if(isset($error)) echo "<div class='alert alert-warning'>$error <a href='firm_lawyers.php?remove_id={$_GET['remove_id']}&confirm=1' class='btn btn-sm btn-danger ml-2'>Confirm Removal Anyway</a></div>"; ?>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Bar Reg #</th>
                                            <th>Bookings</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $lawyers = mysqli_query($conn, "SELECT u.*, l.case_handle as bar_reg FROM user u 
                                                                      LEFT JOIN lawyer l ON u.u_id = l.lawyer_id 
                                                                      WHERE u.firm_id='$firm_id' AND u.role='FIRM_LAWYER' AND u.is_active=1");
                                        while($l = mysqli_fetch_assoc($lawyers)) {
                                            $b_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM booking WHERE lawyer_id='{$l['u_id']}'"))['count'];
                                            echo "<tr>
                                                <td>{$l['first_Name']}</td>
                                                <td>{$l['email']}</td>
                                                <td>" . ($l['bar_reg'] ?? 'N/A') . "</td>
                                                <td>$b_count</td>
                                                <td>
                                                    <a href='firm_lawyers.php?remove_id={$l['u_id']}' class='btn btn-sm btn-outline-danger rounded-pill'>
                                                        <i class='fas fa-user-minus'></i> Remove
                                                    </a>
                                                </td>
                                            </tr>";
                                        }
                                        if(mysqli_num_rows($lawyers) == 0) echo "<tr><td colspan='5' class='text-center text-muted'>No lawyers added yet</td></tr>";
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            <div class="col-md-4" id="add">
                <div class="glass-panel p-4">
                    <h4 class="font-weight-bold mb-4">Add New Lawyer</h4>
                    <form id="addLawyerForm" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="full_name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Email Address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control" name="dob" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Bar Registration #</label>
                                <input type="text" class="form-control" name="bar_registration_number" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>University / College</label>
                            <input type="text" class="form-control" name="university_College" placeholder="University Studied" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Experience (Years)</label>
                                <input type="number" class="form-control" name="experience_years" min="0" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Primary Speciality</label>
                                <select name="speciality" class="form-control" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="Criminal Law">Criminal Law</option>
                                    <option value="Civil Law">Civil Law</option>
                                    <option value="Writ Jurisdiction">Writ Jurisdiction</option>
                                    <option value="Company Law">Company Law</option>
                                    <option value="Contract Law">Contract Law</option>
                                    <option value="Commercial Law">Commercial Law</option>
                                    <option value="Construction Law">Construction Law</option>
                                    <option value="IT Law">IT Law</option>
                                    <option value="Family Law">Family Law</option>
                                    <option value="Religious Law">Religious Law</option>
                                    <option value="Investment Law">Investment Law</option>
                                    <option value="Labour Law">Labour Law</option>
                                    <option value="Property Law">Property Law</option>
                                    <option value="Taxation Law">Taxation Law</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold mb-2">Case Types Handled</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox small mb-1">
                                        <input type="checkbox" class="custom-control-input" name="case_handle[]" value="Criminal matter" id="crime">
                                        <label class="custom-control-label" for="crime">Criminal</label>
                                    </div>
                                    <div class="custom-control custom-checkbox small mb-1">
                                        <input type="checkbox" class="custom-control-input" name="case_handle[]" value="Civil matter" id="civil">
                                        <label class="custom-control-label" for="civil">Civil</label>
                                    </div>
                                    <div class="custom-control custom-checkbox small mb-1">
                                        <input type="checkbox" class="custom-control-input" name="case_handle[]" value="Family Law" id="fam">
                                        <label class="custom-control-label" for="fam">Family</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox small mb-1">
                                        <input type="checkbox" class="custom-control-input" name="case_handle[]" value="Company law" id="comp">
                                        <label class="custom-control-label" for="comp">Company</label>
                                    </div>
                                    <div class="custom-control custom-checkbox small mb-1">
                                        <input type="checkbox" class="custom-control-input" name="case_handle[]" value="Property Law" id="prop">
                                        <label class="custom-control-label" for="prop">Property</label>
                                    </div>
                                    <div class="custom-control custom-checkbox small mb-1">
                                        <input type="checkbox" class="custom-control-input" name="case_handle[]" value="Others" id="oth">
                                        <label class="custom-control-label" for="oth">Others</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Profile Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="fileToUpload" id="customFile" required>
                                <label class="custom-file-label" for="customFile" style="font-size: 0.8rem;">Choose Image</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password (Auto-generated if empty)</label>
                            <input type="password" class="form-control" name="password" placeholder="Leave empty for auto-gen">
                        </div>
                        <button type="submit" class="btn btn-modern btn-block" id="submitBtn">
                            <span id="btnText">Add Lawyer</span>
                            <span id="btnLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 glass-panel">
                <div class="modal-body text-center p-5">
                    <div class="text-success mb-4">
                        <i class="fas fa-check-circle fa-4x"></i>
                    </div>
                    <h4 class="font-weight-bold mb-2">Success!</h4>
                    <p class="text-muted" id="successMsg"></p>
                    <button type="button" class="btn btn-modern px-5 rounded-pill" data-dismiss="modal">Great!</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script>
        function loadLawyers() {
            $.ajax({
                url: 'firm_lawyers.php',
                success: function(data) {
                    const newTbody = $(data).find('tbody').html();
                    $('tbody').html(newTbody);
                }
            });
        }

        $('#addLawyerForm').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#submitBtn');
            const $btnText = $('#btnText');
            const $loader = $('#btnLoader');
            
            $btn.prop('disabled', true);
            $btnText.addClass('d-none');
            $loader.removeClass('d-none');

            const formData = new FormData(this);
            
            $.ajax({
                url: 'api/firm/lawyers.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.success) {
                        $('#successMsg').text(`Lawyer ${formData.get('full_name')} has been successfully added to the firm.`);
                        $('#successModal').modal('show');
                        $('#addLawyerForm')[0].reset();
                        $(".custom-file-label").html("Choose Image");
                        loadLawyers();
                    } else {
                        alert('Error: ' + res.error);
                    }
                },
                error: function(xhr) {
                    const err = xhr.responseJSON ? xhr.responseJSON.error : 'System error occurred';
                    alert('Error: ' + err);
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $btnText.removeClass('d-none');
                    $loader.addClass('d-none');
                }
            });
        });
    </script>
</body>
</html>
