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
				<a class=" profile-edit-btn" href="editProfile.php" title="Edit Profile"><i class="bi bi-gear"></i></a>

            <a class=" btn btn-primary"  data-bs-toggle="modal" data-bs-target="#myPost">Add Post</a>

            <!-- The Modal -->
<div class="modal fade" id="myPost">
  <div class="modal-dialog">
    <div class="modal-content">

     

      <!-- Modal body -->
      <div class="modal-body">
         <form id="postform" class="addpost-container" action="uploadPost.php" method="POST" enctype="multipart/form-data">
  
  <div class="addpost-card">

    <h2 class="heading">Create New Post</h2>

    <!-- Image Upload Section -->
    <div class="upload-box" id="uploadBox">
      <input type="file" id="postFile" name="postFile" accept="image/*" required>
      <div class="upload-hint">Click to upload image</div>
      <img id="previewImg" class="preview-img" />
    </div>

    <!-- Caption -->
    <div class="field">
      <label>Caption</label>
      <textarea id="caption" name="caption" placeholder="Write a caption..." required></textarea>
    </div>

    <!-- Tags -->
    <div class="field">
      <label>Tags</label>
      <input type="text" id="tags" name="tags" placeholder="#travel #friends #sunset" />
    </div>

    <!-- Location -->
    <div class="field">
      <label>Location</label>
      <input type="text" id="location" name="location" placeholder="Add location" />
    </div>

    <!-- Filters -->
    <input type="hidden" name="filter" id="selectedFilter" value="none">

    <div class="filter-section">
      <h3>Filters</h3>
      <div class="filter-options" id="filters">
        <!-- BASIC FILTERS -->
    <div class="filter-item " data-filter="none">Original</div>
    <div class="filter-item" data-filter="grayscale(1)">B&W</div>
    <div class="filter-item" data-filter="sepia(0.8)">Sepia</div>
    <div class="filter-item" data-filter="invert(1)">Invert</div>

    <!-- BRIGHTNESS / CONTRAST -->
    <div class="filter-item" data-filter="brightness(1.2)">Bright</div>
    <div class="filter-item" data-filter="brightness(0.8)">Dim</div>
    <div class="filter-item" data-filter="contrast(1.3)">Contrast+</div>
    <div class="filter-item" data-filter="contrast(0.8)">Contrast-</div>

    <!-- COLOR POP / VIBRANT -->
    <div class="filter-item" data-filter="saturate(1.5)">Vibrant</div>
    <div class="filter-item" data-filter="saturate(2)">Color Pop</div>
    <div class="filter-item" data-filter="saturate(0)">Desaturate</div>

    <!-- WARM / COOL -->
    <div class="filter-item" data-filter="sepia(0.3) brightness(1.1)">Warm</div>
    <div class="filter-item" data-filter="brightness(1.1) saturate(1.3)">Sunny</div>
    <div class="filter-item" data-filter="hue-rotate(20deg)">Warm Tone</div>
    <div class="filter-item" data-filter="hue-rotate(200deg)">Cool Tone</div>

    <!-- INSTAGRAM-INSPIRED EFFECTS -->
    <div class="filter-item" data-filter="brightness(1.1) contrast(1.2) saturate(1.2)">Clarendon</div>
    <div class="filter-item" data-filter="brightness(1.1) saturate(1.3)">Juno</div>
    <div class="filter-item" data-filter="brightness(1.2) saturate(1.1)">Lark</div>
    <div class="filter-item" data-filter="contrast(1.1) sepia(0.2)">Gingham</div>
    <div class="filter-item" data-filter="brightness(0.9) sepia(0.4)">Retro</div>

    <!-- DARK / MOODY -->
    <div class="filter-item" data-filter="brightness(0.7) contrast(1.3)">Moody</div>
    <div class="filter-item" data-filter="brightness(0.6) contrast(1.4) saturate(0.8)">Dark Fade</div>

    <!-- CARTOON / FUN -->
    <div class="filter-item" data-filter="contrast(1.5) saturate(1.8)">Cartoonify</div>
    <div class="filter-item" data-filter="grayscale(0.5) sepia(0.2)">Vintage</div>
      </div>
    </div>
<canvas id="finalCanvas" style="display:none;"></canvas>
<input type="hidden" name="editedImage" id="editedImage">

    <!-- Upload Button -->
    <button class="post-btn" type="submit" id="submitPostBtn">Post</button>

  </div>

</form>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
			</div>

			<div class="profile-stats">
				<ul>
          <br>
					<li><span class="profile-stat-count"><?php echo($pcount);?></span> posts</li>
					<li><a style="text-decoration:none; color:#322627;" href="userFollower.php"><span class="profile-stat-count"><?php echo $count2 ?></span> followers</a></li>
					<li><a style="text-decoration:none; color:#322627;" href="userFollowing.php"><span class="profile-stat-count"><?php echo $count ?></span> following</a></li>
				</ul>
			</div>

			<div class="profile-bio">
				<p><span class="profile-real-name"><?php echo $fullname ?></span> 
        <br>
        <?php echo $bio ?></p>
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

include("connectdb.php");
date_default_timezone_set('Asia/Kolkata'); // or 'Asia/Kolkata' etc.

if(isset($_SESSION["uname"]) && isset($_SESSION["uid"]) ){
  $uname=$_SESSION["uname"];
  $uid=$_SESSION["uid"];
 
     $rs2=mysqli_query($con,"select * from post_info p,user_info u where p.uid='$uid' and u.uid='$uid' order by post_time desc");

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

// === ELEMENTS ===
const fileInput = document.getElementById("postFile");
const uploadBox = document.getElementById("uploadBox");
const previewImg = document.getElementById("previewImg");
const filterItems = document.querySelectorAll(".filter-item");
const selectedFilter = document.getElementById("selectedFilter");
const postForm = document.getElementById("postform");

// CANVAS ELEMENTS
const finalCanvas = document.getElementById("finalCanvas");
const editedImageInput = document.getElementById("editedImage");

// === CLICK TO OPEN FILE PICKER ===
uploadBox.addEventListener("click", () => {
    fileInput.click();
});

// === PREVIEW IMAGE ===
fileInput.addEventListener("change", () => {
    if (fileInput.files && fileInput.files[0]) {
        let reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.style.display = "block";
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
});

// === FILTER SELECTION ===
filterItems.forEach(item => {
    item.addEventListener("click", function () {

        // remove active class
        filterItems.forEach(f => f.classList.remove("active"));
        this.classList.add("active");

        // apply filter to preview image
        const filterValue = this.getAttribute("data-filter");
        previewImg.style.filter = filterValue;

        // save for PHP
        selectedFilter.value = filterValue;
    });
});

// === BEFORE FORM SUBMIT -> MAKE FILTER PERMANENT ===
postForm.addEventListener("submit", function (e) {
    e.preventDefault(); // stop for processing

    if (!fileInput.files[0]) {
        alert("Please select an image");
        return;
    }

    const filter = selectedFilter.value;
    const img = previewImg;
    const canvas = finalCanvas;
    const ctx = canvas.getContext("2d");

    // set canvas size
    canvas.width = img.naturalWidth;
    canvas.height = img.naturalHeight;

    // apply filter permanently
    ctx.filter = filter;
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

    // convert canvas → base64
    const finalImgURL = canvas.toDataURL("image/png");
    editedImageInput.value = finalImgURL;

    this.submit(); // submit to PHP now
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
