<?php
$mid=$_POST["mid"];
include("connectdb.php");

mysqli_query($con,"delete from message_info where mid='$mid'");

echo("delete");

?>