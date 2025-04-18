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
        $sql = "SELECT * FROM `cabecera_relacion_despacho_producto` cbd, bodega b, users u where id_bodega in (select id from bodega where id_plataforma=$plataforma) and  cbd.id_plataforma=b.id_plataforma and cbd.id_usuario=u.id_users; ";
      // echo $sql; 
        return $this->select($sql);
    }
    
     public function listarDevoluciones($plataforma)
    {
        $sql = "SELECT * FROM `cabecera_devolucion` where id_bodega in (select id from bodega where id_plataforma=$plataforma); ";
      // echo $sql; 
        return $this->select($sql);
    }

    public function listarDevolucionesProducto($plataforma)
    {
        $sql = "SELECT * FROM `cabecera_devolucion_producto` where id_plataforma=$plataforma; ";
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
