<?php @session_start();
 
 $a=$_REQUEST["userEmail"];
 $b=$_REQUEST["passWord"];

 include("connectdb.php");

 $rsCust=mysqli_query($con,"select * from user_info where uname='$a' ");

 if(mysqli_num_rows($rsCust)==0){
    header("location:loginForm.php?regmsg=1");
 }
 else{
      $row=mysqli_fetch_array($rsCust);
      if($row["upword"]==$b){
        
        $_SESSION['uname']=$a;
        $_SESSION['uid']=$row["uid"];

        if($row['onboard_status'] == 0){
           $_SESSION['utype']='user';
    header("location:onboard_name.php");  // Page 1
    exit;
}
        if($row["utype"]=='user'){
            
            $_SESSION['utype']='user';
            header("location:feedPage.php");
        }

        elseif($row["utype"]=='admin'){
            
            $_SESSION['utype']='admin';
            header("location:adminChat.php");
        }
      }
      else{
        header("location:loginForm.php?regmsg=2");

      }


 }


?>