<?php
   define("DB_SERVER", "localhost");
   define("DB_USERNAME", "id21210434_root");
   define("DB_PASSWORD", "Password12$");
   define("DB_DATABASE", "id21210434_recommendr");
   
    $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
    $info = "";
?>