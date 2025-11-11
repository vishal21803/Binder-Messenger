<?php @session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./bootstrap.min.css">

    <link rel="stylesheet" href="static/style.css">
</head>
<body>
 
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="javascript:void(0)"><img src="img/logo.png" alt=""> Binder</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav ms-auto">

      <?php
      if(isset($_SESSION['uname'])){
 $uname= $_SESSION['uname'];
 include("connectdb.php");
$rscheck=mysqli_query($con,"select * from user_info where uemail='$uname'");
while($row=mysqli_fetch_array($rscheck)){
    $utype=$row["utype"];
}
      if($utype=='admin'){
        echo("   
        
        <li class='nav-item'>
          <a class='nav-link' href=''>Admin</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='logout.php'>Logout</a>
        </li>
        
        
        ");
      }
         else if($utype=='user'){
        echo("   
         <li class='nav-item'>
          <a class='nav-link' href='friendList.php'>Friendlist</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='userChat.php'>Chat</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='searchFriend.php'>Find</a>
        </li>
          <li class='nav-item'>
          <a class='nav-link' href='requestPage.php'>Requests</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href=''>User</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='logout.php'>Logout</a>
        </li>
        
        
        
        ");
      }
    }
      else{
        echo("
        
         <li class='nav-item'>
          <a class='nav-link' href='loginForm.php'>Login</a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='regisForm.php'>Register</a>
        </li>
        ");
      }
    
      
      ?>
      
       
      </ul>
     
    </div>
  </div>
</nav>
