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
      // echo $sql; 
        return $this->select($sql);
    }

    public function listarDespachosProductos($plataforma)
    {
        $sql = "SELECT * FROM `cabecera_relacion_despacho_producto` cbd, bodega b where id_bodega in (select id from bodega where id_plataforma=$plataforma) and  cbd.id_plataforma=b.id_plataforma; ";
      // echo $sql; 
        return $this->select($sql);
    }
    
     public function listarDevoluciones($plataforma)
    {
        $sql = "SELECT * FROM `cabecera_devolucion` where id_bodega in (select id from bodega where id_plataforma=$plataforma); ";
      // echo $sql; 
        return $this->select($sql);
    }
    
    
     public function obtenerbodega($bodega)
    {
        $sql = "SELECT * FROM `bodega` where id = $bodega";
      // echo $sql; 
        return $this->select($sql);
    }
    
    
}
