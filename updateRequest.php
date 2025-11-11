<?php @session_start();

$rid=$_REQUEST["rid"];

include("connectdb.php");

mysqli_query($con,"update request_info set rstatus='accepted' where rid='$rid' ");



header("location:requestPage.php?status=1");


?>