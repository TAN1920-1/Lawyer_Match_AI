<?php
include_once 'includes/session_init.php';
include_once 'includes/role_middleware.php';
check_role(['FIRM_ADMIN', 'Firm']);
include("db_con/dbCon.php");
include_once 'includes/image_util.php';
$firm_id = $_SESSION['firm_id'];
$conn = connect();
$res = mysqli_query($conn,"SELECT * FROM user INNER JOIN firm ON user.u_id=firm.firm_id WHERE user.u_id='$firm_id'");
$row = mysqli_fetch_array($res);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <link rel="stylesheet" href="css/modern_style.css">
        <title>Edit Firm Profile - Lawyer Match AI</title>
    </head>
    <body class="bg-light-modern">
        <header class="customnav glass-nav">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand cus-a font-weight-bold" href="index.php">LAWYER MATCH AI</a>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ml-auto align-items-center">
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
                    <a href="firm_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
                    <a href="firm_lawyers.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-users mr-2"></i>Manage Lawyers</a>
                    <a href="firm_unified_bookings.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-calendar-alt mr-2"></i>All Bookings</a>
                    <a href="firm_edit_profile.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-user-edit mr-2"></i>Edit Profile</a>
                    <a href="update_password_firm.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-key mr-2"></i>Update Password</a>
                </div>
            </div>

            <div id="page-content-wrapper" class="w-100">
                <div class="container-fluid p-4">
                    <div class="glass-panel p-4 animate-fade-in">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="font-weight-bold mb-0">Edit Firm Profile</h3>
                            <?php if(isset($_GET['ok'])) echo "<span class='badge badge-success p-2'><i class='fas fa-check-circle mr-1'></i> Profile Updated Successfully</span>"; ?>
                        </div>

                        <form action="save_firm_edit_profile.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-uppercase">Firm Name</label>
                                            <input type="text" name="firm_name" class="form-control" value="<?php echo htmlspecialchars($row['firm_name']); ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-uppercase">Email Address (Login)</label>
                                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" readonly disabled>
                                            <small class="text-muted">Email cannot be changed.</small>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-uppercase">Contact Number</label>
                                            <input type="text" name="contact" class="form-control" value="<?php echo htmlspecialchars($row['contact']); ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-uppercase">Number of Lawyers</label>
                                            <input type="number" name="num_lawyers" class="form-control" value="<?php echo $row['num_lawyers']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold small text-uppercase">Office Address</label>
                                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($row['address']); ?>" required>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-uppercase">City</label>
                                            <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($row['city']); ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold small text-uppercase">Zip Code</label>
                                            <input type="text" name="zip_code" class="form-control" value="<?php echo htmlspecialchars($row['zip_code']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold small text-uppercase">Primary Speciality</label>
                                        <select name="speciality" class="form-control" required>
                                            <?php
                                            $specs = ["Criminal Law", "Civil Law", "Writ Jurisdiction", "Company Law", "Contract Law", "Commercial Law", "Construction Law", "IT Law", "Family Law", "Religious Law", "Investment Law", "Labour Law", "Property Law", "Taxation Law"];
                                            foreach($specs as $s) {
                                                $sel = ($row['speciality'] == $s) ? 'selected' : '';
                                                echo "<option value='$s' $sel>$s</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group text-center">
                                        <label class="font-weight-bold small text-uppercase d-block">Firm Logo</label>
                                        <?php 
                                        $p = img_src($row['image'], "images/actors/close-up-lawyer-signing-papers.jpg");
                                        
                                        ?>
                                        <div class="mb-3">
                                            <img id="preview" src="<?php echo $p; ?>" class="img-thumbnail" style="width:150px;height:150px;object-fit:cover;border-radius:15px;">
                                        </div>
                                        <div class="custom-file text-left">
                                            <input type="file" class="custom-file-input" id="logo" name="firm_logo" accept=".jpg,.jpeg,.png,.gif,.webp">
                                            <label class="custom-file-label" for="logo">Change Logo</label>
                                        </div>
                                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($row['image']); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label class="font-weight-bold small text-uppercase">Education/Expertise Summary</label>
                                <textarea name="education" class="form-control" rows="3" required><?php echo htmlspecialchars($row['education']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold small text-uppercase">Firm Description</label>
                                <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($row['description']); ?></textarea>
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-modern px-5 py-2 rounded-pill">
                                    <i class="fas fa-save mr-2"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
        <script>
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                
                // Preview
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        </script>
    </body>
</html>
