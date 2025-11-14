<?php @session_start();
include("connectdb.php");
$uname=$_SESSION["uname"];
$partner=$_SESSION["chat_user"];

mysqli_query($con,"delete from blocked_info where blocker='$uname' AND blocked='$partner'");

echo("unblocked");


?>