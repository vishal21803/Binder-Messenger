<?php
session_start();
include("connectdb.php");

$uid = $_SESSION["uid"];

$q = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM request_info 
                         WHERE uid='$uid' AND rstatus='requested' ");

$row = mysqli_fetch_assoc($q);
echo $row["cnt"];
?>
