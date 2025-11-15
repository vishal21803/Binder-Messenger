<?php
session_start();
if (!isset($_SESSION["uname"])) {
    echo "NO_LOGIN";
    exit;
}

$uname = $_SESSION["uname"];

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (!isset($_FILES["file"])) {
    echo "NO_FILE";
    exit;
}

$file = $_FILES["file"];
$ext = pathinfo($file["name"], PATHINFO_EXTENSION);
$newName = time() . "_" . rand(1000,9999) . "." . $ext;
$targetFile = $targetDir . $newName;

if (move_uploaded_file($file["tmp_name"], $targetFile)) {
    echo $targetFile; // JS will receive this path
} else {
    echo "ERROR";
}
?>
