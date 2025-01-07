<?php
//gestore degli utenti
if (!isset($_SESSION)) {
    session_start();
}
require_once("gestoreAPI.php");

class gestioreGioco
{

    private $API;

    public function __construct()
    {
        $this->API = new GestoreAPI();
    }

    public function getRandomGame($modalita)
    {
        file_put_contents('../files/game/currentGame.csv', '');
        file_get_contents('../files/altro/gamelist.json');
        $games = json_decode(file_get_contents('../files/altro/gamelist.json'), true);
        $randomIndex = array_rand($games);
        print_r($games[$randomIndex]);
        if ($modalita === "GTG")
            return $this->getGameInfo($games[$randomIndex]);
        else
            return $games[$randomIndex];
    }

    public function guessScreen($guess)
    {

        $giocoDaIndovinare = $_SESSION["screenAnswer"];
        $screenGioco = $this->getGameImages($giocoDaIndovinare);
        $this->stampaScreen($screenGioco);

        $vite = sizeof($screenGioco);
        echo ("<div>vite rimanenti: " . $vite-count(file("../files/game/currentGame.csv")) . "</div>");

        if ($this->controlloGuess($guess)) {
            header("location: ../recapPartita.php?msg=hai vinto");
            exit;
        } else {
            file_put_contents('../files/game/currentGame.csv', $guess . "\n", FILE_APPEND);
            if ($vite == count(file("../files/game/currentGame.csv"))) {
                header("location: ../RecapPartita.php?msg=hai perso");
                exit;
            }
        }
    }

    public function stampaScreen($screenGioco)
    {
        if ($this->getTentativi() < 5) {

            echo "<div id=screens>";
            echo "<img src=" . $screenGioco[$this->getTentativi()] . " style='width: 50px heigth: 50px;'>";
            echo "</div>";
        }
    }

    function getTentativi()
    {
        return count(file('../files/game/currentGame.csv'));
    }

    public function getGameInfo($nomeGioco)
    {

        do {
            $APIresponse = $this->API->getGameInfo($nomeGioco);
        } while ($APIresponse == null);

        foreach ($APIresponse["genres"] as $genere) {
            $generi[] = $genere["name"];
        }
        foreach ($APIresponse["tags"] as $tag) {
            $tags[] = $tag["name"];
        }
        foreach ($APIresponse["publishers"] as $publisher) {
            $publishers[] = $publisher["name"];
        }
        foreach ($APIresponse["platforms"] as $platform) {
            $platforms[] = $platform["platform"]["name"];
        }

        $gameInfo = array(
            "nome" => $APIresponse["name"],
            "data" => $APIresponse["released"],
            "playtime" => $APIresponse["playtime"],
            "immagine" => $APIresponse["background_image"],
            "rating" => $APIresponse["rating"],
            "meta" => $APIresponse["metacritic"],
            "generi" => $generi,
            "tags" => $tags,
            "publishers" => $publishers,
            "platforms" => $platforms
        );

        return $gameInfo;
    }

    public function getGameImages($nomeGioco)
    {
        $APIresponse = $this->API->getGameScreenShots($nomeGioco);

        foreach ($APIresponse["results"] as $screenshot) {
            $screenshots[] = $screenshot["image"];
        }

        return $screenshots;
    }

    public function getStringFromArray($array)
    {
        $string = "";
        foreach ($array as $element) {
            $string .= $element . ", ";
        }
        return rtrim($string, ", ");
    }

    public function saveGameInfo($gameInfo)
    {
        $gameInfoString =
            $gameInfo["nome"] . ";" .
            $gameInfo["data"] . ";" .
            $gameInfo["playtime"] . ";" .
            $gameInfo["immagine"] . " ;" .
            $gameInfo["rating"] . ";" .
            $gameInfo["meta"] . ";" .
            $this->getStringFromArray($gameInfo["generi"]) . ";" .
            $this->getStringFromArray($gameInfo["tags"]) . ";" .
            $this->getStringFromArray($gameInfo["publishers"]) . ";" .
            $this->getStringFromArray($gameInfo["platforms"]) . "\n";
        file_put_contents('../files/game/currentGame.csv', $gameInfoString, FILE_APPEND);
    }


    public function getGameInfoFromCSV($line)
    {
        $file = file_get_contents('../files/game/currentGame.csv');
        $gamesInfo = explode("\n", $file);
        $gameInfo = explode(";", $gamesInfo[$line]);

        $gameInfoArray = array(
            "nome" => $gameInfo[0],
            "data" => $gameInfo[1],
            "playtime" => $gameInfo[2],
            "immagine" => $gameInfo[3],
            "rating" => $gameInfo[4],
            "meta" => $gameInfo[5],
            "generi" => explode(", ", $gameInfo[6]),
            "tags" => explode(", ", $gameInfo[7]),
            "publishers" => explode(", ", $gameInfo[8]),
            "platforms" => explode(", ", $gameInfo[9])
        );

        return $gameInfoArray;
    }

    public function guess()
    {
        $numOfGuesses = count(file("../files/game/currentGame.csv"));
        if ($numOfGuesses == 9) {
            //file_put_contents('../files/game/currentGame.csv', '');
            header("Location: recapPartita.php?msg=hai perso");
            exit();
        }

        $gameInfo = $this->getGameInfo($_POST['guess']);


        $guessInfoArray = $this->checkCorrectAnswer($gameInfo);
        if ($guessInfoArray == 1) {
            $_SESSION["game"] = "WINGTG";

            return 1;
        }
        $this->saveGameInfo($gameInfo);


        $classArray = $this->getClassArray($guessInfoArray);


        echo ("<div>vite rimanenti: " . (9 - $numOfGuesses) . "</div>");
        for ($i = 0; $i < $numOfGuesses + 1; $i++) {
            $gameInfo = $this->getGameInfoFromCSV($i);
            $classArray = $this->getClassArray($this->checkCorrectAnswer($gameInfo));
            $this->printHTML($gameInfo, $classArray);
        }
    }


    public function checkCorrectAnswer($gameInfo)
    {


        if (!isset($_SESSION['answer'])) {
            $_SESSION['answer'] = $this->getRandomGame("GTG");
        }
        $correctAnswer = $_SESSION['answer'];

        if ($gameInfo['nome'] == $correctAnswer['nome']) {
            return 1;
        }
        $mMu = [
            "playtime",
            "rating",
            "meta"
        ];
        $arrays = [
            "generi",
            "tags",
            "publishers",
            "platforms"
        ];

        if (strtotime($gameInfo["data"]) > strtotime($correctAnswer["data"])) {
            $guessInfoArray["data"] = "maggiore";
        } else if (strtotime($gameInfo["data"]) < strtotime($correctAnswer["data"])) {
            $guessInfoArray["data"] = "minore";
        } else if (strtotime($gameInfo["data"]) == strtotime($correctAnswer["data"])) {
            $guessInfoArray["data"] = "uguale";
        }


        foreach ($mMu as $key => $nome) {
            if ($gameInfo[$nome] > $correctAnswer[$nome]) {
                $guessInfoArray[$nome] = "maggiore";
            } else if ($gameInfo[$nome] < $correctAnswer[$nome]) {
                $guessInfoArray[$nome] = "minore";
            } else if ($gameInfo[$nome] == $correctAnswer[$nome]) {
                $guessInfoArray[$nome] = "uguale";
            }
        }

        foreach ($arrays as $key => $nome) {
            $guessInfoArray[$nome] = count(array_diff($gameInfo[$nome], $correctAnswer[$nome]));
        }

        return $guessInfoArray;
    }


    public function printHTML($gameInfo, $classArray)
    {
        if ($gameInfo['meta'] == null) {
            $gameInfo['meta'] = "N/A";
        }
        echo "<table class='guess'>";
        echo "<tr><th>Image</th><th>Name</th><th>Release Date</th><th>Playtime</th><th>Genres</th><th>Tags</th><th>Platforms</th><th>Publishers</th><th>Rating</th><th>Metacritic</th></tr>";
        echo "<tr>";
        echo "<td class='guess_element' style='background-image: url(" . $gameInfo['immagine'] . ");'></td>";
        echo "<td class='guess_element'>" . $gameInfo['nome'] . "</td>";
        echo "<td class='guess_element " . $classArray["data"] . "'>" . $gameInfo['data'] . "</td>";
        echo "<td class='guess_element " . $classArray["playtime"] . "'>" . $gameInfo['playtime'] . "</td>";
        echo "<td class='guess_element " . $classArray["generi"] . "'>" . $this->getStringFromArray($gameInfo['generi']) . "</td>";
        echo "<td class='guess_element " . $classArray["tags"] . "'>" . $this->getStringFromArray($gameInfo['tags']) . "</td>";
        echo "<td class='guess_element " . $classArray["platforms"] . "'>" . $this->getStringFromArray($gameInfo['platforms']) . "</td>";
        echo "<td class='guess_element " . $classArray["publishers"] . "'>" . $this->getStringFromArray($gameInfo['publishers']) . "</td>";
        echo "<td class='guess_element " . $classArray["rating"] . "'>" . $gameInfo['rating'] . "</td>";
        echo "<td class='guess_element " . $classArray["meta"] . "' style='background-image: url(\"../files/imgs/Metacritic.png\");'>" . $gameInfo['meta'] . "</td>";
        echo "</tr>";
        echo "</table>";


        
    }

    public function stampaImmagine($immagine)
    {
        echo "<img src=$immagine alt='screen del gioco' style='width: 1000px; height: auto;'>";
    }

    private function getClassArray($guessInfoArray)
    {
        $classArray = array();

        $mMu = [
            "data",
            "playtime",
            "rating",
            "meta"
        ];
        $arrays = [
            "generi",
            "tags",
            "publishers",
            "platforms"
        ];


        foreach ($mMu as $key => $name) {
            $classArray[$name] = $guessInfoArray[$name];
        }

        foreach ($arrays as $key => $name) {
            if ($guessInfoArray[$name] == 0) {
                $classArray[$name] = "giusto";
            }
            if ($guessInfoArray[$name] > 0) {
                $classArray[$name] = "quasi";
            }
            if ($guessInfoArray[$name] > 3) {
                $classArray[$name] = "sbagliato";
            }
        }

        return $classArray;
    }

    public function addWin($modalita)
    {
        if($modalita == "GTG")
            $campoGiusto = 2;
        else if ($modalita == "GTS")
            $campoGiusto = 3;
        else
            return;
        
        if (isset($_SESSION["username"])) {
            $username = $_SESSION["username"];
            $file = trim(file_get_contents('../files/users/users.csv'));
            $users = explode("\n", $file);
            $newFile = "";
            foreach ($users as $user) {
                $campi = explode(";", $user);
                if (count($campi) >= 3 && $campi[0] == $username) {
                    $campi[$campoGiusto] = $campi[$campoGiusto] + 1;
                }
                $newFile .= implode(";", $campi) . "\n";
            }
            file_put_contents('../files/users/users.csv', $newFile);
        }
    }
}
$gioco = new gestioreGioco();
/*
print "<pre>";
print_r($gioco->getGameInfo("sekiro"));
//print_r($gioco->getGameImages("hollow knight"));
print "</pre>";
*/
