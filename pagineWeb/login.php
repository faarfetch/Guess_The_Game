<?php
//pagina di registrazione dellutente 

if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION["autenticato"])) {
    if ($_SESSION["autenticato"] == 1) {
        header("Location: home.php");
        exit();
    }
}
if (isset($_GET["message"])) {
    echo ("<h1>" . $_GET["message"] . "</h1>");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        function toggleForm(formType) {
            if (formType === 'login') {
                document.getElementById('loginForm').style.display = 'block';
                document.getElementById('registerForm').style.display = 'none';
            } else {
                document.getElementById('loginForm').style.display = 'none';
                document.getElementById('registerForm').style.display = 'block';
            }
        }
    </script>
</head>

<body>
    <button onclick="toggleForm('login')">Login</button>
    <button onclick="toggleForm('register')">Registrazione</button>

    <div id="loginForm" style="display: none;">
        <h2>Login</h2>
        <form action="../gestori/gestoreLogin.php" method="post">
            <label for="loginUsername">Username</label>
            <input type="text" name="username" id="loginUsername"><br>
            <label for="loginPassword">Password</label>
            <input type="password" name="password" id="loginPassword"><br>
            <input type="submit" value="Login">
        </form>
    </div>

    <div id="registerForm" style="display: none;">
        <h2>Registrazione</h2>
        <form action="../gestori/gestoreLogin.php" method="post">
            <label for="registerUsername">Username</label>
            <input type="text" name="username" id="registerUsername"><br>
            <label for="registerPassword">Password</label>
            <input type="password" name="password" id="registerPassword"><br>
            <label for="registerPassword2">Conferma Password</label>
            <input type="password" name="password2" id="registerPassword2"><br>
            <input type="submit" value="Registrati">
        </form>
    </div>

    <script>
        // Default to showing the login form
        toggleForm('login');
    </script>
</body>

</html>