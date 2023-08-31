<?php
   include('config.php');
   session_start();
   $message = null;

    #this code is executed when the register button is pressed
   if($_SERVER["REQUEST_METHOD"] == "POST") {
       
     #extracts information from registration form
     $myemail = mysqli_real_escape_string($conn,$_POST["txtEmail"]);
     $myusername = mysqli_real_escape_string($conn,$_POST["txtUsername"]);
     $mypassword = mysqli_real_escape_string($conn,$_POST["txtPassword"]);


     #creates an sql query with information from form and default placerholder information
     $sql = "INSERT INTO user (email, username, password, profileImage, profileBio) VALUES ('$myemail','$myusername','$mypassword','https://via.placeholder.com/500','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.')";
     
     #executes query
     if ($conn->query($sql) === TRUE) {
        #displays success message and links to login form, where users can login with newly created account
        $message = '<div class="d-flex justify-content-center"><a href="login.php">Account successfully created. Click here to login</a></div>';
      } else {
        #this error message is displayed if query cannot be executed
        echo "Error: " . $sql . "<br>" . $conn->error;
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
                <input type="text" class="form-control" id="email" name="txtEmail" placeholder="example@example.com" aria-describedby="emailHelp">
              </div>
                
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="txtUsername" placeholder="Username">
              </div>
                
              <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="txtPassword">
              </div>
             
                
            <input class="btn btn-success text-center" type="submit" value="Register">  
            <?php echo $message; ?>
            
              
            </form>
        </div>
    </body>
</html>