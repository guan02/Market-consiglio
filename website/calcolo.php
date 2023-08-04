<!DOCTYPE html>
<html lang="en">
<head>
  <title>Market</title>
  <link href="assets/stile.css" rel="stylesheet" type="text/css">  
</head>
<body>
<?php
//controllo il valore che viene passato dalla pagina prodotto.php
if (isset($_REQUEST["nome"]))
{
    $nome = $_REQUEST["nome"];
}


    // un array contiene nome fornitore , codice prodotto e quantità
    $elemento= explode("/", $nome);; 
    $nomeFornitore= $elemento[0];
    $codProdotto = $elemento[1];
    $quantita = $elemento[2];
    
    // Create connection
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
  
    //querry  che restituisce le informazioni sullo sconto
    $sql = "SELECT fp.giornoSpedizione AS sTempo,fornitore.nome AS fNome, fp.costo AS prezzo , sconto.valore AS sconto, sconto.scadenza AS scade,sconto.condizione AS requisito, sconto.tipo AS tipoS  
    from fp, fornitore,registrosconto,sconto WHERE fp.codF = fornitore.codF AND fornitore.codF=registrosconto.id_codF AND registrosconto.id_codSconto=sconto.id AND fp.codP = '" . $codProdotto . "' AND fp.quantita >= '" . $quantita . "'";

  
  
    $result = mysqli_query($conn, $sql);
  
  
    if (mysqli_num_rows($result) == 0) {
      echo "0 results";
    }
  
    //chiudo la connessione
    mysqli_close($conn); 
    // applica lo sconto
    foreach ($result as $row) {
      
      if($row["fNome"]==$nomeFornitore){
      $tempo=$row["sTempo"];
      $prezzoScontato = $row["prezzo"] * $quantita;
      // in base alla quantità
      if ($row["tipoS"] == "quantita") {
        if ($quantita >= $row["requisito"]) {
          $prezzoScontato *=  1- $row["sconto"] / 100;
        }
      }
      // in base al prezzo totale
      if ($row["tipoS"] == "prezzoTotale") {
        if ($row["prezzo"] * $quantita >= $row["requisito"]) {
          $prezzoScontato *=  1- $row["sconto"] / 100;
        }
      }
      // in base alla stagione
      if ($row["tipoS"] == "stagione") {
        if (strtotime("now") <= strtotime($row["scade"])) {
          $prezzoScontato *=  1- $row["sconto"] / 100;
        }
      }
    }
      
    }
    echo "<h3 class=child> prezzo finale: " . $prezzoScontato . "€</h3>";
    
    echo "<h3 class=child> tempo di spedizione stimata: " . date("d/m/Y ", strtotime("+".$tempo." day")) ."</h3>";  
    
    
     
    
    echo "</div>";
?>

</body>
</html>
