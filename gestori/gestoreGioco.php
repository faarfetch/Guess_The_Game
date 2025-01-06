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
            return  $this->getGameInfo($games[$randomIndex]);
        else
            return $this->getGameImages($games[$randomIndex]);
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
        $file = fopen('../files/game/currentGame.csv', 'a');
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
        fwrite($file, $gameInfoString);
        fclose($file);
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
            //file_put_contents('../files/game/currentGame.csv', '');
            $_SESSION["game"] = "WIN";

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
        echo ("<div class='guess'>");
        echo ("<div class='guess_element' style='background-image: url(" . $gameInfo['immagine'] . ");'>" . $gameInfo['nome'] . "</div>");
        echo ("<div class='guess_element " . $classArray["data"] . "'>" . $gameInfo['data'] . "</div>");
        echo ("<div class='guess_element " . $classArray["playtime"] . "'>" . $gameInfo['playtime'] . "</div>");
        echo ("<div class='guess_element " . $classArray["generi"] . "'>" . $this->getStringFromArray($gameInfo['generi']) . "</div>");
        echo ("<div class='guess_element " . $classArray["tags"] . "'>" . $this->getStringFromArray($gameInfo['tags']) . "</div>");
        echo ("<div class='guess_element " . $classArray["platforms"] . "'>" . $this->getStringFromArray($gameInfo['platforms']) . "</div>");
        echo ("<div class='guess_element " . $classArray["publishers"] . "'>" . $this->getStringFromArray($gameInfo['publishers']) . "</div>");
        echo ("<div class='guess_element " . $classArray["rating"] . "'>" . $gameInfo['rating'] . "</div>");
        echo ("<div class='guess_element " . $classArray["meta"] . "' style='background-image: url(\"../files/imgs/Metacritic.png\");'>" . $gameInfo['meta'] . "</div>");
        echo ("</div>");
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

    public function addWin()
    {

        if (isset($_SESSION["username"])) {
            $username = $_SESSION["username"];
            $file = file_get_contents('../files/users/users.csv');
            $users = explode("\n", $file);
            $newFile = "";
            foreach ($users as $user) {
                $campi = explode(";", $user);
                if (count($campi) >= 3 && $campi[0] == $username) {
                    $campi[2] = $campi[2] + 1;
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
