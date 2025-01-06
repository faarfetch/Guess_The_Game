<?php
//modalita di gioco alternativa
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
    $_SESSION["game"] = "GTS";
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
    <title>GTS</title>
</head>
<link rel="stylesheet" href="../style/general.css">

<body>
    <?php include 'header.php'; ?>
    <div id="container">
        <h1>Guess The ScreenShot</h1>
        <form method="post" action="">
            <input type="text" name="guess" id="guess" style="color: black;" onkeyup="showSuggestions(this.value)">
            <button type="submit" name="submit" style="color: black;">Indovina!</button>
            <div id="suggestions"
                style="border: 1px solid #ccc; background-color: black; max-height: 150px; overflow-y: auto; display: none;">
            </div>
        </form>
        <div id="guesses">
            <?php
            if (isset($_POST['submit'])) {
                $gestoreGioco = new gestioreGioco();
                if ($gestoreGioco->guess($_POST['guess']) == 1) {

                    header("location: recapPartita.php?msg=hai vinto");
                    exit();
                }
            }
            ?>

        </div>
    </div>


</body>
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

</html>