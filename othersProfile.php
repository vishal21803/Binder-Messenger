<?php 
@session_start();
date_default_timezone_set("Asia/Kolkata"); 

if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user'){
include("header.php");
?>

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
$uname = $_SESSION["uname"];
$uid   = $_SESSION["uid"];

$oid=$_REQUEST["oid"];

include("connectdb.php");

// USER INFO
$info = mysqli_query($con,"SELECT * FROM user_info WHERE uid='$oid'");
$row  = mysqli_fetch_array($info);

$fullname = $row["ufull"];
$email    = $row["uemail"];
$dp       = $row["ufile"];
$website  = $row["uwebsite"];
$bio      = $row["ubio"];
$age      = $row["uage"];
$u=$row["uname"];
// Following Count
$rscheck = mysqli_query($con,"SELECT * FROM request_info r,user_info u 
                              WHERE r.rstatus='accepted' 
                              AND r.remail='$u' 
                              AND u.uid=r.uid");

$count = mysqli_num_rows($rscheck);

// Followers Count
$rscheck2 = "
SELECT *
FROM request_info r,user_info u
WHERE u.uname = r.remail
AND r.rstatus = 'accepted'
AND r.uid = '$oid'
";

$followers = mysqli_query($con, $rscheck2);
$count2 = mysqli_num_rows($followers);

$rsCount = mysqli_query($con,"SELECT * FROM post_info WHERE uid='$oid' ORDER BY post_time DESC");
$pcount=0;
while($row5=mysqli_fetch_array($rsCount)){
  $pcount++;
}





// Fetch other user info
$q = mysqli_query($con, "SELECT * FROM user_info WHERE uid='$oid'");
$user = mysqli_fetch_assoc($q);

$otherUname = $user['uname'];
$dp = $user['ufile'];
$age = $user['uage'];


$isFollowing = false;

$checkFollow = mysqli_query($con,
    "SELECT * FROM request_info 
     WHERE remail='$uname' 
     AND uid='$oid'
     AND rstatus='accepted'"
);

if(mysqli_num_rows($checkFollow) > 0){
    $isFollowing = true;
}
?>

<!-- HEADER UI -->
<header>
	<div class="container">
		<div class="profile">
			
			<div class="profile-image">
				<img src="uploads/<?php echo $dp ?>" alt="">
			</div>

			<div class="profile-user-settings">
				<h1 class="profile-user-name"><?php echo $u ?></h1>
        <!-- FOLLOW BUTTON AREA -->
<div class="profile-actions">
    <div class="profile-req-wrapper">

<?php

$check = mysqli_query($con,
    "SELECT * FROM request_info
     WHERE remail='$uname' 
     AND uid='$oid'"
);

if (mysqli_num_rows($check) > 0) {

    $r = mysqli_fetch_assoc($check);

    if ($r["rstatus"] === "requested") {
        echo "<button class='profilereq status-btn reqBtn' data-uid='$oid'>Requested</button>";
    } 
    elseif ($r["rstatus"] === "accepted") {
        echo "<button class='profilereq status-btn followingBtn' data-uid='$oid'>Following</button>";
    }

} else {
    echo "<button class='profilereq main-btn followBtn' data-uid='$oid'>Follow</button>";
}

?>
</div>
</div>

			</div>

			<div class="profile-stats">
				<ul>
					<li><span class="profile-stat-count"><?php echo($pcount);?></span> posts</li>
					<li><span class="profile-stat-count"><?php echo $count2  ?></span> followers</li>
					<li><span class="profile-stat-count"><?php echo $count ?></span> following</li>
				</ul>
			</div>

			<div class="profile-bio">
				<p><span class="profile-real-name"><?php echo $fullname ?></span>
                 <br>
                  <?php echo $bio ?>
                </p>
			</div>

		</div>
	</div>
</header>

<!-- MAIN GALLERY -->
<main>
	<div class="container">

		<div class="gallery">

<?php
date_default_timezone_set('Asia/Kolkata'); // or 'Asia/Kolkata' etc.



include("connectdb.php");
date_default_timezone_set('Asia/Kolkata'); // or 'Asia/Kolkata' etc.

if(isset($_SESSION["uname"]) && isset($_SESSION["uid"]) ){
  $uname=$_SESSION["uname"];
  $uid=$_SESSION["uid"];
 
     $rs2=mysqli_query($con,"select * from post_info p,user_info u where p.uid='$oid' and u.uid='$oid' order by post_time desc");

     while($row2=mysqli_fetch_array($rs2)){
      $img=$row2["post_img"];
      $caption=$row2["caption"];
      $tags=$row2["tags"];
      $location=$row2["location"];
      $fname=$row2["uname"];
      $pimg=$row2["ufile"];
      $ptime= timeAgo( $row2["post_time"]);
      $pid=$row2["pid"];

               $likes = mysqli_num_rows(mysqli_query($con,"SELECT * FROM post_likes WHERE post_id='$pid'"));
                   $commentCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM post_comments WHERE post_id='$pid'"));

    $userLiked = mysqli_num_rows(mysqli_query($con,"
        SELECT * FROM post_likes WHERE uid='$oid' AND post_id='$pid'
    ")) > 0;

    $likeIcon = $userLiked ? "❤️" : "🤍";



    // UNIQUE MODAL ID

echo "
<!-- Thumbnail -->
<a data-bs-toggle='modal' data-bs-target='#myModal_$pid'>
    <div class='gallery-item'>
        <img src='uploads/$img' class='gallery-image'>
        <div class='gallery-item-info'>
            <ul>
            <li class='gallery-item-likes' id='galleryLikes_$pid'>
    <i class='bi bi-heart-fill'></i> $likes &nbsp;&nbsp;&nbsp;
</li>
  <li class='gallery-item-comment' id='galleryComment_$pid'>
    <i class='bi bi-chat-fill'></i> $commentCount
</li>

            </ul>
        </div>
    </div>
</a>
";


 echo "

 <div class='modal fade' id='myModal_$pid'>
  <div class='modal-dialog modal-fullscreen modal-dialog-centered'>
      <div class='feed-container ' >
<div class='modal-content' style='background:transparent;'>
   <div class='modal-body'>

    <!-- MAIN FEED -->
    <div class='feed-wrapper ' >

        <!-- Example Post -->
        <div class='post-card'>

            <!-- Post Header -->
            <div class='post-user'>
                <img src='uploads/$pimg' class='user-img'>
                <div>
                    <h4 class='user-name'>$fname</h4>
                    <p class='post-time'>$ptime</p>
                </div>
            </div>

            <!-- Post Image -->
            <div class='post-image'>
                <img src='uploads/$img'>
            </div>

            <!-- Post Actions -->
            <div class='post-actions'>
                        <span class='like-btn' id='like_$pid' onclick='likePost($pid)'>$likeIcon</span>
                <i class='bi bi-chat' onclick='toggleComments($pid)'></i>
            </div>

            <!-- Likes -->
            <p class='likes-count' id='likesCount_$pid'>$likes likes</p>

            <!-- Caption -->
            <div class='post-caption'>
                <b>$fname</b> $caption
            </div>

            <!-- View Comments -->
            <div class='view-comments' id='commentCount_$pid' onclick='toggleComments($pid)'>
               $commentCount comments
            
            </div>
 <div id='commentBox_$pid' class='comment-list' style='display:none;'></div>


            <div class='comment-input'>
  <input type='text' id='commentInput_$pid' placeholder='Add a comment...'>
  <button type='button' onclick='addComment($pid)'>Post</button>
</div>

</div>

        </div>

    </div>
</div>
</div>
</div>
</div>
";


     }
   }
  



?>

		</div>
	</div>
</main>



<!-- LIKE FUNCTION JS -->
<script>
function likePost(postId) {

    let btn = document.getElementById("like_" + postId);
    let likeCountBox = document.getElementById("likesCount_" + postId);

    fetch("like.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "post_id=" + postId
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "liked"){
            btn.innerHTML = "❤️";
        } else {
            btn.innerHTML = "🤍";
        }

        likeCountBox.innerHTML = data.likes + " Likes";

         document.getElementById("galleryLikes_" + postId).innerHTML =
            "<i class='bi bi-heart-fill'></i> " + data.likes;
    });
}

/**
 * Add a comment via AJAX and append returned HTML to the list.
 */
async function addComment(postId){
  const input = document.getElementById('commentInput_' + postId);
  if(!input) return;
  const text = input.value.trim();
  if(!text) return;

  try{
    const form = new FormData();
    form.append('post_id', postId);
    form.append('comment', text);

    const res = await fetch('add_comment.php', {
      method: 'POST',
      body: form
    });
    const data = await res.json();

    if(data.status === 'success'){
      // append new comment HTML
      const box = document.getElementById('commentBox_' + postId);
      if(box) box.insertAdjacentHTML('beforeend', data.html);

      // update comment count display if present
      const cnt = document.getElementById('commentCount_' + postId);
      if(cnt) cnt.textContent = data.count + (data.count==1 ? ' comment' : ' comments');

      input.value = '';
    } else {
      console.error('Comment error:', data.message);
      alert('Could not save comment.');
    }
  } catch(err){
    console.error(err);
    alert('Network error.');
  }
}

/**
 * Load all comments HTML into comment box.
 */
async function loadComments(postId){
  try{
    const res = await fetch('get_comment.php?post_id=' + encodeURIComponent(postId));
    const html = await res.text();
    const box = document.getElementById('commentBox_' + postId);
    if(box) box.innerHTML = html;
  } catch(err){
    console.error(err);
  }
}

/**
 * Optionally, update the comment count (pull from server).
 * Simple wrapper that reloads count by counting child nodes or by requesting endpoint.
 */
function updateCommentCountUI(postId){
  const box = document.getElementById('commentBox_' + postId);
  const cntEl = document.getElementById('commentCount_' + postId);
  if(box && cntEl){
    const cnt = box.querySelectorAll('.comment').length;
    cntEl.textContent = cnt + (cnt==1 ? ' comment' : ' comments');
  }
}

/* Example: auto-load comments for visible posts on page load */
document.addEventListener('DOMContentLoaded', () => {
  // find all comment boxes and extract post id suffix
  document.querySelectorAll("[id^='commentBox_']").forEach(el=>{
    const pid = el.id.split('_')[1];
    if(pid) loadComments(pid);
  });
});



$(document).on("click", ".followBtn", function() {
    var btn = $(this);
    var uid = btn.data("uid");

    $.ajax({
        url: "insertRequest.php",
        type: "POST",
        data: { uid: uid },
        success: function(response) {
            if (response.trim() === "requested") {
                btn.text("Requested");
                btn.prop("disabled", true);
            } else if (response.trim() === "exists") {
                btn.text("Following");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});


$(document).on("click", ".followingBtn", function() {
    var btn = $(this);
    var uid = btn.data("uid");

    $.ajax({
        url: "unfollow.php",
        type: "POST",
        data: { uid: uid },
        success: function(response) {
            if (response.trim() === "deleted") {
                btn.text("Follow");
                btn.prop("disabled", false);
                btn.removeClass("followingBtn").addClass("followBtn");

            } else if (response.trim() === "notexists") {
                btn.text("requested");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});



$(document).on("click", ".reqBtn", function() {
    var btn = $(this);
    var uid = btn.data("uid");

    $.ajax({
        url: "reqBack.php",
        type: "POST",
        data: { uid: uid },
        success: function(response) {
            if (response.trim() === "reqback") {
                btn.text("Follow");
                btn.prop("disabled", false);
                btn.removeClass("reqBtn").addClass("followBtn");

            } else if (response.trim() === "notexists") {
                btn.text("requested");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});


function toggleComments(postId){
    const box = document.getElementById("commentBox_" + postId);

    // If hidden → show + load comments
    if(box.style.display === "none" || box.style.display === ""){
        box.style.display = "block";

        // Only load comments if empty (avoid loading again)
        if(box.innerHTML.trim() === ""){
            loadComments(postId);
        }

    } else {
        // Hide comment section
        box.style.display = "none";
    }
}

</script>

<?php include("footer.php"); } else { include("index.php"); } ?>
