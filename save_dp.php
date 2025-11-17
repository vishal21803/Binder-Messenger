<?php @session_start();
include("connectdb.php");

$uid = $_SESSION['uid'];

// Get current DP from database
$q = mysqli_query($con, "SELECT ufile FROM user_info WHERE uid='$uid'");
$old = mysqli_fetch_assoc($q);
$oldDP = $old['ufile'];

// 1. Check if user selected file
if(isset($_FILES["dp"]) && $_FILES["dp"]["error"] === 0){

    // Create unique name
    $ext = pathinfo($_FILES["dp"]["name"], PATHINFO_EXTENSION);
    $dpName = "dp_".$uid.".".strtolower($ext);

    // Upload path
    $uploadPath = "uploads/".$dpName;

    move_uploaded_file($_FILES["dp"]["tmp_name"], $uploadPath);

} else {

    // ❌ You used: $dpName = "" → WRONG
    // ✔ Correct logic:

    if($oldDP == "" || $oldDP == NULL){
        // First time user → use default
        $dpName = "default.png";
    } else {
        // User already had a DP → keep it
        $dpName = $oldDP;
    }
}

// 3. Update DB
mysqli_query($con,
    "UPDATE user_info SET ufile='$dpName', onboard_status=1 WHERE uid='$uid'"
);

// 4. Redirect
header("location:userChat.php");
exit;
?>
