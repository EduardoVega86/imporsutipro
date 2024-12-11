<?php

class InconsistenciasModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getInconsistencias_Gintracom($tipo, $param1 = null, $param2 = null)
    {
        $sql = '';
        $params = [];

        if ($tipo === 'general') {
            $sql = "SELECT * FROM `inconsistencias_gintracom`";
        } elseif ($tipo === 'mes') {
            $sql = "SELECT * FROM `inconsistencias_gintracom` WHERE DATE_FORMAT(fecha, '%Y-%m') = ?";
            $params[] = $param1; // Formato YYYY-MM
        } elseif ($tipo === 'dia') {
            $sql = "SELECT * FROM `inconsistencias_gintracom` WHERE DATE(fecha) = ?";
            $params[] = $param1; // Formato YYYY-MM-DD
        } elseif ($tipo === 'rango') {
            $sql = "SELECT * FROM `inconsistencias_gintracom` WHERE fecha BETWEEN ? AND ?";
            $params[] = $param1; // Fecha inicio
            $params[] = $param2; // Fecha fin
        } else {
            return []; // Retorna vacío si el tipo no es válido
        }

        return $this->select($sql, $params);
    }
}
