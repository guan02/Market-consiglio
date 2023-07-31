<html>

<head>
  <title>esercizio</title>
  <style>
    body {
      background-color: #f8fafc;
    }

    div#menu {
      display: flex;
    }

    .prodotto {
      margin: 10px;
      display: flex;
      flex-direction: column;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);  
      padding: 10px;
    }
    .row {
      width: 100%;
      display: flex;
      flex-direction: row;
    }
    input[type="number"] {
      width: 60%;
    }
    img {
      width: 100%;
    }
    input[type="submit"] {
      width: 40%;
    }
  </style>
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

  //$sql = "SELECT * from prova";
  $sql = "SELECT * from p ";
  //echo $sql;
  //echo "<br>";

  //die;


  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  }


  mysqli_close($conn);

  ?>


  <div id="menu">
    <?php

    foreach ($result as $row) {
      echo "<form class='prodotto' method='POST' action='oggetto.php'>";
      echo "<img src='" . $row["foto"] . "' alt='penna' width='200' height='200'>";
      echo "<input type='hidden' name='nomeProdotto' value='" . $row["Nome"] . "'>";
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