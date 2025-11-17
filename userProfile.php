
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

			

			<div class="gallery-item" tabindex="0">

				<img src="https://images.unsplash.com/photo-1497445462247-4330a224fdb1?w=500&h=500&fit=crop" class="gallery-image" alt="">

				<div class="gallery-item-info">

					<ul>
						<li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i class="fas fa-heart" aria-hidden="true"></i> 89</li>
						<li class="gallery-item-comments"><span class="visually-hidden">Comments:</span><i class="fas fa-comment" aria-hidden="true"></i> 5</li>
					</ul>

				</div>

			</div>

			<div class="gallery-item" tabindex="0">

				<img src="https://images.unsplash.com/photo-1426604966848-d7adac402bff?w=500&h=500&fit=crop" class="gallery-image" alt="">

				<div class="gallery-item-type">

					<span class="visually-hidden">Gallery</span><i class="fas fa-clone" aria-hidden="true"></i>

				</div>

				<div class="gallery-item-info">

					<ul>
						<li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i class="fas fa-heart" aria-hidden="true"></i> 42</li>
						<li class="gallery-item-comments"><span class="visually-hidden">Comments:</span><i class="fas fa-comment" aria-hidden="true"></i> 1</li>
					</ul>

				</div>

			</div>

		
			<div class="gallery-item" tabindex="0">

				<img src="https://images.unsplash.com/photo-1505058707965-09a4469a87e4?w=500&h=500&fit=crop" class="gallery-image" alt="">

				<div class="gallery-item-info">

					<ul>
						<li class="gallery-item-likes"><span class="visually-hidden">Likes:</span><i class="fas fa-heart" aria-hidden="true"></i> 41</li>
						<li class="gallery-item-comments"><span class="visually-hidden">Comments:</span><i class="fas fa-comment" aria-hidden="true"></i> 0</li>
					</ul>

				</div>

			</div>


		</div>
		<!-- End of gallery -->


	</div>
	<!-- End of container -->

</main>



<?php
include("footer.php");
}else{
    include("index.php");
}
?>
