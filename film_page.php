<?php
  include('session.php');

   $sql = "SELECT userID, username FROM user WHERE userID = '$_SESSION[login_user]'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

   #collect film information from database
   $filmsql = "SELECT filmID, imdbID, title, year, length, plot, poster FROM film WHERE filmID =" .$_GET["filmID"];
   $filmresult = mysqli_query($conn,$filmsql);
   $filmrow = mysqli_fetch_array($filmresult,MYSQLI_ASSOC);

   $filmratingsql = "SELECT userID, filmID, rating FROM filmuser WHERE userID =' $_SESSION[login_user]' AND filmID =" .$_GET['filmID'];
   $filmratingresult = mysqli_query($conn,$filmratingsql);
   $filmratingrow = mysqli_fetch_array($filmratingresult,MYSQLI_ASSOC);

   $avgratingsql = "SELECT AVG(rating) FROM filmuser WHERE filmID =" .$_GET["filmID"];
   $avgratingresult = mysqli_query($conn,$avgratingsql);
   $avgratingrow = mysqli_fetch_array($avgratingresult,MYSQLI_ASSOC);

   $filmratingemote = "";

   if(isset($filmratingrow)){
       if($filmratingrow['rating'] === "1"){
           $filmratingemote = "👍";
       }
       
       else if($filmratingrow['rating'] === "0"){
           $filmratingemote = "👎";
       }
       
       else{
           $filmratingemote = "?";
       }
   }
   
   if(isset($_POST['like']))
    {
      #executed if a film has alerady been rated
      if(isset($filmratingrow)){        
        $ratingsql = "UPDATE filmuser SET rating=1 WHERE userID='".$filmratingrow["userID"]."' AND filmID ='".$filmratingrow["filmID"]."'";
      }
       #executed if a new entry needs to be made
       else{
        $myuserid = $row['userID']; 
        $myfilmid = $filmrow['filmID'];
       
       
        $ratingsql = "INSERT INTO filmuser (userID, filmID, rating) VALUES ('$myuserid', '$myfilmid', 1)";
      } 
       #the sql query generated based on whether this film has already been rated executed here
      if ($conn->query($ratingsql) === TRUE) {
        $message = 'rating added';
        #the page is refreshed to update the Your Rating section
        header("Refresh:0");
      } else {
        echo "Error: " . $ratingsql . "<br>" . $conn->error;
      }
    }
    else if(isset($_POST['dislike']))
    {
     #executed if a film has alerady been rated
      if(isset($filmratingrow)){        
        $ratingsql = "UPDATE filmuser SET rating=0 WHERE userID='".$filmratingrow["userID"]."' AND filmID ='".$filmratingrow["filmID"]."'";
      }
       #executed if a new entry needs to be made
       else{
        $myuserid = $row['userID']; 
        $myfilmid = $filmrow['filmID'];
       
       
        $ratingsql = "INSERT INTO filmuser (userID, filmID, rating) VALUES ('$myuserid', '$myfilmid', 0)";
      } 
       #the sql query generated based on whether this film has already been rated executed here
      if ($conn->query($ratingsql) === TRUE) {
        $message = 'rating added';
        #the page is refreshed to update the Your Rating section
        header("Refresh:0");
      } else {
        echo "Error: " . $ratingsql . "<br>" . $conn->error;
      }
    }
    
    #search functionality
    else if (isset($_POST['search'])){
       $mysearchquery = mysqli_real_escape_string($conn,$_POST["txtSearchQuery"]);
       
       header("location: search_results.php?search=".$mysearchquery);
    }

?>

<html lang="en">
    <head>
        <title>Recommendr | <?php echo $filmrow['title'];?></title>
        
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
                  if(isset($row)){
                       echo '<div style="color:white;"> Logged in as: <a href="account.php" style="color:white;">'.$row['username'].'</a></div><a href="logout.php"><button type="button" class="btn btn-outline-success">Logout</button></a>';
                  }
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
                <!-- formatting of the results of SQL query to find film information done here -->
                <div class="col-4">
                    <img src="<?php echo $filmrow['poster'];?>" alt="placeholder poster" class="film-poster img-fluid" >
                </div>
                
                <div class="col-8">
                    <h3><?php echo $filmrow['title'];?> (<?php echo $filmrow['year'];?>)</h3>
                    <hr>
                    
                    <div class="row">
                        <div class="col-6"><h4>Site Rating: <?php 
                            $stringrating = $avgratingrow['AVG(rating)'];
                            $intrating = intval($stringrating);
                            
                            echo $intrating * 10;
                         ?></h4></div>
                        <div class="col-6"><h4>Runtime <?php echo $filmrow['length'];?></h4></div>
                    </div>
                    
                    <?php
                    if(isset($filmratingrow)){
                        echo '<h4>Your Rating: '.$filmratingemote.'</h4>';
                    }
                    ?>
                    
                    <p class="film-desc-text"><?php echo $filmrow['plot'];?></p>
                    
                    <h4>Rate this film:</h4>

                    <?php 
                    #check if user is logged in before displaying rating functionality
                    if(isset($row)){
                        echo '<form method="post" action=""><input class="btn btn-success text-center" type="submit" value="Like 👍" name="like">  <input class="btn btn-success text-center" type="submit" value="Dislike 👎" name ="dislike">  </form>';
                    } else{
                        #login button displayed if not logged in
                        echo '<a href="login.php"><button type="button" class="btn btn-success">Login</button></a>';
                    } 
                    ?>
                </div>
            </div>
        </div>
        
    </body>
</html>