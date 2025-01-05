<?php
//pagina principale del gioco questa pagina verra utilizzzata sia nella modalita principale che nella daily challenge
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != 1) {
    header("location: login.php?message=effettuare il login");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="stylesheet" href="../style/general.css">
<link rel="stylesheet" href="../style/custom.css">

<style>
    .guess_element {
        border: 2px solid #fff;
        border-radius: 10px;
        padding: 10px;
        height: 50px;
        margin: 0px 5px;
        background-color: rgba(0, 0, 0, 0.8);
        background-blend-mode: darken;
        background-size: cover;
    }

    .guess {
        display: flex;
        margin: 20px;

    }
</style>

<body>
    <?php include 'header.php'; ?>
    <div id="container">
        <h1>Guess The Game</h1>
        <form method="post" action="">
            <input type="text" name="guess" id="guess" style="color: black;">
            <button type="submit" name="submit" style="color: black;">Indovina!</button>
        </form>
        <div id="guesses">
            <?php
            if (isset($_POST['submit'])) {
                include '../gestori/gestoreGioco.php';
                $gestoreGioco = new gestioreGioco();
                $gameInfo = $gestoreGioco->getGameInfo($_POST['guess']);


                //count lines in currentGame
                $currentGameLines = count(file("../files/game/currentGame.csv"));

                $gestoreGioco->saveGameInfo($gameInfo);

                for ($i = 0; $i < $currentGameLines+1; $i++) {
                    $gameInfo = $gestoreGioco->getGameInfoFromCSV($i);
                    echo ("<div class='guess'>");
                    echo ("<div class='nome guess_element' style='background-image: url(" . $gameInfo['immagine'] . ");''>" . $gameInfo['nome'] . "</div>");
                    echo ("<div class='data guess_element'>" . $gameInfo['data'] . "</div>");
                    echo ("<div class='playtime guess_element'>" . $gameInfo['playtime'] . "</div>");
                    echo ("<div class='generi guess_element'>" . $gestoreGioco->getStringFromArray($gameInfo['generi']) . "</div>");
                    echo ("<div class='tags guess_element'>" . $gestoreGioco->getStringFromArray($gameInfo['tags']) . "</div>");
                    echo ("<div class='piattaforma guess_element'>" . $gestoreGioco->getStringFromArray($gameInfo['platforms']) . "</div>");
                    echo ("<div class='publishers guess_element'>" . $gestoreGioco->getStringFromArray($gameInfo['publishers']) . "</div>");
                    echo ("<div class='voto guess_element'>" . $gameInfo['rating'] . "</div>");
                    echo ("<div class='metacritic guess_element' style='background-image: url('../files/imgs/Metacritic.png');'>" . $gameInfo['meta'] . "</div>");
                    echo ("</div>");
                }
            }
            ?>

        </div>

    </div>

</body>

</html>