<?php 
@session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user'){
include("header.php");
?>

<?php 
$uname = $_SESSION["uname"];
$uid   = $_SESSION["uid"];

include("connectdb.php");

// USER INFO
$info = mysqli_query($con,"SELECT * FROM user_info WHERE uname='$uname'");
$row  = mysqli_fetch_array($info);

$fullname = $row["ufull"];
$email    = $row["uemail"];
$dp       = $row["ufile"];
$website  = $row["uwebsite"];
$bio      = $row["ubio"];
$age      = $row["uage"];

// Following Count
$rscheck = mysqli_query($con,"SELECT * FROM request_info r,user_info u 
                              WHERE r.rstatus='accepted' 
                              AND r.remail='$uname' 
                              AND u.uid=r.uid");

$count = mysqli_num_rows($rscheck);

// Followers Count
$rscheck2 = "
SELECT *
FROM request_info r,user_info u
WHERE u.uname = r.remail
AND r.rstatus = 'accepted'
AND r.uid = '$uid'
";

$followers = mysqli_query($con, $rscheck2);
$count2 = mysqli_num_rows($followers);


$rsCount = mysqli_query($con,"SELECT * FROM post_info WHERE uid='$uid' ORDER BY post_time DESC");
$pcount=0;
while($row5=mysqli_fetch_array($rsCount)){
  $pcount++;
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
				<h1 class="profile-user-name"><?php echo $uname ?></h1>
				<a class="btn profile-edit-btn" href="editProfile.php">Edit Profile</a>
			</div>

			<div class="profile-stats">
				<ul>
					<li><span class="profile-stat-count"><?php echo($pcount);?></span> posts</li>
					<li><a style="text-decoration:none; color:#322627;" href="userFollower.php"><span class="profile-stat-count"><?php echo $count2 ?></span> followers</a></li>
					<li><a style="text-decoration:none; color:#322627;" href="userFollowing.php"><span class="profile-stat-count"><?php echo $count ?></span> following</a></li>
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

		<div class="gallery">

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
$rspost = mysqli_query($con,"SELECT * FROM post_info WHERE uid='$uid' ORDER BY post_time DESC");

while($row=mysqli_fetch_array($rspost)){
     
    $postimg = $row["post_img"];
    $caption = $row["caption"];
    $pid     = $row["pid"];
    $postime=  timeAgo($row["post_time"]);

    // Count likes
    $likes = mysqli_num_rows(mysqli_query($con,"SELECT * FROM post_likes WHERE post_id='$pid'"));

        $commentCount = mysqli_num_rows(mysqli_query($con, "SELECT * FROM post_comments WHERE post_id='$pid'"));

    // user liked?
    $userLiked = mysqli_num_rows(mysqli_query($con,"
        SELECT * FROM post_likes WHERE uid='$uid' AND post_id='$pid'
    ")) > 0;

    $likeIcon = $userLiked ? "❤️" : "🤍";

    // UNIQUE MODAL ID
    $modalID = "postModal_" . $pid;

echo "
<!-- Thumbnail -->
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

<!-- FULL POST MODAL -->
<div class='modal fade' id='$modalID'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
            
            <div class='modal-body'>
                <div class='post-card'>

                    <div class='post-header'>
                        <img src='uploads/$dp'>
                        <div>
                            <div class='post-username'>$uname</div>
                            <div class='post-time'>$postime</div>
                        </div>
                    </div>

                    <img src='uploads/$postimg' class='post-img'>

                    <div class='post-body'>

                        <!-- LIKE BUTTON -->
                        <span class='like-btn' 
                              id='like_$pid'
                              onclick='likePost($pid)'>
                              $likeIcon
                        </span>

                        <!-- LIKE COUNT -->
                        <div class='likes' id='likesCount_$pid'>$likes Likes</div>

                        <div class='caption'><b>$uname</b> $caption</div>

                        <!-- Comments list -->
<div id='commentBox_$pid' class='comment-list'></div>

<!-- Comment input -->
<div class='comment-input'>
  <input type='text' id='commentInput_$pid' placeholder='Add a comment...'>
  <button type='button' onclick='addComment($pid)'>Post</button>
</div>

<!-- Comment count element (to update) -->
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


</script>

<?php include("footer.php"); } else { include("index.php"); } ?>
