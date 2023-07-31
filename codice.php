<?php

if((!isset($_POST["nome"]))||strlen(ltrim($_POST["nome"]))==0){
   
  echo "Stringa non valida.";
  die;
  }
  
  $str = $_POST["nome"];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

//$sql = "SELECT * from prova";
$sql = "SELECT quantita from prova where descrizione = '$str';";
//echo $sql;
//echo "<br>";

//die;


$result = mysqli_query($conn, $sql);
	

if (mysqli_num_rows($result) > 0) {
       foreach ($result as $row) {
               echo " - Quantita: " . $row["quantita"]. "<br>";
       }
  }
 else {
  echo "0 results";
}

mysqli_close($conn);
?>