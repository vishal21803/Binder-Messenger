<?php @session_start();
$currentPage = basename($_SERVER['PHP_SELF']);  


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emoji-mart@latest/css/emoji-mart.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./bootstrap.min.css">
     
    <link rel="stylesheet" href="static/style.css">

    <script src="./jquery-3.7.1.min.js"></script>
</head>
<body>
 
<nav class="navbar navbar-expand-lg glass-navbar shadow-sm py-2">
  <div class="container-fluid">

    <!-- Logo + Brand -->
    <a class="navbar-brand d-flex align-items-center brand-glow" href="#">
      <img src="img/logo.png" alt="logo" class="nav-logo">
      <span class="ms-2 fw-bold">Binder</span>
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Items -->
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav ms-auto align-items-center">

      <?php
      if(isset($_SESSION['uname'])){
        $uname = $_SESSION['uname'];
        include("connectdb.php");

        $rscheck = mysqli_query($con,"select * from user_info where uname='$uname'");
        while($row = mysqli_fetch_array($rscheck)){
            $utype = $row["utype"];
        }

        $notifyMsg = mysqli_query($con,"select * from message_info where mreceiver='$uname' and is_read=0");
        $msgCount = mysqli_num_rows($notifyMsg);

        if(isset($_SESSION["uid"])){
            $uid = $_SESSION["uid"];
            $notifyReq = mysqli_query($con,"select * from request_info where uid='$uid' and rstatus='requested'");
            $reqCount = mysqli_num_rows($notifyReq);
        }

        if($utype=='admin'){
          echo("
            <li class='nav-item'>
              <a class='nav-link cool-link ".($currentPage=='admin.php'?'active-link':'')."' href=''>Admin</a>
            </li>
            <li class='nav-item'>
              <a class='nav-link cool-link' href='logout.php'>Logout</a>
            </li>
          ");
        }

        else if($utype=='user'){
          echo("
            <li class='nav-item'>
              <a class='nav-link cool-link ".($currentPage=='feedPage.php'?'active-link':'')."' 
              href='feedPage.php'>Feed</a>
            </li>

            <li class='nav-item position-relative'>
              <a class='nav-link cool-link ".($currentPage=='userChat.php'?'active-link':'')."' 
              href='userChat.php?clear=1'>
                Chat
                <span id='msgCount' class='badge notify-badge'>$msgCount</span>
              </a>
            </li>

            <li class='nav-item'>
              <a class='nav-link cool-link ".($currentPage=='searchFriend.php'?'active-link':'')."' 
              href='searchFriend.php'>Find</a>
            </li>

            <li class='nav-item position-relative'>
              <a class='nav-link cool-link ".($currentPage=='requestPage.php'?'active-link':'')."' 
              href='requestPage.php'>
                Request
                <span id='reqCount' class='badge notify-badge'>$reqCount</span>
              </a>
            </li>

            <li class='nav-item'>
              <a class='nav-link cool-link ".($currentPage=='userProfile.php'?'active-link':'')."' 
              href='userProfile.php'>You</a>
            </li>

            <li class='nav-item'>
              <a class='nav-link cool-link' href='logout.php'>Logout</a>
            </li>
          ");
        }
      }
      else{
        echo("
          <li class='nav-item'>
            <a class='nav-link cool-link ".($currentPage=='loginForm.php'?'active-link':'')."' 
            href='loginForm.php'>Login</a>
          </li>

          <li class='nav-item'>
            <a class='nav-link cool-link ".($currentPage=='regisForm.php'?'active-link':'')."' 
            href='regisForm.php'>Register</a>
          </li>
        ");
      }
      ?>

      </ul>
    </div>
  </div>
</nav>



<script>
  function updateMsgCount() {

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let count = xhr.responseText.trim();
            document.getElementById("msgCount").innerText = (count > 0 ? count : "");
            document.getElementById("msgCount").style.visibility= (count > 0 ? "visible" : "hidden");
        }
    };

    xhr.open("GET", "getMessageCount.php", true);
    xhr.send();
}

// Run every 3 seconds
setInterval(updateMsgCount, 100);


 function updateReqCount() {

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let count = xhr.responseText.trim();
            document.getElementById("reqCount").innerText = (count > 0 ? count : "");
            document.getElementById("reqCount").style.visibility= (count > 0 ? "visible" : "hidden");

        }
    };

    xhr.open("GET", "getRequestCount.php", true);
    xhr.send();
}

// Run every 3 seconds
setInterval(updateReqCount, 100);
</script>