<?php

class utente
{
    private $_nome;
    private $_password;
    private $_punteggio;

    public function __construct($nome, $password)
    {
        $this->_nome = $nome;
        $this->_password = $password;
        $this->_punteggio = 0;
    }

    public function getNome()
    {
        return $this->_nome;
    }
    public function getPassword()
    {
        return $this->_password;
    }
    public function getPunteggio()
    {
        return $this->_punteggio;
    }
}


?>