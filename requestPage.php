
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>
   <div id="parent" style="margin-top:20px;">
     <!-- Data will load here from displayRequest.php -->
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

    xml.open("GET", "displayRequest.php", true);
    xml.send();
}
loadRequests();
setInterval(loadRequests, 2000); // refresh every 5 seconds


</script>


<?php
include("footer.php");
}else{
    include("index.php");
}
?>
