
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<header>

<?php @session_start();
$uname=$_SESSION["uname"];
$uid=$_SESSION["uid"];


include("connectdb.php");
$info=mysqli_query($con,"select * from user_info where uname='$uname'");
while($row=mysqli_fetch_array($info)){
    $fullname=$row["ufull"];
    $email=$row["uemail"];
    $dp=$row["ufile"];
    $website=$row["uwebsite"];
    $bio=$row["ubio"];
    $age=$row["uage"];
}

$rscheck=mysqli_query($con,"select  * from request_info r,user_info u where r.rstatus='accepted' && r.remail='$uname' && u.uid=r.uid  ");

//following
$count=0;
if(mysqli_num_rows($rscheck)>0){
    while($row=mysqli_fetch_array($rscheck)){
         $count++ ;
        
    }
}


//followers
$count2=0;
$rscheck2 = "
    SELECT *
    FROM request_info r
    ,user_info u where u.uname = r.remail
    AND r.rstatus = 'accepted'
      AND r.uid = '$uid'
";
$followers = mysqli_query($con, $rscheck2);

if (mysqli_num_rows($followers) > 0) {
    
    while ($row = mysqli_fetch_assoc($followers)) {
    $count2++;

    }
}


?>
	<div class="container">

		<div class="profile">

			<div class="profile-image">

				<img src="<?php echo("uploads/$dp")?>" alt="">

			</div>

			<div class="profile-user-settings">

				<h1 class="profile-user-name"><?php echo("$uname");?></h1>

				<a class="btn profile-edit-btn" href="editProfile.php">Edit Profile</a>


			</div>

			<div class="profile-stats">

				<ul>
					<li><span class="profile-stat-count">164</span> posts</li>
					<li><span class="profile-stat-count"><?php echo("$count");?></span> followers</li>
					<li><span class="profile-stat-count"><?php echo("$count2");?></span> following</li>
				</ul>

			</div>

			<div class="profile-bio">

				<p><span class="profile-real-name"><?php echo("$fullname");?></span> <?php echo("$bio");?></p>

			</div>

		</div>
		<!-- End of profile section -->

	</div>
	<!-- End of container -->

</header>

<main>

	<div class="container">

		<div class="gallery">

			<?php
$rspost=mysqli_query($con,"select * from post_info where uid='$uid' order by post_time desc");

while($row=mysqli_fetch_array($rspost)){
    $postimg=$row["post_img"];
	$caption=$row["caption"];
	$pid=$row["pid"];
    
	

// count likes
$likes = mysqli_num_rows(mysqli_query($con, "SELECT * FROM post_likes WHERE post_id='$pid' "));

// user liked?
$userLiked = mysqli_num_rows(mysqli_query($con, "
   SELECT * FROM post_likes WHERE uid='$uid' AND post_id='$pid' "
)) > 0;

    $likeIcon = $userLiked ? "❤️" : "🤍";

echo "
                <a data-bs-toggle='modal' data-bs-target='#myModal'>

	<div class='gallery-item' tabindex='0'>
				<img src='uploads/$postimg' class='gallery-image' alt='' >
               
				<div class='gallery-item-info'>

					<ul>
						<li class='gallery-item-likes'><span class='visually-hidden'>Likes:</span><i class='fas fa-heart' aria-hidden='true'></i> 89</li>
						<li class='gallery-item-comments'><span class='visually-hidden'>Comments:</span><i class='fas fa-comment' aria-hidden='true'></i> 5</li>
					</ul>

				</div>

			</div>

			</a>




			
<!-- The Modal -->
<div class='modal fade' id='myModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>

      

      <!-- Modal body -->
      <div class='modal-body'>
        <div class='feed-container'>

    <!-- POST TEMPLATE -->
    <div class='post-card'>

        <div class='post-header'>
            <img src='uploads/$dp'>
            <div>
                <div class='post-username'>$uname</div>
                <div class='post-time'>2 hours ago</div>
            </div>
        </div>

        <img src='uploads/$postimg' class='post-img'>

        <div class='post-body'>

            <div class='post-actions'>
              <button><i class='bi bi-heart'></i></button>
                              <button><i class='bi bi-chat-left-dots'></i></button>

                
            </div>

            <div class='likes'> $likes Likes</div>

            <div class='caption'><b></b>  $caption</div>

            <button class='delete-btn' onclick='deletePost(this)'>Delete Post</button>

            <div class='comment-list' id='commentBox'>
			

                <span class='like-btn' id='like_$pid' onclick='likePost($pid)'>
             $likeIcon
                </span>
            </div>

            <div class='comment-input'>
                <input type='text' id='commentText' placeholder='Add a comment...'>
                <button onclick='addComment()'>Post</button>
            </div>

        </div>

    </div>
</div>
      </div>

      <!-- Modal footer -->
      <div class='modal-footer'>
        <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
      </div>

    </div>
  </div>
</div>

			";
}
?>

			

		

		
		<!-- End of gallery -->


	</div>
	<!-- End of container -->








	
</main>


<script>
	function likePost(postId, btn) {
    fetch("like.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "post_id=" + postId
    })
    .then(res => res.text())
    .then(data => {
        if (data === "liked") {
            btn.innerHTML = "❤️";
        } else {
            btn.innerHTML = "🤍";
        }
    });
}

</script>

<?php
include("footer.php");
}else{
    include("index.php");
}
?>
