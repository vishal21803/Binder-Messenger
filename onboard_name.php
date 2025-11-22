
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main class="name-container">
    <form action="save_name.php" method="post" class="name-box">
        <h2>Welcome 👋</h2>
        <p class="sub-text">Please enter your full name to continue</p>

        <input type="text" name="fullname" required placeholder="Enter full name">

        <button type="submit">Next ➜</button>
    </form>
</main>



<?php
include("footer.php");
}else{
    include("index.php");
}
?>
