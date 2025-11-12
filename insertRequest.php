<?php
@session_start();
include("connectdb.php");

if (isset($_POST["uid"])) {
    $uid = $_POST["uid"];
    $uname = $_SESSION["uname"];

    // Check if already exists
    $check = mysqli_query($con, "SELECT * FROM request_info WHERE uid='$uid' AND remail='$uname'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($con, "INSERT INTO request_info (uid, remail, rstatus, rdate) VALUES ('$uid', '$uname', 'requested', NOW())");
        echo "requested";
    } else {
        echo "exists";
    }
}
?>
