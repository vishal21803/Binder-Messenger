<?php @session_start();
include("connectdb.php");

$uid = $_SESSION["uid"];
$uname = $_SESSION["uname"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Followers & Following</title>
    <link rel="stylesheet" href="followers.css">
</head>

<body>

<div class="container2">
    <h2 class="title">Following</h2>
     <div class="users-grid">

<?php
// ----------- FOLLOWING LIST --------------
$rscheck = mysqli_query($con,
    "SELECT u.uid, u.uname, u.uage, u.uemail, u.ufile 
     FROM request_info r
     JOIN user_info u ON u.uid = r.uid
     WHERE r.rstatus='accepted' AND r.remail='$uname'"
);

$count = 0;

if (mysqli_num_rows($rscheck) > 0) {
    while ($row = mysqli_fetch_array($rscheck)) {
        
        $count++;
        $user   = $row["uid"];
        $name   = $row["uname"];
        $age    = $row["uage"];
        $email  = $row["uemail"];
        $dp     = $row["ufile"];

        echo "
         <div class='user-card' id='user-$user'>
            <div class='avatar'>
                <img src='uploads/$dp'>
            </div>
            <h3>$name</h3>
            <p class='age'>Age: $age</p>
            <p class='email'>$email</p>

            <div class='btn-box'>
                <button onclick='deleteFollowing($user)' class='remove-btn'>Unfollow</button>

                <a href='userChat.php?user=$user&name=$name' class='chat-btn'>Chat</a>
                <a href='othersProfile.php?oid=$user' class='profile-btn'>View Profile</a>
            </div>
        </div>
        "
        ;
    }
}
?>
    </div>
    <br>


<!-- ---------------- FOLLOWERS ---------------- -->
<!-- <h2 class="title">Followers</h2>
<div class="users-grid"> -->

<?php
// $rscheck2 = "
//     SELECT u.uid, u.uname, u.uage, u.uemail, u.ufile
//     FROM request_info r
//     JOIN user_info u ON u.uname = r.remail
//     WHERE r.rstatus = 'accepted'
//     AND r.uid = '$uid'
// ";

// $followers = mysqli_query($con, $rscheck2);

// $count2 = 0;

// if (mysqli_num_rows($followers) > 0) {

//     while ($row = mysqli_fetch_assoc($followers)) {

//         $count2++;
//         $user   = $row["uid"];
//         $name   = $row["uname"];
//         $age    = $row["uage"];
//         $email  = $row["uemail"];
//         $dp     = $row["ufile"];

//         echo "
//         <div class='user-card'>
//             <div class='avatar'>
//                 <img src='uploads/$dp'>
//             </div>
//             <h3>$name</h3>
//             <p class='age'>Age: $age</p>
//             <p class='email'>$email</p>

//             <div class='btn-box'>
//                 <a href='userChat.php?user=$user&name=$name' class='chat-btn'>Chat</a>
//                 <a href='othersProfile.php?oid=$user' class='profile-btn'>View Profile</a>
//             </div>
//         </div>";
//     }
// }
?>
<!-- </div> -->

<!-- Counts
<div class="footer-counts">
    <p><b>Following:</b> <?php echo $count; ?></p>
    <p><b>Followers:</b> <?php echo $count2; ?></p>
</div> -->

</div>

</body>
</html>
