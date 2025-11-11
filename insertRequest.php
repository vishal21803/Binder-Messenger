<?php @session_start();


$a=$_REQUEST["uid"];
$uname=$_SESSION["uname"];
include("connectdb.php");
mysqli_query($con,"insert into request_info(uid,rstatus,rdate,remail) values('$a','requested',NOW(),'$uname' )");

header("location:searchFriend.php?status=1");




?>