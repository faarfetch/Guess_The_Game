<?php
//gestore degli utenti
if (!isset($_SESSION)) {
    session_start();
}
require_once("gestoreAPI.php");

//classe che gestisce entrambe le modalita del gioco e che si interfaccia con l'API
class gestioreGioco
{

    //variabile con dentro la classe dell'api
    private $API;

    public function __construct()
    {
        $this->API = new GestoreAPI();
    }

    //funzione che gestisce un singolo guess della modalita GTG
    public function guess()
    {
        //gestione della perdita dell'utente
        $numOfGuesses = count(file("../files/game/currentGame.csv"));
        if ($numOfGuesses == 9) {
            //file_put_contents('../files/game/currentGame.csv', '');
            header("Location: recapPartita.php?msg=hai perso");
            exit();
        }

        $gameInfo = $this->getGameInfo($_POST['guess']);

        if($gameInfo == null){
            return 0;
        }

        //gedtione della vittoria dell'utente
        $guessInfoArray = $this->checkCorrectAnswer($gameInfo);
        if ($guessInfoArray == 1) {
            $_SESSION["gameStatus"] = "WINGTG";

            return 1;
        }

        //salva le info del gioco su file
        $this->saveGameInfo($gameInfo);

        //stampa tutte le cose visuali quindi vite rimanenti e guess fatti fino ad ora
        $classArray = $this->getClassArray($guessInfoArray);
        echo ("<div>vite rimanenti: " . (9 - $numOfGuesses) . "</div>");
        for ($i = 0; $i < $numOfGuesses + 1; $i++) {
            $gameInfo = $this->getGameInfoFromCSV($i);
            $classArray = $this->getClassArray($this->checkCorrectAnswer($gameInfo));
            $this->printHTML($gameInfo, $classArray);
        }
    }

    //funzione che gestisce un singolo guess della modalita GTS
    public function guessScreen($guess)
    {
        //gedtione della vittoria dell'utente
        if ($guess == $_SESSION["screenAnswer"]) {
            $_SESSION["gameStatus"] = "WINGTS";
            header("Location: recapPartita.php?msg=hai vinto");
            exit();
        }

        //salvo il tentativo nel file
        file_put_contents('../files/game/currentGame.csv', $guess . "\n", FILE_APPEND);


        //stampo tutte le cose visuali quindi vite rimanenti e screen
        $images = $this->getGameImages($_SESSION["screenAnswer"]);

        $vite = count($images);
        $numOfGuesses = count(file("../files/game/currentGame.csv"));
        echo "vite rimanenti: " . $vite - $numOfGuesses;
        echo "<br>";
        $this->stampaImmagine($images[$numOfGuesses]);


        //gestione della perdita dell'utente
        if ($numOfGuesses == $vite) {
            //file_put_contents('../files/game/currentGame.csv', '');
            header("Location: recapPartita.php?msg=hai perso");
            exit();
        }
    }

    //funzione che ritorna per la modalita "GTG" tutte le informazioni del gioco e solo il nome per la modalita GTS
    public function getRandomGame($modalita)
    {
        //per sicurezza elimina anche tutti i guess precedenti
        file_put_contents('../files/game/currentGame.csv', '');
        file_get_contents('../files/altro/gamelist.json');
        $games = json_decode(file_get_contents('../files/altro/gamelist.json'), true);
        $randomIndex = array_rand($games);
        //print_r($games[$randomIndex]);
        if ($modalita === "GTG")
            return $this->getGameInfo($games[$randomIndex]);
        else
            return $games[$randomIndex];
    }   

    //ritorna il numero di tentatativi fatti. funziona per entrambe le modalià
    function getTentativi()
    {
        return count(file('../files/game/currentGame.csv'));
    }

    //ritorna in un vettrore tutte le info del gioco di nome nomeGioco
    public function getGameInfo($nomeGioco)
    {

        //chiedo a api le info del gioco (continua fino a che api non da una risposta)
        do {
            $APIresponse = $this->API->getGameInfo($nomeGioco);
        } while ($APIresponse == null);

        if(isset($APIresponse["error"])){
            return null;
        }

        //formatta le informazioni del gioco
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


    //funzione che ritorna gli screnshot del gioco che si vuole
    public function getGameImages($nomeGioco)
    {
        do {
            $APIresponse = $this->API->getGameScreenShots($nomeGioco);
        } while ($APIresponse == null);

        if(isset($APIresponse["error"])){
            return null;
        }

        foreach ($APIresponse["results"] as $screenshot) {
            $screenshots[] = $screenshot["image"];
        }

        return $screenshots;
    }

    //trasforma un array in una stringa con ogni valore dellarray diviso da una virgola
    private function getStringFromArray($array)
    {
        $string = "";
        foreach ($array as $element) {
            $string .= $element . ", ";
        }
        return rtrim($string, ", ");
    }

    //salva nel file tutte le info passate come parametro nel file current game
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


    //data una linea(numero del tentativo) prende le info di quel gioco e le ritorna sottoforma di array
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

    //se la guess era corretta ritorna 1 se non è corretta ritorna le info di quanto era vicino a essere corretta
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


    //il primo parametro sono le informazioni del gioco, il secondo sono le info in paragone alla risposta corretta
    //questa funzione stampa le info in modo personalizzato a quanto simile la risposta era alla risposta corretta
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

    //data un immagine la stampa sul html
    public function stampaImmagine($immagine)
    {
        echo "<img src=$immagine alt='screen del gioco' style='width: 600px; height: auto;'>";
    }

    //date le informazioni sulla guess fata gestisce come poi lo style del html sara
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

    //aggiunge 1 al punteggio dell'utente attuale
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
//$gioco = new gestioreGioco();
/*
print "<pre>";
print_r($gioco->getGameInfo("sekiro"));
//print_r($gioco->getGameImages("hollow knight"));
print "</pre>";
*/
