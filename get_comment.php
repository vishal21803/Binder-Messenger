<?php
include("connectdb.php");
date_default_timezone_set("Asia/Kolkata"); 

// TIME AGO FUNCTION
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


$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
if($post_id <= 0) exit;

$q = "SELECT pc.comment, pc.created_at, u.uname 
      FROM post_comments pc
      JOIN user_info u ON u.uid = pc.uid
      WHERE pc.post_id = '$post_id'
      ORDER BY pc.id desc";

$res = mysqli_query($con, $q);
$out = "";

while($row = mysqli_fetch_assoc($res)){
    $uname = htmlspecialchars($row['uname']);
    $comment = htmlspecialchars($row['comment']);
    $timeAgo = timeAgo($row['created_at']);

    $out .= "
      <div class='comment'>
        <b>{$uname}</b>
        <span class='comment-text'>{$comment}</span>
        <div class='comment-time'>{$timeAgo}</div>
      </div>
    ";
}


echo $out;
?>
