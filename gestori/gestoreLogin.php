<?php
//pagina dove verra gestita la fase di login in 
require_once("gestoreUtenti.php");


if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_POST["username"]) || !isset($_POST["password"])) {
    header("location:../pagineWeb/login.php?message=inserire username e password");
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$gestoreUtenti = new gestoreUtenti();


if (!isset($_POST["password2"])) {
    if ($gestoreUtenti->login($username, $password) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION["autenticato"] = 1;
        header("location:../pagineWeb/home.php?msg=login effettuato");
        exit();
    }
} else {
    if ($password == $_POST['password2']) {
        if ($gestoreUtenti->registrazione($username, $password) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION["autenticato"] = 1;
            header("location:../pagineWeb/home.php?msg=registrazione effettuata");
            exit();
        }
    }
}



header("location:../pagineWeb/login.php?message=login fallito");
exit();
