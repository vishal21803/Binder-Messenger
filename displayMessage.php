<?php @session_start();
include("connectdb.php");

if(isset($_SESSION["chat_id"]) && isset($_SESSION["ckey"])){
$chat_id=$_SESSION["chat_id"];
$user=$_SESSION["uname"];
$key=$_SESSION["ckey"];
$rschat=mysqli_query($con,"select * from message_info where mkey='$key' order by mtime");

while($row=mysqli_fetch_assoc($rschat)){
    $msg=$row["message"];
    $time = date("h:i A", strtotime($row["mtime"]));
   if($row["msender"]==$user){
     $position="right";
   }
   else{
    $position="left";
   }

   echo "<div class='message $position'> $msg <small> $time </small></div>";

}

}





?>