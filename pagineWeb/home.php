<?php
//pagina dove lutente puo scelgliere la modalita o le classifiche bla bla bla
//si arriva a modalita
//si arriva a classifica
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
    <title>Home</title>
</head>
<style>
    #container {
        width: auto;
        display: flex;
        margin-left: 15px;
        text-align: center;

    }

    div {
        border-color: black;
        text-align: center;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        //const divContainer=document.getElementById("container");
        let divScelte = document.getElementsByClassName("scelte");

        for (let div of divScelte) {


            div.addEventListener("click", function () {  
                let nome=div.id;
                if(nome==="daily")
                    window.location.href = "GTG.php"; 
                else   
                    window.location.href = nome+".php";
             })
        }

    })


</script>

<body>
    
<h1>Scegli cosa fare!</h1>
    <?php
    $scelteUtente = [
        "modalità",
        "classifica",
        "daily"
    ];

    echo "<div id=container>";
    foreach ($scelteUtente as $value) {
        echo "<div id=$value class=scelte>";
        echo "<img src=../files/imgs/$value.png alt=$value>
                    <br>
                    <label for=$value>$value</label>";

        echo "</div>";
    }



    echo "</div>";
    ?>


    <a href="../gestori/gestoreLogout.php">logout</a>
</body>

</html>