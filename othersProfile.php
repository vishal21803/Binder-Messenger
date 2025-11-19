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

			<div class="profile-stats">
				<ul>
					<li><span class="profile-stat-count"><?php echo($pcount);?></span> posts</li>
					<li><span class="profile-stat-count"><?php echo $count2  ?></span> followers</li>
					<li><span class="profile-stat-count"><?php echo $count ?></span> following</li>
				</ul>
			</div>

			<div class="profile-bio">
				<p><span class="profile-real-name"><?php echo $fullname ?></span> <?php echo $bio ?></p>
			</div>

		</div>
	</div>
</header>

<!-- MAIN GALLERY -->
<main>
    <div class="container">

<?php if($isFollowing) { ?> 

        <!-- SHOW POSTS ONLY IF FOLLOWING -->
        <div class="gallery">

<?php
$rspost = mysqli_query($con,"SELECT * FROM post_info WHERE uid='$oid' ORDER BY post_time DESC");

while($row=mysqli_fetch_array($rspost)){

    $postimg = $row["post_img"];
    $caption = $row["caption"];
    $pid     = $row["pid"];
    $postime=  timeAgo($row["post_time"]);

    // Count likes
    $likes = mysqli_num_rows(mysqli_query($con,"SELECT * FROM post_likes WHERE post_id='$pid'"));
    $commentCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM post_comments WHERE post_id='$pid'"));

    $userLiked = mysqli_num_rows(mysqli_query($con,"
        SELECT * FROM post_likes WHERE uid='$uid' AND post_id='$pid'
    ")) > 0;

    $likeIcon = $userLiked ? "❤️" : "🤍";
    $modalID = "postModal_" . $pid;

echo "
<a data-bs-toggle='modal' data-bs-target='#$modalID'>
    <div class='gallery-item'>
        <img src='uploads/$postimg' class='gallery-image'>
        <div class='gallery-item-info'>
            <ul>
            <li class='gallery-item-likes' id='galleryLikes_$pid'>
                <i class='bi bi-heart-fill'></i> $likes
            </li>
            </ul>
        </div>
    </div>
</a>

<div class='modal fade' id='$modalID'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
            <div class='modal-body'>
                <div class='post-card'>
                    <div class='post-header'>
                        <img src='uploads/$dp'>
                        <div>
                            <div class='post-username'>$u</div>
                            <div class='post-time'>$postime</div>
                        </div>
                    </div>
                    <img src='uploads/$postimg' class='post-img'>
                    <div class='post-body'>
                        <span class='like-btn' id='like_$pid' onclick='likePost($pid)'>$likeIcon</span>
                        <div class='likes' id='likesCount_$pid'>$likes Likes</div>
                        <div class='caption'><b>$u</b> $caption</div>

                        <div id='commentBox_$pid' class='comment-list'></div>
                        <div class='comment-input'>
                            <input type='text' id='commentInput_$pid' placeholder='Add a comment...'>
                            <button type='button' onclick='addComment($pid)'>Post</button>
                        </div>
                        <div id='commentCount_$pid'>$commentCount comments</div>

                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>
";
}
?>
        </div>

<?php } else { ?> 

        <!-- PRIVATE ACCOUNT MESSAGE -->
        <div class="private-box">
            <img src="images/private.png" class="private-icon">
            <h2>This Account is Private</h2>
            <p>Follow to see photos and videos.</p>
        </div>

<?php } ?>

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
</script>

<?php include("footer.php"); } else { include("index.php"); } ?>
