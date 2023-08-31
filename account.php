<?php
  #includes the current log in session
  include('session.php');

   #retrives login data
   $sql = "SELECT userID, username, profileImage, profileBio FROM user WHERE userID = '$_SESSION[login_user]'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

   #retrives count of how many films a user has rated
   $userratingsql = "SELECT COUNT(rating) FROM filmuser WHERE userID = '$_SESSION[login_user]'";
   $userratingresult = mysqli_query($conn,$userratingsql);
   $userratingrow = mysqli_fetch_array($userratingresult,MYSQLI_ASSOC);
   
   #search functionality
   if($_SERVER["REQUEST_METHOD"] == "POST") {
       $mysearchquery = mysqli_real_escape_string($conn,$_POST["txtSearchQuery"]);
       
       header("location: search_results.php?search=".$mysearchquery);
   }

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
  
                <?php
                  #if a login session is active the username and logout button is displayed
                  if(isset($row)){
                       echo '<div style="color:white;"> Logged in as: <a href="account.php" style="color:white;">'.$row['username'].'</a></div><a href="logout.php"><button type="button" class="btn btn-outline-success">Logout</button></a>';
                  }
                  #if a user session is not active then a log in button is displayed, which directs to the login form
                  else{
                  echo '<a href="login.php"><button type="button" class="btn btn-outline-success">Login</button></a>';
                  }
                ?>
                  
                <form class="d-flex" action="" method="post">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="txtSearchQuery">
                    <input class="btn btn-outline-success text-center" type="submit" value="Search" name="search">
                </form>
              </div>
                
             
            </nav>
        
        <!--SITE CONTENT -->
        <div class="container my-page-block">
            <div class="row">
            <?php 
              if(isset($row)){
                #creates the html for the users profile with information pulled from the database
                echo '<div class="col-4"><img src='.$row['profileImage'].' alt="film poster" class="film-poster img-fluid" ></div><div class="col-8"><h3>Hello, '.$row['username'].'</h3><hr><div class="row"><div class="col-6"><h4>Films Rated: '.$userratingrow['COUNT(rating)'].'</h4></div></div><p class="film-desc-text">'.$row['profileBio'].'</p><a href="edit_profile.php"><button type="button" class="btn btn-success text-center">Edit</button></a></div>';   
              }
              #if no login information is present a login button is displayed
              else{
                echo '<a href="login.php"><button type="button" class="btn btn-success">Login</button></a>';
              }
            ?>
                
            </div>
        </div>        

        
    </body>
</html>




