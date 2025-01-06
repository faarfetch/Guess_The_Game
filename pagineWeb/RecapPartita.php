<?php
//pagina utilizzarla per la fase di recap di tutte le modalita 
//da la possibilita di ripartire subito con un altra partita
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != 1) {
    header("location: login.php?message=effettuare il login");
    exit();
}

if (isset($_SESSION["game"]) && $_SESSION["game"] == "WIN") {
    include_once '../gestori/gestoreGioco.php';
    $gestoreGioco = new gestioreGioco();
    $gestoreGioco->addWin();
}


if (isset($_SESSION["game"]) && $_SESSION["game"] != "") {
    $_SESSION["game"] = "";
}

if (isset($_SESSION["answer"]) && $_SESSION["answer"] != "") {
    $_SESSION["answer"] = "";
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riepilogo Partita</title>
</head>
<link rel="stylesheet" href="../style/general.css">

<body>
    <?php include 'header.php'; ?>

    <div id="container">
        <?php
        if (isset($_GET["msg"])) {
            echo ("<h1>" . $_GET["msg"] . "</h1>");
        }
        echo "<h1>Riepilogo Partita</h1>";

        $numOfGuesses = count(file("../files/game/currentGame.csv"));
        echo "Tentativi effettuati: " . $numOfGuesses . "<br>";


        $currentGame = file_get_contents("../files/game/currentGame.csv");
        //echo $currentGame;

        $tentativi = explode("\n", $currentGame);
        //print_r($tentativi);

        foreach ($tentativi as $tentativo) {
            $caratteristicheGioco = explode(";", $tentativo);
            echo $caratteristicheGioco[0] . "<br>";
        }
        ?>

        <a href="GTG.php"><button style="color: black;">Gioca ancora!</button></a>
    </div>
</body>

</html>