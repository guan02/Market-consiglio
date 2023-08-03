<!DOCTYPE html>
<html lang="en">

<head>
    <title>Market</title>
    <link href="assets/stile.css" rel="stylesheet" type="text/css">
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    <script>
        function trovaChecked() {
            //prende il valore della lista radio button
            var radios = document.getElementsByName('prezzo');
            //ritorna il valore di  tag con id log
            const log = document.querySelector("#log");
            //trova il radio button selezionato
            var selected = Array.from(radios).find(radio => radio.checked);
            
            log.innerText = selected.value;
            //invia la richiesta al server
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    log.innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "http://localhost/market-consiglio/website/calcolo.php?nome=" + selected.value, true);
            xmlhttp.send();
        }
    </script>
</head>

<body>

</body>

</html>

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

    // restituisce le informazioni sul prodotto e sul fornitore
    $sql = "SELECT  fornitore.nome AS fNome , fp.costo AS prezzo , fp.giornoSpedizione AS tempo, fp.codP   
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

    foreach ($result as $row) {
        if ($minPrezzo == null && $minGiorno == null) {
            $minPrezzo = $row["prezzo"];
            $minGiorno = $row["tempo"];
        }
        if ($row["prezzo"] < $minPrezzo) {
            $minPrezzo = $row["prezzo"];

            $prezzoTOt = $row["prezzo"] * $quantita;
        }
        if ($row["tempo"] < $minGiorno) {
            $minGiorno = $row["tempo"];
        }
    }

    echo "<div id =elenco class= child>";
    foreach ($result as $row) {
        // crea un radio button per ogni fornitore
        $info = $row["fNome"] . "/" . $row["codP"] . "/" . $quantita;
        if ($row["prezzo"] == $minPrezzo) {
            echo "<input type='radio'   id='pEconomico' name='prezzo' value='$info' checked />";
            echo "<label for='pEconomico'  class= 'coloreEconomico'>";
            echo "il fornitore " . $row["fNome"] . " offre il prodotto a " . $row["prezzo"] * $quantita . "€ ";
            echo "     (più consigliato) <br>";
            echo "</label>";

            continue;
        }
        if ($row["tempo"] == $minGiorno) {
            echo "<input type='radio' id='pVeloce' name='prezzo' value='$info'  />";
            echo "<label for='pVeloce' class='coloreVeloce '>";
            echo "il fornitore " . $row["fNome"] . " offre il prodotto a " . $row["prezzo"] * $quantita . "€ ";
            echo "     (più veloce) <br>";
            echo "</label>";

            continue;
        }
        echo "<input type='radio' id='pNormale' name='prezzo' value='$info'  />";
        echo "<label for='pNormale' >";
        echo "il fornitore " . $row["fNome"] . " offre il prodotto a " . $row["prezzo"] * $quantita . "€ <br>";
        echo "</label>";
    }
    echo "<button id=conferma onclick='trovaChecked()''> Conferma</button>";
    echo "</div>";
    echo "<p id='log'></p>";
    ?>