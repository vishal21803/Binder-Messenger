<?php @session_start();
$chat_id=$_SESSION["chat_id"];

$uname=$_SESSION["uname"];
$ckey=$_SESSION["ckey"];

include("connectdb.php");

$chat_id=$_SESSION["chat_id"];

$msg=mysqli_query($con,"select * from message_info where cid='$chat_id' ");

while($row=mysqli_fetch_assoc($msg)){
    $mid=$row["mid"];
    if($row["msender"]==$uname){
        mysqli_query($con,"update message_info set clear_by_sender=1 where mid='$mid' ");
    }
    elseif($row["mreceiver"]==$uname){
        mysqli_query($con,"update message_info set clear_by_receiver=1 where mid='$mid' ");
    }
}

echo("cleared");




?>