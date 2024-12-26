<?php

class Prueba2Model extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function saludoACliente($text)
    {
        echo $text;
    }
}
