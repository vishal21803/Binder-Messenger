<?php
include("connectdb.php"); // your DB connection

if(!isset($_POST['type']) || !isset($_POST['value'])) {
    echo "invalid";
    exit;
}

$type = $_POST['type'];
$value = trim($_POST['value']);

if ($type == "username") {
    $query = $con->prepare("SELECT uid FROM user_info WHERE uname = ?");
    $query->bind_param("s", $value);
    $query->execute();
    $query->store_result();

    echo ($query->num_rows > 0) ? "used" : "available";
    exit;
}

if ($type == "email") {
    $query = $con->prepare("SELECT uid FROM user_info WHERE uemail = ?");
    $query->bind_param("s", $value);
    $query->execute();
    $query->store_result();

    echo ($query->num_rows > 0) ? "used" : "available";
    exit;
}

echo "invalid";
?>
