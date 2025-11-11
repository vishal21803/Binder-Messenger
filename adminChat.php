
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='admin')
{
include("header.php");
?>

<main>

</main>




<?php
include("footer.php");
}else{
include("index.php");
}
?>
