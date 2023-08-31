<?php
   #includes user log in session
   include('session.php');
    
    #importants imdb api and relevent libraries
    require "vendor/autoload.php";
    use hmerritt\Imdb;

   #retrievs information on loggied in user from sql database
   $sql = "SELECT userID, username FROM user WHERE userID = '$_SESSION[login_user]'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

   #creates an empty message
   $message = "";
   
   #when the 'add' button is pressed on the form this code is executed
    if(isset($_POST['imdb'])) {
     $imdb = new Imdb;

     #the imdb film ID entered into text box is pulled from the page and input into an imdb api object
     $myfilmid = mysqli_real_escape_string($conn,$_POST["txtFilmID"]);
     $currentfilm = $imdb->film($myfilmid);  
        
     #relevent information on the film ID is scraped from imdb using the api
     $title = $currentfilm['title'];
     $year = $currentfilm['year'];
     $length = $currentfilm['length'];
     $plot = addslashes($currentfilm['plot']);
     $poster = $currentfilm['poster'];
     $genre = $currentfilm['genres'][0];
          
     #an SQL query is created with the information taked from IMDB to be entered into the film table
     $addfilmsql = "INSERT INTO film (imdbID, title, year, length, plot, poster, genre) VALUES ('$myfilmid','$title','$year', '$length', '$plot', '$poster','$genre')"; 
     
     #the SQL query is executed 
     #if it is successful the message is changed to a success message which is then displayed at the bottom of the form
     if ($conn->query($addfilmsql) === TRUE) {
         $message = "film added";
      } else {
        echo "Error: " . $addfilmsql . "<br>" . $conn->error;
      }

   }
   #search query
   else if(isset($_POST['search'])){
       #search query is pulled from the search bar form
       $mysearchquery = mysqli_real_escape_string($conn,$_POST["txtSearchQuery"]);
       
       #the page is redirected to the search results page and the url is appended with the search query
       header("location: search_results.php?search=".$mysearchquery);
   }
   mysqli_close($conn);
?>

<html lang="en">
    <head>
        <title>Recommendr | Template</title>
        
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
        <div class="container my-login-form">
            <form action="" method="post">

              <div class="mb-3">
                <label class="form-label">Film Imdb ID</label>
                <input type="text" class="form-control" id="filmID" name="txtFilmID" placeholder="filmID">
              </div>
                 
            <input class="btn btn-success text-center" type="submit" value="Add" name="imdb">  
            
            <?php     
               echo $message;
            ?>
            
              
            </form>
        </div>

        
    </body>
</html>