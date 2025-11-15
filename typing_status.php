<?php
session_start();
include("connectdb.php");

$uname = $_SESSION["uname"];
$ckey = $_SESSION["ckey"];

$result = mysqli_query($con, "
    SELECT typing_user 
    FROM chat_info 
    WHERE ckey='$ckey'
");

$row = mysqli_fetch_assoc($result);

if ($row["typing_user"] && $row["typing_user"] !== $uname) {
    echo "typing";
} else {
    echo "none";
}
?>
