<?php
//gestore degli utenti


//classe che gestisce gli utenti
class gestoreUtenti
{

    public function __construct() {}


    //funzione di login che controlla se il nome e la pwd combaciano e ritorna 1 se è valido e 0 se non lo è
    public function login($nome, $password)
    {
        $filePath = file_get_contents("../files/users/users.csv");
        $utenti = explode("\n", $filePath);
        foreach ($utenti as $utente) {
            $campi = explode(";", $utente);
            if (count($campi) >= 2 && $campi[0] == $nome && $campi[1] == $password) { // username;pwd;punteggio
                return 1;
            }
        }
        return 0;
    }


    //se il nome utente non è gia stato usato e non ha il carattere ; al suo intrerno crea il nuovo utente e lo aggiuge al file ritorna 0 se va male 1 se va tutto bene
    public function registrazione($nome, $password)
    {
        if (strpos($nome, ";") !== false || strpos($password, ";") !== false) {
            return 0;
        }

        $filePath = "../files/users/users.csv";
        $fileContent = file_get_contents($filePath);
        $utenti = explode("\n", $fileContent);
        
        foreach ($utenti as $utente) {
            $campi = explode(";", $utente);
            if (count($campi) >= 2 && $campi[0] == $nome) {
                return 0; // Username already exists
            }
        }
        
        $userData = $nome . ";" . $password . ";" . "0" . ";" . "0"."\n";
        file_put_contents($filePath, $userData, FILE_APPEND);
        return 1;
    }


    //ritorna la classifica gia ordinata della modalita scelta ("GTS"/"GTG")
    public function getClassifica($modalita)
    {

        if($modalita == "GTG")
            $campoGiusto = 2;
        else if ($modalita == "GTS")
            $campoGiusto = 3;
        else
            return;

        $filePath = file_get_contents("../files/users/users.csv");
        $utenti = explode("\n", $filePath);
        $classifica = array();
        foreach ($utenti as $utente) {
            $campi = explode(";", $utente);
            if (count($campi) >= 3) {
                $classifica[] = array("username" => $campi[0], "punteggio" => $campi[$campoGiusto]);
            }
        }
        usort($classifica, function ($a, $b) {
            return $b['punteggio'] - $a['punteggio'];
        });
        return array_slice($classifica, 0, 50);
    }
}
