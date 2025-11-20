
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

    xml.onreadystatechange = function () {
        if (xml.readyState === 4 && xml.status === 200) {
            const parent = document.getElementById("parent");

            // Only replace if content is different
            if (parent.dataset.lastHtml !== xml.responseText) {
                parent.innerHTML = xml.responseText;
                parent.dataset.lastHtml = xml.responseText;
            }
        }
    };

    xml.open("GET", "displayRequest.php", true);
    xml.send();
}

loadRequests();
setInterval(loadRequests, 2000); // 2 sec

function acceptRequest(rid) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {

            // Remove that request box from UI
            let box = document.getElementById("req-" + rid);
            if (box) box.remove();
        }
    };

    xhr.open("GET", "updateRequest.php?rid=" + rid, true);
    xhr.send();
}


</script>


<?php
include("footer.php");
}else{
    include("index.php");
}
?>
