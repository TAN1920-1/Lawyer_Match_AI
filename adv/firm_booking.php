<?php
include_once 'includes/session_init.php';
if($_SESSION['login']==TRUE AND $_SESSION['status']=='Active' AND isset($_SESSION['role']) AND $_SESSION['role']=='Firm'){
    include("db_con/dbCon.php");
    $conn = connect();
    if(isset($_POST['firm_talk_update'])){
        $fid = isset($_POST['id']) ? $_POST['id'] : '';
        $code = isset($_POST['talk_room_code']) ? trim($_POST['talk_room_code']) : '';
        $name = isset($_POST['talk_display_name']) ? trim($_POST['talk_display_name']) : '';
        if($fid !== ''){
            @mysqli_query($conn,"ALTER TABLE firm_booking ADD COLUMN IF NOT EXISTS talk_room_code varchar(64)");
            @mysqli_query($conn,"ALTER TABLE firm_booking ADD COLUMN IF NOT EXISTS talk_display_name varchar(64)");
            $codeEsc = mysqli_real_escape_string($conn,$code);
            $nameEsc = mysqli_real_escape_string($conn,$name);
            mysqli_query($conn,"UPDATE firm_booking SET talk_room_code='$codeEsc', talk_display_name='$nameEsc' WHERE id='$fid'");
        }
        header("Location:firm_booking.php");
        exit;
    }
    if(isset($_GET['accept_id'])){
        $id = $_GET['accept_id'];
        mysqli_query($conn,"UPDATE firm_booking SET status='Accepted' WHERE id='$id'");
        header("Location:firm_booking.php");
    }
    if(isset($_GET['reject_id'])){
        $id = $_GET['reject_id'];
        mysqli_query($conn,"UPDATE firm_booking SET status='Rejected' WHERE id='$id'");
        header("Location:firm_booking.php");
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
        <title>Firm Booking Requests</title>
    </head>
    <body>
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
        <div class="container py-4">
            <h3 class="mb-4 font-weight-bold">Client Requests</h3>
            <div class="glass-panel p-4">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>No.</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Talk Code</th>
                                <th>Talk Name</th>
                                <th>Talk</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                mysqli_query($conn,"CREATE TABLE IF NOT EXISTS firm_booking (
                                    id int AUTO_INCREMENT PRIMARY KEY,
                                    client_id varchar(50),
                                    firm_id varchar(50),
                                    date date,
                                    description text,
                                    status varchar(20),
                                    talk_room_code varchar(64),
                                    talk_display_name varchar(64)
                                )");
                                $firm_id = isset($_SESSION['firm_id']) ? $_SESSION['firm_id'] : '';
                                @mysqli_query($conn,"ALTER TABLE firm_booking ADD COLUMN IF NOT EXISTS talk_room_code varchar(64)");
                                @mysqli_query($conn,"ALTER TABLE firm_booking ADD COLUMN IF NOT EXISTS talk_display_name varchar(64)");
                                $result = mysqli_query($conn,"SELECT firm_booking.id, user.first_Name, user.last_Name, firm_booking.date, firm_booking.description, firm_booking.status, firm_booking.talk_room_code, firm_booking.talk_display_name 
                                    FROM firm_booking 
                                    INNER JOIN user ON firm_booking.client_id=user.u_id 
                                    WHERE firm_booking.firm_id='$firm_id'");
                                $i=0;
                                if($result && mysqli_num_rows($result) > 0){
                                    while($row=mysqli_fetch_array($result)){
                                    echo "<tr>
                                        <td>".(++$i)."</td>
                                        <td>".$row['first_Name']." ".$row['last_Name']."</td>
                                        <td>".$row['date']."</td>
                                        <td>".$row['description']."</td>
                                        <td>".$row['status']."</td>
                                        <td>
                                            <form method='post' class='form-inline'>
                                                <input type='hidden' name='id' value='".$row['id']."'>
                                                <input type='text' class='form-control form-control-sm' name='talk_room_code' value='".(isset($row['talk_room_code'])?$row['talk_room_code']:'')."' placeholder='Code'>
                                        </td>
                                        <td>
                                                <input type='text' class='form-control form-control-sm mr-2' name='talk_display_name' value='".(isset($row['talk_display_name'])?$row['talk_display_name']:'')."' placeholder='Name'>
                                                <button type='submit' name='firm_talk_update' class='btn btn-outline-primary btn-sm'>Save</button>
                                            </form>
                                        </td>
                                        <td>";
                                        $code = isset($row['talk_room_code'])?$row['talk_room_code']:'';
                                        $name = isset($row['talk_display_name'])?$row['talk_display_name']:'';
                                        $href = 'https://aetherastra.vercel.app/direct';
                                        $qs = [];
                                        if($code!=='') $qs[] = 'room='.urlencode($code);
                                        if($name!=='') $qs[] = 'name='.urlencode($name);
                                        if(!empty($qs)) $qs[] = 'mode=direct';
                                        if(!empty($qs)) $href .= '?'.implode('&',$qs);
                                        echo "<a href='".$href."' target='_blank' class='btn btn-modern btn-sm rounded-pill'><i class='fas fa-comments mr-1'></i> Talk</a>";
                                        echo "</td>
                                        <td>";
                                    if(strcasecmp($row['status'],'Pending')==0){
                                        echo "<a href='firm_booking.php?accept_id=".$row['id']."' class='btn btn-success btn-sm mr-2'>Accept</a>
                                              <a href='firm_booking.php?reject_id=".$row['id']."' class='btn btn-danger btn-sm'>Reject</a>";
                                    } else {
                                        echo "<span class='badge badge-info'>".$row['status']."</span>";
                                    }
                                    echo "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center text-muted'>No requests found yet. Ask clients to submit a firm request.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
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
