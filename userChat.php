<?php 
@session_start();
if (isset($_SESSION["uname"]) && $_SESSION["utype"] == 'user') {
    include("header.php");
    include("connectdb.php");

    $uname = $_SESSION["uname"];

    // Optional parameters check
    // $username = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;

    $username = isset($_SESSION["chat_user"]) ? $_SESSION["chat_user"] : null;
    $chat_id = isset($_SESSION["chat_id"]) ? $_SESSION["chat_id"] : null;
    $ckey = isset($_SESSION["ckey"]) ? $_SESSION["ckey"] : null;

    // Insert new chat only if username is provided and not duplicate
    if ($username) {
      $chatKey = md5(min($uname, $username) . "_" . max($uname, $username));
        $checkExists = mysqli_query($con, "SELECT * FROM chat_info WHERE cuser='$username' AND you='$uname' ");
        if (mysqli_num_rows($checkExists) == 0) {
            mysqli_query($con, "INSERT INTO chat_info(cswitch, cdate, cuser, you,ckey) VALUES (1, NOW(), '$username', '$uname','$chatKey')");
        }
    }

    // Fetch chat contacts
    $rscheck = mysqli_query($con, "SELECT  * FROM chat_info WHERE you='$uname' AND cswitch=1");
?>

<main>
<div class="chat-container">
  <div class="sidebar">
    <h3>Contacts</h3>
    <div id="userList">
      <?php
      if (mysqli_num_rows($rscheck) > 0) {
          while ($row = mysqli_fetch_assoc($rscheck)) {
            $a=$row["cid"];
            $partner = ($row["cuser"] == $uname) ? $row["you"] : $row["cuser"];
              $activeClass = ($partner == $username) ? "active-user" : "";
              echo "<div class='user $activeClass'>";
              echo ("<a href='insertSession.php?chat=$a' style='display:block;text-decoration:none;color:white'>".$row["cuser"]."</a>");
              echo "</div>";
          }
      } else {
          echo "<p>No active chats yet.</p>";
      }
      ?>
    </div>
  </div>

  <div class="chat-box">
    <div class="chat-header" >
      <?php echo $username ? "Chat with " . htmlspecialchars($username) : "Select a contact"; ?>
    </div>

    <div id="messages">
      <?php
      // Example static messages — later connect this to message table
      // echo "<div class='message left'>Hey! How are you?<small>10:20 AM</small></div>";
      // echo "<div class='message right'>I'm fine, what about you?<small>10:22 AM</small></div>";
      ?>
    </div>

    <div class="input-area">
      <input type="text" placeholder="Type a message..." id="msgInput" />
      <button id="sendBtn">➤</button>
    </div>
  </div>
</div>
</main>




<script>
  function loadMessages() {
  fetch("displayMessage.php")
    .then(res => res.text())
    .then(data => {
      const messagesDiv = document.getElementById("messages");
      messagesDiv.innerHTML = data;
    
    });
}
setInterval(loadMessages, 1000); // every 2 sec
loadMessages();
  
document.getElementById("sendBtn").addEventListener("click",function () {
    const msg=document.getElementById("msgInput").value.trim();
    if(msg !== ""){
      fetch("insertMessage.php",{
        method:"POST",
        headers:{"Content-Type": "application/x-www-form-urlencoded"},
        body:"message=" + encodeURIComponent(msg)
      }).then(()=>{
        document.getElementById("msgInput").value= "";
        loadMessages();
      });
    }
  });
</script>
<?php
    include("footer.php");
} else {
    include("index.php");
}
?>
