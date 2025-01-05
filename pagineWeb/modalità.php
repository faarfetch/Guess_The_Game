<?php
//pagina che permette la scelta della modalità di gioco 
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != 1) {
    header("location: login.php?message=effettuare il login");
    exit();
}

$modalità = [
    "GTG",
    "GTS"
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioca!</title>
</head>
<link rel="stylesheet" href="../style/general.css">
<style>
    #container {
        display: flex;
        text-align: center;
    }

    .modalita {
        width: 250px;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        let divMods = document.getElementsByClassName("modalita");
        let dGTG = "GUESS THE GAME In questa modalità dovrai indovinare il videogioco basandoti sulle sue caratteristiche e dalla visione parziale della sua copertina";
        let dGTS = "GUESS THE SCREENSHOT In questa modalità dovrai indovinare il videogioco basandoti sullo screeenshot di una parte di gioco";

        let divSpiegazione = document.createElement("div");
        divSpiegazione.id = "spiegazione";

        for (let div of divMods) {

            div.addEventListener("mouseover", function() {
                if (div.id === "GTG")
                    divSpiegazione.innerHTML = dGTG;
                else
                    divSpiegazione.innerHTML = dGTS;

                div.appendChild(divSpiegazione);
            })
            div.addEventListener("mouseleave", function() {
                divSpiegazione.innerHTML = "";
            })

            div.addEventListener("click", function() {
                let nome = div.id;
                if (nome === "GTG")
                    window.location.href = "GTG.php";
                else
                    window.location.href = "GTS.php";
            })
        }

    })
</script>

<body>
    <?php include 'header.php'; ?>
    <div id="container">

        <h1>Scegli la modalità di gioco!</h1>

        <div id=modalità style=display:flex;>

            <?php
            foreach ($modalità as $value) {
                echo "<div id=$value class=modalita>";
                echo "<img src=../files/imgs/$value.png height=200 width=200 alt=$value>";
                echo "<h2>" . $value . "</h2>";

                echo "</div>";
            }
            ?>
            
        </div>
    </div>


</body>

</html>