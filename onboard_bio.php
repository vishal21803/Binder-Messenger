
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main class="bio-container">
<?php @session_start(); ?>

    <form action="save_bio.php" method="post" class="bio-box">
        <h2>Your Bio ✍️</h2>
        <p class="bio-sub">Tell something about yourself (max 150 characters)</p>

        <textarea name="bio" maxlength="150"  placeholder="Write your bio..."></textarea>

        <button type="submit">Next ➜</button>
    </form>

</main>




<?php
include("footer.php");
}else{
    include("index.php");
}
?>
