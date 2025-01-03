<?php
//utilizzata per infaccaire le API di rawg.io
class GestoreAPI
{


    private $__APIKEY = "32afb6ae78a34aef86706e686d43fea5"; //DA NASCONDERE!!!!!!!

    public function __construct() {}

    public function getGameList($starting = 1, $ending = 50)
    {
        $url = "https://api.rawg.io/api/games?key=" . $this->__APIKEY . "&page_size=" . 40;
        $gameList = [];

        for ($i = $starting; $i <= $ending; $i++) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url . "&page=" . $i);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response, true);
            if (isset($response['results'])) {
                $gameList = array_merge($gameList, $response['results']);
            }
        }
        return $gameList;
    }

    public function getAllGamesName()
    {
        $gameList = $this->getGameList();

        $gameNames = array_column($gameList, 'name');
        return $gameNames;
    }

    public function refreshGameFile()//NON UTILIZZATO
    {
        $gameNames = $this->getAllGamesName();
        $filePath = "../files/gamelist.json";

        // Read the existing content of the file
        $existingContent = file_get_contents($filePath);
        $existingArray = json_decode($existingContent, true);

        // Merge the existing content with the new game names and remove duplicates
        $newContent = array_unique(array_merge($existingArray, $gameNames));

        // Write the merged content back to the file
        $gameFile = fopen($filePath, "w");
        fwrite($gameFile, json_encode($newContent, JSON_PRETTY_PRINT));
        fclose($gameFile);
    }

    public function getGameId($gameName)
    {
        $url = "https://api.rawg.io/api/games?search=" . urlencode($gameName) . "&key=" . $this->__APIKEY;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);


        if (isset($response['results'][0]['id'])) {
            return $response['results'][0]['id'];
        } else {
            return null;
        }
    }

    public function getGameInfo($id)
    {
        if (!is_numeric($id)) {
            $id = $this->getGameId($id);
        }

        $url = "https://api.rawg.io/api/games/" . $id . "?key=" . $this->__APIKEY;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function getGameScreenShots($id)
    {
        if (!is_numeric($id)) {
            $id = $this->getGameId($id);
        }

        $url = "https://api.rawg.io/api/games/" . $id . "/screenshots" . "?key=" . $this->__APIKEY;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function getGameAchievements($id)
    {
        if (!is_numeric($id)) {
            $id = $this->getGameId($id);
        }

        $url = "https://api.rawg.io/api/games/" . $id . "/achievements" . "?key=" . $this->__APIKEY;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}


$API = new GestoreAPI();
//print_r($API->getGameList()); 
//print_r($API->getGameId("The Witcher 3: Wild Hunt"));
//print_r($API->getGameInfo(3498)); 
//print_r($API->getGameInfo("Outer Wilds"));
//print_r($API->getGameScreenShots(3498));
//print_r($API->getGameScreenShots("Outer Wilds"));
//print_r($API->getGameAchievements(3498));
//print_r($API->getGameAchievements("hollow knight"));
//tutte chiamate funzionanti che altri file possono utilizzare

/*
foreach($API->getGameScreenShots("Outer Wilds")['results'] as $screenshot) {
    echo "<img src='" . $screenshot['image'] . "' alt=''>";
}
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>prova</h1>

</body>

</html>