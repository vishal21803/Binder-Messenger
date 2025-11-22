<?php @session_start();
include("connectdb.php");

$uid = $_SESSION['uid'];
$web = $_POST['website'];

mysqli_query($con, "UPDATE user_info SET uwebsite='$web',onboard_status=1 WHERE uid='$uid'");

header("location:feedPage.php");
?>
