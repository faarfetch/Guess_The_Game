<?php
//visualizzazione della classifica generale (top 50 punteggi degli utenti)



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica</title>
</head>
<link rel="stylesheet" href="../style/general.css">

<style>
    
</style>


<body>
<?php include 'header.php'; ?>
<div id="container">
    <h1>Classifica</h1>

    <table>
        <tr>
            <th>Posizione</th>
            <th>Username</th>
            <th>Punteggio</th>
        </tr>
        <?php
        include_once '../gestori/gestoreUtenti.php';
        $gestoreUtente = new gestoreUtenti();
        $classifica = $gestoreUtente->getClassifica();
        $posizione = 1;
        foreach ($classifica as $utente) {
            echo "<tr>";
            echo "<td>" . $posizione . "</td>";
            echo "<td>" . $utente["username"] . "</td>";
            echo "<td>" . $utente["punteggio"] . "</td>";
            echo "</tr>";
            $posizione++;
        }
        ?>

</div>


</body>

</html>