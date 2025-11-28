
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>

<?php
include("connectdb.php");
date_default_timezone_set('Asia/Kolkata'); // or 'Asia/Kolkata' etc.

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
if(isset($_SESSION["uname"]) && isset($_SESSION["uid"]) ){
  $uname=$_SESSION["uname"];
  $uid=$_SESSION["uid"];
  $rs1=mysqli_query($con,"select uid from request_info  where rstatus='accepted' and remail='$uname' ");
  $followingCount = mysqli_num_rows($rs1);

if ($followingCount == 0) {
    echo "
    <div class='no-follow-box'>
        <h2>You are not following anyone yet 👀</h2>
        <p>Start following friends to see their posts in your feed.</p>
        <a href='searchFriend.php' class='follow-btn'>Search Friends</a>
        <br><br>
    </div>";

    include("footer.php");
    exit;
}
   while($row=mysqli_fetch_array($rs1)){
     $feedid=$row["uid"];

     $rs2=mysqli_query($con,"select * from post_info p,user_info u where p.uid='$feedid' and u.uid='$feedid' order by post_time desc ");

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
        SELECT * FROM post_likes WHERE uid='$uid' AND post_id='$pid'
    ")) > 0;

    $likeIcon = $userLiked ? "❤️" : "🤍";


 echo "
      <div class='feed-container'>

   

    <!-- MAIN FEED -->
    <div class='feed-wrapper' >

        <!-- Example Post -->
        <div class='post-card'>

            <!-- Post Header -->
            <div class='post-user'>
                <img src='uploads/$pimg' class='user-img'>
                <div>
                    <h4 class='user-name'>$fname</h4>
                   <p class='post-time fade-switch' id='postTime_$pid'>$ptime</p>

<p class='post-time fade-switch fade-hidden' id='postLocation_$pid'>$location</p>

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
";


     }
   }
  
}

?>

</main>

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
    const res = await fetch('get_comment.php?post_id=' + postId);
    const html = await res.text();
    const box = document.getElementById('commentBox_' + postId);
    
    if(box){
        box.innerHTML = html;
        updateCommentCountUI(postId); // <-- FIX
    }

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


document.addEventListener("DOMContentLoaded", function () {
    // Find all posts
    document.querySelectorAll("[id^='postTime_']").forEach(el => {
        const pid = el.id.split("_")[1];
        startPostTimeToggle(pid);
    });
});


function startPostTimeToggle(pid) {
    let timeEl = document.getElementById("postTime_" + pid);
    let locEl  = document.getElementById("postLocation_" + pid);

    if (!timeEl || !locEl) return;

    // Start with location hidden
    locEl.classList.add("fade-hidden");

    setInterval(() => {
        timeEl.classList.toggle("fade-hidden");
        locEl.classList.toggle("fade-hidden");
    }, 2000);
}

</script>


<?php
include("footer.php");
}else{
    include("index.php");
}
?>
