
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>
<?php @session_start(); ?>

<form action="save_dp.php" method="post" enctype="multipart/form-data">
    <h2>Upload Profile Photo</h2>
    <input type="file" name="dp" accept="image/*" >
    <button type="submit">Finish</button>
</form>

</main>




<?php
include("footer.php");
}else{
    include("index.php");
}
?>
