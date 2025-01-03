<?php
//pagina dove lutente puo scelgliere la modalita o le classifiche bla bla bla
//si arriva a punteggi
//si arriva a modalita
//si arriva a classifica
//si arriva a impostazioni 
//si arriva a daily challenge
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
    <h1>home</h1>
</body>

</html>