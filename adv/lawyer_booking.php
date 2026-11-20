<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Lawyer'){
		
		include("db_con/dbCon.php");
		$conn = connect();
        if(isset($_POST['talk_update'])){
            $bid = isset($_POST['booking_id']) ? $_POST['booking_id'] : '';
            $code = isset($_POST['talk_room_code']) ? trim($_POST['talk_room_code']) : '';
            $name = isset($_POST['talk_display_name']) ? trim($_POST['talk_display_name']) : '';
            if($bid !== ''){
                @mysqli_query($conn,"ALTER TABLE booking ADD COLUMN IF NOT EXISTS talk_room_code varchar(64)");
                @mysqli_query($conn,"ALTER TABLE booking ADD COLUMN IF NOT EXISTS talk_display_name varchar(64)");
                $codeEsc = mysqli_real_escape_string($conn,$code);
                $nameEsc = mysqli_real_escape_string($conn,$name);
                mysqli_query($conn,"UPDATE booking SET talk_room_code='$codeEsc', talk_display_name='$nameEsc' WHERE booking_id='$bid'");
            }
            header("Location:lawyer_booking.php");
            exit;
        }
		if(isset($_GET['unblock_id'])){
			
			$id = $_GET['unblock_id'];
			
			$sql = "UPDATE `booking` SET `status`='Accepted' WHERE booking_id='$id'";
			$conn->query($sql);

            // Notify Client
            $lawyer_name = $_SESSION['first_Name'] . " " . $_SESSION['last_Name'];
            $b_res = mysqli_query($conn, "SELECT client_id, date FROM booking WHERE booking_id='$id'");
            if($b_data = mysqli_fetch_assoc($b_res)) {
                $client_id = $b_data['client_id'];
                $b_date = $b_data['date'];
                $notif_title = "Booking Accepted";
                $notif_msg = "Your booking for $b_date has been accepted by $lawyer_name. You can now start the discussion.";
                mysqli_query($conn, "INSERT INTO notifications (user_id, title, message, booking_id, type) VALUES ('$client_id', '$notif_title', '$notif_msg', '$id', 'booking_accepted')");
            }

			header("Location:lawyer_booking.php");
            exit;
		}
        if(isset($_GET['reject_id'])){
			
			$id = $_GET['reject_id'];
			
			$sql = "UPDATE `booking` SET `status`='Rejected' WHERE booking_id='$id'";
			$conn->query($sql);

            // Notify Client
            $lawyer_name = $_SESSION['first_Name'] . " " . $_SESSION['last_Name'];
            $b_res = mysqli_query($conn, "SELECT client_id, date FROM booking WHERE booking_id='$id'");
            if($b_data = mysqli_fetch_assoc($b_res)) {
                $client_id = $b_data['client_id'];
                $b_date = $b_data['date'];
                $notif_title = "Booking Rejected";
                $notif_msg = "Your booking for $b_date has been rejected by $lawyer_name.";
                mysqli_query($conn, "INSERT INTO notifications (user_id, title, message, booking_id, type) VALUES ('$client_id', '$notif_title', '$notif_msg', '$id', 'booking_rejected')");
            }

			header("Location:lawyer_booking.php");
            exit;
		}
	?>
	<!doctype html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
			<link rel="stylesheet" href="css/simple-sidebar.css">
			<link rel="stylesheet" href="css/modern_style.css">
			<title>Booking Requests - Lawyer Match AI</title>
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
						<a href="lawyer_booking.php" class="list-group-item list-group-item-action active-modern">
							<i class="fas fa-calendar-check mr-2"></i> Booking Requests
						</a>
						<a href="update_password_lawyer.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-key mr-2"></i> Update Password
						</a>
					</div>
				</div>
				<!-- /#sidebar-wrapper -->
				
				<!-- Page Content -->
				<div id="page-content-wrapper">
					<div class="container-fluid p-4">
						
						<h2 class="font-weight-bold mb-4 animate-fade-in">Client Booking Requests</h2>
						
						<div class="glass-panel p-4 animate-fade-in">
                            <div class="table-responsive">
                                <table class="table table-hover">
									<thead class="thead-light">
                                        <tr>
                                            <th scope="col" class="border-0 rounded-left">No.</th>
                                            <th scope="col" class="border-0">Client Name</th>
                                            <th scope="col" class="border-0">Date</th>
                                            <th scope="col" class="border-0">Description</th>
                                            <th scope="col" class="border-0">Status</th>
                                            <th scope="col" class="border-0">Talk Code</th>
                                            <th scope="col" class="border-0">Talk Name</th>
                                            <th scope="col" class="border-0 rounded-right">Talk</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $a=$_SESSION['lawyer_id'];
                                            $conn = connect();
                                            @mysqli_query($conn,"ALTER TABLE booking ADD COLUMN IF NOT EXISTS talk_room_code varchar(64)");
                                            @mysqli_query($conn,"ALTER TABLE booking ADD COLUMN IF NOT EXISTS talk_display_name varchar(64)");
											$result = mysqli_query($conn,"SELECT booking_id,first_Name,last_Name,date,description,booking.status as statuss,talk_room_code,talk_display_name FROM booking,client,user WHERE booking.client_id=client.client_id AND client.client_id=user.u_id and booking.lawyer_id='$a'");
                                            $counter = 0;
                                            while($row = mysqli_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo ++$counter ;?></td>
                                                <td><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></td>
                                                <td><?php echo $row["date"]; ?></td>
                                                <td><?php echo $row["description"]; ?></td>
                                                <td>
                                                    <?php if (strcasecmp($row['statuss'],'Pending')==0){ ?>
                                                        <a href="lawyer_booking.php?unblock_id=<?=$row['booking_id']?>" class="btn btn-success btn-sm rounded-pill shadow-sm mr-2"><i class="fas fa-check mr-1"></i> Accept</a>
                                                        <a href="lawyer_booking.php?reject_id=<?=$row['booking_id']?>" class="btn btn-danger btn-sm rounded-pill shadow-sm"><i class="fas fa-times mr-1"></i> Reject</a>
                                                    <?php } elseif (strcasecmp($row['statuss'],'Accepted')==0){ ?>
                                                        <span class="badge badge-success p-2 rounded-pill"><i class="fas fa-check mr-1"></i> Accepted</span>
                                                    <?php } elseif (strcasecmp($row['statuss'],'Rejected')==0){ ?>
                                                        <span class="badge badge-danger p-2 rounded-pill"><i class="fas fa-times mr-1"></i> Rejected</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-warning p-2 rounded-pill"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                                                    <?php } ?>
                                                </td>
                                                <td style="min-width:140px;">
                                                    <form method="post" class="form-inline">
                                                        <input type="hidden" name="booking_id" value="<?=$row['booking_id']?>">
                                                        <input type="text" class="form-control form-control-sm mr-2" name="talk_room_code" value="<?php echo isset($row['talk_room_code']) ? $row['talk_room_code'] : ''; ?>" placeholder="Code">
                                                </td>
                                                <td style="min-width:140px;">
                                                        <input type="text" class="form-control form-control-sm mr-2" name="talk_display_name" value="<?php echo isset($row['talk_display_name']) ? $row['talk_display_name'] : ''; ?>" placeholder="Name">
                                                        <button type="submit" name="talk_update" class="btn btn-outline-primary btn-sm">Save</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <?php
                                                        $code = isset($row['talk_room_code']) ? $row['talk_room_code'] : '';
                                                        $name = isset($row['talk_display_name']) ? $row['talk_display_name'] : '';
                                                        $href = 'https://aetherastra.vercel.app/direct';
                                                        $qs = [];
                                                        if($code !== '') $qs[] = 'room='.urlencode($code);
                                                        if($name !== '') $qs[] = 'name='.urlencode($name);
                                                        if(!empty($qs)) $qs[] = 'mode=direct';
                                                        if(!empty($qs)) $href .= '?'.implode('&',$qs);
                                                    ?>
                                                    <a href="<?=$href?>" target="_blank" class="btn btn-modern btn-sm rounded-pill"><i class="fas fa-comments mr-1"></i> Talk</a>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
						</div>
					</div>
				</div>
				<!-- /#page-content-wrapper -->
				
			</div>
			<!-- /#wrapper -->

            <?php include 'includes/footer.php'; ?>
		</body>
	</html>
	<?php
		
	}else 
	header('location:login.php?deactivate');
?>
