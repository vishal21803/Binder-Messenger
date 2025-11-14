<?php @session_start();
include("connectdb.php");
$chat_id=$_SESSION["chat_id"];
$user=$_SESSION["uname"];

$row=mysqli_fetch_assoc(mysqli_query($con,"select * from chat_info where cid='$chat_id'")) ;

if($row["cuser"]==$user){
    $receiver=$row["you"];
}
else{
   $receiver=$row["cuser"];

}
$key=$row["ckey"];
// When you send a new message → remove last seen indicator
mysqli_query($con, "
    UPDATE message_info 
    SET read_time = NULL
    WHERE mkey = '$key'
");

$_SESSION["ckey"]=$row["ckey"];
$message=mysqli_real_escape_string($con,$_POST["message"]);
mysqli_query($con,"insert into message_info(cid,msender,mreceiver,mtime,message,mkey) values('$chat_id','$user','$receiver',NOW(),'$message','$key')");





?>