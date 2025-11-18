<?php
session_start();
include("connectdb.php");

$uid = $_SESSION['uid'];
$post_id = $_POST['post_id'];

// check if already liked
$check = mysqli_query($con, "SELECT * FROM post_likes WHERE uid='$uid' AND post_id='$post_id'");

if(mysqli_num_rows($check) > 0){
    // UNLIKE
    mysqli_query($con, "DELETE FROM post_likes WHERE uid='$uid' AND post_id='$post_id'");
    echo "unliked";
} else {
    // LIKE
    mysqli_query($con, "INSERT INTO post_likes(uid, post_id) VALUES('$uid','$post_id')");
    echo "liked";
}
?>
