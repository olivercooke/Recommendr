<?php
  include('session.php');

   #pulls logged in user information
   $sql = "SELECT userID, username FROM user WHERE userID = '$_SESSION[login_user]'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
   
   $message = "";

   #if a user log in session has been initated this is executed
   if(isset($row)){
        #collects a users top two genres from the database
        $getusergenressql = "SELECT film.genre, COUNT(film.genre) as genreFreq FROM filmuser INNER JOIN film ON filmuser.filmID = film.filmID WHERE filmuser.userID = ".$row["userID"]." and filmuser.rating = 1 GROUP BY genre ORDER BY genreFreq DESC LIMIT 2;";
        $getusergenresresult = mysqli_query($conn,$getusergenressql);
        $getusergenresrow = mysqli_fetch_array($getusergenresresult,MYSQLI_ASSOC);
       
        #if a user has two top genres this is executed
        if(isset($getusergenresrow )){
            #first user genre is collected
            $genre1 = $getusergenresrow['genre'];
            
            #second user genre is collected
            $getusergenresrow  = $getusergenresresult->fetch_assoc();
            $genre2 = $getusergenresrow['genre'];
            
            
            #list of films user has already seen in these genres collected
            $alreadywatchedsql  = "SELECT film.filmID FROM film INNER JOIN filmuser ON film.filmID = filmuser.filmID WHERE filmuser.userID = 1 AND (film.genre = '".$genre1."' OR film.genre = '".$genre2."');";
            $alreadywatchedresult = mysqli_query($conn,$alreadywatchedsql);
            $alreadywatchedrow = mysqli_fetch_array($alreadywatchedresult,MYSQLI_ASSOC);

            #films already seen are excluded from the  sql query for user film recommendations
            $userrecommendedsql = "SELECT * FROM film WHERE (genre = '".$genre1."' OR genre= '".$genre2."') AND (filmID <> " . $alreadywatchedrow['filmID'];


            if ($alreadywatchedresult->num_rows > 0) {
            #output data of each row
                while($alreadywatchedrow  = $alreadywatchedresult->fetch_assoc()) {
                    $userrecommendedsql = $userrecommendedsql . " AND filmID <> " . $alreadywatchedrow['filmID'] ;    
                }
            }
            echo $userrecommendedsql;
            $userrecommendedsql = $userrecommendedsql . ") LIMIT 6";

            $userrecommendedresult = mysqli_query($conn,$userrecommendedsql);
            $userrecommendedrow = mysqli_fetch_array($userrecommendedresult,MYSQLI_ASSOC);
        } 
        
        else{
            $message = "please rate more films to get recommendations.";
        }
   }
       
   #collecting list of popular films from database
   $popularsql = "SELECT film.poster, film.title, filmuser.filmID,COUNT(filmuser.filmID) as count FROM filmuser INNER JOIN film ON filmuser.filmID = film.filmID GROUP BY filmuser.filmID ORDER BY COUNT DESC LIMIT 6;";
   $popularresult = mysqli_query($conn,$popularsql);
   $popularrow = mysqli_fetch_array($popularresult,MYSQLI_ASSOC);

   #collecting list of films with recent activity on them
   $recentsql = "SELECT film.poster, film.title, filmuser.userID, filmuser.filmID, filmuser.rating FROM filmuser INNER JOIN film ON filmuser.filmID = film.filmID ORDER BY timestamp DESC LIMIT 6;";
   $recentresult = mysqli_query($conn,$recentsql);
   $recentrow = mysqli_fetch_array($recentresult,MYSQLI_ASSOC);

   #search functionality activated when search button pressed
   if($_SERVER["REQUEST_METHOD"] == "POST") {
       
       #search query is taken from the text box
       $mysearchquery = mysqli_real_escape_string($conn,$_POST["txtSearchQuery"]);
       
       #user is redirected to the page with their search results
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
            <h3 class="main-page-section-headings">For You:</h3>
            <a href="recommendations.php"><button type="button" class="btn btn-success main-page-section-headings">More</button></a>
            <hr>
            
            <div class="row">
            <?php
                #if a user is logged in then recommendations are given
                if(isset($row) ) {
                    if($message === ""){
                        echo '<div class="col-2"> <a href=film_page.php?filmID='.$userrecommendedrow["filmID"].'> <img class="img-fluid" src= '. $userrecommendedrow["poster"].' > </a></div>';

                        if ($userrecommendedresult->num_rows > 0) {
                        // output data of each row
                        while($userrecommendedrow = $userrecommendedresult->fetch_assoc()) {
                            echo '<div class="col-2"> <a href=film_page.php?filmID='.$userrecommendedrow["filmID"].'><img class="img-fluid" src= '. $userrecommendedrow["poster"].' > </a></div>';
                            }
                        }
                    }
                    else{
                        echo "<p class='text-center'>". $message ."</p>";
                    }
                } 
                
                #if not a login button is presented
                else{
                    echo '<h6 class="text-center">Log in to get your recommendations</h6>';
                    echo '<a class="text-center" href="login.php"><button type="button" class="btn btn-success">Login</button></a>';
                }
            ?>
            </div>            
        </div>
        
        
        <div class="container my-page-block">
            <h3 class="main-page-section-headings">Popular:</h3>
            <a href="popular.php"><button type="button" class="btn btn-success main-page-section-headings">More</button></a>
            <hr>
            
            <div class="row">
	        <?php
                #the list of films is echoed here
                echo '<div class="col-2"> <a href=film_page.php?filmID='.$popularrow["filmID"].'> <img class="img-fluid" src= '. $popularrow["poster"].' > </a></div>';
                
                if ($popularresult->num_rows > 0) {
                // output data of each row
                while($popularrow = $popularresult->fetch_assoc()) {
                    echo '<div class="col-2"> <a href=film_page.php?filmID='.$popularrow["filmID"].'><img class="img-fluid" src= '. $popularrow["poster"].' > </a></div>';
                }
            }?>
            </div>
        </div>
        
        <div class="container my-page-block">
            <h3 class="main-page-section-headings">Site Activity:</h3>
            <a href="site_activity.php"><button type="button" class="btn btn-success main-page-section-headings">More</button></a>
            <hr>
            
            <div class="row"> 
            <?php
                #the list of films is echoed here
                echo '<div class="col-2"> <a href=film_page.php?filmID='.$recentrow["filmID"].'><img class="img-fluid" src= '. $recentrow["poster"].' > </a></div>';
                
                if ($recentresult->num_rows > 0) {
                // output data of each row
                while($recentrow = $recentresult->fetch_assoc()) {
                    echo '<div class="col-2"> <a href=film_page.php?filmID='.$recentrow["filmID"].'><img class="img-fluid" src= '. $recentrow["poster"].' > </a> </div>';
                }
            }?>
            </div>
        </div>
        
    </body>
</html>