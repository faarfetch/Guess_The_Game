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
        margin: 0px 5px;
        background-color: rgba(0, 0, 0, 0.8);
        background-blend-mode: darken;
        background-size: cover;
        background-position: center;
    }

    .guess {
        display: flex;
        margin: 20px;

    }

    .maggiore {
        background-image: url("../files/imgs/frecciaGiu.png");
    }

    .minore {
        background-image: url("../files/imgs/frecciaSu.png");
    }

    .ugule {
        background-color: rgba(51, 255, 0, 0.56);
    }

    .giusto {
        background-color: rgba(51, 255, 0, 0.56);
    }

    .quasi {
        background-color: rgba(238, 255, 0, 0.56);
    }

    .sbagliato {
        background-color: rgba(255, 0, 0, 0.56);
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
                $gestoreGioco->guess($_POST['guess']);
            }
            ?>

        </div>

    </div>

</body>

</html>