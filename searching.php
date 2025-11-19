<?php
@session_start();
$uname = $_SESSION["uname"];

include("connectdb.php");

$search = $_GET["sd"];

// Search users except current user
$rscheck = mysqli_query($con,
    "SELECT * FROM user_info 
     WHERE uname LIKE '%$search%'
     AND uname NOT IN('$uname')"
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Users</title>
    <link rel="stylesheet" href="search.css"> <!-- External CSS file -->
</head>

<body>

<div class="search-container">

<?php
if (mysqli_num_rows($rscheck) > 0) {

    while ($row = mysqli_fetch_array($rscheck)) {

        $name = $row["uname"];
        $age  = $row["uage"];
        $id   = $row["uid"];
        $dp   = $row["ufile"];

        // check follow/request status
        $reqCheck = mysqli_query($con,
            "SELECT * FROM request_info 
             WHERE remail='$uname' 
             AND uid='$id'"
        );

        echo "
        <div class='user-card'>
            <div class='user-info'>
                <div class='avatar'>
                 <img src='uploads/$dp'>
                </div>

                <div class='details'>
                    <h3>$name</h3>
                    <p>Age: $age</p>
                </div>
            </div>

            <div class='actions'>
        ";

        if (mysqli_num_rows($reqCheck) > 0) {

            $r = mysqli_fetch_array($reqCheck);

            if ($r["rstatus"] === "requested") {
                echo "<button class='status-btn reqBtn' data-uid='$id'>Requested</button>";
            } 
            elseif ($r["rstatus"] === "accepted") {
                echo "<button class='status-btn followingBtn' data-uid='$id'>Following</button>";
            }

        } else {
            echo "<button class='main-btn followBtn' data-uid='$id'>Follow</button>";
        }

        echo "
                <a href='othersProfile.php?oid=$id' class='secondary-btn viewBtn' data-uid='$id' style='text-decoration:none;'>View Profile</a>
            </div>
        </div>
        ";
    }

} else {
    echo "<p class='no-results'>No results found</p>";
}
?>

</div>

</body>
</html>
