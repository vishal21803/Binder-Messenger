
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>
  <div id="parent">
     
  </div>
</main>

<script>
// ---------------- LOAD FOLLOWING ONCE ----------------
function loadFollowing() {
    let x = new XMLHttpRequest();

    x.onreadystatechange = function(){
        if(x.readyState == 4 && x.status == 200){
            document.getElementById("parent").innerHTML = x.responseText;
        }
    };

    x.open("GET", "displayFollowing.php", true);
    x.send();
}

loadFollowing();   // load once only


// ---------------- REMOVE FOLLOWING (AJAX) ----------------
function deleteFollowing(uid) {

    if(!confirm("Do you want to Unfollow?")) return;

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {

            // Remove the card instantly from UI
            let card = document.getElementById("user-" + uid);
            if (card) {
                card.style.opacity = "0";
                setTimeout(() => card.remove(), 300); // smooth fade-out
            }
        }
    };

    xhr.open("GET", "removefollowing.php?uid=" + uid, true);
    xhr.send();
}


</script>


<?php
include("footer.php");
}else{
    include("index.php");
}
?>
