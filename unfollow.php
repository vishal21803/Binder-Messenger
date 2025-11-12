<?php
@session_start();
include("connectdb.php");

if (isset($_POST["uid"])) {
    $uid = $_POST["uid"];
    $uname = $_SESSION["uname"];

    // Check if already exists
    $check = mysqli_query($con, "SELECT * FROM request_info WHERE uid='$uid' AND remail='$uname' and rstatus='accepted' ");
    if (mysqli_num_rows($check) == 1) {
        mysqli_query($con, "delete from request_info WHERE uid='$uid' AND remail='$uname' and rstatus='accepted' ");
        echo "deleted";
    } else {
        echo "notexists";
    }
}
?>
