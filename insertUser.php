<?php

$a=$_REQUEST["uname"];
$b=$_REQUEST["uemail"];
$c=$_REQUEST["uage"];

$d=$_REQUEST["upassword"];

include("connectdb.php");

mysqli_query($con,"insert into user_info(uname,uemail,uage,upword,utype,reg_date) values('$a','$b','$c','$d','user',NOW() )");


header("location:successPage.php");

?>