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

    public function getReporte($anio, $mes, $plataforma)
    {
        $sql = "SELECT * FROM `reportes_v1_guias` WHERE YEAR(`Fecha de Creación del Pedido`) = ? AND MONTH(`Fecha de Creación del Pedido`) = ? AND `id_plataforma` = ?";
        $data = $this->dselect($sql, [$anio, $mes, $plataforma]);
        return $data;
    }
}
