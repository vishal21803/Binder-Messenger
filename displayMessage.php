<?php
@session_start();
include("connectdb.php");

if (isset($_SESSION["chat_id"]) && isset($_SESSION["ckey"])) {
    $chat_id = $_SESSION["chat_id"];
    $user = $_SESSION["uname"];
    $key = $_SESSION["ckey"];

    $rschat = mysqli_query($con, "SELECT * FROM message_info WHERE mkey='$key' 
        AND ((clear_by_sender=0 AND msender='$user') OR (clear_by_receiver=0 AND mreceiver='$user')) 
        ORDER BY mtime");

    while ($row = mysqli_fetch_assoc($rschat)) {
        $mid=$row["mid"];
        $msg = htmlspecialchars($row["message"]);
        $time = date("h:i A", strtotime($row["mtime"]));

        if ($row["msender"] == $user) {
            // ✅ SENDER MESSAGE (right)
            echo "
            <div class='message right'>
                <div class='msg-wrapper'>
                    <div class='msg-content'>$msg</div>
                    <div class='menu-container'>
                        <button class='menu-btn'>⋮</button>
                        <div class='dropdown-menu'>
                            <a class='delmsg'  data-mid='$mid'>Delete for Everyone</a>
                            <a class='demsg' data-mid='$mid' >Delete for Me</a>
                           <a href='#' class='copymsg' data-message=\"$msg\">Copy</a>
                        </div>
                    </div>
                </div>
                <small class='time'>$time</small>
            </div>
            ";
        } else {
            // ✅ RECEIVER MESSAGE (left)
            echo "
            <div class='message left'>
                <div class='msg-wrapper'>
                    <div class='msg-content'>$msg</div>
                    <div class='menu-container'>
                        <button class='menu-btn'>⋮</button>
                        <div class='dropdown-menu'>
                            <a class='delrec'  data-mid='$mid'>Delete</a>
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



include("footer.php");
?>
