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
<style>
    #container {
        display: flex;
        text-align: center;
    }

    .modalita{
        width: 250px;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        let divMods = document.getElementsByClassName("modalita");
        let dGTG = "GUESS THE GAME In questa modalità dovrai indovinare il videogioco basandoti sulle sue caratteristiche e dalla visione parziale della sua copertina";
        let dGTS = "GUESS THE SCREENSHOT In questa modalità dovrai indovinare il videogioco basandoti sullo screeenshot di una parte di gioco";

        let divSpiegazione = document.createElement("div");
        divSpiegazione.id = "spiegazione";

        for (let div of divMods) {

            div.addEventListener("mouseover", function () {
                if (div.id === "GTG")
                    divSpiegazione.innerHTML = dGTG;
                else
                    divSpiegazione.innerHTML = dGTS;

                div.appendChild(divSpiegazione);
            })
            div.addEventListener("mouseleave", function () {
                divSpiegazione.innerHTML = "";
            })

            div.addEventListener("click", function () {  
                let nome=div.id;
                if(nome==="GTG")
                    window.location.href = "GTG.php"; 
                else   
                    window.location.href = "GTS.php";
             })
        }

    })
</script>

<body>
    <h1>Scegli la modalità di gioco!</h1>
    <?php
    echo "<div id=container>";
    foreach ($modalità as $value) {
        echo "<div id=$value class=modalita>";
        echo "<img src=../files/imgs/$value.png>";
        echo "<h2>" . $value . "</h2>";

        echo "</div>";
    }
    echo "</div>";
    ?>

    </div>

    
</body>

</html>