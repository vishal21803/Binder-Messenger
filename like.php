<?php
session_start();
include("connectdb.php");

if (!isset($_SESSION['uid'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User not logged in"
    ]);
    exit;
}

$uid = $_SESSION['uid'];
$post_id = $_POST['post_id'];

// CHECK IF ALREADY LIKED
$check = mysqli_query($con, 
    "SELECT * FROM post_likes 
     WHERE uid='$uid' AND post_id='$post_id'"
);

if (mysqli_num_rows($check) > 0) {

    // -------- UNLIKE --------
    mysqli_query($con, 
        "DELETE FROM post_likes 
         WHERE uid='$uid' AND post_id='$post_id'"
    );

    $status = "unliked";

} else {

    // -------- LIKE --------
    mysqli_query($con, 
        "INSERT INTO post_likes(uid, post_id) 
         VALUES('$uid', '$post_id')"
    );

    $status = "liked";
}

// COUNT TOTAL LIKES
$likes = mysqli_num_rows(
    mysqli_query($con, 
        "SELECT * FROM post_likes WHERE post_id='$post_id'"
    )
);

// SEND JSON RESPONSE
echo json_encode([
    "status" => $status,
    "likes" => $likes
]);

exit;
?>
