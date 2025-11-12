
<?php @session_start();
if(isset($_SESSION["uname"]) && $_SESSION["utype"]=='user')
{
include("header.php");
?>

<main>
      <nav class="search-navbar py-3">
  <div class="container-fluid d-flex ">
    <form class="d-flex search-form justify-content-center" onsubmit="return false;">
      <input class="form-control me-2" type="search" id="txtSearch" placeholder="Search" aria-label="Search" onkeyup="loadData();">
      <button class="btn btn-outline-success" type="button" onclick="loadData();">Search</button>
    </form>
  </div>
</nav>

<div id="parent">
   




</div>




</main>



<script>
function loadData(){
  var x = document.getElementById("txtSearch").value;

  if(x.length >= 1){
    var xml = new XMLHttpRequest();

    xml.onreadystatechange = function(){
      if(xml.readyState == 4 && xml.status == 200){
        document.getElementById("parent").innerHTML = xml.responseText;
      }
    }

    xml.open("GET", "searching.php?sd=" + x, true);
    xml.send();
  }
  else{
    document.getElementById("searchResult").innerHTML = "No results Found";
  }
}



$(document).on("click", ".followBtn", function() {
    var btn = $(this);
    var uid = btn.data("uid");

    $.ajax({
        url: "insertRequest.php",
        type: "POST",
        data: { uid: uid },
        success: function(response) {
            if (response.trim() === "requested") {
                btn.text("Requested");
                btn.prop("disabled", true);
            } else if (response.trim() === "exists") {
                btn.text("Following");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});


$(document).on("click", ".followingBtn", function() {
    var btn = $(this);
    var uid = btn.data("uid");

    $.ajax({
        url: "unfollow.php",
        type: "POST",
        data: { uid: uid },
        success: function(response) {
            if (response.trim() === "deleted") {
                btn.text("Follow");
                btn.prop("disabled", false);
                btn.removeClass("followingBtn").addClass("followBtn");

            } else if (response.trim() === "notexists") {
                btn.text("requested");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});



$(document).on("click", ".reqBtn", function() {
    var btn = $(this);
    var uid = btn.data("uid");

    $.ajax({
        url: "reqBack.php",
        type: "POST",
        data: { uid: uid },
        success: function(response) {
            if (response.trim() === "reqback") {
                btn.text("Follow");
                btn.prop("disabled", false);
                btn.removeClass("reqBtn").addClass("followBtn");

            } else if (response.trim() === "notexists") {
                btn.text("requested");
                btn.prop("disabled", true);
            } else {
                btn.text("Error");
            }
        }
    });
});
</script>

<?php
include("footer.php");
}else{
    include("index.php");
}
?>
