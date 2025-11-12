 <?php @session_start();
 $uname=$_SESSION["uname"];     
 include("connectdb.php");
 $search = $_GET["sd"];

$rscheck=mysqli_query($con,"select * from user_info where uname like '%$search%' AND uname NOT IN('$uname')");

if(mysqli_num_rows($rscheck)>0){
while($row=mysqli_fetch_array($rscheck)){
    $name=$row["uname"];
    $age=$row["uage"];
    $id=$row["uid"];

    echo("<div class='child'>");
    echo($name);
    echo($age);
    echo("<a href='insertRequest.php?uid=$id'>Send Request</a>");
    echo("</div>");
}
}else {
  echo "<p style='color:white; text-align:center; width:100%;'>No results found</p>";
}


    
    ?>