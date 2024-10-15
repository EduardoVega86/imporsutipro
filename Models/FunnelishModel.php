<?php

class FunnelishModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveData($data)
    {
        $sql = "INSERT INTO `funnel_log` (`json`) VALUES (?);";
        $params = [json_encode($data)];
        return $this->insert($sql, $params);
    }
    public function saveDataPlatform($data, $id_plataforma)
    {
        $sql = "INSERT INTO `funnel_log` (`json`, `id_plataforma`) VALUES (?, ?);";
        $params = [json_encode($data), $id_plataforma];
        return $this->insert($sql, $params);
    }

    public function existenciaPlataforma($id_plataforma)
    {
        $sql = "SELECT id_plataforma FROM configuracion_shopify WHERE id_plataforma = ?";
        $response = $this->simple_select($sql, [$id_plataforma]);
        if ($response > 0) {
            return true;
        } else {
            return false;
        }
    }
}
