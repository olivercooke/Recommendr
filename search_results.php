<?php
  include('session.php');
   
   #check for login session
   $sql = "SELECT userID, username FROM user WHERE userID = '$_SESSION[login_user]'";
   $result = mysqli_query($conn,$sql);
   $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
   
   #collect any entries from the database where the title contains the users search query
   $searchsql = "SELECT filmID, title, year, poster, length FROM film WHERE title LIKE '%". $_GET['search']."%'";
   $searchresult = mysqli_query($conn,$searchsql);
   $searchrow = mysqli_fetch_array($searchresult,MYSQLI_ASSOC);

   $message="";

   #search funtionality
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
            <h3>Results for "<?php echo $_GET['search']; ?>"</h3>
            <br>
            <?php
                #display and format all results in the sql search results query
                if(isset($searchrow)){
                    echo '<hr><a style="color:black;text-decoration:none;" href="film_page.php?filmID='.$searchrow['filmID'].'"><h5>'.$searchrow['title'].' ● '.$searchrow['year'].' ● '.$searchrow['length'].'</h5></a>';

                    if ($searchresult->num_rows > 0) {
                    // output data of each row
                    while($searchrow = $searchresult->fetch_assoc()) {
                    echo '<hr><a style="color:black;text-decoration:none;" href="film_page.php?filmID='.$searchrow['filmID'].'"><h5>'.$searchrow['title'].' ● '.$searchrow['year'].' ● '.$searchrow['length'].'</h5></a>';
                    }
                }
            }?>
            <br>
        </div>
        <div class="container my-page-block">
             <h6 class="text-center">Can't find your film?</h6>
             <p class="text-center">Click <a href="add_film.php">here</a> to add it</p>
        </div>
    </body>
</html>