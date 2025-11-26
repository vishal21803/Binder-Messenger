<?php
session_start();
include("connectdb.php");
$uname=$_SESSION["uname"];
if(isset($_POST["editedDP"])){

    $img = $_POST["editedDP"];

    // base64 string clean
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);

    $data = base64_decode($img);

    $filename = "dp_".time().".png";

    file_put_contents("uploads/".$filename, $data);

    mysqli_query($con, "update user_info set ufile='$filename' where uname='$uname'");
    

    echo "DP Uploaded Successfully: ".$filename;
} else {
    echo "No DP Received!";
}
?>
