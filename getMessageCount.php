<?php
session_start();
include("connectdb.php");

$uname = $_SESSION["uname"];

$q = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM message_info 
                         WHERE mreceiver='$uname' AND is_read=0");

$row = mysqli_fetch_assoc($q);
echo $row["cnt"];
?>
