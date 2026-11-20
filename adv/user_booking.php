<?php
    include_once 'includes/session_init.php';
	if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='User'){
		
		include("db_con/dbCon.php");
		
	?>
	<!doctype html>
	<html lang="en">
		<head>
			<!-- Required meta tags -->
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
			<link rel="stylesheet" href="css/simple-sidebar.css">
			<link rel="stylesheet" href="css/modern_style.css"> <!-- Added Modern CSS -->
			<title>My Bookings - Lawyer Match AI</title>
			<style>
				body {
					background-color: var(--bg-color);
					font-family: 'Inter', sans-serif;
				}
				#wrapper {
					overflow-x: hidden;
				}
				#sidebar-wrapper {
					min-height: 100vh;
					margin-left: -15rem;
					-webkit-transition: margin .25s ease-out;
					-moz-transition: margin .25s ease-out;
					-o-transition: margin .25s ease-out;
					transition: margin .25s ease-out;
					background: white;
					border-right: 1px solid rgba(0,0,0,0.05);
					box-shadow: var(--shadow-sm);
				}
				#sidebar-wrapper .sidebar-heading {
					padding: 1.5rem 1.25rem;
					font-size: 1.2rem;
					font-weight: bold;
					color: var(--primary-color);
					border-bottom: 1px solid rgba(0,0,0,0.05);
				}
				#sidebar-wrapper .list-group {
					width: 15rem;
				}
				#page-content-wrapper {
					min-width: 100vw;
				}
				#wrapper.toggled #sidebar-wrapper {
					margin-left: 0;
				}
				@media (min-width: 768px) {
					#sidebar-wrapper {
						margin-left: 0;
					}
					#page-content-wrapper {
						min-width: 0;
						width: 100%;
					}
					#wrapper.toggled #sidebar-wrapper {
						margin-left: -15rem;
					}
				}
				.list-group-item {
					border: none;
					padding: 15px 20px;
					font-weight: 500;
					color: #666;
					transition: all 0.3s;
				}
				.list-group-item:hover {
					background-color: var(--bg-color);
					color: var(--primary-color);
					padding-left: 25px;
				}
				.list-group-item.active {
					background-color: var(--primary-color);
					color: white;
				}
				.table-responsive {
					display: block;
					width: 100%;
					overflow-x: auto;
					-webkit-overflow-scrolling: touch;
                    border-radius: 10px;
				}
				.table {
					min-width: 1200px !important;
                    margin-bottom: 0;
				}
				.glass-panel {
					width: 100%;
					overflow: hidden;
                    margin-bottom: 2rem;
				}
                /* Star rating logic with radio buttons hidden */
                .star-rating {
                    display: flex;
                    flex-direction: row-reverse;
                    justify-content: flex-end;
                }
                .star-rating input {
                    display: none;
                }
                .star-rating label {
                    color: #ddd;
                    font-size: 1.5rem;
                    padding: 0 0.1rem;
                    cursor: pointer;
                    transition: color 0.2s;
                }
                .star-rating input:checked ~ label,
                .star-rating label:hover,
                .star-rating label:hover ~ label {
                    color: #ffc107;
                }
			</style>
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
												<i class="fas fa-user-circle mr-1"></i>
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
					<div class="sidebar-heading text-primary font-weight-bold">User Dashboard</div>
					<div class="list-group list-group-flush">
						<a href="user_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-tachometer-alt mr-2"></i> Dashboard
						</a>
						<a href="user_profile.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-user-edit mr-2"></i> Edit Profile
						</a>
						<a href="user_booking.php" class="list-group-item list-group-item-action active-modern">
							<i class="fas fa-calendar-check mr-2"></i> My Bookings
						</a>
						<a href="update_password.php" class="list-group-item list-group-item-action bg-transparent text-dark">
							<i class="fas fa-key mr-2"></i> Update Password
						</a>
					</div>
				</div>
				<!-- /#sidebar-wrapper -->
				
				<!-- Page Content -->
				<div id="page-content-wrapper">
					<div class="container-fluid p-4 animate-fade-in">
						<div class="row mb-4">
							<div class="col-md-6">
								<h2 class="font-weight-bold">My Booking Requests</h2>
								<p class="text-muted">View and manage your consultation requests.</p>
							</div>
							<div class="col-md-6 text-md-right">
								<button class="btn btn-modern-outline btn-sm" id="menu-toggle">
									<i class="fas fa-bars"></i> Toggle Menu
								</button>
							</div>
						</div>
						
                        <?php 
                        if(isset($_GET['rated']) && $_GET['rated'] == 'success') {
                            echo '<div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                                    <i class="fas fa-check-circle mr-2"></i> Thank you for your feedback! Rating submitted successfully.
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  </div>';
                        }
                        if(isset($_GET['error'])) {
                            $err_msg = "An error occurred. Please try again.";
                            if($_GET['error'] == 'already_rated') $err_msg = "You have already rated this booking.";
                            echo '<div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                                    <i class="fas fa-exclamation-circle mr-2"></i> '.$err_msg.'
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  </div>';
                        }
                        ?>

						<div class="glass-panel p-4 animate-fade-in">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" class="border-0 rounded-left">No.</th>
                                            <th scope="col" class="border-0">Date</th>
                                            <th scope="col" class="border-0">Description</th>
                                            <th scope="col" class="border-0">Lawyer Name</th>
                                            <th scope="col" class="border-0">Status</th>
                                            <th scope="col" class="border-0">Talk Code</th>
                                            <th scope="col" class="border-0">Talk Name</th>
                                            <th scope="col" class="border-0 rounded-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $a=$_SESSION['client_id'];
                                            $conn = connect();
                                            @mysqli_query($conn,"ALTER TABLE booking ADD COLUMN IF NOT EXISTS talk_room_code varchar(64)");
                                            @mysqli_query($conn,"ALTER TABLE booking ADD COLUMN IF NOT EXISTS talk_display_name varchar(64)");
                                            if(isset($_POST['talk_update'])){
                                                $bid = isset($_POST['booking_id']) ? $_POST['booking_id'] : '';
                                                $code = isset($_POST['talk_room_code']) ? trim($_POST['talk_room_code']) : '';
                                                $name = isset($_POST['talk_display_name']) ? trim($_POST['talk_display_name']) : '';
                                                if($bid !== ''){
                                                    $codeEsc = mysqli_real_escape_string($conn,$code);
                                                    $nameEsc = mysqli_real_escape_string($conn,$name);
                                                    mysqli_query($conn,"UPDATE booking SET talk_room_code='$codeEsc', talk_display_name='$nameEsc' WHERE booking_id='$bid' AND client_id='$a'");
                                                }
                                                header("Location:user_booking.php");
                                                exit;
                                            }
                                            $result = mysqli_query($conn,"SELECT booking.*, user.first_Name, user.last_Name, lawyer_ratings.id as rated_id, booking.talk_room_code, booking.talk_display_name
                                            FROM booking
                                            INNER JOIN user ON booking.lawyer_id=user.u_id
                                            LEFT JOIN lawyer_ratings ON booking.booking_id = lawyer_ratings.booking_id
                                            WHERE booking.client_id='$a'
                                            ORDER BY booking.date DESC
                                            ");
                                            $counter = 0;
                                            while($row = mysqli_fetch_array($result)) {
                                                $status = $row["status"];
                                                $badgeClass = 'badge-secondary';
                                                $canRate = false;
                                                if(stripos($status, 'accept') !== false) {
                                                    $badgeClass = 'badge-success';
                                                    $canRate = true;
                                                } elseif(stripos($status, 'reject') !== false) {
                                                    $badgeClass = 'badge-danger';
                                                } elseif(stripos($status, 'pending') !== false) {
                                                    $badgeClass = 'badge-warning';
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo ++$counter ;?></td>
                                                <td><?php echo $row["date"]; ?></td>
                                                <td><?php echo $row["description"]; ?></td>
                                                <td><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></td>
                                                <td><span class="badge <?php echo $badgeClass; ?> p-2"><?php echo $row["status"]; ?></span></td>
                                                <td style="min-width:120px;">
                                                    <form method="post" class="form-inline">
                                                        <input type="hidden" name="booking_id" value="<?=$row['booking_id']?>">
                                                        <label class="sr-only" for="room_code_<?=$row['booking_id']?>">Talk Room Code</label>
                                                        <input type="text" class="form-control form-control-sm" id="room_code_<?=$row['booking_id']?>" name="talk_room_code" value="<?php echo isset($row['talk_room_code']) ? $row['talk_room_code'] : ''; ?>" placeholder="Code">
                                                </td>
                                                <td style="min-width:120px;">
                                                        <label class="sr-only" for="display_name_<?=$row['booking_id']?>">Talk Display Name</label>
                                                        <input type="text" class="form-control form-control-sm mr-2" id="display_name_<?=$row['booking_id']?>" name="talk_display_name" value="<?php echo isset($row['talk_display_name']) ? $row['talk_display_name'] : ''; ?>" placeholder="Name">
                                                        <button type="submit" name="talk_update" class="btn btn-outline-primary btn-sm">Save</button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center" style="gap: 10px;">
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
                                                        <a href="<?=$href?>" target="_blank" class="btn btn-modern btn-sm rounded-pill d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; padding: 0;" title="Direct Talk"><i class="fas fa-comments"></i></a>
                                                        
                                                        <?php if($canRate && !$row['rated_id']): ?>
                                                            <button type="button" class="btn btn-warning btn-sm rounded-pill d-flex align-items-center px-3" style="height: 38px; font-weight: 600;" data-toggle="modal" data-target="#rateModal<?=$row['booking_id']?>" title="Rate Lawyer">
                                                                <i class="fas fa-star mr-1"></i> Rate
                                                            </button>
                                                        <?php elseif($row['rated_id']): ?>
                                                            <span class="badge badge-light text-muted p-2"><i class="fas fa-check"></i> Rated</span>
                                                        <?php endif; ?>
                                                    </div>
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

            <!-- Modals Container (Outside stacking context) -->
            <?php
            mysqli_data_seek($result, 0); // Reset pointer to loop again for modals
            while($row = mysqli_fetch_array($result)) {
                if(stripos($row["status"], 'accept') !== false && !$row['rated_id']): 
            ?>
                <!-- Rating Modal -->
                <div class="modal fade" id="rateModal<?=$row['booking_id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-weight-bold">Rate Lawyer: <?=$row['first_Name']?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="submit_rating.php" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="booking_id" value="<?=$row['booking_id']?>">
                                    <input type="hidden" name="lawyer_id" value="<?=$row['lawyer_id']?>">
                                    
                                    <div class="form-group text-center mb-4">
                                        <label class="d-block text-muted small text-uppercase font-weight-bold mb-2">Select Rating</label>
                                        <div class="star-rating">
                                            <input type="radio" id="star5_<?=$row['booking_id']?>" name="rating" value="5" required/><label for="star5_<?=$row['booking_id']?>" title="5 stars"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star4_<?=$row['booking_id']?>" name="rating" value="4"/><label for="star4_<?=$row['booking_id']?>" title="4 stars"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star3_<?=$row['booking_id']?>" name="rating" value="3"/><label for="star3_<?=$row['booking_id']?>" title="3 stars"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star2_<?=$row['booking_id']?>" name="rating" value="2"/><label for="star2_<?=$row['booking_id']?>" title="2 stars"><i class="fas fa-star"></i></label>
                                            <input type="radio" id="star1_<?=$row['booking_id']?>" name="rating" value="1"/><label for="star1_<?=$row['booking_id']?>" title="1 star"><i class="fas fa-star"></i></label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="text-muted small text-uppercase font-weight-bold" for="feedback_<?=$row['booking_id']?>">Your Feedback</label>
                                        <textarea name="feedback" id="feedback_<?=$row['booking_id']?>" class="form-control bg-light border-0" rows="3" maxlength="300" placeholder="Describe your experience..." onkeyup="countChars(this, <?=$row['booking_id']?>)"></textarea>
                                        <div class="text-right mt-1">
                                            <small class="text-muted"><span id="charCount_<?=$row['booking_id']?>">0</span>/300 characters</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-modern px-4 rounded-pill">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php 
                endif;
            } 
            ?>

            <?php include 'includes/footer.php'; ?>
			
			<script>
				$("#menu-toggle").click(function(e) {
					e.preventDefault();
					$("#wrapper").toggleClass("toggled");
				});

                function countChars(obj, id){
                    document.getElementById('charCount_'+id).innerHTML = obj.value.length;
                }
			</script>
		</body>
	</html>					
	<?php
		
	}else 
	header('location:login.php?deactivate');
?>
