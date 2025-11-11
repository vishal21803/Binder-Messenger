<?php @session_start();
$_SESSION["chat_id"]=$_REQUEST["chat"];
header("location:userChat.php");
?>