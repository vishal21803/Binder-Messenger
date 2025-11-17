
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>
<?php @session_start(); ?>

<form action="save_bio.php" method="post">
    <h2>Your Bio</h2>
    <textarea name="bio" maxlength="150"></textarea>
    <button type="submit">Next</button>
</form>

</main>




<?php
include("footer.php");
}else{
    include("index.php");
}
?>
