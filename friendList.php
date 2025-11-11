
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

function loadRequests() {
    var xml = new XMLHttpRequest();

    xml.onreadystatechange = function(){
      if(xml.readyState == 4 && xml.status == 200){
        document.getElementById("parent").innerHTML = xml.responseText;
      }
    }

    xml.open("GET", "displayFriends.php", true);
    xml.send();
}
loadRequests();
setInterval(loadRequests, 1000); // refresh every 5 seconds


</script>


<?php
include("footer.php");
}else{
    include("index.php");
}
?>
