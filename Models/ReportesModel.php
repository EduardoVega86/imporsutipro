<?php

class ReportesModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataBilletera()
    {
        $año = $_POST["anio"] != "" ? $_POST["anio"] : "2024";
        $mes = $_POST["mes"] != "" ? $_POST["mes"] : "no";

        if ($mes == "no") {
            return [
                "status" => "400",
                "message" => "No se ha seleccionado un mes"
            ];
        }
    }
}
