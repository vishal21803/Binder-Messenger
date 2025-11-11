<?php @session_start();
include("connectdb.php");
$uid=$_SESSION["uid"];
$uname=$_SESSION["uname"];
$rscheck=mysqli_query($con,"select  * from request_info r,user_info u where r.rstatus='accepted' && r.remail='$uname' && u.uid=r.uid  ");

if(mysqli_num_rows($rscheck)>0){
    while($row=mysqli_fetch_array($rscheck)){
        echo("<div class='child'>");
       
        echo($row["uname"]);
        echo($row["uage"]);
        echo($row["uemail"]);
        $user=$row["uid"];
        $username=$row["uname"];
        echo("<a href='userChat.php?user=$user&name=$username'>Chat</a>");
        echo("</div>");
    }
}


?>