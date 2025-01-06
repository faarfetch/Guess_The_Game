<?php
//pagina principale del gioco questa pagina verra utilizzzata sia nella modalita principale che nella daily challenge
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["autenticato"]) || $_SESSION["autenticato"] != 1) {
    header("location: login.php?message=effettuare il login");
    exit();
}
include_once '../gestori/gestoreGioco.php';
$gestoreGioco = new gestioreGioco();
if(!isset($_SESSION["game"]) || $_SESSION["game"] == ""){
    $_SESSION["game"] = "GTG";
    $_SESSION["answer"] = $gestoreGioco->getRandomGame($_SESSION["game"]);
    print_r($_SESSION["answer"]["nome"]);

}

if((isset($_SESSION["answer"]) && ($_SESSION["answer"] != ""))){
    print_r($_SESSION["answer"]["nome"]);
}

if((isset($_SESSION["game"]) && ($_SESSION["game"] != ""))){

    if($_SESSION["game"] == "WIN"){
        $_SESSION["game"] = "";
        $_SESSION["answer"] = "";
        //print_r("palle");
        header("location: RecapPartita.php?msg=hai vinto");
        exit();
    }

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
            <input type="text" name="guess" id="guess" style="color: black;" onkeyup="showSuggestions(this.value)">
            <button type="submit" name="submit" style="color: black;">Indovina!</button>
            <div id="suggestions" style="border: 1px solid #ccc; background-color: black; max-height: 150px; overflow-y: auto; display: none;"></div>
        </form>
        <div id="guesses">
            <?php
            if (isset($_POST['submit'])) {
                $gestoreGioco = new gestioreGioco();
                if($gestoreGioco->guess($_POST['guess'])==1){
                    
                    header("location: recapPartita.php?msg=hai vinto");
                    exit();
                }
            }
            ?>

        </div>

    </div>

    <script>
    let games = <?php echo json_encode(file_get_contents('../files/altro/gamelist.json')); ?>;
    games = JSON.parse(games);

    // Function to show suggestions
    function showSuggestions(query) {
        const suggestionsBox = document.getElementById("suggestions");
        suggestionsBox.innerHTML = ""; // Clear previous suggestions

        if (query.length === 0) {
            suggestionsBox.style.display = "none"; // Hide suggestions if query is empty
            return;
        }

        // Filter games based on user input
        const filteredGames = games.filter(game => game.toLowerCase().includes(query.toLowerCase()));

        if (filteredGames.length === 0) {
            suggestionsBox.style.display = "none"; // Hide if no suggestions
            return;
        }

        // Display suggestions
        suggestionsBox.style.display = "block";
        filteredGames.forEach(game => {
            const div = document.createElement("div");
            div.textContent = game;
            div.style.padding = "5px";
            div.style.cursor = "pointer";
            div.onclick = () => {
                document.getElementById("guess").value = game;
                suggestionsBox.style.display = "none"; // Hide suggestions after selection
            };
            suggestionsBox.appendChild(div);
        });
    }
</script>

</body>

</html>