<?php
   include('session.php');
   $message = null;

   #retrives a log in session
   $sql = "SELECT userID, username, password FROM user WHERE userID = '$_SESSION[login_user]'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

   if($_SERVER["REQUEST_METHOD"] == "POST") {
     #pulls data from the edit profile form
     $mynewprofilepicture = mysqli_real_escape_string($conn,$_POST["txtProfilePicture"]);
     $mynewusername = mysqli_real_escape_string($conn,$_POST["txtUsername"]);
     $mynewbio = mysqli_real_escape_string($conn,$_POST["txtBio"]);
     $mynewpassword = mysqli_real_escape_string($conn,$_POST["txtNewPassword"]);
     $myoldpassword = mysqli_real_escape_string($conn,$_POST["txtOldPassword"]);

     
     #checks that the correct password has been entered, for security
     if ($myoldpassword = $row['password']){
         
         #creates sql query to update relevent row in user table
         $sql = "UPDATE user SET username='".$mynewusername."', password='".$mynewpassword."', profileImage='".$mynewprofilepicture."', profileBio='".$mynewbio."' WHERE userID='".$row["userID"]."';";
         
         #executes query and displays success message, linking back to profile
         if ($conn->query($sql) === TRUE) {
            $message = '<div class="d-flex justify-content-center"><a href="account.php">Info successfully updated. Click here to visit your profile</a></div>';
          } else {
             #if the sql query is not executed this errror message is displayed
            echo "Error: " . $sql . "<br>" . $conn->error;
          }
      } else{
         #if the incorrect password has been entered this message is displayed
         $message = "Password incorrect! Please try again.";
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
            <?php
            echo '<form action="" method="post"><div class="mb-3"><label for="exampleInputEmail1" class="form-label">Profile Picture (image link)</label><input type="text" class="form-control" id="profile-picture" aria-describedby="emailHelp" name="txtProfilePicture"></div><div class="mb-3"><label for="exampleInputPassword1" class="form-label">New Username</label><input type="text" class="form-control" id="username" name="txtUsername"></div><div class="mb-3"><label for="exampleInputPassword1" class="form-label">Bio</label><input type="text" class="form-control" id="bio" name="txtBio"></div><div class="mb-3"><label for="exampleInputPassword1" class="form-label">New Password</label><input type="password" class="form-control" id="password" name="txtNewPassword"></div><hr><div class="mb-3"><label for="exampleInputPassword1" class="form-label">Confirm Current Password</label><input type="password" class="form-control" id="password" name="txtOldPassword"></div><input class="btn btn-success text-center" type="submit" value="Confirm Edit">';  
             
            echo $message;
              
            echo '</form>';
            ?>
            
        </div>
    </body>
</html>