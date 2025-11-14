<?php @session_start();
include("connectdb.php");
$uname=$_SESSION["uname"];
$partner=$_SESSION["chat_user"];

mysqli_query($con,"insert into blocked_info(blocker,blocked,bdate) values('$uname','$partner',NOW())");

echo("blocked");


?>