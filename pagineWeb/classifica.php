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


<body>
    <?php include 'header.php'; ?>
    <div id="container">
        <h1>Classifiche</h1>
        <div style="display: flex; justify-content: space-around; gap: 200px;">
            <div>
                <?php
                function stampaClassifica($classifica)
                {
                    $posizione = 1;
                    echo "<table>";
                    echo "<tr>";
                    echo "<th>Posizione</th>";
                    echo "<th>Username</th>";
                    echo "<th>Punteggio</th>";
                    echo "</tr>";
                    foreach ($classifica as $utente) {
                        echo "<tr>";
                        echo "<td>" . $posizione . "</td>";
                        echo "<td>" . $utente["username"] . "</td>";
                        echo "<td>" . $utente["punteggio"] . "</td>";
                        echo "</tr>";
                        $posizione++;
                    }
                    echo "</table>";
                }

                include_once '../gestori/gestoreUtenti.php';
                $gestoreUtente = new gestoreUtenti();

                $classifica = $gestoreUtente->getClassifica("GTG");
                echo "<h1>Classifica GTG</h1>";
                stampaClassifica($classifica);
                ?>
            </div>
            <div>
                <?php
                $classifica = $gestoreUtente->getClassifica("GTS");
                echo "<h1>Classifica GTS</h1>";
                stampaClassifica($classifica);
                ?>
            </div>
        </div>
    </div>


</body>

</html>