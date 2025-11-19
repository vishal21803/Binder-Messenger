<?php
session_start();
include("connectdb.php");

header('Content-Type: application/json; charset=utf-8');

if(!isset($_SESSION['uid'])){
    echo json_encode(['status'=>'error','message'=>'not_logged_in']);
    exit;
}

$uid = (int)$_SESSION['uid'];
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if($post_id <= 0 || $comment === ''){
    echo json_encode(['status'=>'error','message'=>'invalid_input']);
    exit;
}

$comment_safe = mysqli_real_escape_string($con, $comment);

$q = "INSERT INTO post_comments (post_id, uid, comment) VALUES ('$post_id', '$uid', '$comment_safe')";
$res = mysqli_query($con, $q);

if(!$res){
    echo json_encode(['status'=>'error','message'=>'db_error']);
    exit;
}

// get inserted comment with user name and timestamp
$last_id = mysqli_insert_id($con);
$q2 = "SELECT pc.comment, pc.created_at, u.uname 
       FROM post_comments pc
       JOIN user_info u ON u.uid = pc.uid
       WHERE pc.id = '$last_id' LIMIT 1";
$r2 = mysqli_query($con, $q2);
$commentRow = mysqli_fetch_assoc($r2);

$comment_html = "
  <div class='comment'>
    <b>".htmlspecialchars($commentRow['uname'])."</b>
    <span class='comment-text'>".htmlspecialchars($commentRow['comment'])."</span>
    <div class='comment-time'>".$commentRow['created_at']."</div>
  </div>
";

// new comment count
$q3 = mysqli_query($con, "SELECT COUNT(*) AS cnt FROM post_comments WHERE post_id='$post_id'");
$countRow = mysqli_fetch_assoc($q3);
$newCount = (int)$countRow['cnt'];

echo json_encode([
    'status'=>'success',
    'html'=>$comment_html,
    'count'=>$newCount
]);
exit;
?>
