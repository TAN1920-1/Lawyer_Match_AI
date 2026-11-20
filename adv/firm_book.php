<?php
include_once 'includes/session_init.php';
include_once 'db_con/dbCon.php';
$conn = connect();
if(!$conn){ header('location:login.php'); exit; }
$firm_id = isset($_GET['firm_id']) ? $_GET['firm_id'] : '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $firm_id = $_POST['firm_id'];
    $desc = $_POST['description'];
    $client_id = isset($_SESSION['client_id']) ? $_SESSION['client_id'] : '';
    if(!$client_id){ header('location:login.php'); exit; }
    if(!$firm_id){ header('location:searchFirm.php'); exit; }
    mysqli_query($conn,"CREATE TABLE IF NOT EXISTS firm_booking (
        id int AUTO_INCREMENT PRIMARY KEY,
        client_id varchar(50),
        firm_id varchar(50),
        date date,
        description text,
        status varchar(20)
    )");
    $date = date('Y-m-d');
    $ok = mysqli_query($conn,"INSERT INTO firm_booking (client_id,firm_id,date,description,status) VALUES ('$client_id','$firm_id','$date','".mysqli_real_escape_string($conn,$desc)."','Pending')");
    if($ok){
        header('location:user_dashboard.php?firm_req=1');
    } else {
        header('location:user_dashboard.php?firm_req=0');
    }
    exit;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" crossorigin="anonymous">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/modern_style.css">
        <title>Request Law Firm</title>
    </head>
    <body>
        <?php include 'includes/navbar.php'; ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="glass-panel p-5">
                        <h3 class="font-weight-bold mb-3">Request Law Firm</h3>
                        <form method="post">
                            <input type="hidden" name="firm_id" value="<?php echo htmlspecialchars($firm_id); ?>">
                            <div class="form-group">
                                <label class="font-weight-bold">Describe your case</label>
                                <textarea class="form-control" name="description" rows="5" required></textarea>
                            </div>
                            <button class="btn btn-modern btn-block">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>
