<!DOCTYPE html>
<html lang="en">
<head>
  <title>Market</title>
  <link href="stile.css" rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/daisyui@3.5.0/dist/full.css" rel="stylesheet" type="text/css" />

  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
<h1 class="titolo">Market</h1>
<?php
  $quantita = $_POST["quantita"];
  //echo $_POST["nomeProdotto"];
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

  
  $sql = "SELECT * from p  WHERE Nome = '" . $_POST["nomeProdotto"] . "'";
  


  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  }


  mysqli_close($conn);
?>
<div id="menu">
<?php

foreach ($result as $row) {
  echo "<div class='prodotto'>";
  echo "<h2 class='titolo'>" . $row["Nome"] . "</h2>";
  echo "<img class='immagine' src='" . $row["foto"] . "' alt='penna' width='200' height='200'>";
  echo "<input type='hidden' name='nomeProdotto' value='" . $row["Nome"] . "'>";

  echo "</div>";
  
}
$codProdotto = $row["CodP"];

?>
</div>
<?php
  //echo $_POST["nomeProdotto"];
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

  
  $sql = "SELECT fornitore.nome AS fNome , fp.costo AS prezzo , fp.giornoSpedizione AS tempo from fp, fornitore WHERE fp.codF = fornitore.codF AND fp.codP = '" . $codProdotto . "' AND fp.quantita >= '" . $quantita . "' ORDER BY fp.costo ASC";
  


  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  }


  mysqli_close($conn);
?>
<?php
$minPrezzo = null;
$minGiorno = null;
foreach ($result as $row){
  if($minPrezzo == null && $minGiorno == null){
    $minPrezzo = $row["prezzo"];
    $minGiorno = $row["tempo"];
  }
  if($row["prezzo"] < $minPrezzo){
    $minPrezzo = $row["prezzo"];
  }
  if($row["tempo"] < $minGiorno){
    $minGiorno = $row["tempo"];
  }
}

echo "<div id=elenco>";
foreach ($result as $row) {
  echo "<input type='radio' name='acquista' value='{$row["fNome"]}' />" . $row["fNome"] . " "; 
  echo "prezzo:  "."{$row["prezzo"]} ". " " ;
  echo "tempo di consegna stimata"."{$row["tempo"] }". "<br />";
  
}

echo "<form action=''>";
echo "<input type='submit'value='acquista'>";

echo "</form>";
echo "<form action=''>";
echo "<input type='submit'value='Aggiungi al carrello'>";
echo "</form>";
echo "</div>";
?>
</body>
</html>