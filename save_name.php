<?php @session_start();
include("connectdb.php");

$uid = $_SESSION['uid'];
$name = $_POST['fullname'];

mysqli_query($con, "UPDATE user_info SET ufull='$name' WHERE uid='$uid'");

header("location:onboard_bio.php");
?>
