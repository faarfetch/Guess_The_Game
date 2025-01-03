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