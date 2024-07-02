<?php

class DespachoModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }
    
      public function listarDespachos($plataforma)
    {
        $sql = "SELECT * FROM `cabecera_relacion_despacho` where id_bodega in (select id from bodega where id_plataforma=$plataforma); ";
       echo $sql; 
        return $this->select($sql);
    }
    
}
