<?php
   include('config.php');
   session_start();
   
  if(isset($_SESSION["login_user"])){
   $user_check = $_SESSION["login_user"];
   $ses_sql = mysqli_query($conn,"select userID from user where userID = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
  }
   if(!isset($_SESSION["login_user"])){
      $_SESSION["login_user"] = null;
   }
?>