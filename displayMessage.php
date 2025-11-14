<?php
@session_start();
include("connectdb.php");





if (isset($_SESSION["chat_id"]) && isset($_SESSION["ckey"])) {

    $chat_id = $_SESSION["chat_id"];
    $user = $_SESSION["uname"];          // YOU
    $partner = $_SESSION["chat_user"];   // OTHER USER
    $key = $_SESSION["ckey"];

    // ---------------------------------------------
    // STEP 1: GET THE LAST MESSAGE YOU SENT THAT WAS READ
    // ---------------------------------------------
   


    // ---------------------------------------------
    // STEP 2: LOAD ALL MESSAGES
    // ---------------------------------------------
    $rschat = mysqli_query($con, "
        SELECT * FROM message_info
        WHERE mkey='$key'
        AND ((clear_by_sender=0 AND msender='$user')
            OR (clear_by_receiver=0 AND mreceiver='$user'))
        ORDER BY mtime
    ");

    while ($row = mysqli_fetch_assoc($rschat)) {
        $mid = $row["mid"];
        $msg = htmlspecialchars($row["message"]);
        $time = date("h:i A", strtotime($row["mtime"]));

        // $sentTime = timeAgo($row["mtime"]);

        // ---------------------------
        // SENDER MESSAGE (RIGHT SIDE)
        // ---------------------------
        if ($row["msender"] == $user) {

            echo "
            <div class='message right'>
                <div class='msg-wrapper'>
                    <div class='msg-content'>$msg</div>

                    <div class='menu-container'>
                        <button class='menu-btn'>⋮</button>
                        <div class='dropdown-menu'>
                            <a class='delmsg' data-mid='$mid'>Delete for Everyone</a>
                            <a class='demsg' data-mid='$mid'>Delete for Me</a>
                            <a href='#' class='copymsg' data-message=\"$msg\">Copy</a>
                        </div>
                    </div>
                </div>
                <small class='time'>$time</small>
            ";




            echo "</div>";

        }
          
        // ---------------------------
        // RECEIVER MESSAGE (LEFT)
        // ---------------------------
        else {

            echo "
            <div class='message left'>
                <div class='msg-wrapper'>
                    <div class='msg-content'>$msg</div>

                    <div class='menu-container'>
                        <button class='menu-btn'>⋮</button>
                        <div class='dropdown-menu'>
                            <a class='delrec' data-mid='$mid'>Delete</a>
                            <a href='#' class='copymsg' data-message=\"$msg\">Copy</a>
                        </div>
                    </div>
                </div>
                <small class='time'>$time</small>
            </div>
            ";
        }
    }
}

// --- SAFE CHECK BEFORE USING SESSION ---

if (!isset($_SESSION["ckey"]) || !isset($_SESSION["uname"]) || !isset($_SESSION["chat_user"])) {
    return; // Stop execution, user hasn’t selected chat yet
}

$key     = $_SESSION["ckey"];
$user    = $_SESSION["uname"];
$partner = $_SESSION["chat_user"];
// Get last message of the chat
$lastMsgQ = mysqli_query($con, "
    SELECT mid, msender, mreceiver, is_read, read_time, mtime 
    FROM message_info
    WHERE mkey='$key'
    ORDER BY mtime DESC
    LIMIT 1
");

$lastMsg = mysqli_fetch_assoc($lastMsgQ);

// 🔥 If there are NO messages in chat → stop safely
if (!$lastMsg) {
    return; 
}

// If YOU did NOT send the last message → do not show anything
if ($lastMsg["msender"] !== $user) {
    return;
}

// YOU sent the last message → Show proper Seen/Sent status
if ($lastMsg["is_read"] == 1) {

    $seenTime = $lastMsg["read_time"];
    echo "<span id='seenIndicator' data-readtime=\"$seenTime\"></span>";

} else {

    $sentTime = $lastMsg["mtime"];
    echo "<span id='seenIndicator' data-senttime=\"$sentTime\"></span>";

}


include("footer.php");
?>

