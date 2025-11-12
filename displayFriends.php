<?php @session_start();
include("connectdb.php");
$uid=$_SESSION["uid"];
$uname=$_SESSION["uname"];
$rscheck=mysqli_query($con,"select  * from request_info r,user_info u where r.rstatus='accepted' && r.remail='$uname' && u.uid=r.uid  ");

//following
$count=0;
if(mysqli_num_rows($rscheck)>0){
    while($row=mysqli_fetch_array($rscheck)){
         $count++ ;
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


//followers
$count2=0;
$rscheck2 = "
    SELECT *
    FROM request_info r
    ,user_info u where u.uname = r.remail
    AND r.rstatus = 'accepted'
      AND r.uid = '$uid'
";
$followers = mysqli_query($con, $rscheck2);

if (mysqli_num_rows($followers) > 0) {
    
    while ($row = mysqli_fetch_assoc($followers)) {
    $count2++;

    }
}
echo("following:$count");
echo("<br>");
echo("followers:$count2");
?>