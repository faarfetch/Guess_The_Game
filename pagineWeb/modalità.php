<?php
//pagina che permette la scelta della modalità di gioco 
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != 1) {
    header("location: login.php?message=effettuare il login");
    exit();
}

$modalità=[
    "GTG",
    "GTS"
];

$dGTG="In questa modalità di gioco dovrai indovinare il videogioco basandoti su bla bla bla";
$dGTS="In questa modalità di gioco dovrai indovinare il videogioco basandoti su bla bla bla";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioca!</title>
</head>
<style>
    #container{
        display: flex;
        text-align: center;
    }

</style>
<script>
    document.addEventListener("DOMContentLoaded", function(){

        let divMods=document.getElementsByClassName("modalita");

        for (let div of divMods) {
            
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
            echo "<h2>".$value."</h2>";

            echo "</div>";
        }
        echo "</div>";
    ?>

    </div>
</body>
</html>