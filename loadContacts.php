<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("connectdb.php");
 $username = isset($_SESSION["chat_user"]) ? $_SESSION["chat_user"] : null;
$uname = $_SESSION["uname"];

$rscheck = mysqli_query($con, "
  SELECT
    CASE WHEN c.cuser = '$uname' THEN c.you ELSE c.cuser END AS partner,
    MAX(m.mtime) AS last_msg_time,
    c.cid, c.ckey, c.cuser, c.you
  FROM chat_info c
  LEFT JOIN message_info m ON m.mkey = c.ckey
  WHERE '$uname' IN (c.cuser, c.you)
  GROUP BY c.cid, partner, c.ckey
  ORDER BY last_msg_time DESC
");

while ($row = mysqli_fetch_assoc($rscheck)) {

    $partner = ($row["cuser"] == $uname) ? $row["you"] : $row["cuser"];

    $q = "
        SELECT COUNT(*) AS unread_count 
        FROM message_info 
        WHERE mreceiver = '$uname' 
          AND msender = '$partner' 
          AND is_read = 0
    ";
    $res = mysqli_query($con, $q);
    $r = mysqli_fetch_assoc($res);
    $unread = $r["unread_count"];
 $activeClass = ($partner == $username) ? "active-user" : "";
    echo "<div class='user $activeClass' onclick='openChat();'>
            <a href='userChat.php?name=$partner' 
               style='display:block;text-decoration:none;color:white' onclick='openChat();'>$partner</a>";

    if ($unread > 0) {
        echo "<span class='badge'>$unread</span>";
    }

    echo "</div>";
}
?>

<script>
        

    function loadContacts() {
    fetch("loadContacts.php")
        .then(res => res.text())
        .then(data => {
            const list = document.getElementById("userList");

            if (list.dataset.last !== data) {
                list.innerHTML = data;
                list.dataset.last = data;
            }
             if (sidebarLocked) {
                document.querySelector(".sidebar").classList.add("hidden");
            }
        });
}

setInterval(loadContacts, 1000);
loadContacts();

</script>