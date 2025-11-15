<?php
@session_start();
include("connectdb.php");

$chat_id = $_SESSION["chat_id"];
$user    = $_SESSION["uname"];

$row = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM chat_info WHERE cid='$chat_id'"));

$receiver = ($row["cuser"] == $user) ? $row["you"] : $row["cuser"];
$key = $row["ckey"];

// remove last seen on new message
mysqli_query($con, "UPDATE message_info SET read_time=NULL WHERE mkey='$key'");

// 🟢 Send TEXT message only if message exists
if (isset($_POST["message"]) && trim($_POST["message"]) !== "") {

    $message = mysqli_real_escape_string($con, $_POST["message"]);

    mysqli_query($con, "
        INSERT INTO message_info (cid, msender, mreceiver, mtime, message, mkey)
        VALUES ('$chat_id', '$user', '$receiver', NOW(), '$message', '$key')
    ");

    echo "MSG_SENT";
    exit();
}

// 🟢 Send FILE message
if (isset($_POST["file"]) && $_POST["file"] !== "") {

    $file = mysqli_real_escape_string($con, $_POST["file"]);

    mysqli_query($con, "
        INSERT INTO message_info (cid, msender, mreceiver, mtime, message, file, mkey)
        VALUES ('$chat_id', '$user', '$receiver', NOW(), NULL, '$file', '$key')
    ");

    echo "FILE_SENT";
    exit();
}

?>
