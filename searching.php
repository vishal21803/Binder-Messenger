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

    // 🔍 check request status for this specific user
      $reqCheck = mysqli_query($con, "SELECT * FROM request_info 
                                      WHERE remail='$uname' 
                                      AND uid='$id'");

      if (mysqli_num_rows($reqCheck) > 0) {
          $row2 = mysqli_fetch_array($reqCheck);
          if ($row2["rstatus"] == "requested") {
              echo("<button class='reqBtn' data-uid='$id'>Requested</button>");
          } elseif ($row2["rstatus"] == "accepted") {
              echo("<button class='followingBtn' data-uid='$id'>Following</button>");
          }
      } else {
              echo("<button class='followBtn' data-uid='$id' >Follow</button>");
      }
   
    echo("</div>");
}
}else {
  echo "<p style='color:white; text-align:center; width:100%;'>No results found</p>";
}


    
    ?>

  