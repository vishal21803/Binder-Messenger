<?php 
@session_start();

if (isset($_GET['clear']) && $_GET['clear'] == 1) {

    unset($_SESSION["chat_id"]);
    unset($_SESSION["ckey"]);
    unset($_SESSION["chat_user"]);
    
    // Optional: agar redirect chahiye ho
    // header("Location: yourpage.php");
    // exit;
}
if (isset($_SESSION["uname"]) && $_SESSION["utype"] == 'user') {
    include("header.php");
    include("connectdb.php");

    $uname = $_SESSION["uname"];

    // When user clicks a contact (from contact list)
    $usname = isset($_REQUEST["name"]) ? $_REQUEST["name"] : null;
   
     mysqli_query($con,"update message_info set is_read=1 ,read_time=NOW() where mreceiver = '$uname' 
    AND msender = '$usname' 
    AND is_read = 0");

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
 

if(isset($_SESSION["chat_user"])){
$partner = $_SESSION["chat_user"];    // Chat partner

$me = $uname;
$other = $partner;

// Check if I blocked the other user
$blockedByMe = mysqli_query($con,
    "SELECT * FROM blocked_info
     WHERE blocker='$me'
     AND blocked='$other'"
);

$isBlockedByMe = mysqli_num_rows($blockedByMe) > 0;

// Check if the other user blocked me
$blockedByOther = mysqli_query($con,
    "SELECT * FROM blocked_info
     WHERE blocker='$other'
     AND blocked='$me'"
);

$isBlockedByOther = mysqli_num_rows($blockedByOther) > 0;

}

if(isset($_SESSION["chat_user"])){
if ($_SESSION["chat_user"] == $partner) {
    // mark all messages as read for this chat
    mysqli_query($con,
        "UPDATE message_info SET is_read = 1 
         WHERE mreceiver='$uname' 
           AND msender='$partner'"
    );
}
}



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
              echo "<a href='#' onclick=\"openChat('$partner')\"  style='display:block;text-decoration:none;color:white'>$partner</a>";

              if($read>0){
                echo("<span class='badge'>$read</span>");
              }
             
              echo "</div>";
          }
          include("loadContacts.php");
      } else {
          echo "<p>No active chats yet.</p>";
      }
      
    
    

      ?>
    </div>
  </div>

  <!-- Chat Box -->
  <div class="chat-box">
    <div class="chat-header">
                  <span title="Chatlist" class="back-btn" onclick="showSidebar()">⬅</span>

      <?php echo $username ? "Chat with " . htmlspecialchars($username) : "Select a contact"; ?>
    <?php if ($username): ?> 
        <button title="clear chat" id="clearbtn"><i title="clear chat" class="bi bi-trash"></i></button>
        <button id="blockbtn">Block</button>
    <?php endif; ?>

      
    </div>

    <div id="typingIndicator" style="padding:5px 15px; color:#aaa; font-size:14px; display:none;">
</div>

    <div id="messages">
      
    </div>

    <?php if ($username): ?>
    <div class="input-area">
      
        <button id="emojiBtn" title="Emoji">😀</button>
<button id="gifBtn" title="send gif"><i class="bi bi-collection-play"></i></button>
<button id="stickerBtn" title="send stickers" class="icon-btn"><i class="bi bi-emoji-wink
"></i></button>


      <input type="text" placeholder="Type a message..." id="msgInput" />
      <button id="sendBtn">➤</button>
         <button id="fileBtn" title="send files"><i class="bi bi-folder"></i></button>
<input type="file" id="fileInput" style="display:none;" accept="image/*,application/pdf,video/*,audio/*">

        <div id="emojiPicker" class="emoji-picker"></div>
        <!-- GIF Search Box -->
<div id="gifBox" class="gif-box">
    <input type="text" id="gifSearch" placeholder="Search GIFs..." />
    <div id="gifResults" class="gif-results"></div>
</div>

<!-- Sticker Search Box -->
<div id="stickerBox" class="sticker-box">
    <input type="text" id="stickerSearch" placeholder="Search Stickers..." />
    <div id="stickerResults" class="sticker-results"></div>
</div>

    </div>
    <?php endif; ?>

    <?php
   
echo "<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockBtn = document.getElementById('blockbtn');
    const msgInput = document.getElementById('msgInput');
    const emojiBtn = document.getElementById('emojiBtn');
    const gifBtn = document.getElementById('gifBtn');
    const stickerBtn = document.getElementById('stickerBtn');
    const fileBtn = document.getElementById('fileBtn');


    if (!blockBtn || !msgInput) return;

";
if ($isBlockedByMe) {
    echo "
        blockBtn.textContent = 'Unblock';
        msgInput.disabled = true;
        emojiBtn.disabled = true;
        gifBtn.disabled = true;
        stickerBtn.disabled = true;
        fileBtn.disabled=true;

        msgInput.placeholder = 'You blocked this user';
    ";
}
if ($isBlockedByOther) {
    echo "
        blockBtn.style.display = 'none';
        msgInput.disabled = true;
        emojiBtn.disabled = true;
        gifBtn.disabled = true;
        stickerBtn.disabled = true;
        fileBtn.disabled=true;

        msgInput.placeholder = 'You are blocked by this user';
    ";
}
echo "});
</script>";


    ?>
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

  // ❌ prevent empty message sending
  if (msg === "") return;

  fetch("insertMessage.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "message=" + encodeURIComponent(msg)
  })
  .then(() => {
      msgInput.value = "";
      removeSeenText();
      loadMessages();
              setTimeout(scrollToBottom, 200);  // 🔥 always scroll after sending message

  });

}

function removeSeenText() {
  document.querySelectorAll(".seen-time").forEach(el => {
    el.innerText = "";   // remove seen text
  });
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

document.addEventListener("DOMContentLoaded", function() {
    const blockBtn = document.getElementById("blockbtn");
    if (!blockBtn) return;

    blockBtn.onclick = function () {
        fetch("toggleBlock.php")
            .then(res => res.text())
            .then(result => {

                if (result.trim() === "blocked") {
                    blockBtn.textContent = "Unblock";
                    msgInput.disabled = true;
                    emojiBtn.disabled = true;
                    gifBtn.disabled = true;
                    stickerBtn.disabled = true;
                    fileBtn.disabled=true;
                    msgInput.placeholder = "You blocked this user";
                } else {
                    blockBtn.textContent = "Block";
                    msgInput.disabled = false;
                    emojiBtn.disabled = false;
                    gifBtn.disabled = false;
                    stickerBtn.disabled = false;
                    fileBtn.disabled=false;
                    msgInput.placeholder = "Type a message...";
                }
            });
    };
});

let typingTimer;

// When user types
msgInput?.addEventListener("input", function () {
    sendTypingStatus("typing");

    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        sendTypingStatus("stop");
    }, 1000); // stop typing after 1 sec inactivity
});

function sendTypingStatus(state) {
    fetch("typing_update.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "status=" + (state === "typing" ? "typing" : "not_typing")
    });
}

setInterval(() => {
    fetch("typing_status.php")
        .then(res => res.text())
        .then(status => {
            const indicator = document.getElementById("typingIndicator");

            if (status === "typing") {
                indicator.style.display = "block";
                indicator.innerText = "<?php echo $username; ?> is typing...";
            } else {
                indicator.style.display = "none";
            }
        });
}, 1000);


const fileBtn = document.getElementById("fileBtn");
const fileInput = document.getElementById("fileInput");

// open file chooser
fileBtn?.addEventListener("click", () => {
    fileInput.click();
});

// handle selected file
fileInput?.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (!file) return;

    let formData = new FormData();
    formData.append("file", file);

    fetch("uploadFile.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(path => {
        if (path.startsWith("uploads/")) {
            sendFileMessage(path);
        } else {
            alert("Upload failed!");
        }
    });

    fileInput.value = ""; // reset input
});

// After upload – send message with file path
function sendFileMessage(path) {
    fetch("insertMessage.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "file=" + encodeURIComponent(path)
    })
    .then(res => res.text())
    .then(() => {
        loadMessages();
                setTimeout(scrollToBottom, 200);  // 🔥 always scroll after sending message

    });
}

function showSidebar(){
    document.querySelector(".sidebar").classList.add("show");
}

function hideSidebar(){
    document.querySelector(".sidebar").classList.remove("show");
}



</script>

<?php
    include("footer.php");
} else {
    include("index.php");
}
?>
