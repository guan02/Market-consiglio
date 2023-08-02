<!DOCTYPE html>
<html>

<head>
  <title>Market</title>
  <link href="stile.css" rel="stylesheet" type="text/css">
  
</head>

<body>
  
  <?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "dbinfo";

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  //querry da eseguire
  $sql = "SELECT * from p ";
  


  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  }

  //chiudo la connessione
  mysqli_close($conn);

  ?>


  <div id="menu">
    <?php

    foreach ($result as $row) {
      echo "<form class='prodotto' method='POST' action='oggetto.php'>";
      //carica i dati del prodotto
      echo "<h2 class='titolo'>" . $row["Nome"] . "</h2>";
      echo "<img class='immagine' src='" . $row["foto"] . "' alt='penna' width='200' height='200'>";
      echo "<input type='hidden' name='nomeProdotto' value='" . $row["Nome"] . "'>";
      //campo input 
      echo "<div class='row'> ";
      echo "<input type='number' name='quantita' value='1' min='1'> ";
      echo "<input type='submit' value='Acquista'>";
      echo "</div>";
      echo "</form>";
    }

    ?>


  </div>









</body>

</html>