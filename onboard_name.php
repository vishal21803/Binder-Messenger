
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>

<form action="save_name.php" method="post">
    <h2>Welcome! Enter your full name</h2>
    <input type="text" name="fullname" required>
    <button type="submit">Next</button>
</form>
</main>




<?php
include("footer.php");
}else{
    include("index.php");
}
?>
