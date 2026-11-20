<?php
include_once 'db_con/dbCon.php';
$conn = connect();
if(!$conn){ echo "Connection failed"; exit; }

$tables = ['user', 'lawyer', 'firm', 'booking'];
foreach($tables as $table){
    echo "--- $table ---\n";
    $res = mysqli_query($conn, "DESCRIBE $table");
    if($res){
        while($row = mysqli_fetch_assoc($res)){
            echo "{$row['Field']} - {$row['Type']}\n";
        }
    } else {
        echo "Table $table not found\n";
    }
}
?>
