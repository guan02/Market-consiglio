<!DOCTYPE html>
<html lang="en">
<head>
  <title>Market</title>
  <link href="stile.css" rel="stylesheet" type="text/css">
  

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

  
  $sql = "SELECT fornitore.nome AS fNome , fp.costo AS prezzo , fp.giornoSpedizione AS tempo ,sconto.valore AS sconto, sconto.scadenza AS scade,
   sconto.condizione AS requisito, sconto.tipo AS tipoS  from fp, fornitore,registrosconto,sconto WHERE fp.codF = fornitore.codF AND fornitore.codF=registrosconto.id_codF AND registrosconto.id_codSconto=sconto.id AND fp.codP = '" . $codProdotto . "' AND fp.quantita >= '" . $quantita . "'";
  


  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  }


  mysqli_close($conn);


$minPrezzo = null;
$minGiorno = null;
$minPFnome = null;
$minGFnome = null;
foreach ($result as $row){
  if($minPrezzo == null && $minGiorno == null){
    $minPrezzo = $row["prezzo"];
    $minGiorno = $row["tempo"];
  }
  if($row["prezzo"] < $minPrezzo){
    $minPrezzo = $row["prezzo"];
    $minPFnome = $row["fNome"];
    $prezzoTOt = $row["prezzo"] * $quantita;
  }
  if($row["tempo"] < $minGiorno){
    $minGiorno = $row["tempo"];
    $minGFnome = $row["fNome"];
  }
}

echo "<div id=elenco>";
echo "<h3> Il fornitore " . $minPFnome . " offre il prezzo più basso: " . $minPrezzo * $quantita. "€</h3>";
echo "<h3> Il fornitore " . $minGFnome . " offre il tempo di spedizione più corto: " . $minGiorno . " giorni</h3>";

foreach ($result as $row){
  
  $prezzoScontato=0;
  if($row["tipoS"] == "quantita"){
    if($quantita >= $row["requisito"]){
      $prezzoScontato += $row["prezzo"] * $quantita *$row["sconto"] / 100;
      
    }
  }
  if($row["tipoS"] == "prezzoTotale"){
    if($row["prezzo"] * $quantita >= $row["requisito"]){
      $prezzoScontato += $row["prezzo"] * $quantita *$row["sconto"] / 100;
      
    }
  }
  if($row["tipoS"] == "stagione"){
    if(strtotime("now") <= strtotime($row["scade"])){
      $prezzoScontato += $row["prezzo"] * $quantita *$row["sconto"] / 100;
      
    }
  }
  if($prezzoTOt >= $row["prezzo"] * $quantita - $prezzoScontato){
    $prezzoTOt = $row["prezzo"] * $quantita - $prezzoScontato;
  }
}
echo "prezzo scontato: ".$prezzoScontato."€";
echo "<h3> Il prezzo totale più basso (applicando sconto) è: " . $prezzoTOt . "€</h3>";

echo "</div>";
?>
</div>
</body>
</html>
