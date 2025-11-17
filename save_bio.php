<?php @session_start();
include("connectdb.php");

$uid = $_SESSION['uid'];
$bio = $_POST['bio'];

mysqli_query($con, "UPDATE user_info SET ubio='$bio' WHERE uid='$uid'");

header("location:onboard_dp.php");
?>
