<?php @session_start();
$uname=$_SESSION["uname"];
include("connectdb.php");

$fname=$_REQUEST["fname"];
$age=$_REQUEST["age"];
$website=$_REQUEST["website"];
$bio=$_REQUEST["bio"];

mysqli_query($con,"update user_info set ufull='$fname',uage='$age',uwebsite='$website',ubio='$bio' where uname='$uname' ");

header("location:userProfile.php?status=1");







?>