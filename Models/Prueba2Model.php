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

    public function recibirDatos(array $data)
    {
        $result = "";
        switch ($data["option"]) {
            case 1:
                $sum = $data["num1"] + $data["num2"];
                $result = $sum;
                break;
            case 2:
                $rest = $data["num1"] - $data["num2"];
                $result = $rest;
                break;
            case 3:
                $mult = $data["num1"] * $data["num2"];
                $result = $mult;
                break;
            case 4:

                $div = $data["num1"] / $data["num2"];
                $result = $div;
                break;
        }
        return  json_encode(
            ["status" => true, "message" => "Operacion completada el resultado es", "resultado" => $result]
        );
    }
}
