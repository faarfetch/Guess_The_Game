<?php
//utilizzata per interfacciarsi con le API di rawg.io
class GestoreAPI
{

    private $__APIKEY = "32afb6ae78a34aef86706e686d43fea5"; //DA NASCONDERE!!!!!!! // Chiave API per accedere ai servizi di rawg.io

    public function __construct() {} // Costruttore vuoto della classe

    public function getGameList($starting = 1, $ending = 50)
    {
        // Metodo per ottenere una lista di giochi da rawg.io
        $url = "https://api.rawg.io/api/games?key=" . $this->__APIKEY . "&page_size=" . 40; // URL base delle API con parametri
        $gameList = []; // Array per memorizzare i risultati dei giochi

        for ($i = $starting; $i <= $ending; $i++) {
            // Ciclo per effettuare richieste su più pagine di risultati
            $curl = curl_init(); // Inizializza una sessione cURL
            curl_setopt($curl, CURLOPT_URL, $url . "&page=" . $i); // Imposta l'URL con il numero di pagina
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // Restituisce il risultato come stringa

            $response = curl_exec($curl); // Esegue la richiesta
            curl_close($curl); // Chiude la sessione cURL

            $response = json_decode($response, true); // Decodifica la risposta JSON
            if (isset($response['results'])) {
                // Controlla se la risposta contiene risultati
                $gameList = array_merge($gameList, $response['results']); // Aggiunge i risultati all'array
            }
        }
        return $gameList; // Restituisce la lista dei giochi
    }

    public function getAllGamesName()
    {
        // Metodo per ottenere i nomi di tutti i giochi
        $gameList = $this->getGameList(); // Ottiene la lista completa dei giochi

        $gameNames = array_column($gameList, 'name'); // Estrae la colonna "name" da ogni gioco
        return $gameNames; // Restituisce i nomi dei giochi
    }

    public function refreshGameFile() //NON UTILIZZATO
    {
        // Metodo per aggiornare il file contenente i nomi dei giochi
        $gameNames = $this->getAllGamesName(); // Ottiene i nomi dei giochi
        $filePath = "../files/gamelist.json"; // Percorso del file da aggiornare

        // Legge il contenuto esistente del file
        $existingContent = file_get_contents($filePath);
        $existingArray = json_decode($existingContent, true);

        // Unisce il contenuto esistente con i nuovi nomi e rimuove i duplicati
        $newContent = array_unique(array_merge($existingArray, $gameNames));

        // Scrive il contenuto unificato nel file
        $gameFile = fopen($filePath, "w");
        fwrite($gameFile, json_encode($newContent, JSON_PRETTY_PRINT));
        fclose($gameFile);
    }

    public function getGameId($gameName)
    {
        // Metodo per ottenere l'ID di un gioco dato il nome
        $url = "https://api.rawg.io/api/games?search=" . urlencode($gameName) . "&key=" . $this->__APIKEY;

        $curl = curl_init(); // Inizializza una sessione cURL
        curl_setopt($curl, CURLOPT_URL, $url); // Imposta l'URL della richiesta
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // Restituisce il risultato come stringa

        $response = curl_exec($curl); // Esegue la richiesta
        curl_close($curl); // Chiude la sessione cURL
        $response = json_decode($response, true); // Decodifica la risposta JSON

        if (isset($response['results'][0]['id'])) {
            // Restituisce l'ID del primo risultato
            return $response['results'][0]['id'];
        } else {
            return null; // Restituisce null se non trova l'ID
        }
    }

    public function getGameInfo($id) //funziona anche con il nome del gioco
    {
        // Metodo per ottenere le informazioni di un gioco dato l'ID o il nome
        if (!is_numeric($id)) {
            // Se l'ID non è numerico, lo converte da nome
            $id = $this->getGameId($id);
        }

        $url = "https://api.rawg.io/api/games/" . $id . "?key=" . $this->__APIKEY; // URL per ottenere le informazioni del gioco

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true); // Restituisce le informazioni del gioco
    }

    public function getGameScreenShots($id)
    {
        // Metodo per ottenere gli screenshot di un gioco dato l'ID o il nome
        if (!is_numeric($id)) {
            $id = $this->getGameId($id); // Converte il nome in ID se necessario
        }

        $url = "https://api.rawg.io/api/games/" . $id . "/screenshots" . "?key=" . $this->__APIKEY;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true); // Restituisce gli screenshot del gioco
    }
}

/*
$API = new GestoreAPI();
print "<pre>";
//print_r($API->getGameList()); 
//print_r($API->getGameId("ultrakill"));
for ($i=1; $i >0; $i++) { 
    if(isset($API->getGameInfo($i)["id"])){
        print_r($API->getGameInfo($i));
        break;
    }

}
//num of id 995589
//print_r($API->getGameInfo("dark souls"));
//print_r($API->getGameScreenShots(3498));
//print_r($API->getGameScreenShots("Outer Wilds"));
//print_r($API->getGameAchievements(3498));
//print_r($API->getGameAchievements("hollow knight"));
//tutte chiamate funzionanti che altri file possono utilizzare
print "</pre>";
/*
foreach($API->getGameScreenShots("Outer Wilds")['results'] as $screenshot) {
    echo "<img src='" . $screenshot['image'] . "' alt=''>";
}
*/
