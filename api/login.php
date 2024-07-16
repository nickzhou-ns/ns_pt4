<?php 
$username = $_POST['username'];
$password = $_POST['password'];
if($password == 'admin' && $username == 'admin'){
    session_start();
    $_SESSION["loggedin"] = true;
    header("location: ../daily_report/daily_report_index.php");

}else{
    header("location: ../login.php");
}

?>