<?php @session_start();
include("connectdb.php");
$uid=$_SESSION["uid"];
$rscheck=mysqli_query($con,"select distinct * from request_info r where r.rstatus='requested' && r.uid='$uid'  ");

if(mysqli_num_rows($rscheck)>0){
    while($row=mysqli_fetch_array($rscheck)){
        echo("<div class='child'>");
       
        echo($row["remail"]);
        echo($row["rdate"]);
        $rid=$row["rid"];
        echo("<a href='updateRequest.php?rid=$rid'>Accept</a>");
        echo("</div>");
    }
}


?>