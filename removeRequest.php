<?php
@session_start();
include("connectdb.php");

$uid = $_SESSION["uid"];
$removeUid = $_GET["uid"];

// delete request relation
mysqli_query($con,
    "DELETE FROM request_info 
     WHERE uid='$uid' AND rstatus='accepted' AND remail = (
         SELECT uname FROM user_info WHERE uid='$removeUid'
     )"
);

echo "done";
?>
