<?php
//gestore degli utenti

class gestoreUtenti
{

    public function __construct()
    {
    }

    public function login($nome, $password)
    {
        /*$filePath = "../files/users/users.csv";
        if (file_exists($filePath)) {
            $userData = json_decode(file_get_contents($filePath), true);

            if ($userData['password'] == $password) {
                return 1;
            }
        }
        return 0;*/

        $filePath = file_get_contents("../files/users/users.csv");
        $utenti=explode("\r\n", $filePath);
        foreach ($utenti as $utente) {
            $campi=explode(";", $utente);
            if($campi[1]==$password) // username;pwd;punteggio
                return 1;
        }

        return 0;
    }

    public function registrazione($nome, $password)
    {
        $filePath = "../files/users/users.csv";
        $userData = $nome . ";" . $password . ";" . "0";
        file_put_contents($filePath, $userData);
        return 1;
    }
}
