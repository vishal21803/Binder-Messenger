<?php 
@session_start();
if (isset($_SESSION["uname"]) && $_SESSION["utype"] == 'user') {
    include("header.php");
    include("connectdb.php");

    $uname = $_SESSION["uname"];

    // Optional parameters check
    $username = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;

    // Insert new chat only if username is provided and not duplicate
    if ($username) {
        $checkExists = mysqli_query($con, "SELECT * FROM chat_info WHERE cuser='$username' AND you='$uname'");
        if (mysqli_num_rows($checkExists) == 0) {
            mysqli_query($con, "INSERT INTO chat_info(cswitch, cdate, cuser, you) VALUES (1, NOW(), '$username', '$uname')");
        }
    }

    // Fetch chat contacts
    $rscheck = mysqli_query($con, "SELECT DISTINCT cuser FROM chat_info WHERE you='$uname' AND cswitch=1");
?>

<main>
<div class="chat-container">
  <div class="sidebar">
    <h3>Contacts</h3>
    <div id="userList">
      <?php
      if (mysqli_num_rows($rscheck) > 0) {
          while ($row = mysqli_fetch_assoc($rscheck)) {
              echo "<div class='user' onclick=\"window.location.href='chat.php?username=".$row['cuser']."'\">";
              echo htmlspecialchars($row["cuser"]);
              echo "</div>";
          }
      } else {
          echo "<p>No active chats yet.</p>";
      }
      ?>
    </div>
  </div>

  <div class="chat-box">
    <div class="chat-header">
      <?php echo $username ? "Chat with " . htmlspecialchars($username) : "Select a contact"; ?>
    </div>

    <div id="messages">
      <?php
      // Example static messages — later connect this to message table
      echo "<div class='message left'>Hey! How are you?<small>10:20 AM</small></div>";
      echo "<div class='message right'>I'm fine, what about you?<small>10:22 AM</small></div>";
      ?>
    </div>

    <div class="input-area">
      <input type="text" placeholder="Type a message..." id="msgInput" />
      <button id="sendBtn">➤</button>
    </div>
  </div>
</div>
</main>

<?php
    include("footer.php");
} else {
    include("index.php");
}
?>
