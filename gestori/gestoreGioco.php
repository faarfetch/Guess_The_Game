<?php
//gestore degli utenti
require_once("gestoreAPI.php");


class gestioreGioco
{

    private $API;

    public function __construct()
    {
        $this->API = new GestoreAPI();
    }

    public function getRandomGame()
    {
        
    }

    public function getGameInfo($nomeGioco)
    {
        $APIresponse = $this->API->getGameInfo($nomeGioco);

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

    public function getGameImages($nomeGioco){
        $APIresponse = $this->API->getGameScreenShots($nomeGioco);

        foreach($APIresponse["results"] as $screenshot){
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
            $gameInfo["nome"].";".
            $gameInfo["data"].";".
            $gameInfo["playtime"].";".
            $gameInfo["immagine"]." ;".
            $gameInfo["rating"].";".
            $gameInfo["meta"].";".
            $this->getStringFromArray($gameInfo["generi"]).";".
            $this->getStringFromArray($gameInfo["tags"]).";".
            $this->getStringFromArray($gameInfo["publishers"]).";".
            $this->getStringFromArray($gameInfo["platforms"])."\n";
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

}
$gioco = new gestioreGioco();
print "<pre>";
//print_r($gioco->getGameInfo("hollow knight"));
//print_r($gioco->getGameImages("hollow knight"));
print "</pre>";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>