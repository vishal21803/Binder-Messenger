<?php
@session_start();
include("connectdb.php");

/* ============================================================
   SAFETY CHECK → USER MUST HAVE SELECTED A CHAT
============================================================ */
if (
    !isset($_SESSION["chat_id"]) ||
    !isset($_SESSION["ckey"]) ||
    !isset($_SESSION["uname"]) ||
    !isset($_SESSION["chat_user"])
) {
    exit;
}

$chat_id = $_SESSION["chat_id"];
$user    = $_SESSION["uname"];   // logged user
$partner = $_SESSION["chat_user"];
$key     = $_SESSION["ckey"];

/* ============================================================
   LOAD ALL MESSAGES
============================================================ */
$rschat = mysqli_query($con, "
    SELECT * FROM message_info
    WHERE mkey='$key'
    AND (
        (msender='$user' AND clear_by_sender=0) OR
        (mreceiver='$user' AND clear_by_receiver=0)
    )
    ORDER BY mtime
");

/* ============================================================
   PRINT ALL MESSAGES
============================================================ */
while ($row = mysqli_fetch_assoc($rschat)) {

    $mid  = $row["mid"];
    $msg  =($row["message"]);
    $file = $row["file"];
    $time = date("h:i A", strtotime($row["mtime"]));

    $isMe = ($row["msender"] == $user);

    echo "<div class='message ".($isMe ? "right" : "left")."'>";

    echo "<div class='msg-wrapper'>";

    /* ------------------------------
       TEXT MESSAGE
    --------------------------------*/
   if (!empty($msg)) {
    echo "<div class='msg-content'>$msg</div>";
}

    /* ------------------------------
       FILE MESSAGE
    --------------------------------*/
  /* ------------------------------
   FILE MESSAGE (IMAGE / GIF / STICKER / VIDEO / AUDIO / DOC)
--------------------------------*/
if (!empty($file)) {

    // check extension
    $ext = strtolower(pathinfo(parse_url($file, PHP_URL_PATH), PATHINFO_EXTENSION));

    // IMAGE / GIF / WEBP / Sticker URL
    if (in_array($ext, ["jpg","jpeg","png","gif","webp"])) {
        echo "<img src='$file' class='chat-img'>";
    }

    // VIDEO
    elseif (in_array($ext, ["mp4","webm","mkv"])) {
        echo "<video controls class='chat-video'><source src='$file'></video>";
    }

    // AUDIO
    elseif (in_array($ext, ["mp3","wav","aac","m4a"])) {
        echo "<audio controls class='chat-audio'><source src='$file'></audio>";
    }

    // OTHER FILES → download
    else {
        echo "<a href='$file' download class='file-download'>📄 Download File</a>";
    }
}



    /* ------------------------------
       3-dot menu
    --------------------------------*/
    echo "<div class='menu-container'>
            <button class='menu-btn'>⋮</button>
            <div class='dropdown-menu'>";
    
    if ($isMe) {
        echo "<a class='delmsg' data-mid='$mid'>Delete for Everyone</a>
              <a class='demsg' data-mid='$mid'>Delete for Me</a>";
    } else {
        echo "<a class='delrec' data-mid='$mid'>Delete</a>";
    }

    echo "<a href='#' class='copymsg' data-message=\"$msg\">Copy</a>
        </div>
    </div>";

    echo "</div>"; // msg-wrapper
    echo "<small class='time'>$time</small>";
    echo "</div>"; // message
}

/* ============================================================
   SHOW SEEN / SENT ONLY FOR LAST MESSAGE YOU SENT
============================================================ */

// get last message
$lastMsgQ = mysqli_query($con, "
    SELECT mid, msender, mreceiver, is_read, read_time, mtime
    FROM message_info
    WHERE mkey='$key'
    ORDER BY mtime DESC
    LIMIT 1
");

$lastMsg = mysqli_fetch_assoc($lastMsgQ);

// no messages
if (!$lastMsg) exit;

// if the last message is NOT yours → no seen/sent needed
if ($lastMsg["msender"] !== $user) exit;

/* ------------------------------------------------------------
   If message is READ
------------------------------------------------------------ */
if ($lastMsg["is_read"] == 1) {
    echo "<span id='seenIndicator' 
            data-readtime='{$lastMsg["read_time"]}'></span>";
}
/* ------------------------------------------------------------
   If message is just SENT
------------------------------------------------------------ */
else {
    echo "<span id='seenIndicator'
            data-senttime='{$lastMsg["mtime"]}'></span>";
}

?>
