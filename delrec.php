<?php
$mid=$_POST["mid"];
include("connectdb.php");

mysqli_query($con,"update message_info set clear_by_receiver=1 where mid='$mid' ");

echo("deleterec");

?>