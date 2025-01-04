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


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>recap Partita</h1>



    <a href="home.php">home</a>
    <a href="../gestori/gestoreLogout.php">logout</a>
</body>

</html>