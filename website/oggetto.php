<!DOCTYPE html>
<html lang="en">

<head>
  <title>Market</title>
  <link href="assets/stile.css" rel="stylesheet" type="text/css">


</head>

<body>
  <h1 class="titolo">Market</h1>
  <?php
  //prende i dati dal form
  $quantita = $_POST["quantita"];

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
  $sql = "SELECT * from p  WHERE Nome = '" . $_POST["nomeProdotto"] . "'";



  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  }

  //chiudo la connessione
  mysqli_close($conn);
  ?>
  <div id="menu">
    <?php
    //carica i dati del prodotto
    $row = mysqli_fetch_assoc($result);
      echo "<div class='prodotto'>";
      echo "<h2 class='titolo'>" . $row["Nome"] . "</h2>";
      echo "<img class='immagine' src='" . $row["foto"] . "' alt='penna' width='200' height='200'>";
      echo "<input type='hidden' name='nomeProdotto' value='" . $row["Nome"] . "'>";

      echo "</div>";
    
    $codProdotto = $row["CodP"];


    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }


    $sql = "SELECT fornitore.nome AS fNome , fp.costo AS prezzo , fp.giornoSpedizione AS tempo   
   from fp, fornitore 
   WHERE fp.codF = fornitore.codF  AND fp.codP = '" . $codProdotto . "' AND fp.quantita >= '" . $quantita . "' ";



    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) == 0) {
      echo "0 results";
    }


    mysqli_close($conn);

//trova il prezzo minimo e il tempo minimo
    $minPrezzo = null;
    $minGiorno = null;
    $minPFnome = null;
    $minGFnome = null;
    foreach ($result as $row) {
      if ($minPrezzo == null && $minGiorno == null) {
        $minPrezzo = $row["prezzo"];
        $minGiorno = $row["tempo"];
      }
      if ($row["prezzo"] < $minPrezzo) {
        $minPrezzo = $row["prezzo"];
        $minPFnome = $row["fNome"];
        $prezzoTOt = $row["prezzo"] * $quantita;
      }
      if ($row["tempo"] < $minGiorno) {
        $minGiorno = $row["tempo"];
        $minGFnome = $row["fNome"];
      }
    }

    echo "<div id=elenco>";
    foreach ($result as $row){
      
      if ($row["prezzo"] == $minPrezzo) {
        echo "<h3>";
        echo "il fornitore " . $row["fNome"] . " offre il prodotto a " . $row["prezzo"]*$quantita . "€ e lo spedisce in " . $row["tempo"] . " giorni ";
        echo " è il fornitore più economico <br>";
        echo "</h3>";
        continue;
      }
      echo "il fornitore " . $row["fNome"] . " offre il prodotto a " . $row["prezzo"]*$quantita . "€ e lo spedisce in " . $row["tempo"] . " giorni <br>";
    }

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }


    $sql = "SELECT fp.costo AS prezzo , sconto.valore AS sconto, sconto.scadenza AS scade,sconto.condizione AS requisito, sconto.tipo AS tipoS  
    from fp, fornitore,registrosconto,sconto WHERE fp.codF = fornitore.codF AND fornitore.codF=registrosconto.id_codF AND registrosconto.id_codSconto=sconto.id AND fp.codP = '" . $codProdotto . "' AND fp.quantita >= '" . $quantita . "'";



    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) == 0) {
      echo "0 results";
    }


    mysqli_close($conn);
    // applica lo sconto
    foreach ($result as $row) {

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
      // confronta il prezzo totale minimo senza sconto con quello scontato
      if ($prezzoScontato < $prezzoTOt) {
        $prezzoSMinimo = $prezzoScontato;
      }
    }
    echo "prezzo scontato: " . $prezzoScontato . "€";
    echo "<h3> Il prezzo totale più basso (applicando sconto) è: " . $prezzoSMinimo . "€</h3>";

    echo "</div>";
    ?>
  </div>
</body>

</html>