<?php
require_once("../classes/utente.php");

class gestoreUtenti
{

    public function __construct() {}

    public function login($nome, $password)
    {
        $filePath = "../files/users/" . $nome . ".json";
        if (file_exists($filePath)) {
            $userData = json_decode(file_get_contents($filePath), true);

            if ($userData['password'] == $password) {
                return 1;
            }
        }
        return 0;
    }

    public function registrazione($nome, $password)
    {
        $filePath = "../files/users/" . $nome . ".json";
        if (file_exists($filePath)) {
            return 0;
        }

        $userData = array(
            'username' => $nome,
            'password' => $password,
            'punteggio' => 0
        );

        file_put_contents($filePath, json_encode($userData));
        return 1;
    }
}
