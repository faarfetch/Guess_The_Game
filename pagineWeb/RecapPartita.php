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

if (isset($_SESSION["gameStatus"]) && $_SESSION["gameStatus"] == "WINGTG") {
    include_once '../gestori/gestoreGioco.php';
    $gestoreGioco = new gestioreGioco();
    $gestoreGioco->addWin("GTG");
    $_SESSION["gameStatus"] = "";
}
if (isset($_SESSION["gameStatus"]) && $_SESSION["gameStatus"] == "WINGTS") {
    include_once '../gestori/gestoreGioco.php';
    $gestoreGioco = new gestioreGioco();
    $gestoreGioco->addWin("GTS");
    $_SESSION["gameStatus"] = "";
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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<body>
    <?php include 'header.php'; ?>

    <div id="container">
        <?php
        if (isset($_GET["msg"])) {
            echo ("<h1>" . $_GET["msg"] . "</h1>");
        }
        echo "<h2>Riepilogo Partita</h1>";

        echo "<div id=recap>";
        $numOfGuesses = count(file("../files/game/currentGame.csv"));
        echo "Tentativi effettuati: " . $numOfGuesses . "<br>";


        $currentGame = file_get_contents("../files/game/currentGame.csv");

        $tentativi = explode("\n", $currentGame);

        foreach ($tentativi as $tentativo) {
            $caratteristicheGioco = explode(";", $tentativo);
            echo $caratteristicheGioco[0] . "<br>";
        }
        echo "</div>";
        
        echo '<a href="'.$_SESSION["gameMode"].'.php"><button style="color: black;">Gioca ancora!</button></a>';

        if (isset($_SESSION["gameMode"]) && $_SESSION["gameMode"] != "") {
            $_SESSION["gameMode"] = "";
        }
        ?>

        
    </div>
</body>

</html>