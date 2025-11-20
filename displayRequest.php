<?php @session_start();
include("connectdb.php");
date_default_timezone_set("Asia/Kolkata");

$uid = $_SESSION["uid"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Follow Requests</title>
    <link rel="stylesheet" href="requests.css">
</head>

<body>

<div class="container">
    <h2 class="title">Pending Follow Requests</h2>

    <div class="users-grid">

<?php
function timeAgo($time){
    $timestamp = strtotime($time);
    $current = time();
    
    // Fix negative values
    if ($timestamp > $current) {
        return "Just now";
    }

    $diff = $current - $timestamp;

    if ($diff < 60) return $diff . " sec ago";
    if ($diff < 3600) return floor($diff / 60) . " min ago";
    if ($diff < 86400) return floor($diff / 3600) . " hours ago";
    if ($diff < 172800) return "Yesterday";
    if ($diff < 604800) return floor($diff / 86400) . " days ago";

    // If older than 7 days → show actual date
    return date("d M Y", $timestamp);
}
// fetch pending requests
$rscheck = mysqli_query($con,"
    SELECT r.*, u.uname, u.uage, u.ufile, u.uid AS sender_uid
    FROM request_info r
    JOIN user_info u ON u.uname = r.remail
    WHERE r.rstatus='requested' AND r.uid='$uid' order by rdate desc
");

if(mysqli_num_rows($rscheck) > 0){

    while($row = mysqli_fetch_assoc($rscheck)){

        $name = $row["uname"];
        $age  = $row["uage"];
        $dp   = $row["ufile"];
        $rid  = $row["rid"];
        $sender = $row["sender_uid"];
        $date = timeAgo($row["rdate"]);

        echo "
        <div class='user-card'>
            
            <div class='avatar'>
                <img src='uploads/$dp'>
            </div>

            <h3>$name</h3>
            <p class='age'>Age: $age</p>
            <p class='date'>Requested : $date</p>

            <div class='btn-box'>
                <a class='accept-btn' onclick='acceptRequest($rid)'>Accept</a>
                <a href='othersProfile.php?oid=$sender' class='profile-btn'>View Profile</a>
            </div>
        </div>
        ";
    }

} else {
    echo "<p class='no-results'>No pending requests</p>";
}
?>

    </div>
</div>

</body>
</html>
