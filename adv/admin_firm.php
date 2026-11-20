<?php
include_once 'includes/session_init.php';
if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Admin'){
    include("db_con/dbCon.php");
    include_once 'includes/image_util.php';
    $conn = connect();
    if(isset($_GET['approve_id'])){
        $id = $_GET['approve_id'];
        mysqli_query($conn,"UPDATE `user` SET `status`='Active' WHERE u_id='$id'");
        header("Location:admin_firm.php");
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
        <title>Admin - Law Firms</title>
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
        </style>
    </head>
    <body class="bg-light-modern">
        <header class="customnav glass-nav">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-expand-lg ">
                            <a class="navbar-brand cus-a font-weight-bold" href="#">LAWYER MATCH AI</a>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto align-items-center">
                                    <li class="nav-item">
                                        <a class="nav-link cus-a" href="index.php">
                                            <i class="fas fa-home mr-1"></i> Home
                                        </a>
                                    </li>
                                    <li class="">
                                        <a class="nav-link cus-a" href="logout.php">Log Out</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <div class="d-flex" id="wrapper">
            <div class="glass-sidebar border-right" id="sidebar-wrapper">
                <div class="sidebar-heading text-primary font-weight-bold">Admin Panel</div>
                <div class="list-group list-group-flush">
                    <a href="admin_dashboard.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard</a>
                    <a href="admin_lawyer.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-gavel mr-2"></i>See Lawyers</a>
                    <a href="admin_user.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-users mr-2"></i>See Users</a>
                    <a href="admin_firm.php" class="list-group-item list-group-item-action active-modern"><i class="fas fa-building mr-2"></i>See Firms</a>
                    <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-transparent text-dark"><i class="fas fa-key mr-2"></i>Update Password</a>
                </div>
            </div>
            <!-- Page Content -->
            <div id="page-content-wrapper">
                <div class="container-fluid p-4 animate-fade-in">
                    <div class="glass-panel p-4">   
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="mb-0 text-dark font-weight-bold"><i class="fas fa-building mr-2 text-primary"></i>Registered Firms</h3>
                            <span class="badge badge-pill badge-primary p-2 px-3">Total Firms</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>City</th>
                                        <th>Logo</th>
                                        <th>Speciality</th>
                                        <th>No. Lawyers</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $result = mysqli_query($conn,"SELECT user.u_id, user.status, firm.firm_name, firm.email, firm.contact, firm.city, firm.image, firm.speciality, firm.num_lawyers 
                                        FROM user INNER JOIN firm ON user.u_id=firm.firm_id");
                                    $counter=0;
                                    while($row=mysqli_fetch_array($result)){
                                        $img = $row['image'];
                                        $p = img_src($img, "images/actors/close-up-lawyer-signing-papers.jpg");
                                        
                                        echo "<tr>
                                            <td>".(++$counter)."</td>
                                            <td>".$row['u_id']."</td>
                                            <td><div class='font-weight-bold'>".$row['firm_name']."</div></td>
                                            <td>".$row['email']."</td>
                                            <td>".$row['contact']."</td>
                                            <td>".$row['city']."</td>
                                            <td><img src='".$p."' style='width:50px;height:50px;border-radius:8px;object-fit:cover'></td>
                                            <td><span class='badge badge-info'>".$row['speciality']."</span></td>
                                            <td>".$row['num_lawyers']."</td>
                                            <td>".$row['status']."</td>
                                            <td>";
                                        if($row['status']!='Active'){
                                            echo "<a class='btn btn-sm btn-success rounded-pill' href='admin_firm.php?approve_id=".$row['u_id']."'><i class='fas fa-check'></i> Approve</a>";
                                        } else {
                                            echo "<span class='badge badge-success px-3 py-2'>Active</span>";
                                        }
                                        echo "</td></tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>
<?php
}else 
header('location:login.php?deactivate');
?>
