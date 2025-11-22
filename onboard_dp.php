
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>
<main class="dp-container">
<?php @session_start(); ?>

    <form action="save_website.php" method="post" class="dp-box">
        
        <h2>Add Your Website 🌐</h2>
        <p class="dp-sub">Share your personal or portfolio website</p>

        <!-- Website Input -->
        <div class="input-group">
            <label>Your Website URL</label>
            <input type="url" name="website" placeholder="https://yourwebsite.com" >
        </div>

        <button type="submit">Finish ✔</button>
    </form>

</main>





<?php
include("footer.php");
}else{
    include("index.php");
}
?>
