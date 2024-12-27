<?php
//utilizzata per infaccaire le API di rawg.io
class GestoreAPI {
    
    
    private $__APIKEY = "32afb6ae78a34aef86706e686d43fea5";

    public function __construct() {
        
    }

    public function getGameList() {
        $url = "https://api.rawg.io/api/games?key=" . $this->__APIKEY;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function getGameId($gameName) {
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

    public function getGameInfo($id) {
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

    public function getGameScreenShots($id) {
        if (!is_numeric($id)) {
            $id = $this->getGameId($id);
        }

        $url = "https://api.rawg.io/api/games/" . $id ."/screenshots". "?key=" . $this->__APIKEY;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public function getGameAchievements($id) {
        if (!is_numeric($id)) {
            $id = $this->getGameId($id);
        }

        $url = "https://api.rawg.io/api/games/" . $id ."/achievements". "?key=" . $this->__APIKEY;

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
//print_r($API->getGameAchievements(3498));

//tutte chiamate funzionanti che altri file possono utilizzare

?>