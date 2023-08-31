<?php
   include('config.php');
   session_start();
   
   #this is executed when the log in button is pressed
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      #email and password is taken from the html form
      $myemail = mysqli_real_escape_string($conn,$_POST["txtEmail"]);
      $mypassword = mysqli_real_escape_string($conn,$_POST["txtPassword"]); 
      
      #the sql query is created and executed
      $sql = "SELECT userID FROM user WHERE email = '$myemail' and password = '$mypassword'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row["active"];
      
      #retrives the number of results for the information in the form
      $count = mysqli_num_rows($result);
      
      #this is executed if 1 account with information matching the information in the form
      if($count == 1) {
         # a login session is initiated
         $_SESSION["login_user"] = $row["userID"];
        
         #the page is redirected to the users account page
         header("location: account.php");
      }else {
         #if no valid username and password combination is present in the form this message is displayed
         $info = "Your Login Name or Password is invalid";
      }
   }
   mysqli_close($conn);
?>

<html lang="en">
    <head>
        <title>Recommendr | Homepage</title>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="css/mystyle.css">
    </head>
    
    <body>
        <!--NAV BAR -->
            <nav class="navbar navbar-dark bg-dark">
              <div class="container-fluid">
                <a class="navbar-brand" href="/..">Recommendr</a>
              </div>
                
             
            </nav>
        
        <!--SITE CONTENT -->
        
        <div class="container my-login-form">
            <form action="" method="post">
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="text" class="form-control" id="email" name ="txtEmail" placeholder="example@example.com" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="txtPassword">
              </div>

               <input class="btn btn-success text-center" type="submit" value="Login">
              <a href="register.php"><button type="button" class="btn btn-success text-center">Register</button></a>
            </form>
        </div>
    </body>
</html>