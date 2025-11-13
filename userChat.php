<?php 
@session_start();
if (isset($_SESSION["uname"]) && $_SESSION["utype"] == 'user') {
    include("header.php");
    include("connectdb.php");

    $uname = $_SESSION["uname"];

    // When user clicks a contact (from contact list)
    $usname = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;
   

    // If a new chat is being initiated
    if ($usname && $usname !== $uname) {

        // ✅ Create a shared unique key for both users
        $chatKey = md5(min($uname, $usname) . "_" . max($uname, $usname));

        // ✅ Check if a chat already exists (in either direction)
        $checkExists = mysqli_query($con, "
            SELECT * FROM chat_info 
            WHERE ckey='$chatKey' LIMIT 1
        ");

        // ✅ If no chat exists, insert one record (shared for both)
        if (mysqli_num_rows($checkExists) == 0) {
            mysqli_query($con, "
                INSERT INTO chat_info(cswitch, cdate, cuser, you, ckey) 
                VALUES (1, NOW(), '$uname', '$usname', '$chatKey')
            ");
        }

        // ✅ Now fetch or reuse that chat
        $res = mysqli_query($con, "SELECT * FROM chat_info WHERE ckey='$chatKey' LIMIT 1");
        $chatData = mysqli_fetch_assoc($res);
        $_SESSION["chat_id"] = $chatData["cid"];
        $_SESSION["ckey"] = $chatData["ckey"];
        $_SESSION["chat_user"] = $usname;

        // Redirect to self (so refresh works cleanly)
        header("Location: userChat.php");
        exit();
    }

    // Load active session info
    $username = isset($_SESSION["chat_user"]) ? $_SESSION["chat_user"] : null;
    $chat_id = isset($_SESSION["chat_id"]) ? $_SESSION["chat_id"] : null;
    $ckey = isset($_SESSION["ckey"]) ? $_SESSION["ckey"] : null;

    // ✅ Fetch all contacts related to current user
    $rscheck = mysqli_query($con, "
        SELECT * FROM chat_info 
        WHERE (you='$uname' OR cuser='$uname') 
        AND cswitch=1
    ");

   
?>
<main>
<div class="chat-container">
  <!-- Sidebar -->
  <div class="sidebar">
    <h3>Contacts</h3>
    <div id="userList">
      <?php
      if (mysqli_num_rows($rscheck) > 0) {
          while ($row = mysqli_fetch_assoc($rscheck)) {
              // Figure out the partner's name
              $partner = ($row["cuser"] == $uname) ? $row["you"] : $row["cuser"];
               $_SESSION["m"]=$partner;
              $b=$_SESSION["m"];
              $a = $row["cid"];

               $q = "
  SELECT COUNT(*) AS unread_count 
  FROM message_info 
  WHERE mreceiver = '$uname' 
    AND msender = '$b' 
    AND is_read = 0
";
$res = mysqli_query($con, $q);
$row = mysqli_fetch_assoc($res);
$read = $row['unread_count'];
              
              $activeClass = ($partner == $username) ? "active-user" : "";
              echo "<div class='user $activeClass'>";
              echo "<a href='userChat.php?name=$partner' style='display:block;text-decoration:none;color:white'>$partner $read</a>";
              echo "</div>";
          }
      } else {
          echo "<p>No active chats yet.</p>";
      }
      ?>
    </div>
  </div>

  <!-- Chat Box -->
  <div class="chat-box">
    <div class="chat-header">
      <?php echo $username ? "Chat with " . htmlspecialchars($username) : "Select a contact"; ?>
      <button id="clearbtn"><i class="bi bi-trash"></i></button>
    </div>

    <div id="messages">
      
    </div>

    <?php if ($username): ?>
    <div class="input-area">
        <button id="emojiBtn" title="Emoji">😀</button>

      <input type="text" placeholder="Type a message..." id="msgInput" />
      <button id="sendBtn">➤</button>

        <div id="emojiPicker" class="emoji-picker"></div>

    </div>
    <?php endif; ?>
  </div>
</div>
</main>

<script>
function loadMessages() {
  fetch("displayMessage.php")
    .then(res => res.text())
    .then(data => {
      const messagesDiv = document.getElementById("messages");

      // ✅ Update only if content changed
      if (messagesDiv.dataset.lastHtml !== data) {
        messagesDiv.innerHTML = data;
        messagesDiv.dataset.lastHtml = data;
      }
    })
    .catch(err => console.error("Error loading messages:", err));
}

// ✅ Call every 1.5–2 sec (1000 ms is too fast)
setInterval(loadMessages, 1000);
loadMessages();


const msgInput = document.getElementById("msgInput");
const sendBtn = document.getElementById("sendBtn");

// ✅ Send message function
function sendMessage() {
  const msg = msgInput.value.trim();
  if (msg !== "") {
    fetch("insertMessage.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "message=" + encodeURIComponent(msg)
    })
    .then(() => {
      msgInput.value = "";
      loadMessages();
    });
  }
}

// ✅ Click on send button
sendBtn?.addEventListener("click", sendMessage);

// ✅ Press Enter key
msgInput?.addEventListener("keypress", function(e) {
  if (e.key === "Enter") {
    e.preventDefault(); // stop newline
    sendMessage();
  }
});


document.addEventListener("DOMContentLoaded", function() {
  const clearBtn = document.getElementById("clearbtn");
  if (clearBtn) {
    clearBtn.addEventListener("click", function() {
      if (confirm("Do you really want to clear this chat?")) {
        fetch("clearChat.php")
        .then(res => res.text())
        .then(data => {
          if (data.trim() === "cleared") {
            document.getElementById("messages").innerHTML = "";
            alert("Chat cleared for you!");
          }
        });
      }
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
