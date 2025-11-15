<?php
session_start();
include "connectdb.php";

$uname   = $_SESSION["uname"];
$partner = $_SESSION["chat_user"];

/* Check existing block */
$check = mysqli_query($con,
    "SELECT * FROM blocked_info
     WHERE blocker='$uname' AND blocked='$partner'"
);

/* If already blocked → Unblock */
if (mysqli_num_rows($check) > 0) {
    mysqli_query($con,
        "DELETE FROM blocked_info
         WHERE blocker='$uname' AND blocked='$partner'"
    );

    echo "unblocked";
}
/* Otherwise block */
else {
    mysqli_query($con,
        "INSERT INTO blocked_info(blocker, blocked, bdate)
         VALUES('$uname', '$partner', NOW())"
    );

    echo "blocked";
}
?>
