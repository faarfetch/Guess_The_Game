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

<body>
    <h1>Guess The Game</h1>

    <a href="home.php">home</a>
    <a href="../gestori/gestoreLogout.php">logout</a>
</body>
</html>