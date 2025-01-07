<?php
//pagina dove verra gestita la fase di login in 
require_once("gestoreUtenti.php");

if (!isset($_SESSION)) {
    session_start();
}

//se mancano dei dati rimanda indietro lutente
if (!isset($_POST["username"]) || $_POST["username"] == "" || !isset($_POST["password"]) || $_POST["password"] == "") {
    header("location:../pagineWeb/login.php?message=inserire username e password");
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$gestoreUtenti = new gestoreUtenti();

//se lutente sta cercando di fare il login controllo controllo la validita dei dati e se sono corretti lo mando alla home
if (!isset($_POST["password2"])) {
    if ($gestoreUtenti->login($username, $password) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION["autenticato"] = 1;
        header("location:../pagineWeb/home.php?msg=login effettuato");
        exit();
    }
    
} 
//ae lutente sta cercando di registrarsi controlla se è valido e lo manda sempre alla pagina home
else {
    if ($password == $_POST['password2']) {
        if ($gestoreUtenti->registrazione($username, $password) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION["autenticato"] = 1;
            header("location:../pagineWeb/home.php?msg=registrazione effettuata");
            exit();
        }
    }
}


//aw tutto va male lo rimando alla pagina di login
header("location:../pagineWeb/login.php?message=login fallito");
exit();
