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
}
