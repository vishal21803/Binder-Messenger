<?php
session_start();
include("connectdb.php");

// USER ID
$uid = $_SESSION['uid'];

// ---------- 1. GET TEXT FIELDS ----------
$caption  = mysqli_real_escape_string($con, $_POST['caption']);
$tags     = mysqli_real_escape_string($con, $_POST['tags']);
$location = mysqli_real_escape_string($con, $_POST['location']);
$filter   = mysqli_real_escape_string($con, $_POST['filter']);

// ---------- 2. GET FILTERED BASE64 IMAGE ----------
$editedImage = $_POST["editedImage"];  // from hidden input
$uploadedFileName = "";


// ---------- 3. IF FILTERED IMAGE EXISTS → SAVE IT ----------
if (!empty($editedImage)) {

    // Remove prefix
    $editedImage = str_replace("data:image/png;base64,", "", $editedImage);
    $editedImage = str_replace(" ", "+", $editedImage);

    // Decode
    $imageData = base64_decode($editedImage);

    // File name
    $uploadedFileName = "post_" . $uid . "_" . time() . ".png";

    // Save final edited photo
    file_put_contents("uploads/" . $uploadedFileName, $imageData);

}

// ---------- 4. ELSE FALLBACK → NORMAL FILE UPLOAD ----------
else if (isset($_FILES['postFile']) && $_FILES['postFile']['error'] === 0) {

    $ext = strtolower(pathinfo($_FILES['postFile']['name'], PATHINFO_EXTENSION));

    $uploadedFileName = "post_" . $uid . "_" . time() . "." . $ext;

    $uploadPath = "uploads/" . $uploadedFileName;

    move_uploaded_file($_FILES["postFile"]["tmp_name"], $uploadPath);
}


// ---------- 5. SAVE DATA TO DATABASE ----------
$query = "INSERT INTO post_info(uid, caption, tags, location, filter_used, post_img, post_time)
          VALUES ('$uid', '$caption', '$tags', '$location', '$filter', '$uploadedFileName', NOW())";

mysqli_query($con, $query);


// ---------- 6. REDIRECT ----------
header("Location: userChat.php");
exit;
?>
