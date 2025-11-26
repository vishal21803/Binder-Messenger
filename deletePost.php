<?php
session_start();
include("connectdb.php");

$pid = $_POST["pid"];
$user = $_SESSION["uid"];

mysqli_query($con, "DELETE FROM post_info WHERE pid='$pid' AND uid='$user' ");

echo "DELETED";
?>
