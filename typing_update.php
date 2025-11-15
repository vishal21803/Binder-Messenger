<?php
session_start();
include("connectdb.php");

$uname = $_SESSION["uname"];
$partner = $_SESSION["chat_user"];
$ckey = $_SESSION["ckey"];

$status = $_POST["status"]; // typing / not_typing

if ($status === "typing") {
    mysqli_query($con, "
        UPDATE chat_info 
        SET typing_user = '$uname'
        WHERE ckey='$ckey'
    ");
} else {
    mysqli_query($con, "
        UPDATE chat_info 
        SET typing_user = NULL
        WHERE ckey='$ckey' AND typing_user='$uname'
    ");
}

echo "ok";
?>
