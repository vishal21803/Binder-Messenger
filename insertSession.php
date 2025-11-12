<?php
@session_start();
include("connectdb.php");

if (isset($_GET["chat"])) {
    $cid = $_GET["chat"];
    $_SESSION["chat_id"] = $cid;

    // Fetch chat info
    $rs = mysqli_query($con, "SELECT * FROM chat_info WHERE cid='$cid'");
    if ($row = mysqli_fetch_assoc($rs)) {
        $_SESSION["ckey"] = $row["ckey"];
        $uname = $_SESSION["uname"];

        // Identify chat partner
        if ($row["cuser"] == $uname) {
            $_SESSION["chat_user"] = $row["you"];
        } else {
            $_SESSION["chat_user"] = $row["cuser"];
        }
    }

    // Redirect to main chat page
    header("Location: userChat.php");
    exit();
}
?>
